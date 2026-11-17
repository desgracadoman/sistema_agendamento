<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInvitation;
use App\Services\CompanyInvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;

class CompanyInvitationController extends Controller
{
    /**
     * Exibe os convites empresariais cadastrados.
     */
    public function index(): View
    {
        $invitations = CompanyInvitation::query()
            ->with('creator')
            ->latest()
            ->paginate(15);

        return view('admin.invitations.index', [
            'invitations' => $invitations,
        ]);
    }

    /**
     * Cria um convite empresarial e envia o link por e-mail.
     */
    public function store(
        Request $request,
        CompanyInvitationService $service
    ): RedirectResponse {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'validity_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        try {
            $service->create(
                $data['email'],
                $request->user(),
                $data['phone'] ?? null,
                (int) ($data['validity_days'] ?? 7),
                $data['company_name']
            );
        } catch (InvalidArgumentException | RuntimeException $exception) {
            return redirect()
                ->route('platform.admin.invitations.index')
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('platform.admin.invitations.index')
            ->with(
                'success',
                'Convite empresarial criado e enviado ao endereço de e-mail informado.'
            );
    }

    /**
     * Revoga um convite empresarial ainda não utilizado.
     */
    public function revoke(
        CompanyInvitation $invitation,
        CompanyInvitationService $service,
        Request $request
    ): RedirectResponse {
        $revoked = $service->revoke(
            $invitation,
            $request->user()
        );

        if (! $revoked) {
            return redirect()
                ->route('platform.admin.invitations.index')
                ->with(
                    'error',
                    'O convite não pode ser revogado. Ele pode já ter sido utilizado ou revogado.'
                );
        }

        return redirect()
            ->route('platform.admin.invitations.index')
            ->with(
                'success',
                'Convite empresarial revogado com sucesso.'
            );
    }
}
