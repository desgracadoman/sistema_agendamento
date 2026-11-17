<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyRegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\PermissionRegistrar;

class CompanyRegistrationApprovalService
{
    /**
     * Aprova uma solicitação e cria a empresa.
     *
     * Toda a operação é executada em uma única transação.
     */
    public function approve(
        CompanyRegistrationRequest $request,
        User $reviewer
    ): Company {
        return DB::transaction(function () use ($request, $reviewer) {
            $this->ensurePlatformAdministrator($reviewer);

            $lockedRequest = CompanyRegistrationRequest::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! $lockedRequest->isPending()) {
                throw ValidationException::withMessages([
                    'request' => 'Esta solicitação não está mais pendente.',
                ]);
            }

            if ($lockedRequest->company_id !== null) {
                throw ValidationException::withMessages([
                    'request' => 'Esta solicitação já possui uma empresa vinculada.',
                ]);
            }

            $invitation = $lockedRequest->invitation()
                ->lockForUpdate()
                ->first();

            if (
                ! $invitation
                || $invitation->used_at === null
                || (int) $invitation->used_by !== (int) $lockedRequest->user_id
            ) {
                throw ValidationException::withMessages([
                    'request' => 'O convite associado à solicitação não possui um consumo válido.',
                ]);
            }

            $user = User::query()->findOrFail($lockedRequest->user_id);

            $company = Company::create([
                'name' => $lockedRequest->name,
                'legal_name' => $lockedRequest->legal_name,
                'document' => $lockedRequest->document,
                'email' => $lockedRequest->email,
                'phone' => $lockedRequest->phone,
                'status' => 'active',
                'timezone' => $lockedRequest->timezone,
            ]);

            // O CompanyObserver provisiona os papéis automaticamente.
            // Agora atribuímos o superusuário no contexto da empresa.
            $registrar = app(PermissionRegistrar::class);

            $previousTeamId = $registrar->getPermissionsTeamId();

            try {
                $registrar->setPermissionsTeamId($company->id);

                $user->companies()->attach($company->id, [
                    'status' => 'active',
                    'joined_at' => now(),
                ]);

                $user->assignRole('superuser_empresa');
            } finally {
                $registrar->setPermissionsTeamId($previousTeamId);

                $registrar->forgetCachedPermissions();
            }

            $lockedRequest->update([
                'status' => CompanyRegistrationRequest::STATUS_APPROVED,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
                'company_id' => $company->id,
            ]);

            return $company;
        });
    }

    /**
     * Rejeita uma solicitação empresarial.
     *
     * Nenhuma empresa é criada nesta operação.
     */
    public function reject(
        CompanyRegistrationRequest $request,
        User $reviewer,
        string $reason
    ): CompanyRegistrationRequest {
        $reason = trim($reason);

        if ($reason === '') {
            throw ValidationException::withMessages([
                'rejection_reason' => 'O motivo da rejeição é obrigatório.',
            ]);
        }

        return DB::transaction(function () use (
            $request,
            $reviewer,
            $reason
        ) {
            $this->ensurePlatformAdministrator($reviewer);

            $lockedRequest = CompanyRegistrationRequest::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! $lockedRequest->isPending()) {
                throw ValidationException::withMessages([
                    'request' => 'Esta solicitação não está mais pendente.',
                ]);
            }

            $lockedRequest->update([
                'status' => CompanyRegistrationRequest::STATUS_REJECTED,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
                'rejection_reason' => $reason,
            ]);

            return $lockedRequest;
        });
    }

    /**
     * Garante que a decisão seja tomada por um administrador
     * global ativo da plataforma.
     */
    private function ensurePlatformAdministrator(User $user): void
    {
        if (! $user->isPlatformAdministrator()) {
            throw new AuthorizationException(
                'Somente administradores globais ativos podem analisar solicitações empresariais.'
            );
        }
    }
}
