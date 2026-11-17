<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Contracts\View\View;

class PlatformDashboardController extends Controller
{
    /**
     * Exibe o painel administrativo geral da plataforma.
     */
    public function index(): View
    {
        $companies = Company::query()
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', [
            'companies' => $companies,
        ]);
    }
}
