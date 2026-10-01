<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0"
          rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    @yield('head')

    <title>@yield('title', 'Staff Piket')</title>


    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            overflow-x: hidden;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: #fbfbfb;
            color: #1f2937;
            min-height: 100vh;
        }

        img, svg, video, canvas, iframe, embed, object {
            max-width: 100%;
            height: auto;
        }

        a, button, input, select, textarea {
            max-width: 100%;
        }

        .content * {
            max-width: 100%;
        }

        /* Overlay untuk menutup sidebar saat klik di luar (Mobile) */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 99;
            backdrop-filter: blur(2px);
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* =========================
           SIDEBAR
        ========================= */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #30366f;
            padding: 31px 24px;
            z-index: 100;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #30366f;
            flex-shrink: 0;
        }

        .brand-icon .material-symbols-outlined {
            font-size: 28px;
        }

        .brand-text h1 {
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand-text p {
            color: #aeb2d0;
            font-size: 10px;
            margin-top: 3px;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .nav-item {
            height: 44px;
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 0 14px;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: .2s;
        }

        .nav-item .material-symbols-outlined {
            font-size: 22px;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, .08);
        }

        .nav-item.active {
            background: #1e2945;
            color: #93c5fd;
            position: relative;
        }

        .nav-item.active .material-symbols-outlined {
            color: #93c5fd;
        }

        .nav-item.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #4169ff;
            border-radius: 3px 0 0 3px;
        }

        /* =========================
           LOGOUT
        ========================= */
        .logout-wrap {
            margin-top: auto;
            padding-top: 24px;
            width: 100%;
        }

        .logout-wrap form {
            width: 100%;
        }

        .logout-wrap button {
            width: 100%;
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.25s ease;
            outline: none;
        }

        .logout-wrap button .material-symbols-outlined {
            font-size: 20px;
            transition: transform 0.25s ease;
        }

        .logout-wrap button:hover {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4);
        }

        .logout-wrap button:hover .material-symbols-outlined {
            transform: translateX(4px);
        }

        /* =========================
           MAIN & TOPBAR
        ========================= */
        .main {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 90;
            height: 72px;
            background: #fff;
            border-bottom: 1px solid #eeeeee;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
        }

        .topbar-meta {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-clock {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f6f8ff;
            border: 1px solid #dde5ff;
            border-radius: 12px;
            padding: 8px 12px;
            min-width: 240px;
            justify-content: flex-end;
        }

        .topbar-clock .material-symbols-outlined {
            font-size: 18px;
            color: #3b5bd4;
        }

        .topbar-clock small {
            display: block;
            color: #53627d;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .topbar-clock strong {
            display: block;
            color: #17265d;
            font-size: 13px;
            font-weight: 800;
            line-height: 1.3;
        }

        /* Tombol Menu Garis 3 (Sembunyi di Desktop) */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #17265d;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            align-items: center;
            justify-content: center;
        }

        .menu-toggle:hover {
            background: #f1f2f5;
        }

        .menu-toggle .material-symbols-outlined {
            font-size: 28px;
        }

        .topbar h2 {
            color: #17265d;
            font-size: 19px;
            font-weight: 700;
        }

        .content {
            padding: 32px 24px 20px;
        }

        /* =========================
           RESPONSIVE (HP & TABLET)
        ========================= */
        @media (max-width: 800px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main { margin-left: 0; }
            .menu-toggle { display: flex; flex: 0 0 36px; }
            .topbar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 90;
                background: #30366f;
                color: #fff;
                display: flex;
                flex-wrap: nowrap;
                height: 64px;
                min-height: 64px;
                padding: 0 12px;
                gap: 8px;
            }
            .menu-toggle {
                border-color: rgba(255,255,255,.25);
                background: rgba(255,255,255,.12);
                color: #fff;
            }
            .topbar-clock .material-symbols-outlined { color: rgba(255,255,255,.9); }
            .topbar h2 {
                color: #fff;
                flex: 1 1 auto;
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-size: 15px;
            }
            .topbar-meta {
                width: auto;
                flex: 0 0 auto;
                margin-left: auto;
                justify-content: flex-end;
            }
            .topbar-clock {
                min-width: 0;
                gap: 6px;
                border-color: rgba(255,255,255,.24);
                background: rgba(255,255,255,.12);
                color: #fff;
                padding: 6px 8px;
            }
            .topbar-clock > div {
                display: flex;
                align-items: center;
                gap: 6px;
                white-space: nowrap;
            }
            .topbar-clock small {
                color: rgba(255,255,255,.72);
                font-size: 8px;
                letter-spacing: 0;
            }
            .topbar-clock strong { color: #fff; font-size: 11px; }
            .content { padding: 84px 12px 24px; }
        }

        @media (max-width: 380px) {
            .topbar-clock small { display: none; }
            .topbar h2 { font-size: 14px; }
        }
    </style>
