<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin - Jurnify')</title>

    <!-- Google Fonts & Material Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0"
          rel="stylesheet">

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
           MAIN
        ========================= */

        .main {
            margin-left: 260px;
            min-height: 100vh;
        }

        .topbar {
            height: 72px;
            background: #fff;
            border-bottom: 1px solid #eeeeee;
            display: flex;
            align-items: center;
            padding: 0 24px;
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
           STATISTICS
        ========================= */

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 0 0 23px;
        }

        .stat-card {
            min-height: 113px;
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 9px;
            padding: 22px 23px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .025);
            position: relative;
        }

        .stat-title {
            color: #41434c;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .stat-value {
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .stat-value strong {
            color: #1d2c67;
            font-size: 31px;
            font-weight: 800;
            line-height: 1;
        }

        .stat-value span {
            color: #51525b;
            font-size: 19px;
            font-weight: 400;
        }

        .today-badge {
            position: absolute;
            top: 24px;
            right: 23px;
            background: #dce4ff;
            color: #263b78;
            border-radius: 14px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 700;
        }

        .stat-icon {
            position: absolute;
            top: 24px;
            right: 23px;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #dce4ff;
            color: #172b67;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon .material-symbols-outlined {
            font-size: 22px;
        }

        /* =========================
           ACTIVITY
        ========================= */

        .activity-card {
            background: #fff;
            border-radius: 9px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .025);
        }

        .activity-header {
            padding: 25px 24px 24px;
        }

        .activity-title {
            color: #1d2c67;
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .activity-description {
            color: #4b4d56;
            font-size: 14px;
        }

        .filters {
            display: grid;
            grid-template-columns:
                minmax(300px, 1.7fr)
                minmax(210px, 1fr)
                minmax(150px, .7fr)
                minmax(150px, .7fr);
            gap: 12px;
            padding: 0 24px 24px;
        }

        .search-box,
        .filter-select {
            height: 40px;
            background: #f1f2f5;
            border: none;
            border-radius: 4px;
            color: #24252b;
            font-family: 'Manrope', sans-serif;
            font-size: 13px;
        }

        .search-box {
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 9px;
        }

        .search-box .material-symbols-outlined {
            color: #777b86;
            font-size: 21px;
        }

        .search-box input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-family: inherit;
            font-size: 13px;
            color: #333;
        }

        .search-box input::placeholder {
            color: #858891;
        }

        .filter-select {
            width: 100%;
            padding: 0 14px;
            outline: none;
            cursor: pointer;
        }

        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead {
            background: #f1f2f5;
        }

        th {
            height: 60px;
            padding: 0 12px;
            text-align: left;
            color: #484a53;
            font-size: 13px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: .3px;
        }

        td {
            height: 74px;
            padding: 9px 12px;
            color: #17181d;
            font-size: 14px;
            vertical-align: middle;
        }

        tbody tr {
            border-bottom: 1px solid #f4f4f4;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        .number {
            color: #70737d;
        }

        .date {
            font-weight: 600;
            color: #202126;
            display: block;
            margin-bottom: 3px;
        }

        .time {
            color: #5e6068;
            font-size: 10px;
        }

        .teacher {
            color: #152963;
            font-weight: 800;
            line-height: 1.35;
        }

        .subject {
            line-height: 1.45;
        }

        .class-badge {
            display: inline-block;
            background: #dbe3ff;
            color: #25386f;
            padding: 5px 19px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .attendance {
            text-align: center;
        }

        .attendance strong {
            display: block;
            font-size: 15px;
            font-weight: 800;
        }

        .attendance small {
            display: block;
            margin-top: 2px;
            font-size: 10px;
        }

        .attendance .green {
            color: #159568;
        }

        .attendance .red {
            color: #e00000;
        }

        /* =========================
           STATUS
        ========================= */

        .status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 11px;
            border-radius: 14px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status .material-symbols-outlined {
            font-size: 13px;
        }

        .status.valid {
            background: #d5f7e8;
            color: #087451;
        }

        .status.waiting {
            background: #fff0c5;
            color: #99520a;
        }

        .status.rejected {
            background: #ffd9d5;
            color: #a9211d;
        }

        /* =========================
           ACTION
        ========================= */

        .action {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 4px;
            background: #182864;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
        }

        .action:hover {
            background: #253779;
        }

        .action .material-symbols-outlined {
            font-size: 19px;
        }

        /* =========================
           BOTTOM
        ========================= */

        .bottom {
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .entries {
            color: #3f4148;
            font-size: 13px;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .page {
            min-width: 36px;
            height: 36px;
            border: none;
            border-radius: 4px;
            background: #f0f1f4;
            color: #25272d;
            font-family: inherit;
            font-size: 13px;
            cursor: pointer;
        }

        .page.active {
            background: #182864;
            color: #fff;
            font-weight: 700;
        }

        .page .material-symbols-outlined {
            font-size: 18px;
            vertical-align: middle;
        }

        .dots {
            min-width: 24px;
            text-align: center;
            color: #555861;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {

            .sidebar {
                width: 230px;
            }

            .main {
                margin-left: 230px;
            }

            .stats {
                margin-left: 0;
                margin-right: 0;
            }

            .filters {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 800px) {

            .sidebar {
                width: 200px;
                padding: 25px 15px;
            }

            .main {
                margin-left: 200px;
            }

            .brand-text p {
                display: none;
            }

            .content {
                padding: 20px 15px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .filters {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
            }

            .nav {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .nav-item {
                flex: 1 1 130px;
            }

            .topbar {
                padding: 0 20px;
            }

            .content {
                padding: 20px 10px;
            }

            .bottom {
                flex-direction: column;
                gap: 15px;
                padding: 18px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- =========================
         SIDEBAR ADMIN
    ========================= --}}

    <aside class="sidebar">

        {{-- BRAND --}}
        <div class="brand">

            <div class="brand-icon">
                <span class="material-symbols-outlined">
                    menu_book
                </span>
            </div>

            <div class="brand-text">
                <h1>Jurnify</h1>
                <p>Kementerian Pendidikan</p>
            </div>

        </div>


        {{-- NAVIGATION --}}
        <nav class="nav">

            {{-- DASHBOARD --}}
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                <span class="material-symbols-outlined">
                    home
                </span>

                <span>Dashboard</span>

            </a>


            {{-- DAFTAR JURNAL --}}
            <a href="{{ route('admin.jurnal.index') }}"
               class="nav-item {{ request()->routeIs('admin.jurnal.*') ? 'active' : '' }}">

                <span class="material-symbols-outlined">
                    menu_book
                </span>

                <span>Daftar Jurnal</span>

            </a>


            {{-- USER --}}
            <a href="{{ route('admin.user.index') }}"
               class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">

                <span class="material-symbols-outlined">
                    person_add
                </span>

                <span>Manajemen Akun</span>

            </a>


            {{-- GURU --}}
            <a href="{{ route('admin.guru.index') }}"
               class="nav-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">

                <span class="material-symbols-outlined">
                    person
                </span>

                <span>Guru</span>

            </a>


            {{-- KELAS --}}
            <a href="{{ route('admin.kelas.index') }}"
               class="nav-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">

                <span class="material-symbols-outlined">
                    groups
                </span>

                <span>Kelas</span>

            </a>


            {{-- MAPEL --}}
            <a href="{{ route('admin.mapel.index') }}"
               class="nav-item {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">

                <span class="material-symbols-outlined">
                    auto_stories
                </span>

                <span>Mapel</span>

            </a>


            {{-- JAM PELAJARAN --}}
            <a href="{{ route('admin.jam.index') }}"
               class="nav-item {{ request()->routeIs('admin.jam.*') ? 'active' : '' }}">

                <span class="material-symbols-outlined">
                    schedule
                </span>

                <span>Jam Pelajaran</span>

            </a>


            {{-- JADWAL --}}
            <a href="{{ route('admin.jadwal.index') }}"
               class="nav-item {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">

                <span class="material-symbols-outlined">
                    calendar_month
                </span>

                <span>Jadwal</span>

            </a>


            {{-- PROFIL --}}
            <a href="{{ route('admin.profil') }}"
               class="nav-item {{ request()->routeIs('admin.profil') ? 'active' : '' }}">

                <span class="material-symbols-outlined">
                    person
                </span>

                <span>Profil</span>

            </a>

        </nav>

    </aside>


    {{-- =========================
         MAIN CONTENT
    ========================= --}}

    <main class="main">

        {{-- TOPBAR --}}
        <header class="topbar">

            <h2>
                @yield('page-title', 'Dashboard')
            </h2>

        </header>


        {{-- CONTENT --}}
        <div class="content">

            @yield('content')

        </div>

    </main>


    @stack('scripts')

</body>
</html>