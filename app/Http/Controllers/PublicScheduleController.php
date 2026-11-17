<?php

namespace App\Http\Controllers;

use App\Exceptions\ScheduleSlotConflictException;
use App\Exceptions\ScheduleSlotExpiredException;
use App\Models\Company;
use App\Services\CompanyScheduleStorageService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class PublicScheduleController extends Controller
{
    /**
     * Exibe uma agenda pública com os horários disponíveis.
     */
    public function show(
        Company $company,
        string $agendaId,
        CompanyScheduleStorageService $storage
    ): View {
        abort_unless(Str::isUuid($agendaId), 404);

        $companyId = (int) $company->id;

        $agenda = $storage->findForCompany(
            $companyId,
            $agendaId
        );

        abort_if($agenda === null, 404);

        $reservas = $storage->listReservationsForCompany(
            $companyId,
            $agendaId
        );

        $profissionais = data_get(
            $agenda,
            'profissionais',
            []
        );

        $horarios = data_get(
            $agenda,
            'previsao.horarios',
            []
        );

        $dataEvento = data_get(
            $agenda,
            'evento.data'
        );

        abort_unless(
            is_array($profissionais) && is_array($horarios),
            404
        );

        abort_unless(is_array($reservas), 500);

        abort_unless(
            is_string($dataEvento),
            404
        );

        $timezone = $company->timezone ?: config('app.timezone');

        $agora = CarbonImmutable::now($timezone);

        $horariosDisponiveis = [];

        foreach ($profissionais as $profissional) {
            if (! is_string($profissional)) {
                continue;
            }

            $horariosDisponiveis[$profissional] = [];

            foreach ($horarios as $horario) {
                if (! is_array($horario)) {
                    continue;
                }

                $horarioInicio = $horario['inicio'] ?? null;

                if (! is_string($horarioInicio)) {
                    continue;
                }

                try {
                    $dataHoraInicio = CarbonImmutable::createFromFormat(
                        '!Y-m-d H:i',
                        "{$dataEvento} {$horarioInicio}",
                        $timezone
                    );
                } catch (Throwable $exception) {
                    continue;
                }

                if (
                    $dataHoraInicio === false
                    || $dataHoraInicio->lessThanOrEqualTo($agora)
                ) {
                    continue;
                }

                $ocupado = false;

                foreach ($reservas as $reserva) {
                    if (! is_array($reserva)) {
                        continue;
                    }

                    if (
                        ($reserva['profissional'] ?? null) === $profissional
                        && ($reserva['horario_inicio'] ?? null) === $horarioInicio
                        && ($reserva['status'] ?? null) !== 'cancelada'
                    ) {
                        $ocupado = true;
                        break;
                    }
                }

                if (! $ocupado) {
                    $horariosDisponiveis[$profissional][] = $horario;
                }
            }
        }

        return view('public.schedules.show', [
            'company' => $company,
            'agenda' => $agenda,
            'horariosDisponiveis' => $horariosDisponiveis,
        ]);
    }

    /**
     * Registra uma reserva realizada pela página pública.
     */
    public function store(
        Request $request,
        Company $company,
        string $agendaId,
        CompanyScheduleStorageService $storage
    ): RedirectResponse {
        abort_unless(Str::isUuid($agendaId), 404);

        $validated = $request->validate([
            'profissional' => [
                'required',
                'string',
                'max:150',
            ],

            'horario_inicio' => [
                'required',
                'string',
                'max:10',
            ],

            'horario_fim' => [
                'required',
                'string',
                'max:10',
            ],

            'cliente.nome' => [
                'required',
                'string',
                'max:150',
            ],

            'cliente.telefone' => [
                'required',
                'string',
                'max:30',
            ],

            'cliente.email' => [
                'nullable',
                'email',
                'max:150',
            ],
        ], [
            'profissional.required' => 'Selecione um profissional.',
            'horario_inicio.required' => 'Selecione um horário disponível.',
            'horario_fim.required' => 'O horário selecionado é inválido.',
            'cliente.nome.required' => 'Informe seu nome completo.',
            'cliente.telefone.required' => 'Informe seu telefone ou WhatsApp.',
            'cliente.email.email' => 'Informe um endereço de e-mail válido.',
        ]);

        $timezone = $company->timezone ?: config('app.timezone');

        try {
            $storage->createReservationForCompany(
                (int) $company->id,
                $agendaId,
                [
                    'profissional' => $validated['profissional'],
                    'horario_inicio' => $validated['horario_inicio'],
                    'horario_fim' => $validated['horario_fim'],

                    'cliente' => [
                        'nome' => $validated['cliente']['nome'],
                        'telefone' => $validated['cliente']['telefone'],
                        'email' => $validated['cliente']['email'] ?? null,
                    ],
                ],
                $timezone
            );
        } catch (ScheduleSlotExpiredException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'horario_inicio' =>
                        'Este horário já passou e não está mais disponível. '
                        . 'Atualize a página e escolha outro horário.',
                ]);
        } catch (ScheduleSlotConflictException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'horario_inicio' =>
                        'Este horário acabou de ser reservado por outra pessoa. '
                        . 'Atualize a página e escolha outro horário.',
                ]);
        } catch (RuntimeException $exception) {
            Log::error(
                'Falha ao processar reserva pública.',
                [
                    'company_id' => $company->id,
                    'agenda_id' => $agendaId,
                    'erro' => $exception->getMessage(),
                ]
            );

            return back()
                ->withInput()
                ->withErrors([
                    'reserva' =>
                        'Não foi possível concluir seu agendamento. '
                        . 'Tente novamente mais tarde.',
                ]);
        } catch (Throwable $exception) {
            Log::error(
                'Erro inesperado ao processar reserva pública.',
                [
                    'company_id' => $company->id,
                    'agenda_id' => $agendaId,
                    'erro' => $exception->getMessage(),
                    'exception' => $exception::class,
                ]
            );

            return back()
                ->withInput()
                ->withErrors([
                    'reserva' =>
                        'Ocorreu um erro inesperado. '
                        . 'Tente novamente mais tarde.',
                ]);
        }

        return redirect()
            ->route('public.schedules.show', [
                'company' => $company,
                'agendaId' => $agendaId,
            ])
            ->with(
                'success',
                'Seu agendamento foi confirmado com sucesso!'
            );
    }
}
