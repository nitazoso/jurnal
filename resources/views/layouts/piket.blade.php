<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <title>@yield('title', 'Staff Piket')</title>


    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: #fbfbfb;
            color: #1f2937;
            min-height: 100vh;
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
            position: relative;
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

        .logout-wrap button i {
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

        .logout-wrap button:hover i {
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
            height: 72px;
            background: #fff;
            border-bottom: 1px solid #eeeeee;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
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
            /* Sembunyikan sidebar ke kiri luar layar */
            .sidebar {
                transform: translateX(-100%);
            }

            /* Tampilkan sidebar saat status open */
            .sidebar.active {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
            }

            /* Tampilkan Tombol Garis Tiga */
            .menu-toggle {
                display: flex;
            }
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
                <a href="{{ route('piket.jurnal.index') }}" class="nav-item {{ request()->routeIs('piket.jurnal.index') ? 'active' : '' }}"> 
                    <span class="material-symbols-outlined">menu_book</span> 
                    <span>Riwayat Jurnal</span>
                </a>
                <a href="{{ route('piket.jadwal.index') }}" class="nav-item {{ request()->routeIs('piket.jadwal.*') ? 'active' : '' }}"> 
                    <span class="material-symbols-outlined">calendar_today</span> 
                    <span>Jadwal Piket</span>
                </a>
                <a href="{{ route('piket.dispen.index') }}" class="nav-item {{ request()->routeIs('piket.dispen.*') ? 'active' : '' }}"> 
                    <span class="material-symbols-outlined">report</span> 
                    <span>Dispen</span>
                </a>
                <a href="{{ route('piket.dispen.history') }}" class="nav-item {{ request()->routeIs('piket.dispen.history') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">history</span>
                    <span>Riwayat Dispen</span>
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
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
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
            </header>

            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Script Toggler Sidebar Mobile -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('show');
        }
    </script>
</body>
</html>