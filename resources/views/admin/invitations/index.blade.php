
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Convites Empresariais</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f3f4f6;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        main {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }

        section {
            margin-top: 25px;
            padding: 25px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        input {
            width: 100%;
            max-width: 450px;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        button {
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            background: #374151;
            color: #ffffff;
            cursor: pointer;
        }

        button:hover {
            background: #1f2937;
        }

        .message {
            margin-top: 18px;
            padding: 13px;
            border-radius: 6px;
        }

        .success {
            background: #ecfdf5;
            color: #065f46;
        }

        .error {
            background: #fef2f2;
            color: #991b1b;
        }

        .info {
            padding: 12px;
            background: #eff6ff;
            color: #1e40af;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1.5;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: #ffffff;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #f9fafb;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            section {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

    <main>

        <h1>Convites Empresariais</h1>

        <p>
            Administração dos convites para cadastro de novas empresas.
        </p>

        <p>
            <a href="{{ route('platform.admin.dashboard') }}">
                Voltar ao painel administrativo
            </a>
        </p>

        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="message error">
                <strong>Verifique os seguintes erros:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section>
            <h2>Criar novo convite</h2>

            <div class="info">
                O convite será enviado automaticamente ao endereço
                de e-mail informado. O destinatário poderá concluir
                o cadastro e definir o nome definitivo da empresa.
            </div>

            <form
                method="POST"
                action="{{ route('platform.admin.invitations.store') }}"
            >
                @csrf

                <div class="form-group">
                    <label for="company_name">
                        Nome sugerido da empresa
                    </label>

                    <input
                        type="text"
                        id="company_name"
                        name="company_name"
                        value="{{ old('company_name') }}"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">
                        E-mail autorizado
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="phone">
                        Telefone (opcional)
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        maxlength="20"
                    >
                </div>

                <div class="form-group">
                    <label for="validity_days">
                        Validade em dias
                    </label>

                    <input
                        type="number"
                        id="validity_days"
                        name="validity_days"
                        value="{{ old('validity_days', 7) }}"
                        min="1"
                        max="365"
                    >
                </div>

                <button type="submit">
                    Criar e enviar convite
                </button>
            </form>
        </section>

        <section>
            <h2>Convites cadastrados</h2>

            @if ($invitations->isEmpty())
                <p>Nenhum convite cadastrado.</p>
            @else
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Empresa sugerida</th>
                                <th>E-mail autorizado</th>
                                <th>Criado por</th>
                                <th>Validade</th>
                                <th>Status</th>
                                <th>Utilizado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($invitations as $invitation)
                                <tr>
                                    <td>
                                        {{ $invitation->company_name ?? 'Não informado' }}
                                    </td>

                                    <td>{{ $invitation->email }}</td>

                                    <td>
                                        {{ $invitation->creator?->name ?? 'Administrador não disponível' }}
                                    </td>

                                    <td>
                                        {{ $invitation->expires_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td>
                                        @if ($invitation->used_at)
                                            Utilizado
                                        @elseif ($invitation->revoked_at)
                                            Revogado
                                        @elseif ($invitation->expires_at->isPast())
                                            Expirado
                                        @else
                                            Disponível
                                        @endif
                                    </td>

                                    <td>
                                        {{ $invitation->used_at?->format('d/m/Y H:i') ?? '—' }}
                                    </td>

                                    <td>
                                        @if ($invitation->isAvailable())
                                            <form
                                                method="POST"
                                                action="{{ route('platform.admin.invitations.revoke', $invitation) }}"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit">
                                                    Revogar
                                                </button>
                                            </form>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $invitations->links() }}
            @endif
        </section>

    </main>

</body>
</html>
