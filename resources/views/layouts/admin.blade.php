```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin - Jurnify')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0" rel="stylesheet">

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
        }

        .brand-text h1 {
            color: #fff;
            font-size: 18px;
            font-weight: 700;
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
        }

        .nav-item:hover {
            background: rgba(255,255,255,.08);
        }

        .nav-item.active {
            background: #1e2945;
        }

        .nav-item .material-symbols-outlined {
            font-size: 22px;
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
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {
            .sidebar {
                width: 230px;
            }

            .main {
                margin-left: 230px;
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
        }
    </style>
</head>

<body>

    {{-- =========================
         SIDEBAR ADMIN
    ========================= --}}

    <aside class="sidebar">

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

        <nav class="nav">

            <a href="#" class="nav-item active">
                <span class="material-symbols-outlined">home</span>
                <span>Dashboard</span>
            </a>

            <a href="#" class="nav-item">
                <span class="material-symbols-outlined">menu_book</span>
                <span>Daftar Jurnal</span>
            </a>

            <a href="#" class="nav-item">
                <span class="material-symbols-outlined">person_add</span>
                <span>User</span>
            </a>

            <a href="#" class="nav-item">
                <span class="material-symbols-outlined">groups</span>
                <span>Kelas</span>
            </a>

            <a href="#" class="nav-item">
                <span class="material-symbols-outlined">menu_book</span>
                <span>Mapel</span>
            </a>

            <a href="#" class="nav-item">
                <span class="material-symbols-outlined">calendar_month</span>
                <span>Jadwal</span>
            </a>

            <a href="#" class="nav-item">
                <span class="material-symbols-outlined">person</span>
                <span>Profil</span>
            </a>

        </nav>

    </aside>


    {{-- =========================
         CONTENT UTAMA
    ========================= --}}

    <main class="main">

        <header class="topbar">
            <h2>@yield('page-title', 'Dashboard')</h2>
        </header>

        <div class="content">

            @yield('content')

        </div>

    </main>

</body>
</html>
```
