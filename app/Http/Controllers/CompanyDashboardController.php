<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class CompanyDashboardController extends Controller
{
    /**
     * Lista as empresas às quais o usuário possui vínculo ativo.
     */
    public function index(Request $request): View
    {
        $companies = $request->user()
            ->companies()
            ->wherePivot('status', 'active')
            ->where('companies.status', 'active')
            ->orderBy('companies.name')
            ->get();

        return view('company.index', compact('companies'));
    }

    /**
     * Exibe o painel de uma empresa específica.
     */
    public function show(
        Request $request,
        Company $company
    ): View {
        $user = $request->user();

        abort_if(
            $company->status !== 'active',
            403,
            'Esta empresa não está ativa.'
        );

        $hasActiveMembership = $user->companies()
            ->whereKey($company->id)
            ->wherePivot('status', 'active')
            ->exists();

        abort_unless(
            $hasActiveMembership,
            403,
            'Você não possui acesso a esta empresa.'
        );

        $registrar = app(PermissionRegistrar::class);

        $previousTeamId = $registrar->getPermissionsTeamId();

        try {
            $registrar->setPermissionsTeamId($company->id);

            $user->unsetRelation('roles');

            $isCompanyAdministrator = $user->hasAnyRole([
                'superuser_empresa',
                'administrador',
            ]);

            $canManageSchedules = $user->hasPermissionTo(
                'schedules.manage'
            );

            abort_unless(
                $isCompanyAdministrator,
                403,
                'Acesso restrito à administração da empresa.'
            );
        } finally {
            $registrar->setPermissionsTeamId($previousTeamId);

            $user->unsetRelation('roles');
        }

        return view(
            'company.dashboard',
            compact('company', 'canManageSchedules')
        );
    }
}