</head>
<body>
    <!-- Overlay hitam saat sidebar mobile terbuka -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="app">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-icon">
                    <span class="material-symbols-outlined">menu_book</span>
                </div>
                <div class="brand-text">
                    <h1>Jurnify</h1>
                    <p>Kementerian Pendidikan</p>
                </div>
            </div>

            <nav class="nav">
                <a href="{{ route('piket.dashboard') }}" class="nav-item {{ request()->routeIs('piket.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">home</span> 
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('piket.jurnal.rekap') }}" class="nav-item {{ request()->routeIs('piket.jurnal.rekap') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">summarize</span>
                    <span>Rekap Aktivitas Jurnal</span>
                </a>
                @unless(cache('jadwal_all_disabled', false))
                <a href="{{ route('piket.jurnal.create') }}" class="nav-item {{ request()->routeIs('piket.jurnal.create', 'piket.jurnal.form') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">edit_note</span>
                    <span>Isi Jurnal Guru</span>
                </a>
                @endunless
                <a href="{{ route('piket.dispen.index') }}" class="nav-item {{ request()->routeIs('piket.dispen.*') && !request()->routeIs('piket.dispen.sakit.*', 'piket.dispen.history') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">report</span> 
                    <span>Dispen</span>
                </a>
                <a href="{{ route('piket.izin-sakit.index') }}" class="nav-item {{ request()->routeIs('piket.izin-sakit.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">medical_services</span>
                    <span>Izin &amp; Sakit</span>
                </a>
                <a href="{{ route('piket.profil') }}" class="nav-item {{ request()->routeIs('piket.profil') ? 'active' : '' }}"> 
                    <span class="material-symbols-outlined">person</span> 
                    <span>Profil</span>
                </a>
            </nav>

            <div class="logout-wrap">
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Anda yakin ingin logout?');">
                    @csrf
                    <button type="submit">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>

        </aside>

        <!-- Content Area -->
        <main class="main">
            <header class="topbar">
                <!-- Tombol Garis Tiga di HP -->
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <span class="material-symbols-outlined">menu</span>
                </button>

                <h2>@yield('page-title', 'Dashboard')</h2>

                <div class="topbar-meta">
                    <div class="topbar-clock">
                        <span class="material-symbols-outlined">schedule</span>
                        <div>
                            <small id="liveDate">Tanggal</small>
                            <strong id="liveTime">--:--:--</strong>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function updateDashboardClock() {
            const dateEl = document.getElementById('liveDate');
            const timeEl = document.getElementById('liveTime');

            if (!dateEl || !timeEl) return;

            const now = new Date();
            const dateText = new Intl.DateTimeFormat('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }).format(now);

            const timeText = new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).format(now);

            dateEl.textContent = dateText;
            timeEl.textContent = timeText;
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('show');
        }

        updateDashboardClock();
        setInterval(updateDashboardClock, 1000);
    </script>
</body>
</html>