<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Permissões disponíveis na plataforma.
     */
    public function run(): void
    {
        $guardName = config('auth.defaults.guard');

        $permissions = [
            // Agendamentos
            'appointments.view_all',
            'appointments.create_for_clients',
            'appointments.update',
            'appointments.cancel',
            'appointments.book_own',
            'appointments.view_own',
            'appointments.cancel_own',

            // Clientes
            'clients.search_platform_users',
            'clients.link',

            // Profissionais
            'professionals.manage',

            // Serviços
            'services.manage',

            // Agendas
            'schedules.manage',

            // Empresa
            'company.manage',

            // Usuários e permissões da empresa
            'company_users.manage',
            'roles.assign',
        ];

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, $guardName);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
