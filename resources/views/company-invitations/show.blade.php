
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concluir cadastro empresarial</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 15px;
            background: #f3f4f6;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        main {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
        }

        h1 {
            margin-top: 0;
            font-size: 25px;
        }

        h2 {
            margin-top: 30px;
            font-size: 19px;
        }

        p {
            line-height: 1.6;
        }

        .field {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
        }

        input[readonly] {
            background: #f3f4f6;
            color: #4b5563;
        }

        button {
            width: 100%;
            margin-top: 15px;
            padding: 14px;
            border: none;
            border-radius: 6px;
            background: #374151;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1f2937;
        }

        .errors {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #fecaca;
            border-radius: 6px;
            background: #fef2f2;
            color: #991b1b;
        }

        .success {
            margin: 20px 0;
            padding: 15px;
            border-radius: 6px;
            background: #ecfdf5;
            color: #065f46;
        }

        .help {
            color: #6b7280;
            font-size: 13px;
        }

        @media (max-width: 500px) {
            main {
                padding: 22px 18px;
            }
        }
    </style>
</head>

<body>
<main>

    <h1>Concluir cadastro empresarial</h1>

    <p>
        Você recebeu um convite autorizado para cadastrar uma empresa
        na plataforma. Preencha os dados abaixo para criar sua conta
        pessoal e concluir o cadastro empresarial.
    </p>

    <section>
        <h2>Dados do convite</h2>

        <p>
            <strong>Empresa sugerida:</strong><br>
            {{ $invitation->company_name ?? 'Não informada' }}
        </p>

        <p>
            <strong>E-mail autorizado:</strong><br>
            {{ $invitation->email }}
        </p>

        <p class="help">
            Este endereço foi autorizado pelo convite e não poderá
            ser alterado neste formulário.
        </p>

        <p>
            <strong>Validade do convite:</strong><br>
            {{ $invitation->expires_at->format('d/m/Y H:i') }}
        </p>
    </section>

    @if ($errors->any())
        <section class="errors">
            <strong>Verifique os dados informados:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </section>
    @endif

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('company-invitations.store', ['token' => request()->route('token')]) }}">

        @csrf

        <h2>Dados do responsável</h2>

        <div class="field">
            <label for="responsible_name">
                Nome completo
            </label>

            <input
                type="text"
                id="responsible_name"
                name="responsible_name"
                value="{{ old('responsible_name') }}"
                maxlength="255"
                autocomplete="name"
                required
            >
        </div>

        <h2>Dados da empresa</h2>

        <div class="field">
            <label for="name">
                Nome da empresa
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $invitation->company_name) }}"
                maxlength="255"
                required
            >
        </div>

        <div class="field">
            <label for="legal_name">
                Razão social (opcional)
            </label>

            <input
                type="text"
                id="legal_name"
                name="legal_name"
                value="{{ old('legal_name') }}"
                maxlength="255"
            >
        </div>

        <div class="field">
            <label for="document">
                CNPJ (opcional)
            </label>

            <input
                type="text"
                id="document"
                name="document"
                value="{{ old('document') }}"
                maxlength="14"
            >
        </div>

        <div class="field">
            <label for="email">
                E-mail de contato da empresa
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                maxlength="255"
                autocomplete="email"
                required
            >

            <p class="help">
                Este endereço pode ser diferente do e-mail autorizado
                no convite.
            </p>
        </div>

        <div class="field">
            <label for="phone">
                Telefone de contato (opcional)
            </label>

            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old('phone', $invitation->phone) }}"
                maxlength="20"
                autocomplete="tel"
            >
        </div>

        <div class="field">
            <label for="timezone">
                Fuso horário
            </label>

            <select id="timezone" name="timezone" required>
                <option
                    value="America/Sao_Paulo"
                    @selected(old('timezone', 'America/Sao_Paulo') === 'America/Sao_Paulo')
                >
                    Brasília — America/Sao_Paulo
                </option>
            </select>
        </div>

        <h2>Criação da senha de acesso</h2>

        <div class="field">
            <label for="password">
                Senha
            </label>

            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                autocomplete="new-password"
                required
            >

            <p class="help">
                A senha deve possuir pelo menos oito caracteres.
            </p>
        </div>

        <div class="field">
            <label for="password_confirmation">
                Confirmar senha
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="8"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit">
            Criar conta e concluir cadastro empresarial
        </button>

    </form>

</main>
</body>
</html>
