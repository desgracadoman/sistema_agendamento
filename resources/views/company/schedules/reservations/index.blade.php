
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservas - AgendaPro</title>

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

        .app {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #172554;
            color: #fff;
            padding: 28px 20px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 36px;
        }

        .company-name {
            color: #cbd5e1;
            font-size: 13px;
            margin-bottom: 28px;
            overflow-wrap: anywhere;
        }

        .menu-label {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .menu a {
            display: block;
            color: #dbeafe;
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .menu a:hover,
        .menu a.active {
            background: #263b75;
            color: #fff;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 30px;
        }

        .sidebar-footer a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13px;
        }

        .main {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            height: 72px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 36px;
        }

        .user-name {
            font-size: 14px;
            color: #475569;
        }

        .content {
            padding: 36px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .breadcrumb {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 18px;
        }

        .breadcrumb a {
            color: #4f46e5;
            text-decoration: none;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 28px;
        }

        h1 {
            font-size: 27px;
            margin-bottom: 8px;
            color: #172554;
        }

        .subtitle {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .summary-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 22px;
        }

        .summary-label {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .summary-value {
            color: #172554;
            font-size: 27px;
            font-weight: 700;
        }

        .section {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .section-header {
            padding: 22px 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-header h2 {
            font-size: 17px;
            color: #172554;
            margin-bottom: 6px;
        }

        .section-header p {
            color: #64748b;
            font-size: 13px;
        }

        .reservation-list {
            padding: 20px;
            display: grid;
            gap: 16px;
        }

        .reservation-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
        }

        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .reservation-code {
            color: #64748b;
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-confirmada {
            color: #166534;
            background: #dcfce7;
        }

        .status-cancelada {
            color: #991b1b;
            background: #fee2e2;
        }

        .status-outro {
            color: #475569;
            background: #e2e8f0;
        }

        .reservation-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .field-label {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .field-value {
            color: #1e293b;
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .client-section {
            border-top: 1px solid #e5e7eb;
            margin-top: 18px;
            padding-top: 18px;
        }

        .client-section h3 {
            font-size: 14px;
            color: #172554;
            margin-bottom: 14px;
        }

        .empty-state {
            padding: 55px 24px;
            text-align: center;
        }

        .empty-state h3 {
            color: #172554;
            font-size: 17px;
            margin-bottom: 9px;
        }

        .empty-state p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 210px;
            }

            .content {
                padding: 26px;
            }

            .reservation-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .app {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 18px;
            }

            .brand {
                margin-bottom: 16px;
            }

            .company-name {
                margin-bottom: 16px;
            }

            .sidebar-footer {
                margin-top: 16px;
                padding-top: 10px;
            }

            .topbar {
                height: 58px;
                padding: 0 20px;
            }

            .content {
                padding: 22px 16px;
            }

            .page-header {
                flex-direction: column;
            }

            .summary {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .reservation-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    @php
        $totalReservas = $reservas->count();

        $reservasConfirmadas = $reservas
            ->filter(fn ($reserva) => data_get($reserva, 'status') === 'confirmada')
            ->count();

        $reservasCanceladas = $reservas
            ->filter(fn ($reserva) => data_get($reserva, 'status') === 'cancelada')
            ->count();

        $nomeEvento = data_get($agenda, 'evento.nome', 'Agenda');
        $dataEvento = data_get($agenda, 'evento.data');

        $dataFormatada = $dataEvento
            ? \Carbon\Carbon::parse($dataEvento)->format('d/m/Y')
            : 'Não informada';
    @endphp

    <div class="app">
        <aside class="sidebar">
            <div class="brand">AgendaPro</div>

            <div class="company-name">
                {{ $company->name }}
            </div>

            <div class="menu">
                <div class="menu-label">Menu principal</div>

                <a href="{{ route('company.schedules.index', $company) }}">
                    Agendas
                </a>

                <a href="{{ route('company.schedules.index', $company) }}"
                   class="active">
                    Reservas
                </a>
            </div>

            <div class="sidebar-footer">
                <a href="{{ route('home') }}">
                    ← Voltar à conta
                </a>
            </div>
        </aside>

        <main class="main">
            <header class="topbar">
                <div class="user-name">
                    {{ auth()->user()->name }}
                </div>
            </header>

            <div class="content">
                <div class="breadcrumb">
                    <a href="{{ route('company.schedules.index', $company) }}">
                        Agendas
                    </a>
                    / Reservas
                </div>

                <div class="page-header">
                    <div>
                        <h1>Reservas recebidas</h1>

                        <p class="subtitle">
                            {{ $nomeEvento }}
                            · {{ $dataFormatada }}
                        </p>
                    </div>
                </div>

                <section class="summary">
                    <div class="summary-card">
                        <div class="summary-label">
                            Total de reservas
                        </div>

                        <div class="summary-value">
                            {{ $totalReservas }}
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-label">
                            Reservas confirmadas
                        </div>

                        <div class="summary-value">
                            {{ $reservasConfirmadas }}
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-label">
                            Reservas canceladas
                        </div>

                        <div class="summary-value">
                            {{ $reservasCanceladas }}
                        </div>
                    </div>
                </section>

                <section class="section">
                    <div class="section-header">
                        <h2>Lista de reservas</h2>

                        <p>
                            Clientes que realizaram agendamentos nesta agenda.
                        </p>
                    </div>

                    @if ($reservas->isEmpty())
                        <div class="empty-state">
                            <h3>Nenhuma reserva recebida</h3>

                            <p>
                                Quando um cliente realizar um agendamento,
                                as informações aparecerão nesta tela.
                            </p>
                        </div>
                    @else
                        <div class="reservation-list">
                            @foreach ($reservas as $reserva)
                                @php
                                    $status = data_get($reserva, 'status', 'indefinido');

                                    $statusClass = match ($status) {
                                        'confirmada' => 'status-confirmada',
                                        'cancelada' => 'status-cancelada',
                                        default => 'status-outro',
                                    };

                                    $statusLabel = match ($status) {
                                        'confirmada' => 'Confirmada',
                                        'cancelada' => 'Cancelada',
                                        default => ucfirst($status),
                                    };

                                    $dataReserva = data_get($reserva, 'data');

                                    $dataReservaFormatada = $dataReserva
                                        ? \Carbon\Carbon::parse($dataReserva)->format('d/m/Y')
                                        : 'Não informada';

                                    $criadaEm = data_get($reserva, 'created_at');

                                    $criadaEmFormatada = $criadaEm
                                        ? \Carbon\Carbon::parse($criadaEm)
                                            ->timezone($company->timezone ?: config('app.timezone'))
                                            ->format('d/m/Y H:i')
                                        : 'Não informada';
                                @endphp

                                <article class="reservation-card">
                                    <div class="reservation-header">
                                        <div class="reservation-code">
                                            Reserva:
                                            {{ data_get($reserva, 'reserva_id', 'Não identificada') }}
                                        </div>

                                        <span class="status {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>

                                    <div class="reservation-grid">
                                        <div>
                                            <div class="field-label">
                                                Data
                                            </div>

                                            <div class="field-value">
                                                {{ $dataReservaFormatada }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="field-label">
                                                Horário
                                            </div>

                                            <div class="field-value">
                                                {{ data_get($reserva, 'horario_inicio', '--:--') }}
                                                às
                                                {{ data_get($reserva, 'horario_fim', '--:--') }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="field-label">
                                                Profissional
                                            </div>

                                            <div class="field-value">
                                                {{ data_get($reserva, 'profissional', 'Não informado') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="client-section">
                                        <h3>Dados do cliente</h3>

                                        <div class="reservation-grid">
                                            <div>
                                                <div class="field-label">
                                                    Nome completo
                                                </div>

                                                <div class="field-value">
                                                    {{ data_get($reserva, 'cliente.nome', 'Não informado') }}
                                                </div>
                                            </div>

                                            <div>
                                                <div class="field-label">
                                                    Telefone / WhatsApp
                                                </div>

                                                <div class="field-value">
                                                    {{ data_get($reserva, 'cliente.telefone', 'Não informado') }}
                                                </div>
                                            </div>

                                            <div>
                                                <div class="field-label">
                                                    E-mail
                                                </div>

                                                <div class="field-value">
                                                    {{ data_get($reserva, 'cliente.email') ?: 'Não informado' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="client-section">
                                        <div class="field-label">
                                            Reserva realizada em
                                        </div>

                                        <div class="field-value">
                                            {{ $criadaEmFormatada }}
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>
</body>
</html>
