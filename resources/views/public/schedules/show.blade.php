
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agendar atendimento | {{ $company->name }}</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            padding: 35px 18px;
            background: #f4f6fa;
            color: #1e293b;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
        }

        .brand {
            margin-bottom: 25px;
            text-align: center;
        }

        .brand strong {
            color: #172554;
            font-size: 22px;
        }

        .brand span {
            display: block;
            margin-top: 6px;
            color: #64748b;
            font-size: 12px;
        }

        .card {
            overflow: hidden;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, .05);
        }

        .header {
            padding: 30px;
            background: #172554;
            color: #fff;
        }

        .header-label {
            margin-bottom: 12px;
            color: #a5b4fc;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .header h1 {
            margin-bottom: 12px;
            font-size: 25px;
            line-height: 1.3;
        }

        .header p {
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.6;
        }

        .event-details {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 22px;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid rgba(255,255,255,.15);
        }

        .event-detail {
            color: #e2e8f0;
            font-size: 13px;
        }

        .form-content {
            padding: 30px;
        }

        .section-title {
            margin-bottom: 8px;
            color: #172554;
            font-size: 18px;
        }

        .section-description {
            margin-bottom: 25px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
        }

        .required {
            color: #dc2626;
        }

        .field input,
        .field select {
            width: 100%;
            min-height: 47px;
            padding: 12px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            background: #fff;
            color: #1e293b;
            font-family: inherit;
            font-size: 14px;
        }

        .field input:focus,
        .field select:focus {
            border-color: #5369a5;
            box-shadow: 0 0 0 3px rgba(83,105,165,.12);
        }

        .field input::placeholder {
            color: #94a3b8;
        }

        .divider {
            margin: 27px 0;
            border: 0;
            border-top: 1px solid #e5e7eb;
        }

        .submit-button {
            display: flex;
            width: 100%;
            min-height: 50px;
            align-items: center;
            justify-content: center;
            padding: 13px 20px;
            border: none;
            border-radius: 8px;
            background: #263b78;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .submit-button:hover {
            background: #1e3065;
        }

        .submit-button:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        .notice {
            margin-bottom: 22px;
            padding: 13px 15px;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.6;
        }

        .notice-error {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }

        .notice-success {
            padding: 24px;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            background: #f0fdf4;
            color: #166534;
            text-align: center;
        }

        .success-icon {
            display: flex;
            width: 58px;
            height: 58px;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: #dcfce7;
            color: #15803d;
            font-size: 28px;
        }

        .notice-success h2 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .notice-success p {
            font-size: 13px;
            line-height: 1.7;
        }

        .empty-state {
            padding: 25px 18px;
            border: 1px dashed #cbd5e1;
            border-radius: 9px;
            background: #f8fafc;
            color: #64748b;
            font-size: 13px;
            line-height: 1.7;
            text-align: center;
        }

        .footer {
            padding: 20px;
            color: #94a3b8;
            font-size: 11px;
            text-align: center;
        }

        @media (max-width: 520px) {
            body {
                padding: 20px 12px;
            }

            .header,
            .form-content {
                padding: 23px 19px;
            }

            .header h1 {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>

@php
    $profissionais = data_get($agenda, 'profissionais', []);

    $temHorarios = collect($horariosDisponiveis)
        ->flatten(1)
        ->isNotEmpty();

    $dataEvento = data_get($agenda, 'evento.data');

    $horaInicio = data_get($agenda, 'evento.hora_inicio', '--:--');

    $horaFim = data_get($agenda, 'previsao.hora_fim', '--:--');

    $nomeEvento = data_get(
        $agenda,
        'evento.nome',
        'Agendamento'
    );

    $nomeServico = data_get(
        $agenda,
        'servico.tipo',
        'Atendimento'
    );
@endphp

<div class="container">

    <div class="brand">
        <strong>AgendaPro</strong>
        <span>Agendamento online</span>
    </div>

    <main class="card">

        <header class="header">

            <div class="header-label">
                Agendamento disponível
            </div>

            <h1>{{ $nomeEvento }}</h1>

            <p>
                {{ $company->name }}
                ·
                {{ $nomeServico }}
            </p>

            <div class="event-details">

                <div class="event-detail">
                    <strong>Data:</strong>

                    @if ($dataEvento)
                        {{ \Carbon\Carbon::parse($dataEvento)->format('d/m/Y') }}
                    @else
                        Não informada
                    @endif
                </div>

                <div class="event-detail">
                    <strong>Horário:</strong>
                    {{ $horaInicio }} às {{ $horaFim }}
                </div>

            </div>

        </header>

        <section class="form-content">

            @if (session('success'))

                <div class="notice-success">

                    <div class="success-icon">✓</div>

                    <h2>Agendamento confirmado!</h2>

                    <p>
                        {{ session('success') }}
                    </p>

                    <p style="margin-top: 12px;">
                        Agradecemos por escolher
                        {{ $company->name }}.
                    </p>

                </div>

            @else

                <h2 class="section-title">
                    Reserve seu horário
                </h2>

                <p class="section-description">
                    Escolha o profissional e o horário desejado.
                    Depois, informe seus dados para confirmar a reserva.
                </p>

                @if ($errors->any())

                    <div class="notice notice-error">
                        <strong>Não foi possível concluir a solicitação.</strong>

                        <ul style="margin: 8px 0 0 18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                @endif

                @if (! $temHorarios)

                    <div class="empty-state">
                        <strong>Não há horários disponíveis no momento.</strong>
                        <br>
                        Todos os horários desta agenda já foram reservados
                        ou não existem horários cadastrados.
                    </div>

                @else

                    <form
                        method="POST"
                        action="{{ route('public.schedules.store', [
                            'company' => $company,
                            'agendaId' => request()->route('agendaId')
                        ]) }}"
                    >

                        @csrf

                        <div class="field">

                            <label for="profissional">
                                Profissional
                                <span class="required">*</span>
                            </label>

                            <select
                                name="profissional"
                                id="profissional"
                                required
                            >

                                <option value="">
                                    Selecione um profissional
                                </option>

                                @foreach ($profissionais as $profissional)

                                    @if (! empty($horariosDisponiveis[$profissional]))

                                        <option
                                            value="{{ $profissional }}"
                                            @selected(old('profissional') === $profissional)
                                        >
                                            {{ $profissional }}
                                        </option>

                                    @endif

                                @endforeach

                            </select>

                        </div>

                        <div class="field">

                            <label for="horario">
                                Horário disponível
                                <span class="required">*</span>
                            </label>

                            <select
                                id="horario"
                                required
                                disabled
                            >
                                <option value="">
                                    Primeiro selecione um profissional
                                </option>
                            </select>

                            <input
                                type="hidden"
                                name="horario_inicio"
                                id="horario_inicio"
                            >

                            <input
                                type="hidden"
                                name="horario_fim"
                                id="horario_fim"
                            >

                        </div>

                        <hr class="divider">

                        <h2 class="section-title">
                            Seus dados
                        </h2>

                        <p class="section-description">
                            Precisamos dessas informações para identificar
                            e confirmar seu agendamento.
                        </p>

                        <div class="field">

                            <label for="nome">
                                Nome completo
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="cliente[nome]"
                                id="nome"
                                value="{{ old('cliente.nome') }}"
                                maxlength="150"
                                autocomplete="name"
                                placeholder="Digite seu nome completo"
                                required
                            >

                        </div>

                        <div class="field">

                            <label for="telefone">
                                Telefone / WhatsApp
                                <span class="required">*</span>
                            </label>

                            <input
                                type="tel"
                                name="cliente[telefone]"
                                id="telefone"
                                value="{{ old('cliente.telefone') }}"
                                maxlength="30"
                                autocomplete="tel"
                                placeholder="(00) 00000-0000"
                                required
                            >

                        </div>

                        <div class="field">

                            <label for="email">
                                E-mail
                                <span style="color:#94a3b8;font-weight:400;">
                                    (opcional)
                                </span>
                            </label>

                            <input
                                type="email"
                                name="cliente[email]"
                                id="email"
                                value="{{ old('cliente.email') }}"
                                maxlength="150"
                                autocomplete="email"
                                placeholder="seuemail@exemplo.com"
                            >

                        </div>

                        <button
                            type="submit"
                            class="submit-button"
                            id="submit-button"
                            disabled
                        >
                            Confirmar agendamento
                        </button>

                    </form>

                @endif

            @endif

        </section>

    </main>

    <footer class="footer">
        Agendamento online · AgendaPro
    </footer>

