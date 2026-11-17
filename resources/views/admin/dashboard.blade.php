
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Minhas empresas - {{ config('app.name') }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f6fa;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 22px 6%;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        .brand {
            font-size: 21px;
            font-weight: bold;
            color: #111827;
        }

        .user-area {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 14px;
        }

        .logout-button {
            padding: 9px 14px;
            border: none;
            border-radius: 7px;
            background: #374151;
            color: #fff;
            cursor: pointer;
        }

        main {
            width: min(1100px, 90%);
            margin: 55px auto;
        }

        .intro {
            margin-bottom: 35px;
        }

        .intro h1 {
            margin: 0 0 12px;
            font-size: 30px;
        }

        .intro p {
            margin: 0;
            color: #6b7280;
            line-height: 1.6;
        }

        .company-grid {
            display: grid;
            grid-template-columns: repeat(
                auto-fill,
                minmax(260px, 1fr)
            );
            gap: 22px;
        }

        .company-card {
            display: flex;
            flex-direction: column;
            min-height: 190px;
            padding: 25px;
            border: 1px solid #e5e7eb;
            border-radius: 13px;
            background: #fff;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.035);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .company-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.07);
        }

        .company-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            margin-bottom: 20px;
            border-radius: 11px;
            background: #eef2ff;
            color: #4338ca;
            font-size: 21px;
        }

        .company-card h2 {
            margin: 0 0 9px;
            font-size: 18px;
        }

        .company-card p {
            margin: 0 0 22px;
            color: #6b7280;
            font-size: 13px;
        }

        .company-link {
            display: inline-block;
            margin-top: auto;
            color: #4338ca;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
        }

        .company-link:hover {
            text-decoration: underline;
        }

        .empty-state {
            padding: 55px 25px;
            border: 1px dashed #d1d5db;
            border-radius: 13px;
            background: #fff;
            text-align: center;
        }

        .empty-state h2 {
            margin: 0 0 12px;
            font-size: 20px;
        }

        .empty-state p {
            margin: 0;
            color: #6b7280;
            line-height: 1.6;
        }

        .back-link {
            display: inline-block;
            margin-top: 35px;
            color: #4b5563;
            font-size: 14px;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            header {
                align-items: flex-start;
                flex-direction: column;
                padding: 20px;
            }

            main {
                margin: 35px auto;
            }

            .intro h1 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="brand">
        {{ config('app.name') }}
    </div>

    <div class="user-area">
        <span>{{ auth()->user()->name }}</span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-button">
                Sair
            </button>
        </form>
    </div>
</header>

<main>

    <section class="intro">
        <h1>Minhas empresas</h1>

        <p>
            Selecione uma empresa para acessar seu ambiente
            de administração e gerenciamento.
        </p>
    </section>

    @if ($companies->isNotEmpty())

        <section class="company-grid">

            @foreach ($companies as $company)

                <article class="company-card">

                    <div class="company-icon">
                        &#127970;
                    </div>

                    <h2>{{ $company->name }}</h2>

                    <p>
                        Ambiente empresarial
                    </p>

                    <a
                        href="{{ route('company.dashboard', $company) }}"
                        class="company-link"
                    >
                        Acessar empresa →
                    </a>

                </article>

            @endforeach

        </section>

    @else

        <section class="empty-state">

            <h2>Nenhuma empresa disponível</h2>

            <p>
                Sua conta ainda não possui vínculo ativo com
                uma empresa da plataforma.
            </p>

        </section>

    @endif

    <a href="{{ route('home') }}" class="back-link">
        ← Voltar ao painel principal
    </a>

</main>

</body>
</html>
