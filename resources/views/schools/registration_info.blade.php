<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Registrasi Sekolah</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f9; font-family: 'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9; padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                    
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding:30px 40px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:24px; font-weight:600;">
                                🎉 Selamat Datang di {{ $system_name }}!
                            </h1>
                            <p style="color:#e0d4f7; margin:8px 0 0; font-size:14px;">
                                Registrasi sekolah Anda berhasil
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:30px 40px;">
                            <p style="color:#333; font-size:16px; margin:0 0 20px;">
                                Halo <strong>{{ $admin_name }}</strong>,
                            </p>
                            <p style="color:#555; font-size:14px; line-height:1.6; margin:0 0 24px;">
                                Sekolah <strong>{{ $school_name }}</strong> telah berhasil didaftarkan. 
                                Berikut adalah informasi login Anda:
                            </p>

                            {{-- Info Card --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8f9fc; border-radius:8px; border:1px solid #e2e8f0; margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:8px 0; border-bottom:1px solid #e2e8f0;">
                                                    <span style="color:#888; font-size:12px; text-transform:uppercase; letter-spacing:1px;">📧 Email Sekolah</span><br>
                                                    <span style="color:#333; font-size:16px; font-weight:600;">{{ $school_email }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0; border-bottom:1px solid #e2e8f0;">
                                                    <span style="color:#888; font-size:12px; text-transform:uppercase; letter-spacing:1px;">📱 Nomor Telepon</span><br>
                                                    <span style="color:#333; font-size:16px; font-weight:600;">{{ $school_phone }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;">
                                                    <span style="color:#888; font-size:12px; text-transform:uppercase; letter-spacing:1px;">🏫 Kode Sekolah</span><br>
                                                    <span style="color:#667eea; font-size:20px; font-weight:700; letter-spacing:2px;">{{ $school_code }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Login Info --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#fffbeb; border-radius:8px; border:1px solid #fbbf24; margin-bottom:24px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <p style="color:#92400e; font-size:13px; margin:0; font-weight:600;">⚠️ Informasi Login:</p>
                                        <p style="color:#78350f; font-size:13px; margin:6px 0 0; line-height:1.5;">
                                            <strong>Email:</strong> {{ $school_email }}<br>
                                            <strong>Password:</strong> {{ $school_phone }} <em>(nomor telepon Anda)</em><br>
                                            <strong>Kode Sekolah:</strong> {{ $school_code }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA Button --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:8px 0 24px;">
                                        <a href="{{ $login_url }}" style="display:inline-block; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#ffffff; text-decoration:none; padding:14px 40px; border-radius:8px; font-size:15px; font-weight:600;">
                                            Login Sekarang →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color:#999; font-size:12px; line-height:1.5; margin:0; text-align:center;">
                                Jika Anda tidak merasa mendaftar, silakan abaikan email ini.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f8f9fc; padding:20px 40px; text-align:center; border-top:1px solid #e2e8f0;">
                            <p style="color:#999; font-size:12px; margin:0;">
                                © {{ date('Y') }} {{ $system_name }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
