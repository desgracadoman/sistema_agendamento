<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agendas | {{ $company->name }}</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6fa;
            color: #1e293b;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 255px;
            background: #172554;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 28px 18px;
            z-index: 10;
        }

        .brand {
            padding: 0 12px 30px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        .brand strong {
            display: block;
            font-size: 19px;
            margin-bottom: 7px;
        }

        .brand span {
            color: #a5b4fc;
            font-size: 12px;
        }

        .company-name {
            padding: 22px 12px;
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.5;
        }

        .company-name strong {
            display: block;
            color: #fff;
            font-size: 15px;
            margin-top: 5px;
        }

        .menu-label {
            padding: 10px 12px;
            color: #94a3b8;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 12px;
            margin-bottom: 5px;
            border-radius: 8px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            transition: .2s;
        }

        .menu a:hover,
        .menu a.active {
            background: #263b78;
            color: #fff;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 18px 12px 0;
            border-top: 1px solid rgba(255,255,255,.12);
        }

        .sidebar-footer a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13px;
        }

        .main {
            margin-left: 255px;
            min-height: 100vh;
        }

        .topbar {
            min-height: 76px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 38px;
        }

        .topbar h2 {
            font-size: 17px;
            font-weight: 600;
        }

        .user-info {
            color: #64748b;
            font-size: 13px;
        }

        .content {
            padding: 38px;
            max-width: 1250px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .page-header h1 {
            font-size: 26px;
            color: #172554;
            margin-bottom: 9px;
        }

        .page-header p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .primary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 19px;
            background: #263b78;
            color: #fff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .primary-button:hover {
            background: #1e3065;
        }

        .content-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
            overflow: hidden;
        }

        .card-header {
            padding: 22px 25px;
            border-bottom: 1px solid #e5e7eb;
        }

        .card-header h2 {
            font-size: 16px;
            color: #334155;
        }

        .empty-state {
            min-height: 340px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 35px 20px;
        }

        .empty-icon {
            width: 68px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef2ff;
            color: #3730a3;
            border-radius: 18px;
            font-size: 30px;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #1e293b;
        }

        .empty-state p {
            max-width: 390px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        .agenda-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            padding: 25px;
        }

        .agenda-item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 22px;
            background: #fff;
            transition: .2s;
        }

        .agenda-item:hover {
            border-color: #c7d2fe;
            box-shadow: 0 5px 16px rgba(15, 23, 42, .06);
        }

        .agenda-item h3 {
            color: #172554;
            font-size: 17px;
            margin-bottom: 16px;
            line-height: 1.4;
        }

        .agenda-detail {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.5;
        }

        .agenda-detail strong {
            color: #334155;
            font-weight: 600;
        }

        .agenda-detail .detail-icon {
            width: 18px;
            flex-shrink: 0;
            text-align: center;
        }

        .agenda-divider {
            border: 0;
            border-top: 1px solid #e5e7eb;
            margin: 17px 0;
        }

        .agenda-capacity {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 10px;
            background: #eef2ff;
            color: #3730a3;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .agenda-footer {
            margin-top: 17px;
            color: #94a3b8;
            font-size: 11px;
        }

        .agenda-actions {
            display: flex;
            flex-direction: column;
            gap: 9px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .secondary-button,
        .public-button,
        .copy-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 40px;
            padding: 11px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .secondary-button {
            background: #eef2ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
        }

        .secondary-button:hover {
            background: #e0e7ff;
            border-color: #a5b4fc;
        }

        .public-button {
            background: #263b78;
            color: #fff;
            border: 1px solid #263b78;
        }

        .public-button:hover {
            background: #1e3065;
        }

        .copy-button {
            background: #fff;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .copy-button:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .copy-feedback {
            min-height: 16px;
            color: #15803d;
            font-size: 11px;
            text-align: center;
        }

        @media (max-width: 760px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
                padding: 18px;
            }

            .company-name,
            .menu-label,
            .sidebar-footer {
                display: none;
            }

            .menu {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                margin-top: 15px;
            }

            .menu a {
                margin: 0;
                padding: 10px;
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                padding: 15px 20px;
                gap: 12px;
                flex-wrap: wrap;
            }

            .content {
                padding: 25px 18px;
            }

            .page-header h1 {
                font-size: 22px;
            }

            .primary-button {
                width: 100%;
            }

            .agenda-list {
                grid-template-columns: 1fr;
                padding: 18px;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">

        <div class="brand">
            <strong>AgendaPro</strong>
            <span>Gestão de serviços e eventos</span>
        </div>

        <div class="company-name">
            Ambiente empresarial
            <strong>{{ $company->name }}</strong>
        </div>

        <div class="menu-label">Menu principal</div>

        <nav class="menu">

            <a href="{{ route('company.dashboard', $company) }}">
                <span>⌂</span>
                Visão geral
            </a>

            <a href="{{ route('company.schedules.index', $company) }}"
               class="active">
                <span>▦</span>
                Agendas
            </a>

        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('home') }}">← Voltar à minha conta</a>
        </div>

    </aside>

    <main class="main">

        <header class="topbar">
            <h2>Gestão de agendas</h2>

            <div class="user-info">
                {{ auth()->user()->name }}
            </div>
        </header>

        <section class="content">

            <div class="page-header">

                <div>
                    <h1>Agendas</h1>

                    <p>
                        Organize eventos, horários e reservas dos
                        profissionais da sua empresa.
                    </p>
                </div>

                <a
                    href="{{ route('company.schedules.create', $company) }}"
                    class="primary-button"
                >
                    <span>＋</span>
                    Adicionar agenda
                </a>

            </div>

            <div class="content-card">

                <div class="card-header">
                    <h2>Suas agendas</h2>
                </div>

                @if ($agendas->isEmpty())

                    <div class="empty-state">

                        <div class="empty-icon">▦</div>

                        <h3>Nenhuma agenda cadastrada</h3>

                        <p>
                            Comece criando uma agenda para organizar
                            os atendimentos de um evento, distribuir
                            horários e disponibilizar reservas.
                        </p>

                        <a
                            href="{{ route('company.schedules.create', $company) }}"
                            class="primary-button"
                        >
                            ＋ Criar primeira agenda
                        </a>

                    </div>

                @else

                    <div class="agenda-list">

                        @foreach ($agendas as $agenda)

                            @php
                                $agendaId = data_get($agenda, 'agenda_id');

                                $linkPublico = route(
                                    'public.schedules.show',
                                    [
                                        'company' => $company,
                                        'agendaId' => $agendaId,
                                    ]
                                );
                            @endphp

                            <article class="agenda-item">

                                <h3>
                                    {{ data_get($agenda, 'evento.nome', 'Evento sem nome') }}
                                </h3>

                                <div class="agenda-detail">
                                    <span class="detail-icon">▦</span>

                                    <span>
                                        <strong>Data:</strong>

                                        @if (data_get($agenda, 'evento.data'))
                                            {{ \Carbon\Carbon::parse(data_get($agenda, 'evento.data'))->format('d/m/Y') }}
                                        @else
                                            Não informada
                                        @endif
                                    </span>
                                </div>

                                <div class="agenda-detail">
                                    <span class="detail-icon">◷</span>

                                    <span>
                                        <strong>Horário:</strong>
                                        {{ data_get($agenda, 'evento.hora_inicio', '--:--') }}
                                        às
                                        {{ data_get($agenda, 'previsao.hora_fim', '--:--') }}
                                    </span>
                                </div>

                                <div class="agenda-detail">
                                    <span class="detail-icon">✦</span>

                                    <span>
                                        <strong>Serviço:</strong>
                                        {{ data_get($agenda, 'servico.tipo', 'Não informado') }}
                                    </span>
                                </div>

                                <div class="agenda-detail">
                                    <span class="detail-icon">◴</span>

                                    <span>
                                        <strong>Duração:</strong>

                                        @php
                                            $duracao = (int) data_get(
                                                $agenda,
                                                'servico.duracao_minutos',
                                                0
                                            );
                                        @endphp

                                        @if ($duracao > 0)
                                            {{ $duracao }} minutos
                                        @else
                                            Não informada
                                        @endif
                                    </span>
                                </div>

                                <hr class="agenda-divider">

                                @php
                                    $profissionais = data_get(
                                        $agenda,
                                        'profissionais',
                                        []
                                    );

                                    $quantidadeProfissionais = is_array($profissionais)
                                        ? count($profissionais)
                                        : 0;
                                @endphp

                                <div class="agenda-capacity">
                                    <span>♙</span>

                                    {{ $quantidadeProfissionais }}
                                    {{ $quantidadeProfissionais === 1 ? 'profissional' : 'profissionais' }}
                                </div>

                                <div class="agenda-footer">
                                    Criada em

                                    @if (data_get($agenda, 'created_at'))
                                        {{ \Carbon\Carbon::parse(data_get($agenda, 'created_at'))->format('d/m/Y H:i') }}
                                    @else
                                        data não informada
                                    @endif
                                </div>

                                <div class="agenda-actions">

                                    <a
                                        href="{{ $linkPublico }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="public-button"
                                    >
                                        Abrir página pública ↗
                                    </a>

                                    <button
                                        type="button"
                                        class="copy-button"
                                        data-copy-url="{{ $linkPublico }}"
                                    >
                                        Copiar link público
                                    </button>

                                    <div
                                        class="copy-feedback"
                                        aria-live="polite"
                                    ></div>

                                    <a
                                        href="{{ route(
                                            'company.schedules.reservations.index',
                                            [
                                                'company' => $company,
                                                'agendaId' => $agendaId,
                                            ]
                                        ) }}"
                                        class="secondary-button"
                                    >
                                        Ver reservas
                                    </a>

                                </div>

                            </article>

                        @endforeach

                    </div>

                @endif

            </div>

        </section>

    </main>

    <script>
        document.querySelectorAll('[data-copy-url]').forEach(function (button) {

            button.addEventListener('click', async function () {

                const url = button.dataset.copyUrl;
                const feedback = button.nextElementSibling;

                try {
                    await navigator.clipboard.writeText(url);

                    feedback.textContent = 'Link copiado com sucesso!';
                } catch (error) {
                    const campo = document.createElement('textarea');

                    campo.value = url;
                    campo.setAttribute('readonly', '');
                    campo.style.position = 'absolute';
                    campo.style.left = '-9999px';

                    document.body.appendChild(campo);

                    campo.select();

                    const copiado = document.execCommand('copy');

                    campo.remove();

                    feedback.textContent = copiado
                        ? 'Link copiado com sucesso!'
                        : 'Não foi possível copiar automaticamente.';

                    if (!copiado) {
                        window.prompt('Copie o link público:', url);
                    }
                }

                window.setTimeout(function () {
                    feedback.textContent = '';
                }, 4000);

            });

        });
    </script>

</body>
</html>
