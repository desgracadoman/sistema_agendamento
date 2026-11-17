<?php

namespace App\Services;

use App\Mail\CompanyInvitationMail;
use App\Models\Company;
use App\Models\CompanyInvitation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class CompanyInvitationService
{
    public function create(
        string $email,
        User $createdBy,
        ?string $phone = null,
        int $validityDays = 7,
        ?string $companyName = null
    ): CompanyInvitation {
        if (! $createdBy->isPlatformAdministrator()) {
            throw new AuthorizationException(
                'Somente administradores globais ativos podem criar convites empresariais.'
            );
        }

        if ($validityDays < 1) {
            throw new InvalidArgumentException(
                'A validade do convite deve ser de pelo menos um dia.'
            );
        }

        $email = mb_strtolower(trim($email));

        $companyName = $companyName !== null
            ? trim($companyName)
            : null;

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                'O endereço de e-mail informado é inválido.'
            );
        }

        if ($companyName === '') {
            throw new InvalidArgumentException(
                'O nome sugerido da empresa não pode ficar vazio.'
            );
        }

        if (User::query()->where('email', $email)->exists()) {
            throw new InvalidArgumentException(
                'Este e-mail já possui uma conta na plataforma. A criação da empresa deverá ser realizada pelo superusuário global, sem convite.'
            );
        }

        $existingInvitation = CompanyInvitation::query()
            ->where('email', $email)
            ->whereNull('used_at')
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->exists();

        if ($existingInvitation) {
            throw new InvalidArgumentException(
                'Já existe um convite empresarial ativo para este endereço de e-mail.'
            );
        }

        $token = Str::random(64);

        $invitation = CompanyInvitation::create([
            'company_name' => $companyName,
            'email' => $email,
            'phone' => $phone,
            'token_hash' => hash('sha256', $token),
            'expires_at' => now()->addDays($validityDays),
            'created_by' => $createdBy->id,
        ]);

        $invitationUrl = route(
            'company-invitations.show',
            ['token' => $token]
        );

        try {
            Mail::to($email)->send(
                new CompanyInvitationMail(
                    companyName: $companyName,
                    invitationUrl: $invitationUrl,
                    expiresAt: $invitation->expires_at
                        ->format('d/m/Y H:i')
                )
            );
        } catch (Throwable $exception) {
            $invitation->update([
                'revoked_at' => now(),
            ]);

            report($exception);

            throw new RuntimeException(
                'Não foi possível enviar o convite por e-mail. O convite foi invalidado. Tente novamente.'
            );
        }

        return $invitation;
    }

    public function findAvailableByToken(
        string $token
    ): ?CompanyInvitation {
        if (trim($token) === '') {
            return null;
        }

        $tokenHash = hash('sha256', $token);

        $invitation = CompanyInvitation::query()
            ->where('token_hash', $tokenHash)
            ->first();

        if (! $invitation || ! $invitation->isAvailable()) {
            return null;
        }

        return $invitation;
    }

    /**
     * Conclui o cadastro empresarial por convite.
     */
    public function consume(
        string $token,
        array $companyData
    ): User {
        if (trim($token) === '') {
            throw new InvalidArgumentException(
                'O token do convite é obrigatório.'
            );
        }

        $responsibleName = trim(
            $companyData['responsible_name'] ?? ''
        );

        $companyName = trim(
            $companyData['name'] ?? ''
        );

        $companyEmail = mb_strtolower(
            trim($companyData['email'] ?? '')
        );

        $password = $companyData['password'] ?? '';

        if ($responsibleName === '') {
            throw new InvalidArgumentException(
                'O nome do responsável é obrigatório.'
            );
        }

        if ($companyName === '') {
            throw new InvalidArgumentException(
                'O nome da empresa é obrigatório.'
            );
        }

        if (! filter_var($companyEmail, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                'O e-mail de contato da empresa é inválido.'
            );
        }

        if (! is_string($password) || mb_strlen($password) < 8) {
            throw new InvalidArgumentException(
                'A senha deve possuir pelo menos oito caracteres.'
            );
        }

        return DB::transaction(function () use (
            $token,
            $companyData,
            $responsibleName,
            $companyName,
            $companyEmail,
            $password
        ) {
            $tokenHash = hash('sha256', $token);

            $invitation = CompanyInvitation::query()
                ->where('token_hash', $tokenHash)
                ->lockForUpdate()
                ->first();

            if (! $invitation || ! $invitation->isAvailable()) {
                throw new InvalidArgumentException(
                    'O convite é inválido, expirou, foi revogado ou já foi utilizado.'
                );
            }

            $authorizedEmail = mb_strtolower(
                trim($invitation->email)
            );

            if (User::query()->where('email', $authorizedEmail)->exists()) {
                throw new InvalidArgumentException(
                    'Este e-mail já possui uma conta na plataforma. Não é possível criar uma conta duplicada.'
                );
            }

            // Cria a identidade pessoal.
            $user = User::create([
                'name' => $responsibleName,
                'email' => $authorizedEmail,
                'password' => $password,
            ]);

            // O token exclusivo enviado ao e-mail comprova sua posse.
            // forceFill é necessário porque email_verified_at não
            // pertence aos campos liberados para preenchimento em massa.
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();

            // Cria a empresa.
            // O CompanyObserver provisiona os papéis automaticamente.
            $company = Company::create([
                'name' => $companyName,
                'legal_name' => $companyData['legal_name'] ?? null,
                'document' => $companyData['document'] ?? null,
                'email' => $companyEmail,
                'phone' => $companyData['phone'] ?? null,
                'status' => 'active',
                'timezone' => $companyData['timezone']
                    ?? 'America/Sao_Paulo',
            ]);

            // Vincula o responsável à empresa.
            $company->users()->attach($user->id, [
                'status' => 'active',
                'joined_at' => now(),
            ]);

            // Atribui o papel no contexto da empresa.
            $registrar = app(PermissionRegistrar::class);

            $previousTeamId = $registrar->getPermissionsTeamId();

            try {
                $registrar->setPermissionsTeamId($company->id);

                $user->assignRole('superuser_empresa');
            } finally {
                $registrar->setPermissionsTeamId($previousTeamId);
                $registrar->forgetCachedPermissions();
            }

            // Marca o convite como utilizado.
            $invitation->update([
                'used_at' => now(),
                'used_by' => $user->id,
            ]);

            return $user;
        });
    }

    public function revoke(
        CompanyInvitation $invitation,
        User $revokedBy
    ): bool {
        if (! $revokedBy->isPlatformAdministrator()) {
            throw new AuthorizationException(
                'Somente administradores globais ativos podem revogar convites empresariais.'
            );
        }

        return DB::transaction(function () use ($invitation) {
            $lockedInvitation = CompanyInvitation::query()
                ->whereKey($invitation->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedInvitation) {
                return false;
            }

            if (
                $lockedInvitation->used_at !== null
                || $lockedInvitation->revoked_at !== null
            ) {
                return false;
            }

            $lockedInvitation->update([
                'revoked_at' => now(),
            ]);

            return true;
        });
    }
}
