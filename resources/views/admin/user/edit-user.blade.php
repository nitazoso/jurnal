<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Jurnify</title>
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
            justify-content: space-between;
            padding: 0 24px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #f1f2f5;
            color: #17265d;
            text-decoration: none;
            transition: background 0.2s;
        }

        .back-link:hover {
            background: #e3e6ed;
        }

        .topbar h2 {
            color: #17265d;
            font-size: 19px;
            font-weight: 700;
        }

        .content {
            padding: 32px 36px;
            max-width: 1080px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #70737d;
            margin-bottom: 24px;
        }

        .breadcrumb a {
            color: #4169ff;
            text-decoration: none;
            font-weight: 600;
        }

        .breadcrumb .material-symbols-outlined {
            font-size: 16px;
        }

        .edit-card {
            background: #fff;
            border-radius: 9px;
            box-shadow: 0 1px 4px rgba(0,0,0,.025);
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }

        .edit-header {
            padding: 24px 28px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .edit-header-info h3 {
            color: #1d2c67;
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .edit-header-info p {
            color: #4b4d56;
            font-size: 14px;
        }

        .user-id-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f2f5;
            color: #4d5059;
            padding: 6px 14px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 13px;
            font-weight: 600;
        }

        .edit-body {
            padding: 28px;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 24px;
            margin-bottom: 28px;
            border-bottom: 1px solid #f4f4f4;
        }

        .avatar-placeholder {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #dce4ff;
            color: #263b78;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
        }

        .avatar-info h4 {
            color: #17265d;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .avatar-info p {
            color: #70737d;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .avatar-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-upload {
            height: 32px;
            padding: 0 14px;
            background: #fff;
            border: 1px solid #cfd2dc;
            border-radius: 6px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            color: #30323a;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-upload .material-symbols-outlined {
            font-size: 16px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #41434c;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-label .required {
            color: #e00000;
        }

        .form-control {
            height: 44px;
            padding: 0 14px;
            background: #fbfbfb;
            border: 1px solid #cfd2dc;
            border-radius: 8px;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            color: #1f2937;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control:focus {
            background: #fff;
            border-color: #4169ff;
            box-shadow: 0 0 0 3px rgba(65, 105, 255, 0.12);
        }

        select.form-control {
            cursor: pointer;
        }

        textarea.form-control {
            height: 90px;
            padding: 12px 14px;
            resize: vertical;
        }

        .form-hint {
            font-size: 12px;
            color: #858891;
        }

        .status-options {
            display: flex;
            gap: 16px;
            align-items: center;
            height: 44px;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #30323a;
            cursor: pointer;
        }

        .radio-label input[type="radio"] {
            accent-color: #182864;
            width: 17px;
            height: 17px;
            cursor: pointer;
        }

        .edit-footer {
            padding: 20px 28px;
            background: #f8f9fc;
            border-top: 1px solid #eeeeee;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-cancel {
            height: 42px;
            padding: 0 20px;
            border: 1px solid #cfd2dc;
            border-radius: 8px;
            background: #fff;
            color: #484a53;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .2s;
        }

        .btn-cancel:hover {
            background: #f1f2f5;
        }

        .btn-save {
            height: 42px;
            padding: 0 24px;
            border: none;
            border-radius: 8px;
            background: #2d336b;
            color: #fff;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .2s;
        }

        .btn-save:hover {
            background: #1e2450;
        }

        .btn-save .material-symbols-outlined {
            font-size: 18px;
        }

        @media (max-width: 1100px) {
            .sidebar { width: 230px; }
            .main { margin-left: 230px; }
        }

        @media (max-width: 800px) {
            .sidebar { width: 200px; padding: 25px 15px; }
            .main { margin-left: 200px; }
            .brand-text p { display: none; }
            .content { padding: 20px 16px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
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
            .topbar { padding: 0 16px; }
            .edit-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .edit-footer { flex-direction: column; width: 100%; }
            .btn-cancel, .btn-save { width: 100%; justify-content: center; }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <svg width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.6676 5.66667V22M12.6676 5.66667C12.6676 4.42899 12.1759 3.242 11.3007 2.36683C10.4254 1.49167 9.23834 1 8.00056 1H2.16676C1.85732 1 1.56055 1.12292 1.34174 1.34171C1.12293 1.5605 1 1.85725 1 2.16667V17.3333C1 17.6428 1.12293 17.9395 1.34174 18.1583C1.56055 18.3771 1.85732 18.5 2.16676 18.5H9.16732C10.0957 18.5 10.986 18.8687 11.6424 19.5251C12.2988 20.1815 12.6676 21.0717 12.6676 22M12.6676 5.66667C12.6676 4.42899 13.1593 3.242 14.0345 2.36683C14.9098 1.49167 16.0969 1 17.3346 1H23.1684C23.4779 1 23.7747 1.12292 23.9935 1.34171C24.2123 1.5605 24.3352 1.85725 24.3352 2.16667V17.3333C24.3352 17.6428 24.2123 17.9395 23.9935 18.1583C23.7747 18.3771 23.4779 18.5 23.1684 18.5H16.1679C15.2395 18.5 14.3492 18.8687 13.6928 19.5251C13.0364 20.1815 12.6676 21.0717 12.6676 22" stroke="#2D336B" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="brand-text">
                <h1>Jurnify</h1>
                <p>Kementerian Pendidikan</p>
            </div>
        </div>

        <nav class="nav">
            <a href="dashboard.html" class="nav-item">
                <span class="material-symbols-outlined">home</span>
                <span>Dashboard</span>
            </a>

            <a href="daftar-jurnal.html" class="nav-item">
                <span class="material-symbols-outlined">menu_book</span>
                <span>Daftar Jurnal</span>
            </a>

            <a href="dashboard_user.html" class="nav-item active">
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

    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <a href="dashboard_user.html" class="back-link" title="Kembali ke Manajemen User">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h2>Edit Data User</h2>
            </div>
        </header>

        <div class="content">
            <div class="breadcrumb">
                <a href="dashboard_user.html">Manajemen User</a>
                <span class="material-symbols-outlined">chevron_right</span>
                <span>Edit User</span>
            </div>

            <section class="edit-card">
                <div class="edit-header">
                    <div class="edit-header-info">
                        <h3 class="activity-title">Perbarui Informasi Pengguna</h3>
                        <p class="activity-description">Ubah informasi akun pengguna, penugasan role, dan status keaktifan.</p>
                    </div>
                </div>

                <div class="edit-body">

                    <form onsubmit="event.preventDefault();">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Nama Lengkap & Gelar <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" value="Siti Aminah, S.Pd" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    NIP <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" value="198504122010012005" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Nomor Telepon / WhatsApp
                                </label>
                                <input type="tel" class="form-control" value="081234567890">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Role Pengguna <span class="required">*</span>
                                </label>
                                <select class="form-control">
                                    <option value="Guru" selected>Guru</option>
                                    <option value="Staff">Staff Piket</option>
                                    <option value="Sekretaris">Sekretaris</option>
                                    <option value="Admin">Administrator</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Mata Pelajaran yang Diampu
                                </label>
                                <input type="text" class="form-control" value="Bahasa Indonesia, Literasi">
                                <span class="form-hint">Kosongkan jika bukan pengampu mata pelajaran</span>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Status Akun</label>
                                <div class="status-options">
                                    <label class="radio-label">
                                        <input type="radio" name="user_status" value="active" checked>
                                        <span>Aktif</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="user_status" value="inactive">
                                        <span>Non-Aktif</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="edit-footer">
                    <a href="dashboard_user.html" class="btn-cancel">Batal</a>
                    <button type="button" class="btn-save">
                        Simpan Perubahan
                    </button>
                </div>
            </section>
        </div>
    </main>
</body>
</html>