
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nova agenda | {{ $company->name }}</title>

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

        .breadcrumb {
            display: flex;
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

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 22px;
            align-items: start;
        }

        .form-column {
            min-width: 0;
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .form-group {
            min-width: 0;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .required {
            color: #dc2626;
        }

        .form-control {
            width: 100%;
            min-height: 44px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 11px 12px;
            color: #1e293b;
            background: #fff;
            font-family: inherit;
            font-size: 13px;
            outline: none;
            transition: .2s;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .10);
        }

        textarea.form-control {
            min-height: 125px;
            resize: vertical;
            line-height: 1.7;
        }

        .field-hint {
            margin-top: 7px;
            color: #94a3b8;
            font-size: 11px;
            line-height: 1.6;
        }

        .duration-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .input-suffix {
            position: relative;
        }

        .input-suffix .form-control {
            padding-right: 65px;
        }

        .input-suffix span {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 11px;
            pointer-events: none;
        }

        .error-message {
            color: #dc2626;
            font-size: 11px;
            margin-top: 6px;
            line-height: 1.5;
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

        .preview-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
            position: sticky;
            top: 20px;
            overflow: hidden;
        }

        .preview-header {
            padding: 21px 22px;
            background: #172554;
            color: #fff;
        }

        .preview-header h2 {
            font-size: 16px;
            margin-bottom: 6px;
        }

        .preview-header p {
            color: #c7d2fe;
            font-size: 11px;
            line-height: 1.5;
        }

        .preview-body {
            padding: 20px;
        }

        .preview-event {
            margin-bottom: 19px;
            padding-bottom: 17px;
            border-bottom: 1px solid #e5e7eb;
        }

        .preview-event-label {
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .7px;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .preview-event strong {
            display: block;
            color: #172554;
            font-size: 15px;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .preview-event span {
            display: block;
            margin-top: 6px;
            color: #64748b;
            font-size: 11px;
        }

        .preview-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .metric {
            background: #f8fafc;
            border: 1px solid #edf0f4;
            border-radius: 9px;
            padding: 13px 11px;
            min-height: 82px;
        }

        .metric-label {
            color: #64748b;
            font-size: 10px;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .metric-value {
            color: #172554;
            font-size: 23px;
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

        .preview-divider {
            border: 0;
            border-top: 1px solid #e5e7eb;
            margin: 19px 0;
        }

        .preview-detail {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 11px;
        }

        .preview-detail span {
            color: #64748b;
        }

        .preview-detail strong {
            color: #334155;
            text-align: right;
        }

        .preview-note {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 12px;
            color: #92400e;
            font-size: 10px;
            line-height: 1.6;
            margin-top: 18px;
        }

        .preview-note strong {
            display: block;
            margin-bottom: 4px;
        }

        .preview-status {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #64748b;
            font-size: 10px;
            margin-top: 15px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: #16a34a;
            border-radius: 50%;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 25px;
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

        @media (max-width: 1100px) {
            .layout {
                grid-template-columns: minmax(0, 1fr) 300px;
            }
        }

        @media (max-width: 900px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .preview-card {
                position: static;
                grid-row: 1;
            }

            .preview-metrics {
                grid-template-columns: repeat(4, 1fr);
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

            .preview-metrics {
                grid-template-columns: 1fr 1fr;
            }

            .card-body {
                padding: 19px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
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

                <span>Nova agenda</span>
            </div>

            <div class="page-header">
                <h1>Criar nova agenda</h1>

                <p>
                    Configure o evento, os profissionais, os serviços
                    e os intervalos para organizar os atendimentos.
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

            <form
                method="POST"
                action="{{ route('company.schedules.review', $company) }}"
                id="agenda-form"
            >
                @csrf

                <div class="layout">

                    <div class="form-column">

                        <!-- DADOS DO EVENTO -->

                        <section class="content-card">

                            <div class="card-header">
                                <div class="section-number">01</div>

                                <div>
                                    <h2>Dados do evento</h2>

                                    <p>
                                        Identificação, data e duração do evento.
                                    </p>
                                </div>
                            </div>

                            <div class="card-body">

                                <div class="form-grid">

                                    <div class="form-group full">
                                        <label for="evento-nome">
                                            Nome do evento
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            id="evento-nome"
                                            name="evento[nome]"
                                            class="form-control"
                                            placeholder="Ex.: Evento de bem-estar"
                                            maxlength="150"
                                            value="{{ old('evento.nome') }}"
                                            required
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label for="evento-data">
                                            Data do evento
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="date"
                                            id="evento-data"
                                            name="evento[data]"
                                            class="form-control"
                                            value="{{ old('evento.data') }}"
                                            required
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label for="evento-hora">
                                            Horário de início
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="time"
                                            id="evento-hora"
                                            name="evento[hora_inicio]"
                                            class="form-control"
                                            value="{{ old('evento.hora_inicio', '09:00') }}"
                                            required
                                        >
                                    </div>

                                    <div class="form-group full">

                                        <label>
                                            Duração total do evento
                                            <span class="required">*</span>
                                        </label>

                                        <div class="duration-fields">

                                            <div class="input-suffix">
                                                <input
                                                    type="number"
                                                    id="duracao-horas"
                                                    name="evento[duracao_horas]"
                                                    class="form-control"
                                                    min="0"
                                                    max="72"
                                                    value="{{ old('evento.duracao_horas', 8) }}"
                                                    required
                                                >

                                                <span>horas</span>
                                            </div>

                                            <div class="input-suffix">
                                                <input
                                                    type="number"
                                                    id="duracao-minutos"
                                                    name="evento[duracao_minutos]"
                                                    class="form-control"
                                                    min="0"
                                                    max="59"
                                                    value="{{ old('evento.duracao_minutos', 0) }}"
                                                    required
                                                >

                                                <span>minutos</span>
                                            </div>

                                        </div>

                                        <p class="field-hint">
                                            Informe o tempo total de funcionamento
                                            da agenda, incluindo os intervalos.
                                        </p>

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
                                        Informe os profissionais que participarão
                                        do evento.
                                    </p>
                                </div>
                            </div>

                            <div class="card-body">

                                <div class="form-group">

                                    <label for="profissionais">
                                        Nome dos profissionais
                                        <span class="required">*</span>
                                    </label>

                                    <textarea
                                        id="profissionais"
                                        name="profissionais"
                                        class="form-control"
                                        placeholder="Digite um profissional por linha:&#10;&#10;Ana Silva&#10;Maria Oliveira&#10;João Santos"
                                        required
                                    >{{ old('profissionais') }}</textarea>

                                    <p class="field-hint">
                                        Cada nome corresponde a uma vaga
                                        de atendimento por horário.
                                        Informe um nome por linha.
                                        Limite de 20 profissionais.
                                    </p>

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
                                        Configure o serviço disponibilizado
                                        durante o evento.
                                    </p>
                                </div>
                            </div>

                            <div class="card-body">

                                <div class="form-grid">

                                    <div class="form-group full">
                                        <label for="servico-tipo">
                                            Tipo do serviço
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            id="servico-tipo"
                                            name="servico[tipo]"
                                            class="form-control"
                                            placeholder="Ex.: Massagem relaxante"
                                            maxlength="150"
                                            value="{{ old('servico.tipo') }}"
                                            required
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label for="servico-duracao">
                                            Duração de cada atendimento
                                            <span class="required">*</span>
                                        </label>

                                        <div class="input-suffix">
                                            <input
                                                type="number"
                                                id="servico-duracao"
                                                name="servico[duracao_minutos]"
                                                class="form-control"
                                                min="5"
                                                max="480"
                                                value="{{ old('servico.duracao_minutos', 30) }}"
                                                required
                                            >

                                            <span>minutos</span>
                                        </div>

                                        <p class="field-hint">
                                            Tempo necessário para realizar
                                            cada atendimento.
                                        </p>
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
                                        Configure as pausas simultâneas
                                        de todos os profissionais.
                                    </p>
                                </div>
                            </div>

                            <div class="card-body">

                                <div class="form-grid">

                                    <div class="form-group">
                                        <label for="intervalo-duracao">
                                            Duração de cada intervalo
                                        </label>

                                        <div class="input-suffix">
                                            <input
                                                type="number"
                                                id="intervalo-duracao"
                                                name="intervalo[duracao_minutos]"
                                                class="form-control"
                                                min="0"
                                                max="180"
                                                value="{{ old('intervalo.duracao_minutos', 15) }}"
                                                required
                                            >

                                            <span>minutos</span>
                                        </div>

                                        <p class="field-hint">
                                            Informe zero caso não existam
                                            intervalos.
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label for="intervalo-frequencia">
                                            Frequência dos intervalos
                                        </label>

                                        <div class="input-suffix">
                                            <input
                                                type="number"
                                                id="intervalo-frequencia"
                                                name="intervalo[frequencia_horas]"
                                                class="form-control"
                                                min="1"
                                                max="24"
                                                value="{{ old('intervalo.frequencia_horas', 2) }}"
                                            >

                                            <span>horas</span>
                                        </div>

                                        <p class="field-hint">
                                            Contada a partir do início
                                            do evento.
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </section>

                        <div class="form-actions">

                            <a
                                href="{{ route('company.schedules.index', $company) }}"
                                class="secondary-button"
                            >
                                ← Voltar
                            </a>

                            <button
                                type="submit"
                                class="primary-button"
                            >
                                Revisar agenda
                                <span>→</span>
                            </button>

                        </div>

                    </div>

                    <!-- PREVISÃO EM TEMPO REAL -->

                    <aside class="preview-card">

                        <div class="preview-header">
                            <h2>Previsão da agenda</h2>

                            <p>
                                Os indicadores são atualizados automaticamente
                                conforme você preenche os dados.
                            </p>
                        </div>

                        <div class="preview-body">

                            <div class="preview-event">

                                <div class="preview-event-label">
                                    Evento
                                </div>

                                <strong id="preview-event-name">
                                    Nome do evento
                                </strong>

                                <span id="preview-event-date">
                                    Data não informada
                                </span>

                                <span id="preview-event-time">
                                    09:00 até --:--
                                </span>

                            </div>

                            <div class="preview-metrics">

                                <div class="metric">
                                    <div class="metric-label">
                                        Profissionais
                                    </div>

                                    <div
                                        class="metric-value"
                                        id="metric-profissionais"
                                    >
                                        0
                                    </div>
                                </div>

                                <div class="metric">
                                    <div class="metric-label">
                                        Intervalos
                                    </div>

                                    <div
                                        class="metric-value"
                                        id="metric-intervalos"
                                    >
                                        0
                                    </div>
                                </div>

                                <div class="metric">
                                    <div class="metric-label">
                                        Horários de atendimento
                                    </div>

                                    <div
                                        class="metric-value"
                                        id="metric-horarios"
                                    >
                                        0
                                    </div>
                                </div>

                                <div class="metric highlight">
                                    <div class="metric-label">
                                        Capacidade total
                                    </div>

                                    <div
                                        class="metric-value"
                                        id="metric-capacidade"
                                    >
                                        0
                                    </div>
                                </div>

                            </div>

                            <hr class="preview-divider">

                            <div class="preview-detail">
                                <span>Início</span>

                                <strong id="detail-inicio">--:--</strong>
                            </div>

                            <div class="preview-detail">
                                <span>Encerramento</span>

                                <strong id="detail-fim">--:--</strong>
                            </div>

                            <div class="preview-detail">
                                <span>Duração do evento</span>

                                <strong id="detail-duracao">0h 00min</strong>
                            </div>

                            <div class="preview-detail">
                                <span>Duração do serviço</span>

                                <strong id="detail-servico">0 min</strong>
                            </div>

                            <div class="preview-detail">
                                <span>Tempo de intervalo</span>

                                <strong id="detail-intervalo">0 min</strong>
                            </div>

                            <div class="preview-note">
                                <strong>Importante</strong>

                                Os intervalos são coletivos. Durante as pausas,
                                todos os profissionais ficam indisponíveis.
                                A capacidade considera a quantidade de
                                profissionais multiplicada pelos horários
                                disponíveis.
                            </div>

                            <div class="preview-status">
                                <span class="status-dot"></span>
                                Atualização automática ativada
                            </div>

                        </div>

                    </aside>

                </div>

            </form>

        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const elements = {
                nome: document.getElementById('evento-nome'),
                data: document.getElementById('evento-data'),
                hora: document.getElementById('evento-hora'),
                horas: document.getElementById('duracao-horas'),
                minutos: document.getElementById('duracao-minutos'),
                profissionais: document.getElementById('profissionais'),
                servico: document.getElementById('servico-duracao'),
                intervalo: document.getElementById('intervalo-duracao'),
                frequencia: document.getElementById('intervalo-frequencia')
            };

            const output = {
                nome: document.getElementById('preview-event-name'),
                data: document.getElementById('preview-event-date'),
                horario: document.getElementById('preview-event-time'),

                profissionais: document.getElementById('metric-profissionais'),
                intervalos: document.getElementById('metric-intervalos'),
                horarios: document.getElementById('metric-horarios'),
                capacidade: document.getElementById('metric-capacidade'),

                inicio: document.getElementById('detail-inicio'),
                fim: document.getElementById('detail-fim'),
                duracao: document.getElementById('detail-duracao'),
                servico: document.getElementById('detail-servico'),
                intervalo: document.getElementById('detail-intervalo')
            };

            function getNumber(element) {
                const value = Number.parseInt(element.value, 10);

                return Number.isFinite(value) ? value : 0;
            }

            function formatDate(value) {
                if (!value) {
                    return 'Data não informada';
                }

                const parts = value.split('-');

                if (parts.length !== 3) {
                    return 'Data não informada';
                }

                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            }

            function formatTime(totalMinutes) {
                const normalized = (
                    totalMinutes % 1440 + 1440
                ) % 1440;

                const hours = Math.floor(normalized / 60);
                const minutes = normalized % 60;

                return (
                    String(hours).padStart(2, '0')
                    + ':'
                    + String(minutes).padStart(2, '0')
                );
            }

            function updatePreview() {

                const nome = elements.nome.value.trim() || 'Nome do evento';

                const hora = elements.hora.value || '09:00';

                const [horaInicio, minutoInicio] = hora
                    .split(':')
                    .map(Number);

                const inicioMinutos = (
                    horaInicio * 60 + minutoInicio
                );

                const horas = getNumber(elements.horas);
                const minutos = getNumber(elements.minutos);

                const duracaoTotal = (
                    horas * 60 + minutos
                );

                const duracaoServico = getNumber(elements.servico);

                const duracaoIntervalo = getNumber(elements.intervalo);

                const frequencia = getNumber(elements.frequencia);

                const nomes = elements.profissionais.value
                    .split(/\r\n|\r|\n/)
                    .map(nome => nome.trim())
                    .filter(nome => nome.length > 0);

                const quantidadeProfissionais = nomes.length;

                const eventoValido = (
                    duracaoTotal > 0
                    && duracaoTotal <= 4320
                    && inicioMinutos >= 0
                    && inicioMinutos < 1440
                );

                const intervalos = [];

                if (
                    eventoValido
                    && duracaoIntervalo > 0
                    && frequencia > 0
                ) {
                    let proximoIntervalo = frequencia * 60;

                    while (proximoIntervalo < duracaoTotal) {

                        const fimIntervalo = Math.min(
                            proximoIntervalo + duracaoIntervalo,
                            duracaoTotal
                        );

                        intervalos.push({
                            inicio: inicioMinutos + proximoIntervalo,
                            fim: inicioMinutos + fimIntervalo
                        });

                        proximoIntervalo += frequencia * 60;
                    }
                }

                const horarios = [];

                if (
                    eventoValido
                    && duracaoServico >= 5
                ) {

                    let cursor = inicioMinutos;
                    const fimEvento = inicioMinutos + duracaoTotal;

                    while (
                        cursor + duracaoServico <= fimEvento
                    ) {

                        const fimAtendimento = (
                            cursor + duracaoServico
                        );

                        const conflito = intervalos.find(intervalo => (
                            cursor < intervalo.fim
                            && fimAtendimento > intervalo.inicio
                        ));

                        if (conflito) {
                            cursor = conflito.fim;
                            continue;
                        }

                        horarios.push({
                            inicio: cursor,
                            fim: fimAtendimento
                        });

                        cursor = fimAtendimento;
                    }
                }

                const quantidadeHorarios = horarios.length;

                const capacidade = (
                    quantidadeHorarios * quantidadeProfissionais
                );

                const horaFinal = eventoValido
                    ? formatTime(inicioMinutos + duracaoTotal)
                    : '--:--';

                output.nome.textContent = nome;

                output.data.textContent = formatDate(elements.data.value);

                output.horario.textContent = (
                    `${hora || '--:--'} até ${horaFinal}`
                );

                output.profissionais.textContent = (
                    quantidadeProfissionais
                );

                output.intervalos.textContent = intervalos.length;

                output.horarios.textContent = quantidadeHorarios;

                output.capacidade.textContent = capacidade;

                output.inicio.textContent = hora || '--:--';

                output.fim.textContent = horaFinal;

                output.duracao.textContent = (
                    `${horas}h ${String(minutos).padStart(2, '0')}min`
                );

                output.servico.textContent = (
                    `${duracaoServico} min`
                );

                output.intervalo.textContent = (
                    `${duracaoIntervalo} min`
                );
            }

            Object.values(elements).forEach(element => {
                element.addEventListener('input', updatePreview);
                element.addEventListener('change', updatePreview);
            });

            updatePreview();

        });
    </script>

</body>
</html>
