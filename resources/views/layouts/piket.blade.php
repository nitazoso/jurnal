<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Piket')</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #1f2937;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background: #f3f4f8;
            border-right: 1px solid #dfe3ea;
            padding: 20px 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            padding: 8px 10px;
            font-weight: 700;
            color: #1f2937;
        }

        .brand-badge {
            width: 32px;
            height: 32px;
            background: #d9ddf6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #2f3b83;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav a,
        .nav button {
            display: block;
            width: 100%;
            text-decoration: none;
            background: transparent;
            border: none;
            color: #374151;
            text-align: left;
            padding: 10px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .nav a.active {
            background: #dde3ff;
            color: #1f2d6d;
        }

        .nav a:hover,
        .nav button:hover {
            background: #eceff8;
        }

        .logout-wrap {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .logout-wrap form {
            margin: 0;
        }

        .main {
            flex: 1;
            padding: 28px;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h2 {
            margin: 0;
            font-size: 28px;
            color: #111827;
        }

        .page-header p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(180px, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px 20px;
            min-height: 120px;
        }

        .stat-card.primary { background: #eef2ff; }
        .stat-card.secondary { background: #ecfeff; }
        .stat-card.accent { background: #fef3c7; }

        .label {
            font-size: 13px;
            color: #4b5563;
            margin-bottom: 12px;
        }

        .value {
            font-size: 36px;
            font-weight: 700;
            color: #111827;
            line-height: 1;
        }

        .panel {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 24px;
        }

        .panel-header {
            margin-bottom: 16px;
        }

        .panel-header h3 {
            margin: 0;
            font-size: 18px;
            color: #111827;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table th,
        .data-table td {
            text-align: left;
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table th {
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
        }

        .empty-text {
            text-align: center;
            color: #6b7280;
            padding: 18px;
        }

        .profile-box {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            max-width: 600px;
            padding: 24px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 20px;
        }

        .avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #dfe7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #213774;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .profile-role {
            display: inline-block;
            background: #eef2ff;
            color: #2c3d85;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .profile-list {
            display: grid;
            gap: 12px;
            margin-top: 20px;
        }

        .profile-item {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-item label {
            color: #6b7280;
            font-weight: 600;
        }

        .logout-btn {
            margin-top: 24px;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            cursor: pointer;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .app { display: block; }
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #dfe3ea;
            }
            .main { padding: 18px; }
            .stat-grid { grid-template-columns: 1fr; }
            .profile-item { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-badge">J</div>
                <span>Jurnify</span>
            </div>

            <nav class="nav">
                <a href="{{ route('piket.dashboard') }}" class="{{ request()->routeIs('piket.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('piket.jurnal.index') }}" class="{{ request()->routeIs('piket.jurnal.index') ? 'active' : '' }}">Jurnal</a>
                <a href="{{ route('piket.jadwal.index') }}" class="{{ request()->routeIs('piket.jadwal.*') ? 'active' : '' }}">Jadwal Piket</a>
                <a href="{{ route('piket.dispen.index') }}" class="{{ request()->routeIs('piket.dispen.*') ? 'active' : '' }}">Dispen</a>
                <a href="{{ route('piket.profil') }}" class="{{ request()->routeIs('piket.profil') ? 'active' : '' }}">Profil</a>
            </nav>

            <div class="logout-wrap">
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Anda yakin ingin logout?');">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>

        </aside>

        <main class="main">
            @yield('content')
        </main>
    </div>
</body>
</html>
