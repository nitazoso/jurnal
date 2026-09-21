@extends('layouts.admin')

@section('title', 'Tambah User Baru - Jurnify')

@section('page-title', 'Tambah User Baru')

@push('styles')
<style>
    .create-user-page {
        width: 100%;
        max-width: 1200px;
        animation: pageFadeIn .45s ease both;
    }

    .create-user-page .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 24px;
        animation: fadeDown .4s ease both;
    }

    .create-user-page .breadcrumb a {
        color: #4169ff;
        text-decoration: none;
        font-weight: 600;
        transition: color .2s ease;
    }

    .create-user-page .breadcrumb a:hover {
        color: #30366f;
    }

    .create-user-page .breadcrumb .separator {
        font-size: 14px;
        color: #9ca3af;
    }

    .create-user-page .breadcrumb .current {
        color: #374151;
        font-weight: 600;
    }

    /* FORM CARD */
    .create-user-page .form-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eaedf1;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .025);
        overflow: hidden;
        animation: cardUp .5s .08s ease both;
        transition: box-shadow .25s ease;
    }

    .create-user-page .form-card:hover {
        box-shadow: 0 5px 18px rgba(29, 44, 103, .06);
    }

    .create-user-page .form-header {
        padding: 28px 32px 24px;
        border-bottom: 1px solid #f0f2f5;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .create-user-page .form-header-text h3 {
        color: #1d2c67;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .create-user-page .form-header-text p {
        color: #555861;
        font-size: 14px;
    }

    /* ERROR */
    .create-user-page .validation-errors {
        margin: 24px 32px 0;
        padding: 14px 18px;
        background: #fff1f1;
        border: 1px solid #fecaca;
        border-radius: 8px;
        color: #b42318;
        font-size: 13px;
        animation: errorIn .35s ease both;
    }

    .create-user-page .validation-errors strong {
        font-weight: 800;
    }

    .create-user-page .validation-errors ul {
        margin: 8px 0 0 20px;
    }

    .create-user-page .validation-errors li {
        margin-bottom: 3px;
    }

    /* FORM BODY */
    .create-user-page .form-body {
        padding: 32px;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .create-user-page .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px 28px;
    }

    .create-user-page .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        animation: fieldUp .45s ease both;
    }

    .create-user-page .form-group:nth-child(1) {
        animation-delay: .08s;
    }

    .create-user-page .form-group:nth-child(2) {
        animation-delay: .12s;
    }

    .create-user-page .form-group:nth-child(3) {
        animation-delay: .16s;
    }

    .create-user-page .form-group:nth-child(4) {
        animation-delay: .20s;
    }

    .create-user-page .form-group:nth-child(5) {
        animation-delay: .24s;
    }

    .create-user-page .form-group:nth-child(6) {
        animation-delay: .28s;
    }

    .create-user-page .form-group.full-width {
        grid-column: span 2;
    }

    .create-user-page .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .create-user-page .form-label .required {
        color: #dc2626;
    }

    /* INPUT */
    .create-user-page .form-input,
    .create-user-page .form-select {
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
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .create-user-page .form-input:hover,
    .create-user-page .form-select:hover {
        border-color: #aeb4c0;
    }

    .create-user-page .form-input:focus,
    .create-user-page .form-select:focus {
        border-color: #30366f;
        box-shadow: 0 0 0 3px rgba(48, 54, 111, .1);
        transform: translateY(-1px);
    }

    .create-user-page .form-input::placeholder {
        color: #9ca3af;
        font-size: 13px;
    }

    .create-user-page .form-helper {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
        transition: color .2s ease;
    }

    .create-user-page .form-group:focus-within .form-helper {
        color: #59627e;
    }

    /* INLINE ERROR */
    .create-user-page .field-error {
        color: #d92d20;
        font-size: 12px;
        display: none;
        margin-top: 1px;
        animation: errorIn .25s ease both;
    }

    /* DATA GURU */
    .create-user-page #guruContainer {
        display: none;
    }

    .create-user-page #guruContainer.show {
        animation: fieldUp .3s ease both;
    }

    /* PASSWORD NOTE */
    .create-user-page .password-note {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: #475569;
        transition: border-color .2s ease, background .2s ease, transform .2s ease;
    }

    .create-user-page .password-note:hover {
        border-color: #cbd5e1;
        background: #f9fafb;
        transform: translateY(-1px);
    }

    .create-user-page .password-note .material-symbols-outlined {
        color: #2563eb;
        font-size: 20px;
        flex-shrink: 0;
        transition: transform .25s ease;
    }

    .create-user-page .password-note:hover .material-symbols-outlined {
        transform: scale(1.08);
    }

    /* FOOTER */
    .create-user-page .form-footer {
        padding: 20px 32px;
        background: #fcfcfd;
        border-top: 1px solid #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    .create-user-page .btn-cancel {
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
        transition: background .2s ease, border-color .2s ease, transform .15s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .create-user-page .btn-cancel:hover {
        background: #f3f4f6;
        border-color: #c5c9d0;
        transform: translateY(-1px);
    }

    .create-user-page .btn-cancel:active,
    .create-user-page .btn-submit:active {
        transform: translateY(1px);
    }

    .create-user-page .btn-submit {
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
        transition: background .2s ease, box-shadow .2s ease, transform .15s ease;
    }

    .create-user-page .btn-submit:hover {
        background: #182864;
        box-shadow: 0 4px 10px rgba(24, 40, 100, .18);
        transform: translateY(-1px);
    }

    .create-user-page .btn-submit .material-symbols-outlined {
        font-size: 19px;
        transition: transform .2s ease;
    }

    .create-user-page .btn-submit:hover .material-symbols-outlined {
        transform: translateX(2px);
    }

    /* ANIMATION */
    @keyframes pageFadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes cardUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fieldUp {
        from {
            opacity: 0;
            transform: translateY(7px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes errorIn {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .create-user-page .form-grid {
            grid-template-columns: 1fr;
        }

        .create-user-page .form-group.full-width {
            grid-column: span 1;
        }
    }

    @media (max-width: 800px) {
        .create-user-page .form-header {
            flex-direction: column;
            gap: 12px;
        }

        .create-user-page .form-body {
            padding: 24px;
        }

        .create-user-page .form-footer {
            padding: 20px 24px;
        }
    }

    @media (max-width: 600px) {
        .create-user-page .form-body {
            padding: 20px 16px;
        }

        .create-user-page .form-footer {
            padding: 16px;
            flex-direction: column-reverse;
            width: 100%;
        }

        .create-user-page .btn-cancel,
        .create-user-page .btn-submit {
            width: 100%;
            justify-content: center;
        }

        .create-user-page .validation-errors {
            margin-left: 16px;
            margin-right: 16px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .create-user-page,
        .create-user-page *,
        .create-user-page::before,
        .create-user-page::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>
@endpush

@section('content')
<div class="create-user-page">

    <nav class="breadcrumb">
        <a href="{{ route('admin.user.index') }}">Manajemen User</a>
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

        @if ($errors->any())
            <div class="validation-errors">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-body">

            <form id="addUserForm" action="{{ route('admin.user.store') }}" method="POST" class="form-grid" autocomplete="off">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="nama_user">
                        Nama Lengkap & Gelar <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="nama_user"
                        name="nama_user"
                        class="form-input"
                        placeholder="Contoh: Ahmad Fauzi, S.Pd., M.Pd."
                        value="{{ old('nama_user') }}"
                        autocomplete="off"
                        required
                    >

                    <span class="form-helper">
                        Masukkan nama lengkap pemilik akun.
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="username">
                        Username <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        autocomplete="off"
                        required
                    >

                    <span id="usernameError" class="field-error">
                        Username tidak boleh menggunakan spasi.
                    </span>

                    <span class="form-helper">
                        Digunakan untuk login. Gunakan huruf kecil, angka, atau underscore.
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        Kata Sandi <span class="required">*</span>
                    </label>

                    <div style="position: relative;">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password"
                            style="padding-right: 45px;"
                            required
                        >

                        <button
                            type="button"
                            id="togglePassword"
                            title="Tampilkan password"
                            style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); border: none; background: transparent; cursor: pointer; font-size: 17px; padding: 5px;"
                        >👁️</button>
                    </div>

                    <span id="passwordError" class="field-error">
                        Password minimal 8 karakter.
                    </span>

                    <span class="form-helper">
                        Password digunakan untuk login dan minimal 8 karakter.
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">
                        Role Pengguna <span class="required">*</span>
                    </label>

                    <select id="role" name="role" class="form-select" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                            Pilih Role Pengguna
                        </option>

                        <option value="Guru" {{ old('role') === 'Guru' ? 'selected' : '' }}>
                            Guru Mata Pelajaran
                        </option>

                        <option value="Kesiswaan" {{ old('role') === 'Kesiswaan' ? 'selected' : '' }}>
                            Kesiswaan
                        </option>

                        <option value="Staff Piket" {{ old('role') === 'Staff Piket' ? 'selected' : '' }}>
                            Staff Piket
                        </option>

                        <option value="Sekretaris" {{ old('role') === 'Sekretaris' ? 'selected' : '' }}>
                            Sekretaris / Kurikulum
                        </option>

                        <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>
                            Administrator Sekolah
                        </option>
                    </select>

                    <span class="form-helper">
                        Role menentukan hak akses pengguna di dalam sistem.
                    </span>
                </div>

                <div class="form-group" id="waContainer" style="display: none;">
                    <label class="form-label" for="no_wa">
                        Nomor WhatsApp Kesiswaan <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="no_wa"
                        id="no_wa"
                        value="{{ old('no_wa') }}"
                        placeholder="Contoh: 628123456789"
                        inputmode="tel"
                        class="form-input"
                    >

                    <span class="form-helper">
                        Digunakan untuk notifikasi dan tautan WhatsApp pengajuan dispen.
                    </span>
                </div>

                <div class="form-group" id="guruContainer">
                    <label class="form-label" for="id_guru">
                        Data Guru <span class="required">*</span>
                    </label>

                    <select id="id_guru" name="id_guru" class="form-select">
                        <option value="">-- Pilih Guru --</option>

                        @foreach ($gurus as $guru)
                            <option
                                value="{{ $guru->id_guru }}"
                                {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}
                            >
                                {{ $guru->nama_guru }}
                            </option>
                        @endforeach
                    </select>

                    <span id="guruError" class="field-error">
                        Silakan pilih data Guru untuk akun dengan role Guru atau Staff Piket.
                    </span>

                    <span class="form-helper">
                        Hubungkan akun login ini dengan data guru yang sudah terdaftar.
                    </span>
                </div>

                <div class="form-group full-width">
                    <div class="password-note">
                        <span class="material-symbols-outlined">info</span>

                        <div>
                            Kata sandi digunakan untuk login ke sistem. Pastikan pengguna menyimpan kata sandinya dengan aman.
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div class="form-footer">
            <a href="{{ route('admin.user.index') }}" class="btn-cancel">
                Batal
            </a>

            <button type="submit" form="addUserForm" class="btn-submit">
                Simpan & Daftarkan User
            </button>
        </div>

    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const username = document.getElementById('username');
    const usernameError = document.getElementById('usernameError');
    const password = document.getElementById('password');
    const passwordError = document.getElementById('passwordError');
    const togglePassword = document.getElementById('togglePassword');
    const role = document.getElementById('role');
    const guruContainer = document.getElementById('guruContainer');
    const idGuru = document.getElementById('id_guru');
    const guruError = document.getElementById('guruError');
    const waContainer = document.getElementById('waContainer');
    const noWa = document.getElementById('no_wa');
    const form = document.getElementById('addUserForm');

    username.addEventListener('keydown', function (event) {
        if (event.key === ' ') {
            event.preventDefault();
            usernameError.innerText = 'Username tidak boleh menggunakan spasi.';
            usernameError.style.display = 'block';
        }
    });

    username.addEventListener('input', function () {
        if (/\s/.test(this.value)) {
            this.value = this.value.replace(/\s/g, '');
            usernameError.innerText = 'Spasi otomatis dihapus. Username tidak boleh menggunakan spasi.';
            usernameError.style.display = 'block';
        }

        this.value = this.value.toLowerCase();
    });

    username.addEventListener('blur', function () {
        if (!/\s/.test(this.value)) {
            usernameError.style.display = 'none';
        }
    });

    togglePassword.addEventListener('click', function () {
        if (password.type === 'password') {
            password.type = 'text';
            this.innerText = '🙈';
            this.title = 'Sembunyikan password';
        } else {
            password.type = 'password';
            this.innerText = '👁️';
            this.title = 'Tampilkan password';
        }
    });

    password.addEventListener('input', function () {
        passwordError.style.display =
            this.value.length > 0 && this.value.length < 8
                ? 'block'
                : 'none';
    });

    function updateGuruField() {
        if (role.value === 'Guru' || role.value === 'Staff Piket') {
            guruContainer.style.display = 'flex';
            guruContainer.classList.remove('show');

            requestAnimationFrame(() => {
                guruContainer.classList.add('show');
            });

            idGuru.required = true;
        } else {
            guruContainer.style.display = 'none';
            guruContainer.classList.remove('show');
            idGuru.required = false;
            idGuru.value = '';
            guruError.style.display = 'none';
        }
    }

    function updateWaField() {
        const isKesiswaan = role.value === 'Kesiswaan';

        waContainer.style.display = isKesiswaan ? 'flex' : 'none';
        noWa.required = isKesiswaan;

        if (!isKesiswaan) {
            noWa.value = '';
        }
    }

    role.addEventListener('change', updateGuruField);
    role.addEventListener('change', updateWaField);

    updateGuruField();
    updateWaField();

    form.addEventListener('submit', function (event) {
        let valid = true;

        if (username.value.trim() === '') {
            usernameError.innerText = 'Username wajib diisi.';
            usernameError.style.display = 'block';
            valid = false;
        }

        if (password.value.length < 8) {
            passwordError.innerText = 'Password minimal 8 karakter.';
            passwordError.style.display = 'block';
            valid = false;
        }

        if (role.value === '') {
            alert('Silakan pilih Role / Hak Akses terlebih dahulu.');
            valid = false;
        }

        // Guru dan Staff Piket wajib terhubung ke data guru
        if (
            (role.value === 'Guru' || role.value === 'Staff Piket') &&
            idGuru.value === ''
        ) {
            guruError.style.display = 'block';
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });
});
</script>
@endpush