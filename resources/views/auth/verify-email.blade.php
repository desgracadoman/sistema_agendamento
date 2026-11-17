
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verificar e-mail - {{ config('app.name') }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #f3f4f6;
            font-family: Arial, sans-serif;
            color: #1f2937;
        }

        .verification-container {
            width: 100%;
            max-width: 460px;
            padding: 36px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
        }

        .verification-header {
            margin-bottom: 28px;
            text-align: center;
        }

        .verification-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 62px;
            height: 62px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #eef2ff;
            color: #4338ca;
            font-size: 28px;
        }

        .verification-header h1 {
            margin: 0 0 12px;
            font-size: 25px;
        }

        .verification-header p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.7;
        }

        .user-email {
            margin-top: 14px;
            color: #374151;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 7px;
            background: #374151;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .button:hover {
            background: #1f2937;
        }

        .status {
            margin-bottom: 20px;
            padding: 13px;
            border-radius: 7px;
            background: #ecfdf5;
            color: #065f46;
            font-size: 14px;
            line-height: 1.5;
        }

        .error {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 7px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 14px;
            line-height: 1.5;
        }

        .logout-area {
            margin-top: 22px;
            text-align: center;
        }

        .logout-button {
            padding: 8px 12px;
            border: none;
            background: transparent;
            color: #4b5563;
            font-size: 14px;
            cursor: pointer;
        }

        .logout-button:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .verification-container {
                padding: 28px 22px;
            }
        }
    </style>
</head>

<body>

    <main class="verification-container">

        <header class="verification-header">

            <div class="verification-icon">
                &#9993;
            </div>

            <h1>Confirme seu e-mail</h1>

            <p>
                Antes de continuar, precisamos confirmar
                que este endereço de e-mail pertence a você.
                Enviamos um link de verificação para:
            </p>

            <div class="user-email">
                {{ auth()->user()->email }}
            </div>

        </header>

        @if (session('status') === 'verification-link-sent')
            <div class="status">
                Um novo link de verificação foi enviado
                para o seu endereço de e-mail.
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button class="button" type="submit">
                Reenviar link de verificação
            </button>
        </form>

        <div class="logout-area">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="logout-button" type="submit">
                    Sair da conta
                </button>
            </form>

        </div>

    </main>

</body>
</html>
