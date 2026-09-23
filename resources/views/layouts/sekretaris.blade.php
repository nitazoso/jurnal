<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sekretaris - Jurnify')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0" rel="stylesheet">
    <style>
        :root {
            --primary: #2D336B;
            --secondary: #7886C7;
            --tertiary: #A9B5DF;
            --background: #FBFBFB;
            --white: #FFFFFF;
            --text: #2D336B;
            --muted: #73809A;
            --border: #E7EAF2;
            --active: #1E234C;
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        html, body {
            overflow-x: hidden;
        }

        body {
            min-height: 100vh;
            background: var(--background);
            color: var(--text);
            font-family: 'Manrope', sans-serif;
            -webkit-font-smoothing: antialiased;
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

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* SIDEBAR */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: var(--sidebar-width);
            height: 100vh;
            overflow-y: auto;
            background: var(--primary);
            color: #fff;
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.15);
            border-radius: 999px;
        }

        .sidebar-brand {
            padding: 24px 24px 32px;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: #fff;
            color: var(--primary);
            font-size: 18px;
            font-weight: 800;
            box-shadow: 0 8px 20px rgba(0,0,0,.12);
        }

        .brand-name {
            color: #fff;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -.3px;
        }

        .brand-subtitle {
            margin-top: 3px;
            color: rgba(255,255,255,.65);
            font-size: 11px;
            font-weight: 500;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding: 0 12px 24px;
        }

        .nav-label {
            padding: 0 16px;
            margin: 4px 0 6px;
            color: rgba(255,255,255,.42);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .nav-link {
            position: relative;
            min-height: 48px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: rgba(255,255,255,.72);
            font-size: 15px;
            font-weight: 600;
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
            transform: translateX(2px);
        }

        .nav-link.active {
            background: var(--active);
            color: #fff;
        }

        .nav-link.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 10px;
            bottom: 10px;
            width: 6px;
            border-radius: 0 6px 6px 0;
            background: var(--tertiary);
        }

        .nav-icon {
            width: 22px;
            height: 22px;
            flex: 0 0 22px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-icon svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
        }

        /* MAIN */

        .main {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
        }

        /* DESKTOP HEADER */

        .desktop-header {
            position: sticky;
            top: 0;
            z-index: 100;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 0 32px;
            background: rgba(251,251,251,.94);
            border-bottom: 1px solid rgba(45,51,107,.08);
            backdrop-filter: blur(12px);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-left: auto;
        }

        .header-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-status-block {
            background: #F7F9FF;
            border: 1px solid #E0E9FF;
            border-radius: 10px;
            padding: 8px 12px;
            min-width: 150px;
        }

        .header-status-block small {
            display: block;
            color: #60708D;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header-status-block strong {
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            line-height: 1.35;
        }

        .header-left {
            min-width: 0;
        }

        .header-left h2 {
            color: var(--primary);
            font-size: 20px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -.3px;
        }

        .header-left p {
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-year {
            padding: 8px 12px;
            border: 1px solid #E1E5F2;
            border-radius: 10px;
            background: #fff;
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .header-year strong {
            margin-left: 3px;
            color: var(--primary);
            font-weight: 800;
        }

        .header-secretary {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-secretary-avatar {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--tertiary);
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
        }

        .header-secretary-name {
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            line-height: 1.2;
        }

        .header-secretary-role {
            margin-top: 2px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 500;
        }

        /* CONTENT */

        .content {
            padding: 32px 36px 40px;
            animation: contentFade .35s ease both;
        }

        @keyframes contentFade {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ALERT */

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            padding: 13px 16px;
            border-radius: 12px;
            font-size: 13px;
            line-height: 1.5;
            animation: alertIn .3s ease both;
        }

        @keyframes alertIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            border: 1px solid #B9DDCE;
            background: #EFF9F5;
            color: #245C49;
        }

        .alert-error {
            border: 1px solid #F0C5C0;
            background: #FFF4F2;
            color: #A63C2B;
        }

        /* COMMON CARD */

        .card {
            border: 1px solid var(--border);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 6px 20px rgba(45,51,107,.05);
            transition: box-shadow .25s ease, transform .25s ease;
        }

        .card:hover {
            box-shadow: 0 10px 28px rgba(45,51,107,.08);
        }

        /* MOBILE */

        .mobile-header {
            display: none;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 280px;
                max-width: 82vw;
                transform: translateX(-100%);
                box-shadow: 12px 0 30px rgba(0,0,0,.15);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 999;
                display: block;
                background: rgba(17,24,39,.42);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: opacity .28s ease, visibility .28s ease;
            }

            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }

            .main {
                margin-left: 0;
            }

            .desktop-header {
                display: none;
            }

            .mobile-header {
                position: sticky;
                top: 0;
                z-index: 900;
                height: 58px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 18px;
                background: var(--primary);
                color: #fff;
                box-shadow: 0 4px 14px rgba(45,51,107,.14);
            }

            .mobile-left {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }

            .mobile-menu {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 0;
                border-radius: 10px;
                background: rgba(255,255,255,.1);
                color: #fff;
                cursor: pointer;
                transition: background .2s ease, transform .2s ease;
            }

            .mobile-menu:hover {
                background: rgba(255,255,255,.17);
            }

            .mobile-menu:active {
                transform: scale(.94);
            }

            .mobile-menu svg {
                width: 20px;
                height: 20px;
                stroke: currentColor;
            }

            .mobile-brand {
                color: #fff;
                font-size: 17px;
                font-weight: 800;
            }

            .mobile-year {
                color: rgba(255,255,255,.7);
                font-size: 10px;
                font-weight: 600;
                white-space: nowrap;
            }

            .content {
                padding: 24px 18px 32px;
            }
        }

        @media (max-width: 420px) {
            .content {
                padding: 20px 14px 32px;
            }

            .header-status {
                width: 100%;
                justify-content: center;
            }

            .mobile-header {
                padding: 0 14px;
            }

            .mobile-year {
                display: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>

    @yield('head')
</head>

<body>

    <aside class="sidebar" id="sekretarisSidebar">
        <div class="sidebar-brand">
            <a href="{{ route('sekretaris.dashboard') }}" class="brand-link">
                <div class="brand-logo">
                    <span class="material-symbols-outlined">
                        menu_book
                    </span>
                </div>
                <div>
                    <div class="brand-name">Jurnify</div>
                    <div class="brand-subtitle">Panel Sekretaris</div>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('sekretaris.dashboard') }}"
               class="nav-link {{ request()->routeIs('sekretaris.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M3 10.5 12 3l9 7.5"/>
                        <path d="M5.5 9.5V21h13V9.5"/>
                        <path d="M9.5 21v-6h5v6"/>
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('sekretaris.validasi-jurnal') }}"
               class="nav-link {{ request()->routeIs('sekretaris.validasi-jurnal*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M9 11l3 3L21 5"/>
                        <path d="M21 12v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h11"/>
                    </svg>
                </span>
                <span>Validasi Jurnal</span>
            </a>

            <a href="{{ route('sekretaris.isi-jurnal') }}"
               class="nav-link {{ request()->routeIs('sekretaris.isi-jurnal*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M4 19.5V5a2 2 0 0 1 2-2h10.5L20 6.5V19.5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2Z"/>
                        <path d="M16 3v4h4"/>
                        <path d="M8 12h8M8 16h6"/>
                    </svg>
                </span>
                <span>Isi Jurnal Guru</span>
            </a>

            <a href="{{ route('sekretaris.profil') }}"
               class="nav-link {{ request()->routeIs('sekretaris.profil') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <circle cx="12" cy="8" r="3.5"/>
                        <path d="M5 21a7 7 0 0 1 14 0"/>
                    </svg>
                </span>
                <span>Profil</span>
            </a>
        </nav>
    </aside>

    <div class="sidebar-overlay" id="sekretarisOverlay"></div>

    <main class="main">

        <header class="desktop-header">
            <div class="header-left">
                <h2>@yield('page-title', 'Dashboard Sekretaris')</h2>
                <p>@yield('page-subtitle', 'Kelola validasi dan jurnal pembelajaran')</p>
            </div>

            <div class="header-right">
                <div class="header-status">
                    <div class="header-status-block">
                        <small>Hari Ini</small>
                        <strong id="liveDate">--</strong>
                    </div>
                    <div class="header-status-block">
                        <small>Pukul</small>
                        <strong id="liveTime">--:--:--</strong>
                    </div>
                </div>

                <div class="header-year">
                    Tahun Ajaran:
                    <strong>@yield('tahun_ajaran', 'Ganjil 2026/2027')</strong>
                </div>

                <div class="header-secretary">
                    <div class="header-secretary-avatar">
                        {{ auth()->user()?->initials() ?: 'SK' }}
                    </div>

                    <div>
                        <p class="header-secretary-name">
                            {{ auth()->user()?->nama_user ?? 'Sekretaris' }}
                        </p>
                        <p class="header-secretary-role">Sekretaris Kelas</p>
                    </div>
                </div>
            </div>
        </header>

        <header class="mobile-header">
            <div class="mobile-left">
                <button type="button" class="mobile-menu" id="sekretarisMenu" aria-label="Buka menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="mobile-brand">Jurnify</div>
            </div>

            <div class="mobile-year">
                @yield('tahun_ajaran', 'Ganjil 2026/2027')
            </div>
        </header>

        <div class="content">

            @if(session('success'))
                <div class="alert alert-success">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <span>!</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')

        </div>
    </main>

    @yield('scripts')

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

        updateDashboardClock();
        setInterval(updateDashboardClock, 1000);

        (() => {
            const sidebar = document.getElementById('sekretarisSidebar');
            const overlay = document.getElementById('sekretarisOverlay');
            const menu = document.getElementById('sekretarisMenu');

            if (!sidebar || !overlay || !menu) return;

            const openSidebar = () => {
                sidebar.classList.add('open');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            };

            const closeSidebar = () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            };

            menu.addEventListener('click', openSidebar);
            overlay.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 900) {
                    closeSidebar();
                }
            });

            sidebar.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 900) {
                        closeSidebar();
                    }
                });
            });
        })();
    </script>

</body>
</html>