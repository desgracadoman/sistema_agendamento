<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\CompanyScheduleStorageService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CompanyScheduleController extends Controller
{
    /**
     * Exibe as agendas cadastradas para a empresa.
     */
    public function index(
        Request $request,
        Company $company,
        CompanyScheduleStorageService $storage
    ): View {
        $agendas = collect(
            $storage->listForCompany((int) $company->id)
        );

        return view('company.schedules.index', [
            'company' => $company,
            'agendas' => $agendas,
        ]);
    }

    /**
     * Exibe o formulário de criação de uma agenda.
     */
    public function create(
        Company $company
    ): View {
        return view('company.schedules.create', [
            'company' => $company,
        ]);
    }

    /**
     * Valida os dados e apresenta a revisão da agenda.
     *
     * Nenhum arquivo JSON é criado nesta etapa.
     */
    public function review(
        Request $request,
        Company $company
    ): View {
        $validated = $request->validate([
            'evento.nome' => [
                'required',
                'string',
                'max:150',
            ],

            'evento.data' => [
                'required',
                'date_format:Y-m-d',
            ],

            'evento.hora_inicio' => [
                'required',
                'date_format:H:i',
            ],

            'evento.duracao_horas' => [
                'required',
                'integer',
                'min:0',
                'max:72',
            ],

            'evento.duracao_minutos' => [
                'required',
                'integer',
                'min:0',
                'max:59',
            ],

            'profissionais' => [
                'required',
                'string',
                'max:5000',
            ],

            'servico.tipo' => [
                'required',
                'string',
                'max:150',
            ],

            'servico.duracao_minutos' => [
                'required',
                'integer',
                'min:5',
                'max:480',
            ],

            'intervalo.duracao_minutos' => [
                'required',
                'integer',
                'min:0',
                'max:180',
            ],

            'intervalo.frequencia_horas' => [
                'nullable',
                'integer',
                'min:1',
                'max:24',
            ],
        ]);

        $duracaoHoras = (int) $validated['evento']['duracao_horas'];
        $duracaoMinutos = (int) $validated['evento']['duracao_minutos'];

        $duracaoTotalMinutos = (
            $duracaoHoras * 60
        ) + $duracaoMinutos;

        if ($duracaoTotalMinutos <= 0) {
            throw ValidationException::withMessages([
                'evento.duracao_horas' =>
                    'A duração total do evento deve ser maior que zero.',
            ]);
        }

        if ($duracaoTotalMinutos > 4320) {
            throw ValidationException::withMessages([
                'evento.duracao_horas' =>
                    'A duração máxima permitida para o evento é de 72 horas.',
            ]);
        }

        $duracaoIntervalo = (int) $validated['intervalo']['duracao_minutos'];

        $frequenciaIntervalo = $validated['intervalo']['frequencia_horas'];

        if ($duracaoIntervalo > 0 && $frequenciaIntervalo === null) {
            throw ValidationException::withMessages([
                'intervalo.frequencia_horas' =>
                    'Informe a frequência dos intervalos.',
            ]);
        }

        if ($duracaoIntervalo === 0) {
            $frequenciaIntervalo = null;
        } else {
            $frequenciaIntervalo = (int) $frequenciaIntervalo;
        }

        // Processa a lista de profissionais.
        $linhasProfissionais = preg_split(
            '/\r\n|\r|\n/',
            $validated['profissionais']
        );

        $profissionais = [];

        foreach ($linhasProfissionais as $nome) {
            $nome = trim($nome);

            if ($nome !== '') {
                $profissionais[] = $nome;
            }
        }

        if (count($profissionais) === 0) {
            throw ValidationException::withMessages([
                'profissionais' =>
                    'Informe pelo menos um profissional.',
            ]);
        }

        if (count($profissionais) > 20) {
            throw ValidationException::withMessages([
                'profissionais' =>
                    'O limite é de 20 profissionais por agenda.',
            ]);
        }

        // Impede nomes duplicados.
        $nomesNormalizados = array_map(
            fn (string $nome): string => mb_strtolower($nome),
            $profissionais
        );

        if (
            count(array_unique($nomesNormalizados))
            !== count($profissionais)
        ) {
            throw ValidationException::withMessages([
                'profissionais' =>
                    'Existem nomes de profissionais repetidos.',
            ]);
        }

        // Calcula os horários e intervalos.
        $agenda = [
            'evento' => [
                'nome' => trim($validated['evento']['nome']),
                'data' => $validated['evento']['data'],
                'hora_inicio' => $validated['evento']['hora_inicio'],
                'duracao_horas' => $duracaoHoras,
                'duracao_minutos' => $duracaoMinutos,
                'duracao_total_minutos' => $duracaoTotalMinutos,
            ],

            'profissionais' => $profissionais,

            'servico' => [
                'tipo' => trim($validated['servico']['tipo']),
                'duracao_minutos' => (int) $validated['servico']['duracao_minutos'],
            ],

            'intervalo' => [
                'duracao_minutos' => $duracaoIntervalo,
                'frequencia_horas' => $frequenciaIntervalo,
            ],
        ];

        $previsao = $this->calculateSchedule($agenda, $company);

        // Identificador temporário para a confirmação.
        $confirmationToken = (string) Str::uuid();

        $sessionKey = 'company_schedule_review_'.$confirmationToken;

        session()->put($sessionKey, [
            'company_id' => (int) $company->id,
            'agenda' => $agenda,
            'previsao' => $previsao,
            'expires_at' => now()->addMinutes(30)->timestamp,
        ]);

        return view('company.schedules.review', [
            'company' => $company,
            'agenda' => $agenda,
            'previsao' => $previsao,
            'confirmationToken' => $confirmationToken,
        ]);
    }

    /**
     * Confirma a revisão e grava a agenda em arquivo JSON.
     */
    public function store(
        Request $request,
        Company $company,
        CompanyScheduleStorageService $storage
    ): RedirectResponse {
        $validated = $request->validate([
            'confirmation_token' => [
                'required',
                'uuid',
            ],
        ]);

        $confirmationToken = $validated['confirmation_token'];

        $sessionKey = 'company_schedule_review_'.$confirmationToken;

        $review = session()->get($sessionKey);

        if (! is_array($review)) {
            return redirect()
                ->route('company.schedules.create', $company)
                ->withErrors([
                    'agenda' =>
                        'A revisão não foi encontrada. Preencha os dados novamente.',
                ]);
        }

        if (
            (int) ($review['company_id'] ?? 0)
            !== (int) $company->id
        ) {
            abort(403, 'Esta revisão não pertence à empresa selecionada.');
        }

        if (
            (int) ($review['expires_at'] ?? 0)
            < now()->timestamp
        ) {
            session()->forget($sessionKey);

            return redirect()
                ->route('company.schedules.create', $company)
                ->withErrors([
                    'agenda' =>
                        'O prazo de confirmação expirou. Preencha os dados novamente.',
                ]);
        }

        $agenda = $review['agenda'] ?? null;

        if (! is_array($agenda)) {
            session()->forget($sessionKey);

            return redirect()
                ->route('company.schedules.create', $company)
                ->withErrors([
                    'agenda' =>
                        'Os dados da revisão são inválidos. Tente novamente.',
                ]);
        }

        // Os dados utilizados são os armazenados na sessão,
        // e não valores enviados novamente pelo navegador.
        $agenda['previsao'] = $review['previsao'];

        $storage->createForCompany(
            (int) $company->id,
            $agenda
        );

        // Impede a reutilização da mesma confirmação.
        session()->forget($sessionKey);

        return redirect()
            ->route('company.schedules.index', $company)
            ->with(
                'success',
                'Agenda criada com sucesso.'
            );
    }

    /**
     * Exibe as reservas recebidas para uma agenda específica.
     */
    public function reservations(
        Company $company,
        string $agendaId,
        CompanyScheduleStorageService $storage
    ): View {
        $agenda = $storage->findForCompany(
            (int) $company->id,
            $agendaId
        );

        if ($agenda === null) {
            abort(404);
        }

        $reservas = collect(
            $storage->listReservationsForCompany(
                (int) $company->id,
                $agendaId
            )
        );

        return view('company.schedules.reservations.index', [
            'company' => $company,
            'agenda' => $agenda,
            'reservas' => $reservas,
        ]);
    }

    /**
     * Calcula os horários de atendimento e os intervalos coletivos.
     */
    private function calculateSchedule(
        array $agenda,
        Company $company
    ): array {
        $timezone = $company->timezone ?: config('app.timezone');

        $evento = $agenda['evento'];
        $servico = $agenda['servico'];
        $intervalo = $agenda['intervalo'];

        $inicio = CarbonImmutable::createFromFormat(
            '!Y-m-d H:i',
            $evento['data'].' '.$evento['hora_inicio'],
            $timezone
        );

        if ($inicio === false) {
            throw ValidationException::withMessages([
                'evento' => 'Não foi possível interpretar a data e o horário do evento.',
            ]);
        }

        $fim = $inicio->addMinutes(
            $evento['duracao_total_minutos']
        );

        $duracaoServico = (int) $servico['duracao_minutos'];

        $duracaoIntervalo = (int) $intervalo['duracao_minutos'];

        $frequenciaIntervalo = $intervalo['frequencia_horas'];

        $intervalos = [];

        // Os intervalos são calculados a partir do início do evento.
        if (
            $duracaoIntervalo > 0
            && $frequenciaIntervalo !== null
        ) {
            $proximoIntervalo = $inicio->addHours(
                (int) $frequenciaIntervalo
            );

            while ($proximoIntervalo < $fim) {
                $fimIntervalo = $proximoIntervalo->addMinutes(
                    $duracaoIntervalo
                );

                // O intervalo não ultrapassa o encerramento do evento.
                if ($fimIntervalo > $fim) {
                    $fimIntervalo = $fim;
                }

                $intervalos[] = [
                    'inicio' => $proximoIntervalo->format('H:i'),
                    'fim' => $fimIntervalo->format('H:i'),
                    'inicio_completo' => $proximoIntervalo->toIso8601String(),
                    'fim_completo' => $fimIntervalo->toIso8601String(),
                ];

                $proximoIntervalo = $proximoIntervalo->addHours(
                    (int) $frequenciaIntervalo
                );
            }
        }

        // Distribui os serviços sem permitir sobreposição com intervalos.
        $horarios = [];

        $cursor = $inicio;

        while (
            $cursor->addMinutes($duracaoServico) <= $fim
        ) {
            $fimAtendimento = $cursor->addMinutes(
                $duracaoServico
            );

            $conflitoIntervalo = null;

            foreach ($intervalos as $item) {
                $inicioIntervalo = CarbonImmutable::parse(
                    $item['inicio_completo']
                );

                $fimIntervalo = CarbonImmutable::parse(
                    $item['fim_completo']
                );

                $existeSobreposicao = (
                    $cursor < $fimIntervalo
                    && $fimAtendimento > $inicioIntervalo
                );

                if ($existeSobreposicao) {
                    $conflitoIntervalo = $fimIntervalo;
                    break;
                }
            }

            if ($conflitoIntervalo !== null) {
                $cursor = $conflitoIntervalo;
                continue;
            }

            $horarios[] = [
                'inicio' => $cursor->format('H:i'),
                'fim' => $fimAtendimento->format('H:i'),
                'inicio_completo' => $cursor->toIso8601String(),
                'fim_completo' => $fimAtendimento->toIso8601String(),
            ];

            $cursor = $fimAtendimento;
        }

        $quantidadeProfissionais = count(
            $agenda['profissionais']
        );

        $quantidadeHorarios = count($horarios);

        return [
            'hora_fim' => $fim->format('H:i'),
            'data_hora_fim' => $fim->toIso8601String(),

            'quantidade_profissionais' => $quantidadeProfissionais,

            'quantidade_intervalos' => count($intervalos),

            'quantidade_horarios' => $quantidadeHorarios,

            'capacidade_total' => (
                $quantidadeHorarios * $quantidadeProfissionais
            ),

            'intervalos' => $intervalos,

            'horarios' => $horarios,
        ];
    }
}
