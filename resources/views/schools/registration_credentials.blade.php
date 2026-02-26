<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Credentials — {{ $system_name }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f9; font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">

    <!-- Main Container -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9; padding:40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); padding:40px 40px 30px; text-align:center;">
                            <div style="font-size:48px; margin-bottom:12px;">🎓</div>
                            <h1 style="color:#ffffff; font-size:24px; margin:0 0 8px; font-weight:700;">
                                Selamat Datang di {{ $system_name }}!
                            </h1>
                            <p style="color:rgba(255,255,255,0.85); font-size:14px; margin:0;">
                                Akun sekolah Anda telah berhasil dibuat
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:35px 40px;">

                            <p style="color:#374151; font-size:15px; line-height:1.6; margin:0 0 25px;">
                                Halo <strong>{{ $admin_name }}</strong>,<br>
                                Berikut adalah detail sekolah dan kredensial login Anda.
                            </p>

                            <!-- School Details Card -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4ff; border-radius:10px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <h3 style="color:#4338ca; font-size:14px; text-transform:uppercase; letter-spacing:0.5px; margin:0 0 14px; font-weight:700;">
                                            📋 Detail Sekolah
                                        </h3>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="color:#6b7280; font-size:13px; padding:4px 0; width:120px;">Nama Sekolah</td>
                                                <td style="color:#1f2937; font-size:13px; padding:4px 0; font-weight:600;">{{ $school_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#6b7280; font-size:13px; padding:4px 0;">Kode Sekolah</td>
                                                <td style="color:#1f2937; font-size:13px; padding:4px 0; font-weight:600;">{{ $school_code }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Credentials Card -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fef3c7; border-left:4px solid #f59e0b; border-radius:0 10px 10px 0; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <h3 style="color:#92400e; font-size:14px; text-transform:uppercase; letter-spacing:0.5px; margin:0 0 14px; font-weight:700;">
                                            🔐 Kredensial Login
                                        </h3>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="color:#78350f; font-size:13px; padding:4px 0; width:120px;">Email</td>
                                                <td style="color:#1f2937; font-size:13px; padding:4px 0; font-weight:600;">{{ $email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#78350f; font-size:13px; padding:4px 0;">Password</td>
                                                <td style="color:#1f2937; font-size:13px; padding:4px 0; font-weight:600; font-family:monospace; letter-spacing:1px;">{{ $password }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Login Button -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $login_url }}"
                                           style="display:inline-block; background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:#ffffff; text-decoration:none; padding:14px 40px; border-radius:8px; font-size:15px; font-weight:700; letter-spacing:0.3px;">
                                            🚀 Login Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Warning -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fef2f2; border-radius:10px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <p style="color:#991b1b; font-size:12px; margin:0; line-height:1.5;">
                                            ⚠️ <strong>Penting:</strong> Demi keamanan akun Anda, segera ubah password setelah login pertama kali.
                                            Jangan bagikan kredensial ini kepada pihak yang tidak berwenang.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- WhatsApp Note -->
                            <p style="color:#6b7280; font-size:12px; line-height:1.5; margin:0; text-align:center;">
                                📱 Kredensial ini juga telah dikirim ke nomor WhatsApp yang terdaftar.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f9fafb; padding:24px 40px; border-top:1px solid #e5e7eb; text-align:center;">
                            <p style="color:#9ca3af; font-size:12px; margin:0 0 6px;">
                                {{ $system_name }} — School Management System
                            </p>
                            <p style="color:#9ca3af; font-size:11px; margin:0;">
                                Butuh bantuan? Hubungi <a href="mailto:{{ $support_email }}" style="color:#667eea; text-decoration:none;">{{ $support_email }}</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
