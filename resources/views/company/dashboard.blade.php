
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name }} | Painel empresarial</title>

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

        .menu a,
        .menu .disabled-item {
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

        .menu .disabled-item {
            opacity: .45;
            cursor: default;
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
            height: 76px;
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

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h1 {
            font-size: 26px;
            margin-bottom: 9px;
            color: #172554;
        }

        .welcome p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .section-title {
            font-size: 16px;
            margin-bottom: 18px;
            color: #334155;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
        }

        .card-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #eef2ff;
            color: #3730a3;
            font-size: 21px;
            margin-bottom: 19px;
        }

        .card h3 {
            font-size: 16px;
            margin-bottom: 9px;
        }

        .card p {
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .card-action {
            display: inline-block;
            background: #263b78;
            color: #fff;
            padding: 11px 16px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .card-action:hover {
            background: #1e3065;
        }

        .empty-card {
            border-style: dashed;
            background: #fafbff;
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

            .menu a,
            .menu .disabled-item {
                margin: 0;
                padding: 10px;
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                height: auto;
                min-height: 65px;
                padding: 15px 20px;
                gap: 12px;
                flex-wrap: wrap;
            }

            .content {
                padding: 25px 18px;
            }

            .welcome h1 {
                font-size: 22px;
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

            <a href="{{ route('company.dashboard', $company) }}"
               class="active">
                <span>⌂</span>
                Visão geral
            </a>

            @if ($canManageSchedules)
                <a href="{{ route('company.schedules.index', $company) }}">
                    <span>▦</span>
                    Agendas
                </a>
            @endif

            <span class="disabled-item">
                <span>♙</span>
                Profissionais
            </span>

            <span class="disabled-item">
                <span>◇</span>
                Serviços
            </span>

            <span class="disabled-item">
                <span>⚙</span>
                Configurações
            </span>

        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('home') }}">← Voltar à minha conta</a>
        </div>

    </aside>

    <main class="main">

        <header class="topbar">
            <h2>Painel empresarial</h2>

            <div class="user-info">
                {{ auth()->user()->name }}
            </div>
        </header>

        <section class="content">

            <div class="welcome">
                <h1>Bem-vindo ao ambiente da empresa</h1>

                <p>
                    Gerencie as atividades e acompanhe os recursos
                    disponíveis para {{ $company->name }}.
                </p>
            </div>

            <h2 class="section-title">Acesso rápido</h2>

            <div class="cards">

                @if ($canManageSchedules)
                    <article class="card">

                        <div class="card-icon">▦</div>

                        <h3>Agenda demonstração</h3>

                        <p>
                            Crie agendas para eventos, organize os horários
                            dos profissionais e acompanhe as reservas.
                        </p>

                        <a
                            href="{{ route('company.schedules.index', $company) }}"
                            class="card-action"
                        >
                            Acessar agendas
                        </a>

                    </article>
                @endif

                <article class="card empty-card">

                    <div class="card-icon">♙</div>

                    <h3>Profissionais</h3>

                    <p>
                        Área destinada à gestão dos profissionais
                        vinculados à empresa.
                    </p>

                </article>

                <article class="card empty-card">

                    <div class="card-icon">◇</div>

                    <h3>Serviços</h3>

                    <p>
                        Área destinada à organização dos serviços
                        oferecidos pela empresa.
                    </p>

                </article>

            </div>

        </section>

    </main>

</body>
</html>
