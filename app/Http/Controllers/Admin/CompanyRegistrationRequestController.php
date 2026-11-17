<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyRegistrationRequest;
use App\Services\CompanyRegistrationApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyRegistrationRequestController extends Controller
{
    /**
     * Exibe as solicitações de cadastro empresarial.
     */
    public function index(): View
    {
        $requests = CompanyRegistrationRequest::query()
            ->with([
                'user',
                'invitation',
                'reviewer',
                'company',
            ])
            ->latest()
            ->paginate(15);

        return view('admin.registration-requests.index', [
            'requests' => $requests,
        ]);
    }

    /**
     * Aprova uma solicitação e cria a empresa.
     */
    public function approve(
        CompanyRegistrationRequest $registrationRequest,
        CompanyRegistrationApprovalService $service,
        Request $request
    ): RedirectResponse {
        $service->approve(
            $registrationRequest,
            $request->user()
        );

        return redirect()
            ->route('platform.admin.registration-requests.index')
            ->with(
                'success',
                'Solicitação aprovada e empresa cadastrada com sucesso.'
            );
    }

    /**
     * Rejeita uma solicitação empresarial.
     */
    public function reject(
        CompanyRegistrationRequest $registrationRequest,
        CompanyRegistrationApprovalService $service,
        Request $request
    ): RedirectResponse {
        $data = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        $service->reject(
            $registrationRequest,
            $request->user(),
            $data['rejection_reason']
        );

        return redirect()
            ->route('platform.admin.registration-requests.index')
            ->with(
                'success',
                'Solicitação empresarial rejeitada.'
            );
    }
}
