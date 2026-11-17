
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Revisar agenda | {{ $company->name }}</title>

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
            max-width: 1450px;
        }

        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            color: #94a3b8;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .breadcrumb a {
            color: #475569;
            text-decoration: none;
        }

        .page-header {
            margin-bottom: 28px;
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

        .content-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            padding: 21px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .section-number {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #eef2ff;
            color: #3730a3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .card-header h2 {
            font-size: 15px;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .card-header p {
            font-size: 12px;
            color: #94a3b8;
        }

        .card-body {
            padding: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .info-item {
            min-width: 0;
        }

        .info-item.full {
            grid-column: 1 / -1;
        }

        .info-label {
            display: block;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .6px;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .info-value {
            color: #1e293b;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .info-secondary {
            display: block;
            color: #64748b;
            font-size: 12px;
            font-weight: normal;
            margin-top: 4px;
        }

        .professional-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .professional-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            font-size: 13px;
            color: #334155;
            overflow-wrap: anywhere;
        }

        .professional-number {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: #eef2ff;
            color: #3730a3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .metric {
            background: #f8fafc;
            border: 1px solid #edf0f4;
            border-radius: 9px;
            padding: 17px 14px;
            min-height: 105px;
        }

        .metric-label {
            color: #64748b;
            font-size: 11px;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .metric-value {
            color: #172554;
            font-size: 27px;
            font-weight: bold;
            line-height: 1;
            overflow-wrap: anywhere;
        }

        .metric.highlight {
            background: #eef2ff;
            border-color: #c7d2fe;
        }

        .metric.highlight .metric-value {
            color: #3730a3;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead {
            background: #f8fafc;
        }

        th,
        td {
            padding: 13px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        th {
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        td {
            color: #334155;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .table-number {
            color: #94a3b8;
            font-weight: bold;
        }

        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            border-radius: 9px;
            padding: 16px 18px;
            margin-bottom: 22px;
            font-size: 12px;
            line-height: 1.7;
        }

        .alert-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            border-radius: 9px;
            padding: 15px 18px;
            font-size: 12px;
            line-height: 1.7;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 9px;
            padding: 15px 18px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.7;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 25px;
            margin-bottom: 30px;
        }

        .secondary-button,
        .primary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 44px;
            padding: 12px 19px;
            border-radius: 8px;
            text-decoration: none;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .secondary-button {
            color: #475569;
            background: #fff;
            border: 1px solid #cbd5e1;
        }

        .secondary-button:hover {
            background: #f8fafc;
        }

        .primary-button {
            color: #fff;
            background: #263b78;
            border: none;
        }

        .primary-button:hover {
            background: #1e3065;
        }

        .primary-button:focus,
        .secondary-button:focus {
            outline: 3px solid rgba(99, 102, 241, .2);
            outline-offset: 2px;
        }

        @media (max-width: 1100px) {
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
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

            .card-body {
                padding: 19px;
            }

            .info-grid,
            .professional-list {
                grid-template-columns: 1fr;
            }

            .info-item.full {
                grid-column: auto;
            }

            .summary-grid {
                grid-template-columns: 1fr 1fr;
            }

            .form-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .form-actions a,
            .form-actions button {
                width: 100%;
            }
        }

        @media (max-width: 380px) {
            .summary-grid {
                grid-template-columns: 1fr;
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

            <div class="breadcrumb">

                <a href="{{ route('company.dashboard', $company) }}">
                    Visão geral
                </a>

                <span>›</span>

                <a href="{{ route('company.schedules.index', $company) }}">
                    Agendas
                </a>

                <span>›</span>

                <span>Revisar agenda</span>

            </div>

            <div class="page-header">

                <h1>Revisar agenda</h1>

                <p>
                    Confira os dados do evento, os profissionais,
                    os serviços e os horários calculados antes de confirmar.
                </p>

            </div>

            @if ($errors->any())

                <div class="alert-error">

                    <strong>Não foi possível continuar.</strong>

                    <ul style="margin: 8px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif

            <!-- DADOS DO EVENTO -->

            <section class="content-card">

                <div class="card-header">

                    <div class="section-number">01</div>

                    <div>
                        <h2>Dados do evento</h2>

                        <p>
                            Identificação, data, horário e duração.
                        </p>
                    </div>

                </div>

                <div class="card-body">

                    <div class="info-grid">

                        <div class="info-item full">

                            <span class="info-label">Nome do evento</span>

                            <div class="info-value">
                                {{ $agenda['evento']['nome'] }}
                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">Data</span>

                            <div class="info-value">
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $agenda['evento']['data'])->format('d/m/Y') }}
                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">Horário de início</span>

                            <div class="info-value">
                                {{ $agenda['evento']['hora_inicio'] }}
                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">Duração total</span>

                            <div class="info-value">
                                {{ $agenda['evento']['duracao_horas'] }}h
                                {{ str_pad($agenda['evento']['duracao_minutos'], 2, '0', STR_PAD_LEFT) }}min
                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">Encerramento previsto</span>

                            <div class="info-value">
                                {{ $previsao['hora_fim'] }}

                                <span class="info-secondary">
                                    Horário de Brasília/local da empresa
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </section>

            <!-- PROFISSIONAIS -->

            <section class="content-card">

                <div class="card-header">

                    <div class="section-number">02</div>

                    <div>
                        <h2>Profissionais</h2>

                        <p>
                            Profissionais participantes do evento.
                        </p>
                    </div>

                </div>

                <div class="card-body">

                    <p style="font-size: 12px; color: #64748b; margin-bottom: 16px;">
                        Total de
                        <strong>{{ count($agenda['profissionais']) }}</strong>
                        profissional(is).
                    </p>

                    <div class="professional-list">

                        @foreach ($agenda['profissionais'] as $indice => $profissional)

                            <div class="professional-item">

                                <span class="professional-number">
                                    {{ $indice + 1 }}
                                </span>

                                <span>{{ $profissional }}</span>

                            </div>

                        @endforeach

                    </div>

                </div>

            </section>

            <!-- SERVIÇO -->

            <section class="content-card">

                <div class="card-header">

                    <div class="section-number">03</div>

                    <div>
                        <h2>Serviço oferecido</h2>

                        <p>
                            Configuração dos atendimentos.
                        </p>
                    </div>

                </div>

                <div class="card-body">

                    <div class="info-grid">

                        <div class="info-item">

                            <span class="info-label">Tipo do serviço</span>

                            <div class="info-value">
                                {{ $agenda['servico']['tipo'] }}
                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">Duração por atendimento</span>

                            <div class="info-value">
                                {{ $agenda['servico']['duracao_minutos'] }}
                                minutos
                            </div>

                        </div>

                    </div>

                </div>

            </section>

            <!-- INTERVALOS -->

            <section class="content-card">

                <div class="card-header">

                    <div class="section-number">04</div>

                    <div>
                        <h2>Intervalos coletivos</h2>

                        <p>
                            Pausas simultâneas de todos os profissionais.
                        </p>
                    </div>

                </div>

                <div class="card-body">

                    @if ($agenda['intervalo']['duracao_minutos'] > 0)

                        <div class="info-grid">

                            <div class="info-item">

                                <span class="info-label">Duração de cada intervalo</span>

                                <div class="info-value">
                                    {{ $agenda['intervalo']['duracao_minutos'] }}
                                    minutos
                                </div>

                            </div>

                            <div class="info-item">

                                <span class="info-label">Frequência</span>

                                <div class="info-value">
                                    A cada
                                    {{ $agenda['intervalo']['frequencia_horas'] }}
                                    hora(s)
                                </div>

                                <span class="info-secondary">
                                    Contada a partir do início do evento.
                                </span>

                            </div>

                        </div>

                    @else

                        <p style="color: #64748b; font-size: 13px;">
                            Nenhum intervalo programado.
                        </p>

                    @endif

                </div>

            </section>

            <!-- RESUMO DA PREVISÃO -->

            <section class="content-card">

                <div class="card-header">

                    <div class="section-number">05</div>

                    <div>
                        <h2>Resumo da previsão</h2>

                        <p>
                            Capacidade calculada para a configuração informada.
                        </p>
                    </div>

                </div>

                <div class="card-body">

                    <div class="summary-grid">

                        <div class="metric">

                            <div class="metric-label">
                                Profissionais
                            </div>

                            <div class="metric-value">
                                {{ $previsao['quantidade_profissionais'] }}
                            </div>

                        </div>

                        <div class="metric">

                            <div class="metric-label">
                                Intervalos
                            </div>

                            <div class="metric-value">
                                {{ $previsao['quantidade_intervalos'] }}
                            </div>

                        </div>

                        <div class="metric">

                            <div class="metric-label">
                                Horários de atendimento
                            </div>

                            <div class="metric-value">
                                {{ $previsao['quantidade_horarios'] }}
                            </div>

                        </div>

                        <div class="metric highlight">

                            <div class="metric-label">
                                Capacidade total
                            </div>

                            <div class="metric-value">
                                {{ $previsao['capacidade_total'] }}
                            </div>

                        </div>

                    </div>

                    <p style="font-size: 11px; color: #64748b; line-height: 1.6; margin-top: 17px;">
                        A capacidade total corresponde à quantidade de
                        horários disponíveis multiplicada pelo número
                        de profissionais.
                    </p>

                </div>

            </section>

            <!-- HORÁRIOS CALCULADOS -->

            <section class="content-card">

                <div class="card-header">

                    <div class="section-number">06</div>

                    <div>
                        <h2>Horários previstos</h2>

                        <p>
                            Distribuição dos atendimentos ao longo do evento.
                        </p>
                    </div>

                </div>

                <div class="card-body">

                    @if (count($previsao['horarios']) > 0)

                        <div class="table-wrapper">

                            <table>

                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Início</th>
                                        <th>Fim</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($previsao['horarios'] as $indice => $horario)

                                        <tr>
                                            <td class="table-number">
                                                {{ $indice + 1 }}
                                            </td>

                                            <td>
                                                {{ $horario['inicio'] }}
                                            </td>

                                            <td>
                                                {{ $horario['fim'] }}
                                            </td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert-warning">
                            Não há horários disponíveis com a configuração
                            informada. Verifique a duração do serviço e
                            os intervalos antes de confirmar.
                        </div>

                    @endif

                </div>

            </section>

            <!-- INTERVALOS CALCULADOS -->

            @if (count($previsao['intervalos']) > 0)

                <section class="content-card">

                    <div class="card-header">

                        <div class="section-number">07</div>

                        <div>
                            <h2>Intervalos previstos</h2>

                            <p>
                                Pausas coletivas calculadas para o evento.
                            </p>
                        </div>

                    </div>

                    <div class="card-body">

                        <div class="table-wrapper">

                            <table>

                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Início</th>
                                        <th>Fim</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($previsao['intervalos'] as $indice => $intervalo)

                                        <tr>
                                            <td class="table-number">
                                                {{ $indice + 1 }}
                                            </td>

                                            <td>
                                                {{ $intervalo['inicio'] }}
                                            </td>

                                            <td>
                                                {{ $intervalo['fim'] }}
                                            </td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </section>

            @endif

            <!-- AVISO E CONFIRMAÇÃO -->

            <div class="alert-info">

                <strong>Antes de confirmar:</strong>

                <br>

                Confira os dados do evento e a previsão dos atendimentos.
                Ao confirmar, a agenda será gravada no armazenamento da empresa.

                <br>

                A confirmação utiliza os dados armazenados temporariamente
                no servidor, não os valores enviados novamente pelo navegador.

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('company.schedules.create', $company) }}"
                    class="secondary-button"
                >
                    ← Voltar ao formulário
                </a>

                <form
                    method="POST"
                    action="{{ route('company.schedules.store', $company) }}"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="confirmation_token"
                        value="{{ $confirmationToken }}"
                    >

                    <button
                        type="submit"
                        class="primary-button"
                        @if (count($previsao['horarios']) === 0) disabled @endif
                    >
                        Confirmar e criar agenda
                        <span>→</span>
                    </button>

                </form>

            </div>

        </section>

    </main>

</body>
</html>
