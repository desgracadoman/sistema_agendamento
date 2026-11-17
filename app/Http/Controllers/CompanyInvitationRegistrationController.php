<?php

namespace App\Http\Controllers;

use App\Services\CompanyInvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use InvalidArgumentException;

class CompanyInvitationRegistrationController extends Controller
{
    /**
     * Exibe o formulário de conclusão do cadastro empresarial.
     */
    public function show(
        string $token,
        CompanyInvitationService $service
    ): View {
        $invitation = $service->findAvailableByToken($token);

        abort_unless($invitation, 404);

        return view('company-invitations.show', [
            'invitation' => $invitation,
        ]);
    }

    /**
     * Conclui o cadastro pessoal e empresarial pelo convite.
     */
    public function store(
        Request $request,
        string $token,
        CompanyInvitationService $service
    ): RedirectResponse {
        $data = $request->validate([
            'responsible_name' => [
                'required',
                'string',
                'max:255',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'legal_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'document' => [
                'nullable',
                'string',
                'max:14',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'timezone' => [
                'required',
                'timezone',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        try {
            $user = $service->consume(
                $token,
                $data
            );
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'invitation' => $exception->getMessage(),
            ]);
        }

        // Autentica o responsável após a conclusão do cadastro.
        Auth::login($user);

        // Renova a sessão para evitar fixação de sessão.
        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with(
                'success',
                'Cadastro empresarial concluído com sucesso. Sua conta já está vinculada à empresa como superusuário.'
            );
    }
}
