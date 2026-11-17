
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - {{ config('app.name') }}</title>

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

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 36px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
        }

        .login-header {
            margin-bottom: 28px;
            text-align: center;
        }

        .login-header h1 {
            margin: 0 0 10px;
            font-size: 26px;
        }

        .login-header p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 13px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #9ca3af;
            outline-offset: 1px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 14px;
        }

        .remember label {
            margin: 0;
            font-weight: normal;
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

        .links {
            margin-top: 22px;
            text-align: center;
            font-size: 14px;
        }

        .links p {
            margin: 0 0 14px;
        }

        .links a {
            color: #374151;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .error {
            margin-top: 7px;
            color: #b91c1c;
            font-size: 13px;
        }

        .status {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 7px;
            background: #ecfdf5;
            color: #065f46;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 26px 22px;
            }
        }
    </style>
</head>

<body>

    <main class="login-container">

        <header class="login-header">
            <h1>Bem-vindo</h1>
            <p>Entre com seus dados para continuar.</p>
        </header>

        @if (session('status'))
            <div class="status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">E-mail</label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                >

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Senha</label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >

                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember">
                <input
                    id="remember"
                    type="checkbox"
                    name="remember"
                    value="1"
                >

                <label for="remember">Manter conectado</label>
            </div>

            <button class="button" type="submit">
                Entrar
            </button>
        </form>

        <div class="links">
            <p>
                Não tem uma conta?
                <a href="{{ route('register') }}">
                    Cadastre-se
                </a>
            </p>

            <a href="{{ route('password.request') }}">
                Esqueci minha senha
            </a>
        </div>

    </main>

</body>
</html>
