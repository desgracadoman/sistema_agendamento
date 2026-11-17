<?php

namespace App\Observers;

use App\Models\Company;
use App\Services\CompanyRoleService;

class CompanyObserver
{
    /**
     * Provisiona os papéis quando uma empresa é criada.
     */
    public function created(Company $company): void
    {
        app(CompanyRoleService::class)->provisionFor($company);
    }

    /**
     * Handle the Company "updated" event.
     */
    public function updated(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "restored" event.
     */
    public function restored(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "force deleted" event.
     */
    public function forceDeleted(Company $company): void
    {
        //
    }
}
