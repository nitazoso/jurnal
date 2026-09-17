<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sekretaris - Jurnify')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --navy:#27356f; --navy-dark:#1e2856; --blue:#4c6fff; --paper:#f6f7fb; --muted:#73809a; --line:#e6e9f2; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--paper); color:#202a44; font-family:'Plus Jakarta Sans',sans-serif; }
        .sidebar { position:fixed; inset:0 auto 0 0; width:258px; padding:28px 16px 18px; background:var(--navy); color:#fff; display:flex; flex-direction:column; }
        .brand { display:flex; align-items:center; gap:11px; padding:0 10px; margin-bottom:37px; color:#fff; text-decoration:none; }
        .brand-mark { width:38px; height:38px; display:grid; place-items:center; border-radius:10px; background:#fff; color:var(--navy); font-weight:800; font-size:19px; }
        .brand strong { display:block; font-size:16px; } .brand small { color:#c3caea; font-size:10px; }
        .menu-label { margin:0 11px 11px; color:#aab5df; font-size:10px; font-weight:700; letter-spacing:.09em; }
        .nav { display:grid; gap:5px; }
        .nav a { display:flex; align-items:center; gap:12px; padding:12px 13px; color:#e6eaff; text-decoration:none; border-radius:9px; font-size:13px; font-weight:600; }
        .nav a:hover, .nav a.active { background:var(--navy-dark); color:#fff; }
        .nav-icon { width:20px; font-size:17px; text-align:center; }
        .profile-link { margin-top:auto; border-top:1px solid rgba(255,255,255,.14); padding-top:16px; }
        .profile-link a { display:flex; align-items:center; gap:10px; text-decoration:none; color:#fff; padding:8px; border-radius:9px; }
        .profile-link a:hover { background:rgba(255,255,255,.08); }
        .avatar { width:34px; height:34px; display:grid; place-items:center; background:#bfcaf8; color:var(--navy); border-radius:50%; font-weight:800; font-size:12px; }
        .profile-link strong { display:block; font-size:12px; } .profile-link span { color:#c3caea; font-size:10px; }
        .main { margin-left:258px; min-height:100vh; }
        .topbar { height:72px; background:#fff; border-bottom:1px solid var(--line); padding:0 32px; display:flex; align-items:center; justify-content:space-between; }
        .topbar h1 { margin:0; color:#233268; font-size:18px; } .topbar-date { color:var(--muted); font-size:12px; }
        .content { padding:30px 32px 42px; max-width:1500px; }
        .page-heading { margin-bottom:25px; } .page-heading h2 { margin:0 0 7px; font-size:25px; color:#202d61; } .page-heading p { margin:0; color:var(--muted); font-size:13px; }
        .stats { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:18px; margin-bottom:24px; }
        .stat-card,.card { background:#fff; border:1px solid var(--line); border-radius:13px; box-shadow:0 2px 8px rgba(30,44,85,.03); }
        .stat-card { padding:20px; position:relative; min-height:126px; } .stat-card p { margin:0; color:#69758e; font-size:12px; font-weight:600; } .stat-card strong { display:block; margin-top:13px; font-size:32px; color:#26366f; }
        .stat-card .hint { color:#1a9257; font-size:11px; } .stat-symbol { position:absolute; top:20px; right:20px; display:grid; place-items:center; width:38px; height:38px; border-radius:10px; background:#e9edff; color:#3955cf; font-size:18px; }
        .card { padding:22px; margin-bottom:20px; } .card-head { display:flex; align-items:flex-start; justify-content:space-between; gap:15px; margin-bottom:18px; } .card-head h3 { margin:0; color:#273566; font-size:16px; } .card-head p { margin:5px 0 0; color:var(--muted); font-size:12px; }
        .button { display:inline-flex; align-items:center; justify-content:center; gap:7px; padding:10px 14px; border:0; border-radius:8px; background:var(--blue); color:#fff; font-family:inherit; font-weight:700; font-size:12px; text-decoration:none; cursor:pointer; } .button:hover { background:#385dea; }
        .button.secondary { background:#eef1ff; color:#3b54ba; } .button.outline { border:1px solid #d9dff1; background:#fff; color:#42506c; }
        .table-wrap { overflow-x:auto; } table { width:100%; border-collapse:collapse; min-width:700px; } th { padding:11px 10px; border-bottom:1px solid var(--line); color:#7b869c; text-align:left; font-size:10px; text-transform:uppercase; letter-spacing:.04em; } td { padding:15px 10px; border-bottom:1px solid #f0f2f7; color:#394561; font-size:12px; } tr:last-child td { border:0; }
        .teacher { display:flex; align-items:center; gap:9px; color:#273458; font-weight:700; } .teacher-dot { width:29px; height:29px; display:grid; place-items:center; border-radius:8px; background:#eff2ff; color:#5066c6; font-size:10px; }
        .badge { display:inline-block; padding:5px 9px; border-radius:20px; font-size:10px; font-weight:700; } .badge.pending { background:#fff3d8; color:#a66a00; } .badge.valid { background:#def7e9; color:#16804c; } .badge.revision { background:#ffe5e7; color:#bb4050; } .badge.info { background:#e7edff; color:#435bc2; }
        .split { display:grid; grid-template-columns:1.45fr 1fr; gap:20px; } .notice { border-radius:10px; padding:14px; background:#f1f4ff; color:#4259aa; font-size:12px; line-height:1.55; }
        .filters { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:18px; } .input,.select { height:39px; padding:0 11px; border:1px solid #dfe4ef; border-radius:8px; color:#4c5871; background:#fff; font:12px inherit; } .input { min-width:230px; } .select { min-width:150px; }
        .form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; } .field { display:grid; gap:7px; } .field.full { grid-column:1/-1; } label { color:#4a5671; font-size:12px; font-weight:700; } .field input,.field select,.field textarea { width:100%; border:1px solid #dce1ed; border-radius:8px; padding:11px; color:#40506b; font:12px inherit; background:#fff; } .field textarea { min-height:100px; resize:vertical; }
        .form-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
        .profile-card { max-width:690px; } .profile-hero { display:flex; gap:16px; align-items:center; padding-bottom:20px; border-bottom:1px solid var(--line); } .profile-hero .avatar { width:62px; height:62px; font-size:18px; } .profile-hero h3 { margin:0; font-size:18px; color:#273566; } .profile-hero p { margin:5px 0 0; color:var(--muted); font-size:12px; } .detail { display:grid; grid-template-columns:160px 1fr; gap:12px; padding:15px 0; border-bottom:1px solid #f0f2f7; color:#40506b; font-size:13px; } .detail span:first-child { color:#7c879b; }
        .logout { margin-top:22px; background:#fff1f2; color:#c13c4a; } .logout:hover { background:#ffe1e4; }
        @media(max-width:900px) { .sidebar { position:static; width:100%; min-height:auto; } .profile-link { margin-top:20px; } .main { margin:0; } .stats,.split { grid-template-columns:1fr; } .content { padding:24px 18px; } .topbar { padding:0 18px; } }
        @media(max-width:560px) { .form-grid { grid-template-columns:1fr; } .field.full { grid-column:auto; } .topbar-date { display:none; } }
    </style>
</head>
<body>
    @php($user = auth()->user())
    <aside class="sidebar">
        <a class="brand" href="{{ route('sekretaris.dashboard') }}"><span class="brand-mark">J</span><span><strong>Jurnify</strong><small>Panel Sekretaris</small></span></a>
        <p class="menu-label">MENU UTAMA</p>
        <nav class="nav">
            <a class="{{ request()->routeIs('sekretaris.dashboard') ? 'active' : '' }}" href="{{ route('sekretaris.dashboard') }}"><span class="nav-icon">▦</span>Dashboard</a>
            <a class="{{ request()->routeIs('sekretaris.validasi-jurnal') ? 'active' : '' }}" href="{{ route('sekretaris.validasi-jurnal') }}"><span class="nav-icon">✓</span>Validasi Jurnal</a>
            <a class="{{ request()->routeIs('sekretaris.isi-jurnal') ? 'active' : '' }}" href="{{ route('sekretaris.isi-jurnal') }}"><span class="nav-icon">✎</span>Isi Jurnal Guru</a>
        </nav>
        <div class="profile-link"><a href="{{ route('sekretaris.profil') }}"><span class="avatar">{{ $user?->initials() ?: 'SK' }}</span><span><strong>{{ $user?->nama_user ?? 'Sekretaris' }}</strong><span>Lihat profil</span></span></a></div>
    </aside>
    <main class="main">
        <header class="topbar"><h1>@yield('header', 'Panel Sekretaris')</h1><span class="topbar-date">Senin, 15 September 2026</span></header>
        <section class="content">
            @if(session('success')) <div class="notice" style="margin-bottom:18px;background:#e4f8ed;color:#167344">{{ session('success') }}</div> @endif
            @if($errors->any()) <div class="notice" style="margin-bottom:18px;background:#fff0f1;color:#b23b4a">{{ $errors->first() }}</div> @endif
            @yield('content')
        </section>
    </main>
</body>
</html>
