<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Guru - Jurnify')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f7f8fc;
            color: #20243a;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            height: 100vh;
            background: #303878;
            color: white;
            padding: 24px 16px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 35px;
        }

        .logo-icon {
            width: 34px;
            height: 34px;
            background: white;
            color: #303878;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .logo-text {
            font-size: 15px;
            font-weight: 700;
        }

        .logo-subtitle {
            font-size: 9px;
            opacity: 0.7;
            margin-top: 2px;
        }

        .menu-title {
            font-size: 10px;
            opacity: 0.55;
            margin: 0 0 10px 8px;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            margin-bottom: 6px;
        }

        .menu a,
        .logout-button {
            width: 100%;
            display: block;
            padding: 11px 12px;
            border-radius: 7px;
            color: #e8eaff;
            text-decoration: none;
            font-size: 12px;
            border: none;
            background: transparent;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }

        .menu a:hover,
        .menu a.active {
            background: #202650;
            color: white;
        }

        .logout-button:hover {
            background: #202650;
        }

        .main {
            margin-left: 220px;
            min-height: 100vh;
        }

        .topbar {
            height: 60px;
            background: white;
            border-bottom: 1px solid #e7e8ef;
            display: flex;
            align-items: center;
            padding: 0 28px;
        }

        .tahun {
            font-size: 11px;
            color: #666b7d;
        }

        .tahun-badge {
            display: inline-block;
            margin-left: 8px;
            padding: 5px 9px;
            border-radius: 6px;
            background: #edf2ff;
            color: #4561c7;
            font-size: 10px;
            font-weight: 600;
        }

        .content {
            padding: 28px;
        }

        .welcome {
            background: linear-gradient(100deg, #4d65c9, #303878);
            border-radius: 8px;
            padding: 22px 24px;
            color: white;
            margin-bottom: 18px;
        }

        .welcome-title {
            font-size: 13px;
            font-weight: 600;
        }

        .welcome-text {
            font-size: 10px;
            opacity: 0.8;
            margin-top: 5px;
        }

        .card {
            background: white;
            border: 1px solid #e5e6ec;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 18px;
        }

        .card-title {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .card-subtitle {
            font-size: 9px;
            color: #85899a;
        }

        .total-number {
            font-size: 28px;
            font-weight: 700;
            margin-top: 12px;
            color: #303878;
        }

        .total-text {
            font-size: 9px;
            color: #888d9d;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .filter {
            border: 1px solid #dfe1e8;
            border-radius: 6px;
            padding: 7px 10px;
            background: white;
            font-family: inherit;
            font-size: 10px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 9px;
            color: #777c8d;
            font-weight: 600;
            padding: 10px 8px;
            border-bottom: 1px solid #e6e7ed;
        }

        td {
            font-size: 10px;
            padding: 12px 8px;
            border-bottom: 1px solid #f0f0f3;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 600;
        }

        .status-menunggu {
            background: #fff2c7;
            color: #9a7600;
        }

        .status-valid {
            background: #d9f4df;
            color: #24713a;
        }

        .status-default {
            background: #eeeeee;
            color: #555;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 180px;
            }

            .main {
                margin-left: 180px;
            }

            .content {
                padding: 18px;
            }
        }
    </style>

    @yield('head')
</head>

<body>

    <aside class="sidebar">

        <div class="logo">
            <div class="logo-icon">J</div>

            <div>
                <div class="logo-text">JurnalKita</div>
                <div class="logo-subtitle">Kementerian Pendidikan</div>
            </div>
        </div>

        <p class="menu-title">MENU</p>

        <ul class="menu">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('guru.dashboard') }}"
                   class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            </li>

            {{-- Isi Jurnal --}}
            <li>
                <a href="{{ route('guru.jurnal.create') }}"
                   class="{{ request()->routeIs('guru.jurnal.create') || request()->routeIs('guru.jurnal.form') ? 'active' : '' }}">
                    Isi Jurnal
                </a>
            </li>

            {{-- Daftar Jurnal --}}
            <li>
                <a href="{{ route('guru.jurnal.index') }}"
                   class="{{ request()->routeIs('guru.jurnal.index') || request()->routeIs('guru.jurnal.show') ? 'active' : '' }}">
                    Daftar Jurnal
                </a>
            </li>

            {{-- Profil --}}
            <li>
                <a href="{{ route('guru.profil') }}"
                   class="{{ request()->routeIs('guru.profil') ? 'active' : '' }}">
                    Profil
                </a>
            </li>

            {{-- Logout --}}
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>
            </li>

        </ul>

    </aside>

    <div class="main">

        <div class="topbar">
            <div class="tahun">
                Tahun Ajaran:

                <span class="tahun-badge">
                    @yield('tahun_ajaran', '2026/2027 Ganjil')
                </span>
            </div>
        </div>

        <main class="content">
            @yield('content')
        </main>

    </div>

</body>
</html>