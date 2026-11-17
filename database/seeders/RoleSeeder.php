<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Services\CompanyRoleService;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Provisiona os papéis de todas as empresas cadastradas.
     */
    public function run(): void
    {
        $service = app(CompanyRoleService::class);

        $companies = Company::query()->get();

        foreach ($companies as $company) {
            $service->provisionFor($company);
        }
    }
}
