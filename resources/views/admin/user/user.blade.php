<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Jurnify</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Manrope', sans-serif;
            background: #fbfbfb;
            color: #1f2937;
            min-height: 100vh;
        }

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
            color: #30366f;
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
            background: rgba(255,255,255,.08);
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
            padding: 0 16px;
        }

        .topbar h2 {
            color: #17265d;
            font-size: 19px;
            font-weight: 700;
        }

        .content {
            padding: 32px 24px 20px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 0 42px 23px;
        }

        .stat-card {
            min-height: 113px;
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 9px;
            padding: 22px 23px;
            box-shadow: 0 2px 5px rgba(0,0,0,.025);
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

        .activity-card {
            background: #fff;
            border-radius: 9px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.025);
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
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 24px 16px;
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

        .role-filters {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .role-filter,
        .add-user-btn {
            height: 40px;
            border: 1px solid #cfd2dc;
            border-radius: 10px;
            background: #fff;
            color: #30323a;
            font-family: 'Manrope', sans-serif;
            font-size: 13px;
            font-weight: 700;
            padding: 0 20px;
            cursor: pointer;
        }

        .role-filter.active {
            background: #182864;
            border-color: #182864;
            color: #fff;
        }

        .add-user-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            background: #2d336b;
            border-color: #2d336b;
            color: #fff;
            padding: 0 17px;
        }

        .add-user-btn .material-symbols-outlined {
            font-size: 18px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
            height: 94px;
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

        th:nth-child(1), td:nth-child(1) { width: 33%; padding-left: 36px; }
        th:nth-child(2), td:nth-child(2) { width: 27%; }
        th:nth-child(3), td:nth-child(3) { width: 25%; }
        th:nth-child(4), td:nth-child(4) { width: 15%; padding-right: 36px; }

        .user-cell strong {
            display: block;
            color: #202126;
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .user-cell span {
            display: block;
            color: #555861;
            font-size: 13px;
        }

        .user-id {
            color: #4d5059;
            font-family: monospace;
            font-size: 13px;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #a9b5df;
            color: #344477;
            border-radius: 6px;
            padding: 5px 12px;
            font-size: 11px;
            font-weight: 700;
        }

        .role-badge .material-symbols-outlined {
            font-size: 14px;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .icon-action {
            width: 22px;
            height: 30px;
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .icon-action .material-symbols-outlined {
            font-size: 19px;
        }

        .icon-action.edit {
            color: #182864;
        }

        .icon-action.delete {
            color: #e00000;
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
        }

        .action .material-symbols-outlined {
            font-size: 19px;
        }

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

        @media (max-width: 1100px) {
            .sidebar { width: 230px; }
            .main { margin-left: 230px; }
            .stats { margin-left: 0; margin-right: 0; }
            .filters { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 800px) {
            .sidebar { width: 200px; padding: 25px 15px; }
            .main { margin-left: 200px; }
            .brand-text p { display: none; }
            .content { padding: 20px 15px; }
            .stats { grid-template-columns: 1fr; }
            .filters { flex-wrap: wrap; }
            .search-box { flex: 1 1 100%; }
            .role-filters { margin-left: 0; }
            .add-user-btn { margin-left: auto; }
        }

        @media (max-width: 600px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main { margin-left: 0; }
            .nav { flex-direction: row; flex-wrap: wrap; }
            .nav-item { flex: 1 1 130px; }
            .topbar { padding: 0 20px; }
            .content { padding: 20px 10px; }
            .bottom { flex-direction: column; gap: 15px; padding: 18px; }
        }
    </style>
</head>

<body>
    <x-admin-sidebar />

    <main class="main">
        <header class="topbar">
            <h2>Manajemen User</h2>
        </header>

        <div class="content">
            <section class="stats">
                <div class="stat-card">
                    <div class="stat-title">TOTAL USERS</div>
                    <div class="stat-value"><strong>142</strong></div>
                    <div class="stat-icon"><span class="material-symbols-outlined">groups</span></div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">GURU AKTIF</div>
                    <div class="stat-value"><strong>128</strong></div>
                    <div class="stat-icon"><span class="material-symbols-outlined">school</span></div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">STAFF PIKET</div>
                    <div class="stat-value"><strong>14</strong></div>
                    <div class="stat-icon"><span class="material-symbols-outlined">support_agent</span></div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">TOTAL SEKRE</div>
                    <div class="stat-value"><strong>8</strong></div>
                    <div class="stat-icon"><span class="material-symbols-outlined">edit_note</span></div>
                </div>
            </section>

            <section class="activity-card">
                <div class="activity-header">
                    <h3 class="activity-title">Daftar User</h3>
                    <p class="activity-description">Kelola dan pantau seluruh pengguna yang terdaftar dalam sistem Jurnify.</p>
                </div>

                <div class="filters">
                    <div class="search-box">
                        <span class="material-symbols-outlined">search</span>
                        <input type="text" placeholder="Cari nama, NIP, atau NISN...">
                    </div>
                    <div class="role-filters">
                        <button class="role-filter active">Semua</button>
                        <button class="role-filter">Guru</button>
                        <button class="role-filter">Staff</button>
                    </div>
                    <a href="{{ route('admin.user.create') }}">
                        <button class="add-user-btn">
                            <span class="material-symbols-outlined">person_add</span>
                            Tambah User
                        </button>
                    </a>
                    
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>USER</th>
                                <th>NIP / ID</th>
                                <th>ROLE</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="user-cell">
                                    <strong>Siti Aminah, S.Pd</strong>
                                </td>
                                <td class="user-id">198504122010012005</td>
                                <td><span class="role-badge"><span class="material-symbols-outlined">school</span>Guru</span></td>
                                <td class="user-actions">
                                    <button class="icon-action edit"><span class="material-symbols-outlined"><a href="edit-user.html">edit</a></span></button>
                                    <button class="icon-action delete"><span class="material-symbols-outlined">delete</span></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="user-cell">
                                    <strong>Budi Santoso, M.Pd</strong>
                                </td>
                                <td class="user-id">197811252005011003</td>
                                <td><span class="role-badge"><span class="material-symbols-outlined">school</span>Guru</span></td>
                                <td class="user-actions">
                                    <a href="edit-user.html">
                                        <button class="icon-action edit"><span class="material-symbols-outlined">edit</span></button>
                                    </a>
                                    <button class="icon-action delete"><span class="material-symbols-outlined">delete</span></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="user-cell">
                                    <strong>Nuryanah, M.Pd</strong>
                                </td>
                                <td class="user-id">197811252005211003</td>
                                <td><span class="role-badge"><span class="material-symbols-outlined">school</span>Guru</span></td>
                                <td class="user-actions">
                                    <a href="edit-user.html">
                                        <button class="icon-action edit"><span class="material-symbols-outlined">edit</span></button>
                                    </a>
                                    <button class="icon-action delete"><span class="material-symbols-outlined">delete</span></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="user-cell">
                                    <strong>Diana Lestari, S.Kom</strong>
                                </td>
                                <td class="user-id">199208152018012001</td>
                                <td><span class="role-badge"><span class="material-symbols-outlined">school</span>Guru</span></td>
                                <td class="user-actions">
                                    <button class="icon-action edit"><span class="material-symbols-outlined"><a href="edit-user.html">edit</a></span></button>
                                    <button class="icon-action delete"><span class="material-symbols-outlined">delete</span></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bottom">
                    <div class="entries">Showing 1 to 4 of 142 entries</div>

                    <div class="pagination">
                        <button class="page">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <button class="page active">1</button>
                        <button class="page">2</button>
                        <button class="page">3</button>
                        <span class="dots">...</span>
                        <button class="page">5</button>
                        <button class="page">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>