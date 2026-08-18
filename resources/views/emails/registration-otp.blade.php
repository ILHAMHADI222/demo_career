<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode OTP Registrasi</title>
</head>
<body style="margin:0;background:#f8fafc;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f8fafc;padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:560px;background:#ffffff;border-radius:24px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:#f97316;padding:28px 32px;color:#ffffff;">
                            <h1 style="margin:0;font-size:24px;line-height:1.3;font-weight:800;">Verifikasi Email Registrasi</h1>
                            <p style="margin:8px 0 0;font-size:14px;line-height:1.6;opacity:.95;">Career Indodata</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">Halo <strong>{{ $user->name }}</strong>,</p>
                            <p style="margin:0 0 20px;font-size:15px;line-height:1.7;">Gunakan kode OTP berikut untuk menyelesaikan pendaftaran akun kandidat Anda.</p>

                            <div style="margin:28px 0;padding:22px;border-radius:18px;background:#fff7ed;border:1px solid #fed7aa;text-align:center;">
                                <p style="margin:0 0 10px;font-size:12px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#ea580c;">Kode OTP</p>
                                <div style="font-size:36px;letter-spacing:.28em;font-weight:900;color:#0f172a;">{{ $otp }}</div>
                            </div>

                            <p style="margin:0 0 14px;font-size:14px;line-height:1.7;color:#475569;">Kode ini berlaku selama <strong>{{ $expiresInMinutes }} menit</strong>. Jangan bagikan kode ini kepada siapa pun.</p>
                            <p style="margin:0;font-size:13px;line-height:1.7;color:#64748b;">Jika Anda tidak merasa melakukan pendaftaran, abaikan email ini.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 32px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#94a3b8;">Email otomatis dari sistem Career Indodata.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
