<?php

namespace App\Services;

use App\Exceptions\ScheduleSlotConflictException;
use App\Exceptions\ScheduleSlotExpiredException;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class CompanyScheduleStorageService
{
    /**
     * Retorna o diretório de armazenamento de uma empresa.
     */
    private function companyDirectory(int $companyId): string
    {
        return storage_path(
            'app/private/agendas/companies/'.$companyId
        );
    }

    /**
     * Retorna o caminho de uma agenda.
     */
    private function agendaPath(
        int $companyId,
        string $agendaId
    ): string {
        $this->validateAgendaId($agendaId);

        return $this->companyDirectory($companyId)
            .'/'.$agendaId.'.json';
    }

    /**
     * Retorna o caminho do arquivo de reservas.
     */
    private function reservationsPath(
        int $companyId,
        string $agendaId
    ): string {
        $this->validateAgendaId($agendaId);

        return $this->companyDirectory($companyId)
            .'/'.$agendaId.'_reservas.json';
    }

    /**
     * Valida o identificador UUID da agenda.
     */
    private function validateAgendaId(string $agendaId): void
    {
        if (! Str::isUuid($agendaId)) {
            throw new RuntimeException(
                'Identificador de agenda inválido.'
            );
        }
    }

    /**
     * Garante a existência do diretório da empresa.
     */
    private function ensureCompanyDirectory(int $companyId): string
    {
        if ($companyId <= 0) {
            throw new RuntimeException(
                'Identificador de empresa inválido.'
            );
        }

        $directory = $this->companyDirectory($companyId);

        if (
            ! is_dir($directory)
            && ! mkdir($directory, 0700, true)
            && ! is_dir($directory)
        ) {
            throw new RuntimeException(
                'Não foi possível criar o diretório da empresa.'
            );
        }

        return $directory;
    }

    /**
     * Executa uma operação com bloqueio exclusivo da agenda.
     */
    private function withAgendaLock(
        int $companyId,
        string $agendaId,
        callable $callback
    ): mixed {
        $this->ensureCompanyDirectory($companyId);

        $path = $this->agendaPath($companyId, $agendaId);
        $lockHandle = fopen($path.'.lock', 'c');

        if ($lockHandle === false) {
            throw new RuntimeException(
                'Não foi possível abrir o arquivo de bloqueio da agenda.'
            );
        }

        try {
            if (! flock($lockHandle, LOCK_EX)) {
                throw new RuntimeException(
                    'Não foi possível bloquear a agenda.'
                );
            }

            return $callback($path);
        } finally {
            flock($lockHandle, LOCK_UN);
            fclose($lockHandle);
        }
    }

    /**
     * Executa uma operação com bloqueio exclusivo do arquivo de reservas.
     */
    private function withReservationsLock(
        int $companyId,
        string $agendaId,
        callable $callback
    ): mixed {
        $this->ensureCompanyDirectory($companyId);

        $path = $this->reservationsPath($companyId, $agendaId);
        $lockHandle = fopen($path.'.lock', 'c');

        if ($lockHandle === false) {
            throw new RuntimeException(
                'Não foi possível abrir o arquivo de bloqueio das reservas.'
            );
        }

        try {
            if (! flock($lockHandle, LOCK_EX)) {
                throw new RuntimeException(
                    'Não foi possível bloquear as reservas.'
                );
            }

            return $callback($path);
        } finally {
            flock($lockHandle, LOCK_UN);
            fclose($lockHandle);
        }
    }

    /**
     * Lê e decodifica o conteúdo JSON.
     */
    private function readJson(string $path): array
    {
        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException(
                'Não foi possível ler o arquivo JSON.'
            );
        }

        try {
            $data = json_decode(
                $content,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $exception) {
            throw new RuntimeException(
                'O arquivo contém JSON inválido.',
                previous: $exception
            );
        }

        if (! is_array($data)) {
            throw new RuntimeException(
                'A estrutura do arquivo JSON é inválida.'
            );
        }

        return $data;
    }

    /**
     * Grava o JSON utilizando arquivo temporário e renomeação atômica.
     */
    private function writeJson(
        string $path,
        array $data
    ): void {
        try {
            $content = json_encode(
                $data,
                JSON_PRETTY_PRINT
                    | JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                    | JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $exception) {
            throw new RuntimeException(
                'Não foi possível converter os dados para JSON.',
                previous: $exception
            );
        }

        $temporaryPath = tempnam(
            dirname($path),
            'agenda_'
        );

        if ($temporaryPath === false) {
            throw new RuntimeException(
                'Não foi possível criar o arquivo temporário.'
            );
        }

        try {
            if (file_put_contents($temporaryPath, $content) === false) {
                throw new RuntimeException(
                    'Não foi possível gravar o arquivo temporário.'
                );
            }

            chmod($temporaryPath, 0600);

            if (! rename($temporaryPath, $path)) {
                throw new RuntimeException(
                    'Não foi possível concluir a gravação do arquivo.'
                );
            }
        } finally {
            if (is_file($temporaryPath)) {
                unlink($temporaryPath);
            }
        }
    }

    /**
     * Cria uma nova agenda para uma empresa.
     */
    public function createForCompany(
        int $companyId,
        array $data
    ): array {
        if ($companyId <= 0) {
            throw new RuntimeException(
                'Identificador de empresa inválido.'
            );
        }

        $agendaId = (string) Str::uuid();
        $now = now()->toIso8601String();

        $agenda = array_merge($data, [
            'agenda_id' => $agendaId,
            'company_id' => $companyId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return $this->withAgendaLock(
            $companyId,
            $agendaId,
            function (string $path) use ($agenda): array {
                $this->writeJson($path, $agenda);

                return $agenda;
            }
        );
    }

    /**
     * Lista exclusivamente os arquivos de agendas de uma empresa.
     */
    public function listForCompany(int $companyId): array
    {
        if ($companyId <= 0) {
            throw new RuntimeException(
                'Identificador de empresa inválido.'
            );
        }

        $directory = $this->companyDirectory($companyId);

        if (! is_dir($directory)) {
            return [];
        }

        $files = glob($directory.'/*.json');

        if ($files === false) {
            throw new RuntimeException(
                'Não foi possível listar as agendas.'
            );
        }

        $agendas = [];

        foreach ($files as $file) {
            $agendaId = basename($file, '.json');

            // Ignora arquivos de reservas e outros arquivos JSON.
            if (! Str::isUuid($agendaId)) {
                continue;
            }

            $agenda = $this->findForCompany(
                $companyId,
                $agendaId
            );

            if ($agenda !== null) {
                $agendas[] = $agenda;
            }
        }

        usort(
            $agendas,
            fn (array $a, array $b): int =>
                strcmp(
                    $b['created_at'] ?? '',
                    $a['created_at'] ?? ''
                )
        );

        return $agendas;
    }

    /**
     * Localiza uma agenda pertencente à empresa informada.
     */
    public function findForCompany(
        int $companyId,
        string $agendaId
    ): ?array {
        return $this->withAgendaLock(
            $companyId,
            $agendaId,
            function (string $path) use ($companyId): ?array {
                if (! is_file($path)) {
                    return null;
                }

                $agenda = $this->readJson($path);

                if (($agenda['company_id'] ?? null) !== $companyId) {
                    return null;
                }

                return $agenda;
            }
        );
    }

    /**
     * Atualiza os dados de uma agenda.
     */
    public function updateForCompany(
        int $companyId,
        string $agendaId,
        array $data
    ): array {
        return $this->withAgendaLock(
            $companyId,
            $agendaId,
            function (string $path) use (
                $companyId,
                $data
            ): array {
                if (! is_file($path)) {
                    throw new RuntimeException(
                        'Agenda não encontrada.'
                    );
                }

                $agenda = $this->readJson($path);

                if (($agenda['company_id'] ?? null) !== $companyId) {
                    throw new RuntimeException(
                        'Agenda não pertence a esta empresa.'
                    );
                }

                unset(
                    $data['agenda_id'],
                    $data['company_id'],
                    $data['created_at']
                );

                $updatedAgenda = array_replace(
                    $agenda,
                    $data
                );

                $updatedAgenda['updated_at'] =
                    now()->toIso8601String();

                $this->writeJson($path, $updatedAgenda);

                return $updatedAgenda;
            }
        );
    }

    /**
     * Lista as reservas de uma agenda.
     */
    public function listReservationsForCompany(
        int $companyId,
        string $agendaId
    ): array {
        return $this->withAgendaLock(
            $companyId,
            $agendaId,
            function (string $agendaPath) use (
                $companyId,
                $agendaId
            ): array {
                if (! is_file($agendaPath)) {
                    return [];
                }

                $agenda = $this->readJson($agendaPath);

                if (($agenda['company_id'] ?? null) !== $companyId) {
                    throw new RuntimeException(
                        'Agenda não pertence a esta empresa.'
                    );
                }

                return $this->withReservationsLock(
                    $companyId,
                    $agendaId,
                    function (string $reservationsPath) use (
                        $companyId,
                        $agendaId
                    ): array {
                        if (! is_file($reservationsPath)) {
                            return [];
                        }

                        $data = $this->readJson($reservationsPath);

                        if (
                            ($data['company_id'] ?? null) !== $companyId
                            || ($data['agenda_id'] ?? null) !== $agendaId
                        ) {
                            throw new RuntimeException(
                                'Arquivo de reservas incompatível com a agenda.'
                            );
                        }

                        $reservas = $data['reservas'] ?? [];

                        if (! is_array($reservas)) {
                            throw new RuntimeException(
                                'A estrutura das reservas é inválida.'
                            );
                        }

                        return $reservas;
                    }
                );
            }
        );
    }

    /**
     * Cria uma reserva com confirmação automática.
     *
     * A agenda é bloqueada antes do arquivo de reservas.
     * Essa ordem também é utilizada nas demais operações para
     * evitar conflitos entre reservas e exclusões da agenda.
     *
     * O fuso horário deve ser informado pelo controlador,
     * utilizando o fuso da empresa.
     */
    public function createReservationForCompany(
        int $companyId,
        string $agendaId,
        array $data,
        string $timezone
    ): array {
        return $this->withAgendaLock(
            $companyId,
            $agendaId,
            function (string $agendaPath) use (
                $companyId,
                $agendaId,
                $data,
                $timezone
            ): array {
                if (! is_file($agendaPath)) {
                    throw new RuntimeException(
                        'Agenda não encontrada.'
                    );
                }

                $agenda = $this->readJson($agendaPath);

                if (($agenda['company_id'] ?? null) !== $companyId) {
                    throw new RuntimeException(
                        'Agenda não pertence a esta empresa.'
                    );
                }

                $profissional = trim(
                    (string) ($data['profissional'] ?? '')
                );

                $horarioInicio = trim(
                    (string) ($data['horario_inicio'] ?? '')
                );

                $horarioFim = trim(
                    (string) ($data['horario_fim'] ?? '')
                );

                $cliente = $data['cliente'] ?? [];

                if (
                    $profissional === ''
                    || $horarioInicio === ''
                    || $horarioFim === ''
                    || ! is_array($cliente)
                ) {
                    throw new RuntimeException(
                        'Dados da reserva incompletos.'
                    );
                }

                $nome = trim(
                    (string) ($cliente['nome'] ?? '')
                );

                $telefone = trim(
                    (string) ($cliente['telefone'] ?? '')
                );

                $email = trim(
                    (string) ($cliente['email'] ?? '')
                );

                if ($nome === '' || $telefone === '') {
                    throw new RuntimeException(
                        'Nome e telefone do cliente são obrigatórios.'
                    );
                }

                $profissionais = $agenda['profissionais'] ?? [];

                if (
                    ! is_array($profissionais)
                    || ! in_array($profissional, $profissionais, true)
                ) {
                    throw new RuntimeException(
                        'Profissional não pertence a esta agenda.'
                    );
                }

                $dataEvento = data_get(
                    $agenda,
                    'evento.data'
                );

                if (! is_string($dataEvento) || $dataEvento === '') {
                    throw new RuntimeException(
                        'A data do evento é inválida.'
                    );
                }

                $horarios = data_get(
                    $agenda,
                    'previsao.horarios',
                    []
                );

                $horarioExiste = false;

                if (is_array($horarios)) {
                    foreach ($horarios as $horario) {
                        if (! is_array($horario)) {
                            continue;
                        }

                        if (
                            ($horario['inicio'] ?? null) === $horarioInicio
                            && ($horario['fim'] ?? null) === $horarioFim
                        ) {
                            $horarioExiste = true;
                            break;
                        }
                    }
                }

                if (! $horarioExiste) {
                    throw new RuntimeException(
                        'Horário não pertence à previsão desta agenda.'
                    );
                }

                try {
                    $dataHoraInicio = CarbonImmutable::createFromFormat(
                        '!Y-m-d H:i',
                        "{$dataEvento} {$horarioInicio}",
                        $timezone
                    );
                } catch (Throwable $exception) {
                    throw new RuntimeException(
                        'Não foi possível validar a data e o horário da reserva.',
                        previous: $exception
                    );
                }

                if ($dataHoraInicio === false) {
                    throw new RuntimeException(
                        'A data e o horário da reserva são inválidos.'
                    );
                }

                return $this->withReservationsLock(
                    $companyId,
                    $agendaId,
                    function (string $reservationsPath) use (
                        $companyId,
                        $agendaId,
                        $profissional,
                        $horarioInicio,
                        $horarioFim,
                        $nome,
                        $telefone,
                        $email,
                        $agenda,
                        $dataHoraInicio,
                        $timezone
                    ): array {
                        // Verifica novamente o vencimento dentro do bloqueio,
                        // imediatamente antes de processar a reserva.
                        if (
                            $dataHoraInicio->lessThanOrEqualTo(
                                CarbonImmutable::now($timezone)
                            )
                        ) {
                            throw new ScheduleSlotExpiredException(
                                'O horário selecionado já passou ou não está mais disponível.'
                            );
                        }

                        if (is_file($reservationsPath)) {
                            $storage = $this->readJson(
                                $reservationsPath
                            );

                            if (
                                ($storage['company_id'] ?? null) !== $companyId
                                || ($storage['agenda_id'] ?? null) !== $agendaId
                            ) {
                                throw new RuntimeException(
                                    'Arquivo de reservas incompatível com a agenda.'
                                );
                            }
                        } else {
                            $storage = [
                                'company_id' => $companyId,
                                'agenda_id' => $agendaId,
                                'reservas' => [],
                            ];
                        }

                        $reservas = $storage['reservas'] ?? [];

                        if (! is_array($reservas)) {
                            throw new RuntimeException(
                                'A estrutura das reservas é inválida.'
                            );
                        }

                        foreach ($reservas as $reserva) {
                            if (! is_array($reserva)) {
                                throw new RuntimeException(
                                    'Um registro de reserva possui estrutura inválida.'
                                );
                            }

                            $mesmoProfissional =
                                ($reserva['profissional'] ?? null)
                                === $profissional;

                            $mesmoHorario =
                                ($reserva['horario_inicio'] ?? null)
                                === $horarioInicio;

                            $cancelada =
                                ($reserva['status'] ?? null)
                                === 'cancelada';

                            if (
                                $mesmoProfissional
                                && $mesmoHorario
                                && ! $cancelada
                            ) {
                                throw new ScheduleSlotConflictException(
                                    'Este horário já foi reservado para este profissional.'
                                );
                            }
                        }

                        $now = now()->toIso8601String();

                        $reserva = [
                            'reserva_id' => (string) Str::uuid(),
                            'company_id' => $companyId,
                            'agenda_id' => $agendaId,
                            'data' => data_get(
                                $agenda,
                                'evento.data'
                            ),
                            'profissional' => $profissional,
                            'horario_inicio' => $horarioInicio,
                            'horario_fim' => $horarioFim,
                            'cliente' => [
                                'nome' => $nome,
                                'telefone' => $telefone,
                                'email' => $email !== '' ? $email : null,
                            ],
                            'status' => 'confirmada',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];

                        $reservas[] = $reserva;

                        $storage['company_id'] = $companyId;
                        $storage['agenda_id'] = $agendaId;
                        $storage['reservas'] = $reservas;
                        $storage['updated_at'] = $now;

                        $this->writeJson(
                            $reservationsPath,
                            $storage
                        );

                        return $reserva;
                    }
                );
            }
        );
    }

    /**
     * Exclui uma agenda e seu arquivo de reservas.
     */
    public function deleteForCompany(
        int $companyId,
        string $agendaId
    ): bool {
        return $this->withAgendaLock(
            $companyId,
            $agendaId,
            function (string $path) use (
                $companyId,
                $agendaId
            ): bool {
                if (! is_file($path)) {
                    return false;
                }

                $agenda = $this->readJson($path);

                if (($agenda['company_id'] ?? null) !== $companyId) {
                    throw new RuntimeException(
                        'Agenda não pertence a esta empresa.'
                    );
                }

                if (! unlink($path)) {
                    throw new RuntimeException(
                        'Não foi possível excluir a agenda.'
                    );
                }

                $reservationsPath = $this->reservationsPath(
                    $companyId,
                    $agendaId
                );

                if (
                    is_file($reservationsPath)
                    && ! unlink($reservationsPath)
                ) {
                    throw new RuntimeException(
                        'Agenda excluída, mas não foi possível excluir seu arquivo de reservas.'
                    );
                }

                return true;
            }
        );
    }
}
