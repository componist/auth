<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zwei-Faktor-Code</title>
</head>
<body style="margin:0;padding:0;background-color:#f8fafc;font-family:ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f8fafc;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:480px;background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="height:4px;background-color:#14b8a6;"></td>
                    </tr>
                    <tr>
                        <td style="padding:32px 28px;">
                            <p style="margin:0 0 8px;font-size:14px;color:#64748b;">Hallo {{ $name }},</p>
                            <h1 style="margin:0 0 16px;font-size:22px;font-weight:600;color:#0f172a;">Dein Zwei-Faktor-Code</h1>
                            <p style="margin:0 0 20px;font-size:15px;line-height:1.6;color:#475569;">
                                Verwende den folgenden Code, um deine Anmeldung abzuschließen:
                            </p>
                            <p style="margin:0 0 24px;padding:16px 20px;background:#f0fdfa;border:1px solid #99f6e4;border-radius:12px;text-align:center;font-size:32px;font-weight:700;letter-spacing:0.2em;color:#0f766e;">
                                {{ $code }}
                            </p>
                            <p style="margin:0;font-size:13px;color:#64748b;">
                                Gültig bis: <strong style="color:#0f172a;">{{ $expiresLabel }}</strong> Uhr
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
