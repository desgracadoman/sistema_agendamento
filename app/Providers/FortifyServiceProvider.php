<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tela de login.
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // Tela de cadastro.
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // Tela de aviso de verificação de e-mail.
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        // Cadastro de usuários.
        Fortify::createUsersUsing(CreateNewUser::class);

        // Atualização do perfil.
        Fortify::updateUserProfileInformationUsing(
            UpdateUserProfileInformation::class
        );

        // Atualização de senha.
        Fortify::updateUserPasswordsUsing(
            UpdateUserPassword::class
        );

        // Recuperação de senha.
        Fortify::resetUserPasswordsUsing(
            ResetUserPassword::class
        );

        // Limitação de tentativas de login.
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username()))
                .'|'.$request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
