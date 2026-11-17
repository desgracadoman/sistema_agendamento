
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel - {{ config('app.name') }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f3f4f6;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            padding: 28px 20px;
            background: #111827;
            color: white;
        }

        .brand {
            margin-bottom: 40px;
            font-size: 21px;
            font-weight: bold;
        }

        .menu-title {
            margin: 28px 0 12px;
            color: #9ca3af;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .menu-item {
            display: block;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 7px;
            color: #d1d5db;
            font-size: 14px;
            text-decoration: none;
        }

        .menu-item.active,
        .menu-item:hover {
            background: #374151;
            color: white;
        }

        .main {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 22px 32px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
        }

        .topbar h1 {
            margin: 0;
            font-size: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 14px;
        }

        .logout-button {
            padding: 9px 14px;
            border: none;
            border-radius: 6px;
            background: #374151;
            color: white;
            cursor: pointer;
        }

        .content {
            padding: 32px;
        }

        .welcome {
            padding: 30px;
            margin-bottom: 28px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .welcome h2 {
            margin: 0 0 10px;
            font-size: 25px;
        }

        .welcome p {
            margin: 0;
            color: #6b7280;
            line-height: 1.6;
        }

        .section-title {
            margin-bottom: 18px;
            font-size: 18px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .card {
            min-height: 140px;
            padding: 22px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: white;
        }

        .card h3 {
            margin: 0 0 12px;
            font-size: 15px;
        }

        .card p {
            margin: 0;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        .footer {
            padding: 24px 32px;
            color: #9ca3af;
            font-size: 12px;
        }

        @media (max-width: 850px) {
            .sidebar {
                width: 200px;
            }

            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .layout {
                display: block;
            }

            .sidebar {
                width: 100%;
                padding: 18px;
            }

            .brand {
                margin-bottom: 18px;
            }

            .sidebar nav {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .menu-title {
                width: 100%;
                margin: 15px 0 5px;
            }

            .menu-item {
                margin: 0;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
                padding: 20px;
            }

            .user-info {
                flex-wrap: wrap;
            }

            .content {
                padding: 20px;
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <div class="brand">
            {{ config('app.name') }}
        </div>

        <nav>
            <div class="menu-title">Principal</div>

            <a href="{{ route('home') }}" class="menu-item active">
                Visão geral
            </a>

            <div class="menu-title">Sistema</div>

            <span class="menu-item">
                Agendamentos
            </span>

            <span class="menu-item">
                Empresas
            </span>

            <span class="menu-item">
                Profissionais
            </span>

            <span class="menu-item">
                Clientes
            </span>

            <div class="menu-title">Conta</div>

            <span class="menu-item">
                Meu perfil
            </span>
        </nav>

    </aside>

    <main class="main">

        <header class="topbar">

            <h1>Painel de controle</h1>

            <div class="user-info">

                <span>
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="logout-button">
                        Sair
                    </button>
                </form>

            </div>

        </header>

        <section class="content">

            <div class="welcome">

                <h2>
                    Bem-vindo(a), {{ auth()->user()->name }}!
                </h2>

                <p>
                    Sua área de acesso está funcionando.
                    Aqui você poderá acompanhar e administrar
                    as funcionalidades disponíveis para sua conta.
                </p>

            </div>

            <h2 class="section-title">Visão geral</h2>

            <div class="cards">

                <div class="card">
                    <h3>Agendamentos</h3>
                    <p>
                        Área destinada ao acompanhamento e
                        gerenciamento dos agendamentos.
                    </p>
                </div>

                <div class="card">
                    <h3>Empresas</h3>
                    <p>
                        Área destinada ao cadastro e gerenciamento
                        das empresas participantes.
                    </p>
                </div>

                <div class="card">
                    <h3>Profissionais</h3>
                    <p>
                        Área destinada à gestão dos profissionais
                        e suas disponibilidades.
                    </p>
                </div>

            </div>

        </section>

        <footer class="footer">
            {{ config('app.name') }} —
            Sistema de agendamento
        </footer>

    </main>

</div>

</body>
</html>
