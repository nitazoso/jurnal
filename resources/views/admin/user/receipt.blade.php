<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Akun Pengguna - Jurnify</title>
    <style>
        :root{--receipt-navy:#30366f;--receipt-blue:#4169ff;--receipt-ink:#202747;--receipt-muted:#74809a;--receipt-line:#e6eaf2;--receipt-bg:#f4f6fb}
        *{box-sizing:border-box}
        body{min-height:100vh;margin:0;padding:clamp(18px,4vw,44px);background:var(--receipt-bg);color:var(--receipt-ink);font-family:Manrope,Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif}
        .receipt{width:min(100%,760px);margin:0 auto;overflow:hidden;border:1px solid var(--receipt-line);border-radius:20px;background:#fff;box-shadow:0 18px 48px rgba(35,46,91,.1)}
        .receipt-header{position:relative;overflow:hidden;padding:28px 32px;background:linear-gradient(125deg,#30366f,#202956);color:#fff}
        .receipt-header:after{position:absolute;right:-48px;bottom:-105px;width:250px;height:250px;border:1px solid rgba(255,255,255,.12);border-radius:50%;content:"";box-shadow:0 0 0 24px rgba(255,255,255,.035),0 0 0 52px rgba(255,255,255,.025)}
        .receipt-brand{position:relative;z-index:1;display:flex;align-items:center;gap:13px}.receipt-brand-icon{display:grid;width:46px;height:46px;place-items:center;border:1px solid rgba(255,255,255,.2);border-radius:14px;background:rgba(255,255,255,.12);font-size:21px;font-weight:800}.receipt-brand-name{margin:0;font-size:20px;font-weight:850;letter-spacing:.02em}.receipt-brand-subtitle{margin:3px 0 0;color:#d9def5;font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase}
        .receipt-header-label{position:relative;z-index:1;display:inline-flex;margin-top:25px;padding:6px 10px;border:1px solid rgba(255,255,255,.2);border-radius:999px;background:rgba(255,255,255,.1);color:#e9ecfb;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.receipt-header h1{position:relative;z-index:1;margin:9px 0 0;font-size:23px;font-weight:800;letter-spacing:-.02em}
        .receipt-content{display:grid;gap:20px;padding:26px 32px 30px}.receipt-section{min-width:0}.receipt-section-title{margin:0 0 13px;color:#30366f;font-size:13px;font-weight:850}.receipt-info-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.receipt-info{min-width:0;padding:12px 14px;border:1px solid #e9edf5;border-radius:11px;background:#fbfcff}.receipt-label{display:block;margin-bottom:5px;color:#8791a7;font-size:9px;font-weight:850;letter-spacing:.08em;text-transform:uppercase}.receipt-value{display:block;color:#27324d;font-size:13px;font-weight:750;line-height:1.5;overflow-wrap:anywhere}
        .credential-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.credential-card{min-width:0;padding:15px;border:1px solid #dfe7ff;border-radius:13px;background:linear-gradient(145deg,#f7f9ff,#fff)}.credential-card--password{border-color:#d9e2fb;background:linear-gradient(145deg,#f4f7ff,#fff)}.credential-value{display:block;margin-top:7px;color:#30366f;font-size:16px;font-weight:850;overflow-wrap:anywhere}.credential-caption{display:block;margin-top:5px;color:#8590a7;font-size:10px;line-height:1.45}
        .receipt-note{padding:14px 16px;border:1px solid #f2e4b5;border-radius:12px;background:#fffcf2;color:#76612e;font-size:11px;line-height:1.65}.receipt-note strong{display:block;margin-bottom:4px;color:#6d561e;font-size:12px}.receipt-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;padding-top:16px;border-top:1px solid var(--receipt-line);color:#8b94a8;font-size:10px}.receipt-footer strong{color:#526080;font-weight:800}
        .actions{display:flex;justify-content:center;gap:10px;margin:20px auto 0;flex-wrap:wrap}.btn{display:inline-flex;min-height:42px;align-items:center;justify-content:center;gap:8px;padding:0 16px;border:1px solid #dce2ed;border-radius:10px;background:#fff;color:#48536b;font:inherit;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;transition:background .16s,transform .16s,box-shadow .16s}.btn:hover{transform:translateY(-1px);box-shadow:0 6px 15px rgba(35,46,91,.1)}.btn.primary{border-color:#30366f;background:#30366f;color:#fff}.btn.primary:hover{background:#252b5d}.btn.whatsapp{border-color:#168b55;background:#168b55;color:#fff}.btn.whatsapp:hover{background:#117446}
        @media(max-width:600px){body{padding:14px}.receipt{border-radius:16px}.receipt-header{padding:23px 20px}.receipt-brand-icon{width:42px;height:42px}.receipt-brand-name{font-size:18px}.receipt-header h1{font-size:20px}.receipt-content{gap:17px;padding:20px}.receipt-info-grid{grid-template-columns:1fr 1fr;gap:8px}.receipt-info{padding:10px}.receipt-value{font-size:12px}.credential-grid{grid-template-columns:1fr}.credential-card{padding:13px}.receipt-footer{align-items:flex-start;flex-direction:column}.actions{display:grid;grid-template-columns:1fr;width:min(100%,760px)}.btn{width:100%}}
        @media print{body{min-height:0;padding:0;background:#fff}.receipt{width:100%;max-width:none;border:0;border-radius:0;box-shadow:none}.receipt-header{padding:20px 24px!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}.receipt-content{padding:20px 24px}.actions{display:none}}
    </style>
</head>
<body>
    <main class="receipt">
        <header class="receipt-header">
            <div class="receipt-brand"><div class="receipt-brand-icon">J</div><div><p class="receipt-brand-name">Jurnify</p><p class="receipt-brand-subtitle">Sistem Jurnal Mengajar</p></div></div>
            <span class="receipt-header-label">Informasi akun pengguna</span>
            <h1>Akun berhasil dibuat</h1>
        </header>
        <div class="receipt-content">
            <section class="receipt-section">
                <h2 class="receipt-section-title">Identitas akun</h2>
                <div class="receipt-info-grid">
                    <div class="receipt-info"><span class="receipt-label">Nama</span><span class="receipt-value">{{ $user->nama_user }}</span></div>
                    <div class="receipt-info"><span class="receipt-label">Role</span><span class="receipt-value">{{ strtoupper($user->role) }}</span></div>
                </div>
            </section>
            <section class="receipt-section">
                <h2 class="receipt-section-title">Kredensial awal</h2>
                <div class="credential-grid">
                    <div class="credential-card"><span class="receipt-label">Username</span><span class="credential-value">{{ $user->username }}</span><span class="credential-caption">Gunakan username ini untuk masuk ke Jurnify.</span></div>
                    <div class="credential-card credential-card--password"><span class="receipt-label">Password awal</span><span class="credential-value">{{ $passwordAwal }}</span><span class="credential-caption">Simpan dengan aman dan segera ganti setelah login.</span></div>
                </div>
            </section>
            <div class="receipt-note"><strong>Catatan keamanan</strong>Password awal hanya ditampilkan saat akun dibuat. Administrator tidak dapat melihatnya kembali. Jika lupa password, hubungi Administrator untuk melakukan reset.</div>
            <section class="receipt-section">
                <h2 class="receipt-section-title">Informasi pembuatan</h2>
                <div class="receipt-info-grid">
                    <div class="receipt-info"><span class="receipt-label">Dibuat oleh</span><span class="receipt-value">{{ $createdBy }}</span></div>
                    <div class="receipt-info"><span class="receipt-label">Tanggal dibuat</span><span class="receipt-value">{{ $tanggalDibuat }}</span></div>
                </div>
            </section>
            <footer class="receipt-footer"><strong>JURNIFY · AKUN PENGGUNA</strong><span>Dokumen informasi akun</span></footer>
        </div>
    </main>
    <div class="actions">
        <a href="{{ route('admin.user.index') }}" class="btn">Kembali</a>
        <button class="btn primary" type="button" onclick="window.print()">Cetak struk</button>
        @php
            $waRole = strtoupper($user->role);
            $waMessage = rawurlencode(
                "JURNIFY - INFORMASI AKUN PENGGUNA\n\n" .
                "Nama: {$user->nama_user}\n" .
                "Username: {$user->username}\n" .
                "Role: {$waRole}\n\n" .
                "Username: {$user->username}\n" .
                "Password awal: {$passwordAwal}\n\n" .
                "Password hanya ditampilkan saat akun dibuat. Segera ganti password setelah login.\n\n" .
                "Dibuat oleh: {$createdBy}\n" .
                "Tanggal dibuat: {$tanggalDibuat}"
            );
        @endphp
        <a href="https://wa.me/?text={{ $waMessage }}" class="btn whatsapp" target="_blank" rel="noopener noreferrer">Kirim via WhatsApp</a>
    </div>
</body>
</html>
