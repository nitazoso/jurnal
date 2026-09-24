<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kesiswaan - Jurnify')</title>
    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f6fa; color: #1f2937; }
        img, svg, video, canvas, iframe, embed, object { max-width: 100%; height: auto; }
        a, button, input, select, textarea { max-width: 100%; }
        .content * { max-width: 100%; }
        .app { display: flex; min-height: 100vh; }
        .sidebar { width: 245px; background: #30366f; color: white; padding: 24px 16px; flex-shrink: 0; }
        .brand { padding: 8px 12px 28px; font-size: 21px; font-weight: 700; }
        .brand small { display: block; margin-top: 5px; color: #c7cbed; font-size: 12px; font-weight: 400; }
        .nav { display: grid; gap: 8px; }
        .nav a, .logout-button { display: block; width: 100%; padding: 12px 14px; border: 0; border-radius: 6px; background: transparent; color: white; text-align: left; text-decoration: none; font-size: 14px; cursor: pointer; }
        .nav a:hover, .nav a.active { background: #1e2945; }
        .logout { margin-top: 28px; border-top: 1px solid rgba(255,255,255,.2); padding-top: 18px; }
        .main { flex: 1; min-width: 0; }
        .topbar { padding: 22px 28px; background: white; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .topbar h1 { margin: 0; font-size: 22px; }
        .topbar-clock {
            display: flex; align-items: center; gap: 10px; background: #f5f7ff; border: 1px solid #dde5ff; border-radius: 10px; padding: 8px 12px; min-width: 220px; justify-content: flex-end;
        }
        .topbar-clock small { display: block; color: #5b6478; font-size: 10px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
        .topbar-clock strong { display: block; color: #1f2937; font-size: 13px; font-weight: 800; }
        .content { padding: 28px; }
        .card { padding: 22px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; }
        @media (max-width: 700px) {
            .app { display: block; }
            .sidebar { width: 100%; }
            .topbar { flex-wrap: wrap; padding: 18px 16px; }
            .topbar h1 { font-size: 20px; }
            .topbar-clock { width: 100%; justify-content: center; }
            .content { padding: 18px 14px; }
        }
    </style>
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                Jurnify
                <small>Panel Kesiswaan</small>
            </div>

            <nav class="nav">
                <a href="{{ route('kesiswaan.dashboard') }}" class="{{ request()->routeIs('kesiswaan.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('kesiswaan.dispen.index') }}" class="{{ request()->routeIs('kesiswaan.dispen.index', 'kesiswaan.dispen.show', 'kesiswaan.dispen.approve', 'kesiswaan.dispen.reject') ? 'active' : '' }}">Persetujuan Dispen</a>
                <a href="{{ route('kesiswaan.dispen.history') }}" class="{{ request()->routeIs('kesiswaan.dispen.history') ? 'active' : '' }}">Riwayat Dispen</a>
                <a href="{{ route('kesiswaan.profil') }}" class="{{ request()->routeIs('kesiswaan.profil') ? 'active' : '' }}">Profil</a>
            </nav>

            <div class="logout">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button">Keluar</button>
                </form>
            </div>
        </aside>

        <main class="main">
            <header class="topbar">
                <h1>@yield('page-title', 'Kesiswaan')</h1>
                <div class="topbar-clock">
                    <div>
                        <small id="liveDate">Tanggal</small>
                        <strong id="liveTime">--:--:--</strong>
                    </div>
                </div>
            </header>
            <section class="content">
                @yield('content')
            </section>
        </main>
    </div>

    <script>
        function updateDashboardClock() {
            const dateEl = document.getElementById('liveDate');
            const timeEl = document.getElementById('liveTime');
            if (!dateEl || !timeEl) return;

            const now = new Date();
            dateEl.textContent = new Intl.DateTimeFormat('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }).format(now);

            timeEl.textContent = new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).format(now);
        }

        updateDashboardClock();
        setInterval(updateDashboardClock, 1000);
    </script>
</body>
</html>
