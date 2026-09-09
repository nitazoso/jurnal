<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User Baru - Jurnify</title>
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
            background: #fbfbfb;
        }

        .topbar {
            height: 72px;
            background: #fff;
            border-bottom: 1px solid #eeeeee;
            display: flex;
            align-items: center;
            padding: 0 32px;
            gap: 16px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #17265d;
            cursor: pointer;
            text-decoration: none;
            transition: .2s;
        }

        .back-btn:hover {
            background: #f3f4f6;
        }

        .topbar h2 {
            color: #17265d;
            font-size: 19px;
            font-weight: 700;
        }

        .content {
            padding: 28px 32px 40px;
            max-width: 1200px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        .breadcrumb a {
            color: #4169ff;
            text-decoration: none;
            font-weight: 600;
        }

        .breadcrumb .separator {
            font-size: 14px;
            color: #9ca3af;
        }

        .breadcrumb .current {
            color: #374151;
            font-weight: 600;
        }

        .form-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #eaedf1;
            box-shadow: 0 2px 6px rgba(0,0,0,.025);
            overflow: hidden;
        }

        .form-header {
            padding: 28px 32px 24px;
            border-bottom: 1px solid #f0f2f5;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .form-header-text h3 {
            color: #1d2c67;
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .form-header-text p {
            color: #555861;
            font-size: 14px;
        }

        .status-badge-new {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eef2ff;
            color: #2b3b75;
            border: 1px solid #d5ddfb;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .2px;
        }

        .status-badge-new .material-symbols-outlined {
            font-size: 16px;
        }

        .form-body {
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .photo-upload-section {
            display: flex;
            align-items: center;
            gap: 24px;
            padding-bottom: 28px;
            border-bottom: 1px solid #f3f4f6;
        }

        .photo-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #eef2ff;
            color: #30366f;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #cbd5e1;
            font-size: 36px;
        }

        .photo-avatar .material-symbols-outlined {
            font-size: 38px;
            color: #64748b;
        }

        .photo-info h4 {
            font-size: 15px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .photo-info p {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            transition: .2s;
        }

        .btn-upload:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-upload .material-symbols-outlined {
            font-size: 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px 28px;
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
            color: #374151;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-label .required {
            color: #dc2626;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-input,
        .form-select {
            width: 100%;
            height: 44px;
            padding: 0 14px;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            color: #1f2937;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-input::placeholder {
            color: #9ca3af;
            font-size: 13px;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #30366f;
            box-shadow: 0 0 0 3px rgba(48, 54, 111, 0.1);
        }

        .form-helper {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .radio-group {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-top: 4px;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
        }

        .radio-label input[type="radio"] {
            width: 17px;
            height: 17px;
            accent-color: #1d2c67;
            cursor: pointer;
        }

        .password-note {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: #475569;
        }

        .password-note .material-symbols-outlined {
            color: #2563eb;
            font-size: 20px;
        }

        .form-footer {
            padding: 20px 32px;
            background: #fcfcfd;
            border-top: 1px solid #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-cancel {
            height: 42px;
            padding: 0 24px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #fff;
            color: #374151;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: #f3f4f6;
        }

        .btn-submit {
            height: 42px;
            padding: 0 24px;
            border: none;
            border-radius: 8px;
            background: #252e5e;
            color: #fff;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background .2s;
        }

        .btn-submit:hover {
            background: #182864;
        }

        .btn-submit .material-symbols-outlined {
            font-size: 19px;
        }

        @media (max-width: 1100px) {
            .sidebar { width: 230px; }
            .main { margin-left: 230px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
        }

        @media (max-width: 800px) {
            .sidebar { width: 200px; padding: 25px 15px; }
            .main { margin-left: 200px; }
            .brand-text p { display: none; }
            .content { padding: 20px 16px; }
            .form-header { flex-direction: column; gap: 12px; }
        }

        @media (max-width: 600px) {
            .sidebar { position: relative; width: 100%; height: auto; }
            .main { margin-left: 0; }
            .nav { flex-direction: row; flex-wrap: wrap; }
            .nav-item { flex: 1 1 130px; }
            .topbar { padding: 0 16px; }
            .form-body { padding: 20px 16px; }
            .form-footer { padding: 16px; flex-direction: column-reverse; width: 100%; }
            .btn-cancel, .btn-submit { width: 100%; justify-content: center; }
        }
    </style>
</head>

<body>
    <!-- Sidebar Navigation -->
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

    <!-- Main Content -->
    <main class="main">
        <header class="topbar">
            <a href="dashboard_user.html" class="back-btn" title="Kembali ke Manajemen User">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h2>Tambah User Baru</h2>
        </header>

        <div class="content">
            <nav class="breadcrumb">
                <a href="dashboard_user.html">Manajemen User</a>
                <span class="separator">/</span>
                <span class="current">Tambah User Baru</span>
            </nav>

            <section class="form-card">
                <div class="form-header">
                    <div class="form-header-text">
                        <h3>Informasi Pengguna Baru</h3>
                        <p>Lengkapi formulir di bawah ini untuk mendaftarkan akun pengguna baru ke dalam sistem Jurnify.</p>
                    </div>
                </div>

                <div class="form-body">

                    <!-- Form Inputs Grid -->
                    <form id="addUserForm" onsubmit="event.preventDefault();" class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="namaLengkap">
                                Nama Lengkap & Gelar <span class="required">*</span>
                            </label>
                            <input type="text" id="namaLengkap" class="form-input" placeholder="Contoh: Ahmad Fauzi, S.Pd., M.Pd." required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="nip">
                                NIP <span class="required">*</span>
                            </label>
                            <input type="text" id="nip" class="form-input" placeholder="Masukkan 18 digit NIP atau ID pegawai" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="telepon">
                                Nomor Telepon / WhatsApp <span class="required">*</span>
                            </label>
                            <input type="tel" id="telepon" class="form-input" placeholder="Contoh: 081234567890" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="role">
                                Role Pengguna <span class="required">*</span>
                            </label>
                            <select id="role" class="form-select" required>
                                <option value="" disabled selected>Pilih Role Pengguna</option>
                                <option value="guru">Guru Mata Pelajaran</option>
                                <option value="staff_piket">Staff Piket</option>
                                <option value="sekre">Sekretaris / Kurikulum</option>
                                <option value="admin">Administrator Sekolah</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="mapel">
                                Mata Pelajaran yang Diampu
                            </label>
                            <input type="text" id="mapel" class="form-input" placeholder="Contoh: Matematika, Fisika (Opsional)">
                            <span class="form-helper">Kosongkan jika bukan pengampu mata pelajaran.</span>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">
                                Status Akun Awal
                            </label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="statusAkun" value="aktif" checked>
                                    <span>Aktif Langsung</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="statusAkun" value="nonaktif">
                                    <span>Non-Aktif</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <div class="password-note">
                                <span class="material-symbols-outlined">info</span>
                                <div>
                                    Kata sandi sementara akan dibuat secara otomatis oleh sistem dan dikirimkan langsung ke email instansi pengguna terdaftar.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="form-footer">
                    <a href="dashboard_user.html" class="btn-cancel">Batal</a>
                    <button type="submit" form="addUserForm" class="btn-submit">
                        Simpan & Daftarkan User
                    </button>
                </div>
            </section>
        </div>
    </main>
</body>
</html>