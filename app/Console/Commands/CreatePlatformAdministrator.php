<?php

namespace App\Console\Commands;

use App\Models\PlatformAdministrator;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('platform:admin-create')]
#[Description('Cadastra o primeiro administrador geral da plataforma.')]
class CreatePlatformAdministrator extends Command
{
    /**
     * Executa a inicialização do primeiro administrador geral.
     */
    public function handle(): int
    {
        // Impede uma nova inicialização se já houver algum registro.
        if (PlatformAdministrator::query()->exists()) {
            $this->error(
                'A inicialização já foi realizada. '
                .'Não é permitido cadastrar outro administrador por este comando.'
            );

            return self::FAILURE;
        }

        $email = $this->ask(
            'Informe o e-mail da conta que será o primeiro administrador geral'
        );

        // Valida o formato do e-mail.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('O endereço de e-mail informado não é válido.');

            return self::FAILURE;
        }

        // Localiza uma conta já existente.
        $user = User::query()
            ->where('email', $email)
            ->first();

        if (!$user) {
            $this->error(
                'Nenhuma conta cadastrada foi encontrada com esse e-mail.'
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->info('Conta localizada:');
        $this->line('Nome: '.$user->name);
        $this->line('E-mail: '.$user->email);

        if (!$this->confirm(
            'Confirma a concessão de acesso administrativo geral para esta conta?',
            false
        )) {
            $this->warn('Operação cancelada. Nenhuma alteração foi realizada.');

            return self::SUCCESS;
        }

        // Cria a primeira autorização administrativa.
        DB::transaction(function () use ($user): void {
            PlatformAdministrator::create([
                'user_id' => $user->id,
                'granted_by' => null,
                'status' => PlatformAdministrator::STATUS_ACTIVE,
                'granted_at' => now(),
                'revoked_at' => null,
            ]);
        });

        $this->newLine();
        $this->info('Primeiro administrador geral cadastrado com sucesso.');
        $this->line('Conta autorizada: '.$user->email);

        return self::SUCCESS;
    }
}