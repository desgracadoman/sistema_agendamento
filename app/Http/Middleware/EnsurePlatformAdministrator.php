<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlatformAdministrator
{
    /**
     * Permite acesso somente a administradores gerais ativos.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            !$user ||
            !$user->isPlatformAdministrator()
        ) {
            abort(403, 'Acesso restrito aos administradores da plataforma.');
        }

        return $next($request);
    }
}