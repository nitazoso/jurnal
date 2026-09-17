<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Guru - Jurnify')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: #FBFBFB;
            color: #2D336B;
            -webkit-font-smoothing: antialiased;
        }

        /* =========================
           DESKTOP SIDEBAR
        ========================= */

        .desktop-sidebar {
            width: 260px;
            min-height: 100vh;
            background: #2D336B;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar-top {
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 24px 24px 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2D336B;
            flex-shrink: 0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }

        .sidebar-brand-name {
            margin: 0;
            color: white;
            font-size: 20px;
            line-height: 1;
            font-weight: 700;
            letter-spacing: -0.4px;
        }

        .sidebar-brand-subtitle {
            margin: 5px 0 0;
            color: #C7D2FE;
            font-size: 12px;
            font-weight: 400;
            opacity: 0.85;
        }

        .sidebar-nav {
            padding: 0 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-nav a {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 48px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #C7D2FE;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition:
                background-color 0.2s ease,
                color 0.2s ease,
                transform 0.2s ease;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transform: translateX(2px);
        }

        .sidebar-nav a.active {
            background: #1E234C;
            color: white;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .sidebar-nav a.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 6px;
            background: #A9B5DF;
            border-radius: 0 6px 6px 0;
        }

        .sidebar-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            color: #C7D2FE;
        }

        .sidebar-nav a.active .sidebar-icon {
            color: #A9B5DF;
        }

        .sidebar-nav a:hover .sidebar-icon {
            color: white;
        }

        /* =========================
           MAIN
        ========================= */

        .desktop-main {
            margin-left: 260px;
            min-height: 100vh;
        }

        .desktop-header {
            height: 80px;
            background: white;
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .header-left h2 {
            margin: 0;
            color: #2D336B;
            font-size: 20px;
            font-weight: 700;
        }

        .header-left p {
            margin: 3px 0 0;
            color: #6B7280;
            font-size: 12px;
            font-weight: 500;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-date {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: #F9FAFB;
            border: 1px solid rgba(229, 231, 235, 0.7);
            border-radius: 8px;
            color: #6B7280;
            font-size: 12px;
            font-weight: 500;
        }

        .header-date svg {
            width: 16px;
            height: 16px;
            color: #7886C7;
        }

        .header-year {
            padding: 8px 16px;
            background: #EFF2FA;
            border: 1px solid #D5DDF2;
            border-radius: 999px;
            color: #2D336B;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .header-notification {
            position: relative;
            width: 36px;
            height: 36px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 999px;
            background: transparent;
            color: #9CA3AF;
            cursor: pointer;
            transition:
                color 0.2s ease,
                background-color 0.2s ease;
        }

        .header-notification:hover {
            color: #2D336B;
            background: #F3F4F6;
        }

        .header-notification svg {
            width: 20px;
            height: 20px;
        }

        .notification-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #EF4444;
            border-radius: 999px;
        }

        .header-teacher {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 12px;
            border-left: 1px solid #E5E7EB;
        }

        .header-teacher-avatar {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            background: #0891B2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 700;
        }

        .header-teacher-name {
            margin: 0;
            color: #2D336B;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
        }

        .header-teacher-role {
            margin: 3px 0 0;
            color: #6B7280;
            font-size: 11px;
        }

        .content {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 32px 36px 40px;
        }

        /* =========================
           MOBILE TOPBAR
        ========================= */

        .mobile-topbar {
            display: none;
        }

        .mobile-menu {
            width: 36px;
            height: 36px;
            padding: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            background: transparent;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .mobile-menu:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .mobile-menu span {
            display: block;
            width: 22px;
            height: 2.5px;
            background: white;
            border-radius: 3px;
            transition:
                transform 0.25s ease,
                opacity 0.2s ease;
        }

        /* =========================
           MOBILE SIDEBAR OVERLAY
        ========================= */

        .mobile-overlay {
            display: none;
        }

        /* =========================
           TABLET
        ========================= */

        @media (min-width: 768px) and (max-width: 1100px) {
            .desktop-header {
                padding: 0 24px;
            }

            .header-date {
                display: none;
            }

            .content {
                padding: 24px;
            }
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 767px) {
            .desktop-sidebar {
                display: flex;
                width: 280px;
                max-width: 82vw;
                min-height: 100vh;
                height: 100vh;
                transform: translateX(-100%);
                box-shadow: 8px 0 30px rgba(0, 0, 0, 0.16);
                z-index: 200;
            }

            .desktop-sidebar.mobile-open {
                transform: translateX(0);
            }

            .desktop-main {
                margin-left: 0;
            }

            .desktop-header {
                display: none;
            }

            .mobile-topbar {
                height: 58px;
                background: #2D336B;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 16px;
                position: sticky;
                top: 0;
                z-index: 150;
            }

            .mobile-brand {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .mobile-brand-name {
                color: white;
                font-size: 19px;
                font-weight: 700;
                letter-spacing: -0.3px;
            }

            .mobile-year {
                padding: 7px 10px;
                background: white;
                color: #2D336B;
                border-radius: 6px;
                font-size: 10px;
                font-weight: 700;
                white-space: nowrap;
            }

            .mobile-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.42);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                z-index: 190;
                transition:
                    opacity 0.3s ease,
                    visibility 0.3s ease;
            }

            .mobile-overlay.active {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }

            .content {
                width: 100%;
                max-width: none;
                padding: 24px 24px 32px;
            }

            /* Hamburger berubah menjadi X */
            .mobile-menu.active span:nth-child(1) {
                transform: translateY(6.5px) rotate(45deg);
            }

            .mobile-menu.active span:nth-child(2) {
                opacity: 0;
            }

            .mobile-menu.active span:nth-child(3) {
                transform: translateY(-6.5px) rotate(-45deg);
            }

            .sidebar-brand {
                padding-top: 24px;
            }
        }

        /* =========================
           SMALL MOBILE
        ========================= */

        @media (max-width: 420px) {
            .content {
                padding-left: 20px;
                padding-right: 20px;
            }

            .mobile-topbar {
                padding-left: 12px;
                padding-right: 12px;
            }

            .mobile-year {
                padding: 6px 8px;
                font-size: 9px;
            }

            .mobile-brand {
                gap: 8px;
            }

            .mobile-brand-name {
                font-size: 18px;
            }
        }

        /* =========================
           REDUCED MOTION
        ========================= */

        @media (prefers-reduced-motion: reduce) {
            .desktop-sidebar,
            .mobile-overlay,
            .mobile-menu span {
                transition: none;
            }
        }
    </style>

    @yield('head')
</head>

<body>

    <div class="min-h-screen">

        {{-- DESKTOP / MOBILE SIDEBAR --}}
        <aside class="desktop-sidebar" id="guruSidebar">

            <div class="sidebar-top">

                {{-- BRAND --}}
                <div class="sidebar-brand">

                    <div class="sidebar-logo">
                        <svg
                            width="26"
                            height="22"
                            viewBox="0 0 26 22"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M12.6676 5.66667V22M12.6676 5.66667C12.6676 4.42899 12.1759 3.242 11.3007 2.36683C10.4254 1.49167 9.23834 1 8.00056 1H2.16676C1.85732 1 1.56055 1.12292 1.34174 1.34171C1.12293 1.5605 1 1.85725 1 2.16667V17.3333C1 17.6428 1.12293 17.9395 1.34174 18.1583C1.56055 18.3771 1.85732 18.5 2.16676 18.5H9.16732C10.0957 18.5 10.986 18.8687 11.6424 19.5251C12.2988 20.1815 12.6676 21.0717 12.6676 22M12.6676 5.66667C12.6676 4.42899 13.1593 3.242 14.0345 2.36683C14.9098 1.49167 16.0969 1 17.3346 1H23.1684C23.4779 1 23.7747 1.12292 23.9935 1.34171C24.2123 1.5605 24.3352 1.85725 24.3352 2.16667V17.3333C24.3352 17.6428 24.2123 17.9395 23.9935 18.1583C23.7747 18.3771 23.4779 18.5 23.1684 18.5H16.1679C15.2395 18.5 14.3492 18.8687 13.6928 19.5251C13.0364 20.1815 12.6676 21.0717 12.6676 22"
                                stroke="#2D336B"
                                stroke-width="2"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <div>
                        <h1 class="sidebar-brand-name">
                            Jurnify
                        </h1>

                        <p class="sidebar-brand-subtitle">
                            Kementerian Pendidikan
                        </p>
                    </div>

                </div>

                {{-- NAVIGATION --}}
                <nav class="sidebar-nav">

                    {{-- DASHBOARD --}}
                    <a
                        href="{{ route('guru.dashboard') }}"
                        class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
                    >
                        <svg
                            class="sidebar-icon"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        <span>Dashboard</span>
                    </a>

                    {{-- ISI JURNAL --}}
                    <a
                        href="{{ route('guru.jurnal.create') }}"
                        class="{{ request()->routeIs('guru.jurnal.create') || request()->routeIs('guru.jurnal.form') ? 'active' : '' }}"
                    >
                        <svg
                            class="sidebar-icon"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        <span>Isi Jurnal</span>
                    </a>

                    {{-- DAFTAR JURNAL --}}
                    <a
                        href="{{ route('guru.jurnal.index') }}"
                        class="{{ request()->routeIs('guru.jurnal.index') || request()->routeIs('guru.jurnal.show') ? 'active' : '' }}"
                    >
                        <svg
                            class="sidebar-icon"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        <span>Daftar Jurnal</span>
                    </a>

                    {{-- PROFIL --}}
                    <a
                        href="{{ route('guru.profil') }}"
                        class="{{ request()->routeIs('guru.profil') ? 'active' : '' }}"
                    >
                        <svg
                            class="sidebar-icon"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        <span>Profil</span>
                    </a>

                </nav>

            </div>

        </aside>

        {{-- MOBILE OVERLAY --}}
        <div
            class="mobile-overlay"
            id="mobileOverlay"
            aria-hidden="true"
        ></div>

        {{-- MAIN --}}
        <main class="desktop-main">

            {{-- MOBILE TOPBAR --}}
            <div class="mobile-topbar">

                <div class="mobile-brand">

                    <button
                        class="mobile-menu"
                        id="mobileMenuButton"
                        type="button"
                        aria-label="Buka menu"
                        aria-expanded="false"
                        aria-controls="guruSidebar"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <span class="mobile-brand-name">
                        Jurnify
                    </span>

                </div>

                <div class="mobile-year">
                    @yield('tahun_ajaran', 'Ganjil 2026/2027')
                </div>

            </div>

            {{-- DESKTOP HEADER --}}
            <header class="desktop-header">

                <div class="header-left">

                    <h2>
                        @yield('page-title', 'Dashboard Guru')
                    </h2>

                    <p>
                        @yield('page-subtitle', 'Pantau dan kelola aktivitas pembelajaran Anda')
                    </p>

                </div>

                <div class="header-right">

                    <div class="header-year">
                        Tahun Ajaran:
                        <strong>
                            @yield('tahun_ajaran', 'Ganjil 2026/2027')
                        </strong>
                    </div>

                </div>

            </header>

            {{-- CONTENT --}}
            <main class="content">
                @yield('content')
            </main>

        </main>

    </div>

    @yield('scripts')

    {{-- MOBILE SIDEBAR SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuButton = document.getElementById('mobileMenuButton');
            const sidebar = document.getElementById('guruSidebar');
            const overlay = document.getElementById('mobileOverlay');

            if (!menuButton || !sidebar || !overlay) {
                return;
            }

            function openSidebar() {
                sidebar.classList.add('mobile-open');
                menuButton.classList.add('active');
                overlay.classList.add('active');

                menuButton.setAttribute('aria-expanded', 'true');
                menuButton.setAttribute('aria-label', 'Tutup menu');
                overlay.setAttribute('aria-hidden', 'false');

                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('mobile-open');
                menuButton.classList.remove('active');
                overlay.classList.remove('active');

                menuButton.setAttribute('aria-expanded', 'false');
                menuButton.setAttribute('aria-label', 'Buka menu');
                overlay.setAttribute('aria-hidden', 'true');

                document.body.style.overflow = '';
            }

            menuButton.addEventListener('click', function () {
                if (sidebar.classList.contains('mobile-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            overlay.addEventListener('click', function () {
                closeSidebar();
            });

            sidebar.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    closeSidebar();
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768) {
                    closeSidebar();
                }
            });
        });
    </script>

</body>
</html>