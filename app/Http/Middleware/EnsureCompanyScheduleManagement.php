<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyScheduleManagement
{
    /**
     * Permite a gestão de agendas somente a usuários
     * com vínculo ativo e permissão na empresa informada.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        // A empresa deve ser resolvida pela rota {company}.
        $company = $request->route('company');

        if (! $company instanceof Company) {
            abort(404, 'Empresa não encontrada.');
        }

        // Impede acesso a empresas inativas.
        if ($company->status !== 'active') {
            abort(403, 'Esta empresa não está ativa.');
        }

        // Confirma que o usuário possui vínculo ativo.
        $hasActiveMembership = $user->companies()
            ->whereKey($company->id)
            ->wherePivot('status', 'active')
            ->exists();

        if (! $hasActiveMembership) {
            abort(403, 'Você não possui acesso a esta empresa.');
        }

        $registrar = app(PermissionRegistrar::class);

        // Preserva o contexto anterior do Spatie.
        $previousTeamId = $registrar->getPermissionsTeamId();

        try {
            // Define a empresa para verificar as permissões.
            $registrar->setPermissionsTeamId($company->id);

            // Evita reutilizar papéis carregados em outro contexto.
            $user->unsetRelation('roles');

            if (! $user->hasPermissionTo('schedules.manage')) {
                abort(
                    403,
                    'Você não possui permissão para gerenciar agendas nesta empresa.'
                );
            }

            return $next($request);
        } finally {
            // Restaura o contexto anterior.
            $registrar->setPermissionsTeamId($previousTeamId);

            // Evita manter papéis carregados no contexto da empresa.
            $user->unsetRelation('roles');
        }
    }
}
