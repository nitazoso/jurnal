<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Akun Pengguna - Jurnify</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #f4f5f9;
            margin: 0;
            padding: 30px;
            color: #1f2937;
        }

        .receipt {
            max-width: 480px;
            margin: 0 auto;
            background: white;
            border: 2px solid #1f2937;
            padding: 22px 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }

        .center {
            text-align: center;
        }

        .title {
            font-size: 30px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
        }

        .subtitle {
            font-size: 12px;
            margin-top: 6px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .divider {
            border-top: 2px solid #1f2937;
            margin: 18px 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 10px 0 12px;
            letter-spacing: 1px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin: 8px 0;
            font-size: 13px;
            line-height: 1.5;
        }

        .label {
            font-weight: 700;
            width: 120px;
        }

        .value {
            flex: 1;
            text-align: left;
            word-break: break-word;
        }

        .small {
            font-size: 12px;
            font-weight: 700;
            margin-top: 6px;
        }

        .note {
            margin-top: 14px;
            font-size: 12px;
            line-height: 1.6;
        }

        .foot {
            margin-top: 18px;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border: 1px solid #1f2937;
            text-decoration: none;
            color: #1f2937;
            background: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .btn.primary {
            background: #1f2937;
            color: white;
        }

        .btn.whatsapp {
            background: #25D366;
            border-color: #25D366;
            color: white;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .actions {
                display: none;
            }

            .receipt {
                box-shadow: none;
                border: none;
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="center">
            <div class="title">JURNIFY</div>
            <div class="subtitle">SISTEM JURNAL MENGAJAR</div>
        </div>

        <div class="divider"></div>

        <div class="section-title center">INFORMASI AKUN PENGGUNA</div>

        <div class="info-row">
            <div class="label">Nama</div>
            <div class="value">: {{ $user->nama_user }}</div>
        </div>

        <div class="info-row">
            <div class="label">Username</div>
            <div class="value">: {{ $user->username }}</div>
        </div>

        <div class="info-row">
            <div class="label">Role</div>
            <div class="value">: {{ strtoupper($user->role) }}</div>
        </div>

        <div class="divider"></div>

        <div class="small">USERNAME</div>
        <div>{{ $user->username }}</div>

        <div class="small" style="margin-top: 14px;">PASSWORD AWAL</div>
        <div>{{ $passwordAwal }}</div>

        <div class="divider"></div>

        <div class="info-row">
            <div class="label">DIBUAT OLEH</div>
            <div class="value">: {{ $createdBy }}</div>
        </div>

        <div class="info-row">
            <div class="label">TANGGAL DIBUAT</div>
            <div class="value">: {{ $tanggalDibuat }}</div>
        </div>

        <div class="divider"></div>

        <div class="section-title center">CATATAN PENTING</div>

        <div class="note">
            Password di atas adalah password awal.<br>
            Silakan login menggunakan akun tersebut<br>
            dan segera ganti password setelah login.<br><br>

            Password tidak dapat dilihat kembali oleh<br>
            Administrator setelah akun dibuat.<br><br>

            Jika lupa password, hubungi Administrator<br>
            untuk melakukan reset password.
        </div>

        <div class="divider"></div>

        <div class="foot">JURNIFY - AKUN PENGGUNA</div>
    </div>

    <div class="actions">
        <a href="{{ route('admin.user.index') }}" class="btn">Kembali</a>
        <button class="btn primary" onclick="window.print(); return false;">Cetak Struk</button>

        @php
            $waRole = strtoupper($user->role);

            $waMessage = rawurlencode(
                "========================================\n" .
                "              JURNIFY\n" .
                "       SISTEM JURNAL MENGAJAR\n" .
                "========================================\n\n" .
                "          INFORMASI AKUN PENGGUNA\n\n" .
                "Nama        : {$user->nama_user}\n" .
                "Username    : {$user->username}\n" .
                "Role        : {$waRole}\n\n" .
                "----------------------------------------\n\n" .
                "USERNAME\n" .
                "{$user->username}\n\n" .
                "PASSWORD AWAL\n" .
                "{$passwordAwal}\n\n" .
                "----------------------------------------\n\n" .
                "DIBUAT OLEH\n" .
                "{$createdBy}\n\n" .
                "TANGGAL DIBUAT\n" .
                "{$tanggalDibuat}\n\n" .
                "----------------------------------------\n\n" .
                "CATATAN PENTING\n\n" .
                "Password di atas adalah password awal.\n" .
                "Silakan login menggunakan akun tersebut\n" .
                "dan segera ganti password setelah login.\n\n" .
                "Password tidak dapat dilihat kembali oleh\n" .
                "Administrator setelah akun dibuat.\n\n" .
                "Jika lupa password, hubungi Administrator\n" .
                "untuk melakukan reset password.\n\n" .
                "========================================\n" .
                "       JURNIFY - AKUN PENGGUNA\n" .
                "========================================"
            );
        @endphp

        <a
            href="https://wa.me/?text={{ $waMessage }}"
            class="btn whatsapp"
            target="_blank"
            rel="noopener noreferrer"
        >
            Kirim via WhatsApp
        </a>
    </div>
</body>
</html>