</div>

<script>
    const horariosDisponiveis = {{ Illuminate\Support\Js::from($horariosDisponiveis) }};

    const profissionalSelect = document.getElementById('profissional');
    const horarioSelect = document.getElementById('horario');
    const horarioInicioInput = document.getElementById('horario_inicio');
    const horarioFimInput = document.getElementById('horario_fim');
    const submitButton = document.getElementById('submit-button');

    if (
        profissionalSelect
        && horarioSelect
        && horarioInicioInput
        && horarioFimInput
        && submitButton
    ) {

        function atualizarHorarios() {
            const profissional = profissionalSelect.value;

            horarioSelect.innerHTML = '';
            horarioInicioInput.value = '';
            horarioFimInput.value = '';

            const horarios = horariosDisponiveis[profissional] || [];

            if (!profissional || horarios.length === 0) {
                horarioSelect.disabled = true;

                horarioSelect.add(
                    new Option(
                        profissional
                            ? 'Nenhum horário disponível'
                            : 'Primeiro selecione um profissional',
                        ''
                    )
                );

                submitButton.disabled = true;
                return;
            }

            horarioSelect.disabled = false;

            horarioSelect.add(
                new Option('Selecione um horário', '')
            );

            horarios.forEach(function (horario) {
                const inicio = horario.inicio;
                const fim = horario.fim;

                if (!inicio || !fim) {
                    return;
                }

                horarioSelect.add(
                    new Option(inicio + ' às ' + fim, inicio + '|' + fim)
                );
            });

            submitButton.disabled = true;
        }

        function atualizarSelecao() {
            const valor = horarioSelect.value;

            horarioInicioInput.value = '';
            horarioFimInput.value = '';

            if (!valor) {
                submitButton.disabled = true;
                return;
            }

            const partes = valor.split('|');

            if (partes.length !== 2) {
                submitButton.disabled = true;
                return;
            }

            horarioInicioInput.value = partes[0];
            horarioFimInput.value = partes[1];

            submitButton.disabled = false;
        }

        profissionalSelect.addEventListener(
            'change',
            atualizarHorarios
        );

        horarioSelect.addEventListener(
            'change',
            atualizarSelecao
        );

        if (profissionalSelect.value) {
            atualizarHorarios();
        }
    }
</script>

</body>
</html>
