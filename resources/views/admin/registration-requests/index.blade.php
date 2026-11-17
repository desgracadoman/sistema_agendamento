
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Solicitações Empresariais</title>
</head>
<body>

    <main>
        <h1>Solicitações Empresariais</h1>

        <p>
            Análise dos pedidos de cadastro de novas empresas na plataforma.
        </p>

        <p>
            <a href="{{ route('platform.admin.dashboard') }}">
                Voltar ao painel administrativo
            </a>
        </p>

        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p>{{ session('error') }}</p>
        @endif

        @if ($errors->any())
            <div>
                <strong>Verifique os seguintes erros:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section>
            <h2>Solicitações recebidas</h2>

            @if ($requests->isEmpty())
                <p>Nenhuma solicitação empresarial cadastrada.</p>
            @else
                @foreach ($requests as $registrationRequest)
                    <article>
                        <hr>

                        <h3>
                            {{ $registrationRequest->name }}
                        </h3>

                        <p>
                            <strong>Status:</strong>

                            @if ($registrationRequest->status === 'pending')
                                Pendente
                            @elseif ($registrationRequest->status === 'approved')
                                Aprovada
                            @elseif ($registrationRequest->status === 'rejected')
                                Rejeitada
                            @else
                                {{ $registrationRequest->status }}
                            @endif
                        </p>

                        <h4>Dados da empresa</h4>

                        <p>
                            <strong>Nome:</strong>
                            {{ $registrationRequest->name }}
                        </p>

                        <p>
                            <strong>Razão social:</strong>
                            {{ $registrationRequest->legal_name ?? 'Não informada' }}
                        </p>

                        <p>
                            <strong>CNPJ:</strong>
                            {{ $registrationRequest->document ?? 'Não informado' }}
                        </p>

                        <p>
                            <strong>E-mail institucional:</strong>
                            {{ $registrationRequest->email }}
                        </p>

                        <p>
                            <strong>Telefone:</strong>
                            {{ $registrationRequest->phone ?? 'Não informado' }}
                        </p>

                        <p>
                            <strong>Fuso horário:</strong>
                            {{ $registrationRequest->timezone }}
                        </p>

                        <h4>Dados do solicitante</h4>

                        <p>
                            <strong>Nome:</strong>
                            {{ $registrationRequest->user?->name ?? 'Usuário não disponível' }}
                        </p>

                        <p>
                            <strong>E-mail da conta:</strong>
                            {{ $registrationRequest->user?->email ?? 'Não disponível' }}
                        </p>

                        <p>
                            <strong>Convite utilizado:</strong>
                            {{ $registrationRequest->invitation?->email ?? 'Não disponível' }}
                        </p>

                        <p>
                            <strong>Solicitação enviada em:</strong>
                            {{ $registrationRequest->created_at->format('d/m/Y H:i') }}
                        </p>

                        @if ($registrationRequest->status === 'pending')
                            <section>
                                <h4>Analisar solicitação</h4>

                                <form
                                    method="POST"
                                    action="{{ route('platform.admin.registration-requests.approve', $registrationRequest) }}"
                                >
                                    @csrf

                                    <button type="submit">
                                        Aprovar cadastro
                                    </button>
                                </form>

                                <form
                                    method="POST"
                                    action="{{ route('platform.admin.registration-requests.reject', $registrationRequest) }}"
                                >
                                    @csrf

                                    <div>
                                        <label for="rejection_reason_{{ $registrationRequest->id }}">
                                            Motivo da rejeição
                                        </label>

                                        <textarea
                                            id="rejection_reason_{{ $registrationRequest->id }}"
                                            name="rejection_reason"
                                            rows="3"
                                            maxlength="2000"
                                            required
                                        ></textarea>
                                    </div>

                                    <button type="submit">
                                        Rejeitar solicitação
                                    </button>
                                </form>
                            </section>
                        @else
                            <h4>Resultado da análise</h4>

                            <p>
                                <strong>Responsável:</strong>
                                {{ $registrationRequest->reviewer?->name ?? 'Não disponível' }}
                            </p>

                            <p>
                                <strong>Data da análise:</strong>
                                {{ $registrationRequest->reviewed_at?->format('d/m/Y H:i') ?? 'Não disponível' }}
                            </p>

                            @if ($registrationRequest->isRejected())
                                <p>
                                    <strong>Motivo da rejeição:</strong>
                                    {{ $registrationRequest->rejection_reason }}
                                </p>
                            @endif

                            @if ($registrationRequest->isApproved())
                                <p>
                                    <strong>Empresa criada:</strong>
                                    {{ $registrationRequest->company?->name ?? 'Empresa não disponível' }}
                                </p>
                            @endif
                        @endif
                    </article>
                @endforeach

                {{ $requests->links() }}
            @endif
        </section>
    </main>

</body>
</html>
