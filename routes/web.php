<?php

use App\Http\Controllers\Admin\CompanyInvitationController;
use App\Http\Controllers\Admin\CompanyRegistrationRequestController;
use App\Http\Controllers\Admin\PlatformDashboardController;
use App\Http\Controllers\CompanyDashboardController;
use App\Http\Controllers\CompanyInvitationRegistrationController;
use App\Http\Controllers\CompanyScheduleController;
use App\Http\Controllers\PublicScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Fluxo público de conclusão do cadastro empresarial.
Route::get('/empresa/convite/{token}', [
    CompanyInvitationRegistrationController::class,
    'show',
])->name('company-invitations.show');

Route::post('/empresa/convite/{token}', [
    CompanyInvitationRegistrationController::class,
    'store',
])->name('company-invitations.store');

// Agendamento público, sem necessidade de autenticação.
Route::get('/agendar/{company}/{agendaId}', [
    PublicScheduleController::class,
    'show',
])
    ->whereUuid('agendaId')
    ->name('public.schedules.show');

Route::post('/agendar/{company}/{agendaId}', [
    PublicScheduleController::class,
    'store',
])
    ->whereUuid('agendaId')
    ->name('public.schedules.store');

// Área dos usuários autenticados e com e-mail confirmado.
Route::middleware(['auth', 'verified'])->group(function () {

    // Painel geral da conta.
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Área empresarial.
    Route::prefix('empresa')
        ->name('company.')
        ->group(function () {

            // Lista as empresas vinculadas ao usuário.
            Route::get('/', [
                CompanyDashboardController::class,
                'index',
            ])->name('index');

            // Rotas de gestão de agendas.
            Route::middleware('company.schedules.manage')
                ->prefix('{company}/agendas')
                ->name('schedules.')
                ->group(function () {

                    // Lista as agendas da empresa.
                    Route::get('/', [
                        CompanyScheduleController::class,
                        'index',
                    ])->name('index');

                    // Exibe o formulário de criação.
                    Route::get('/criar', [
                        CompanyScheduleController::class,
                        'create',
                    ])->name('create');

                    // Valida os dados e apresenta a revisão da agenda.
                    Route::post('/revisar', [
                        CompanyScheduleController::class,
                        'review',
                    ])->name('review');

                    // Confirma a criação e grava a agenda em JSON.
                    Route::post('/confirmar', [
                        CompanyScheduleController::class,
                        'store',
                    ])->name('store');

                    // Lista as reservas recebidas por uma agenda.
                    Route::get('/{agendaId}/reservas', [
                        CompanyScheduleController::class,
                        'reservations',
                    ])
                        ->whereUuid('agendaId')
                        ->name('reservations.index');
                });

            // Abre o painel de uma empresa específica.
            Route::get('/{company}', [
                CompanyDashboardController::class,
                'show',
            ])->name('dashboard');
        });
});

// Área administrativa geral da plataforma.
Route::middleware(['auth', 'platform.admin'])
    ->prefix('admin')
    ->name('platform.admin.')
    ->group(function () {

        // Painel administrativo.
        Route::get('/', [PlatformDashboardController::class, 'index'])
            ->name('dashboard');

        // Administração dos convites empresariais.
        Route::get('/convites', [CompanyInvitationController::class, 'index'])
            ->name('invitations.index');

        Route::post('/convites', [CompanyInvitationController::class, 'store'])
            ->name('invitations.store');

        Route::delete('/convites/{invitation}', [CompanyInvitationController::class, 'revoke'])
            ->name('invitations.revoke');

        // Rotas antigas de solicitações, preservadas temporariamente.
        Route::get('/solicitacoes-empresariais', [
            CompanyRegistrationRequestController::class,
            'index',
        ])->name('registration-requests.index');

        Route::post('/solicitacoes-empresariais/{registrationRequest}/aprovar', [
            CompanyRegistrationRequestController::class,
            'approve',
        ])->name('registration-requests.approve');

        Route::post('/solicitacoes-empresariais/{registrationRequest}/rejeitar', [
            CompanyRegistrationRequestController::class,
            'reject',
        ])->name('registration-requests.reject');
    });
