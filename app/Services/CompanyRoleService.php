<?php

namespace App\Services;

use App\Models\Company;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CompanyRoleService
{
    /**
     * Cria ou atualiza os papéis e suas permissões
     * para uma empresa específica.
     */
    public function provisionFor(Company $company): void
    {
        $guardName = config('auth.defaults.guard');

        $registrar = app(PermissionRegistrar::class);

        // Preserva o contexto anterior do Spatie.
        $previousTeamId = $registrar->getPermissionsTeamId();

        $roles = [
            'superuser_empresa' => [
                'appointments.view_all',
                'appointments.create_for_clients',
                'appointments.update',
                'appointments.cancel',
                'clients.search_platform_users',
                'clients.link',
                'professionals.manage',
                'services.manage',
                'schedules.manage',
                'company.manage',
                'company_users.manage',
                'roles.assign',
            ],

            'administrador' => [
                'appointments.view_all',
                'appointments.create_for_clients',
                'appointments.update',
                'appointments.cancel',
                'appointments.book_own',
                'appointments.view_own',
                'appointments.cancel_own',
                'clients.search_platform_users',
                'clients.link',
            ],

            'cliente' => [
                'appointments.book_own',
                'appointments.view_own',
                'appointments.cancel_own',
            ],
        ];

        try {
            $registrar->setPermissionsTeamId($company->id);

            foreach ($roles as $roleName => $permissions) {
                $role = Role::findOrCreate(
                    $roleName,
                    $guardName
                );

                $role->syncPermissions($permissions);
            }
        } finally {
            // Restaura o contexto que existia antes da execução.
            $registrar->setPermissionsTeamId($previousTeamId);

            $registrar->forgetCachedPermissions();
        }
    }
}
