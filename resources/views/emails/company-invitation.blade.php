
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite para cadastro empresarial</title>
</head>
<body style="margin: 0; padding: 30px 15px; background: #f3f4f6; font-family: Arial, sans-serif; color: #1f2937;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px;">
        <tr>
            <td style="padding: 36px 30px;">

                <h1 style="margin: 0 0 24px; font-size: 24px; text-align: center;">
                    Convite para cadastro empresarial
                </h1>

                <p style="font-size: 16px; line-height: 1.6;">
                    Você recebeu um convite para cadastrar uma empresa na plataforma.
                </p>

                <p style="font-size: 16px; line-height: 1.6;">
                    <strong>Nome sugerido da empresa:</strong><br>
                    {{ $companyName }}
                </p>

                <p style="font-size: 16px; line-height: 1.6;">
                    Para concluir o cadastro, confirme seu endereço de e-mail e informe os dados do responsável e da empresa. Você poderá alterar o nome sugerido durante esse processo.
                </p>

                <div style="margin: 32px 0; text-align: center;">
                    <a href="{{ $invitationUrl }}"
                       style="display: inline-block; padding: 15px 28px; background: #374151; color: #ffffff; text-decoration: none; border-radius: 7px; font-size: 16px; font-weight: bold;">
                        Confirmar e concluir cadastro
                    </a>
                </div>

                <p style="font-size: 14px; line-height: 1.6; color: #6b7280;">
                    <strong>Validade do convite:</strong><br>
                    {{ $expiresAt }}
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #6b7280;">
                    Este convite é pessoal, destinado ao endereço de e-mail que o recebeu e pode ser utilizado uma única vez.
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #6b7280;">
                    Se você não reconhece este convite, pode simplesmente ignorar esta mensagem.
                </p>

                <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">

                <p style="margin: 0; text-align: center; font-size: 12px; color: #9ca3af;">
                    Mensagem automática. Não responda a este e-mail.
                </p>

            </td>
        </tr>
    </table>

</body>
</html>
