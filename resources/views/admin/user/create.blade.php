@extends('layouts.admin')

@section('title', 'Tambah User Baru - Jurnify')
@section('page-title', 'Tambah User Baru')
@section('page-subtitle', 'Daftarkan akun pengguna baru ke dalam sistem Jurnify')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div id="jurnify-user-create" class="user-create-page">

    {{-- BREADCRUMB --}}
    <nav class="breadcrumb-nav">
        <a href="{{ route('admin.user.index') }}">Manajemen User</a>
        <span class="sep">/</span>
        <span class="cur">Tambah User Baru</span>
    </nav>

    {{-- FORM CARD --}}
    <div class="main-form-card">

        {{-- CARD HEADER --}}
        <div class="card-header-box">
            <div class="header-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="8" r="4"/>
                    <path d="M3 21c0-3.3 2.7-6 6-6"/>
                    <path d="M19 8v6"/>
                    <path d="M16 11h6"/>
                </svg>
            </div>

            <div>
                <h2 class="header-title-text">Informasi Pengguna Baru</h2>
                <p class="header-sub-text">
                    Lengkapi formulir di bawah ini untuk mendaftarkan akun pengguna baru.
                </p>
            </div>
        </div>

        {{-- VALIDATION ERROR ALERT --}}
        @if ($errors->any())
            <div class="alert-box-danger">
                <div class="icon-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>

                <div>
                    <strong>Data belum dapat disimpan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- FORM --}}
        <form id="addUserForm" action="{{ route('admin.user.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="card-body-box">
                <div class="input-grid">

                    {{-- USERNAME --}}
                    <div class="field-group">
                        <label class="field-label" for="username">
                            Username <span class="req">*</span>
                        </label>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="custom-input @error('username') is-invalid @enderror"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}"
                            required
                        >

                        <span id="usernameError" class="err-text" style="display: none;"></span>

                        <span class="field-hint">
                            Gunakan huruf kecil, angka, atau underscore tanpa spasi.
                        </span>
                    </div>

                    {{-- KATA SANDI --}}
                    <div class="field-group">
                        <label class="field-label" for="password">
                            Kata Sandi <span class="req">*</span>
                        </label>

                        <div class="pw-input-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="custom-input @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                                required
                            >

                            <button
                                type="button"
                                id="togglePassword"
                                class="btn-toggle-eye"
                                title="Tampilkan password"
                                aria-label="Tampilkan password"
                            >
                                <svg
                                    id="eyeIcon"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>

                        <span id="passwordError" class="err-text" style="display: none;">
                            Password minimal 8 karakter.
                        </span>

                        <span class="field-hint">
                            Digunakan untuk akses login pengguna.
                        </span>
                    </div>

                    {{-- ROLE --}}
                    <div class="field-group">
                        <label class="field-label" for="role">
                            Role Pengguna <span class="req">*</span>
                        </label>

                        <select
                            id="role"
                            name="role"
                            class="custom-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                                Pilih Role Pengguna
                            </option>

                            <option value="Guru" {{ old('role') === 'Guru' ? 'selected' : '' }}>
                                Guru Mata Pelajaran
                            </option>

                            <option value="Staff Piket" {{ old('role') === 'Staff Piket' ? 'selected' : '' }}>
                                Staff Piket
                            </option>

                            <option value="Sekretaris" {{ old('role') === 'Sekretaris' ? 'selected' : '' }}>
                                Sekretaris
                            </option>

                            <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>
                                Administrator Sekolah
                            </option>
                        </select>

                        <span class="field-hint">
                            Role menentukan hak akses pengguna di sistem.
                        </span>
                    </div>

                    {{-- DATA GURU --}}
                    <div class="field-group" id="guruContainer" hidden>
                        <label class="field-label" for="id_guru">
                            Data Guru <span class="req">*</span>
                        </label>

                        <select
                            id="id_guru"
                            name="id_guru"
                            class="custom-select @error('id_guru') is-invalid @enderror"
                        >
                            <option value="">-- Pilih Guru --</option>

                            @foreach ($gurus as $guru)
                                <option
                                    value="{{ $guru->id_guru }}"
                                    {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}
                                >
                                    {{ $guru->nama_guru }}
                                    @if ($guru->nip)
                                        - NIP {{ $guru->nip }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <span id="guruError" class="err-text" style="display: none;">
                            Silakan pilih data Guru untuk akun Guru atau Admin.
                        </span>

                        <span class="field-hint">
                            Hubungkan akun ini dengan data guru yang sudah terdaftar.
                        </span>
                    </div>

                    {{-- NOTE FOOTER --}}
                    <div class="field-group full-width">
                        <div class="info-note-card">
                            <div class="icon-info">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="10" x2="12" y2="16"/>
                                    <circle cx="12" cy="7" r="0.5" fill="currentColor"/>
                                </svg>
                            </div>

                            <div>
                                Kata sandi digunakan untuk login ke sistem.
                                Pastikan pengguna menyimpan kata sandinya dengan aman.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- FORM FOOTER --}}
            <div class="card-footer-box">

                <a href="{{ route('admin.user.index') }}" class="btn-cancel-custom">
                    Batal
                </a>

                <button type="submit" class="btn-submit-custom">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M19 8v6"/>
                        <path d="M16 11h6"/>
                    </svg>

                    <span>Simpan & Daftarkan User</span>
                </button>

            </div>

        </form>

    </div>

</div>

<style>
    #jurnify-user-create,
    #jurnify-user-create * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        box-sizing: border-box !important;
    }

    #jurnify-user-create {
        width: 100% !important;
        max-width: 1100px !important;
        margin: 0 auto !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 20px !important;
    }

    /* BREADCRUMB */
    #jurnify-user-create .breadcrumb-nav {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        font-size: 13.5px !important;
        color: #64748B !important;
        font-weight: 500 !important;
    }

    #jurnify-user-create .breadcrumb-nav a {
        color: #7886C7 !important;
        text-decoration: none !important;
        font-weight: 700 !important;
    }

    #jurnify-user-create .breadcrumb-nav a:hover {
        color: #2D336B !important;
    }

    #jurnify-user-create .breadcrumb-nav .cur {
        color: #1E293B !important;
        font-weight: 700 !important;
    }

    /* CARD */
    #jurnify-user-create .main-form-card {
        width: 100% !important;
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03) !important;
        overflow: hidden !important;
    }

    /* CARD HEADER */
    #jurnify-user-create .card-header-box {
        display: flex !important;
        align-items: center !important;
        gap: 16px !important;
        padding: 28px 32px !important;
        background: #FAFAFC !important;
        border-bottom: 1px solid #E2E8F0 !important;
    }

    #jurnify-user-create .header-icon-box {
        width: 52px !important;
        height: 52px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        border-radius: 16px !important;
        background: #F0F3FF !important;
        color: #7886C7 !important;
    }

    #jurnify-user-create .header-icon-box svg {
        width: 26px !important;
        height: 26px !important;
    }

    #jurnify-user-create .header-title-text {
        margin: 0 !important;
        font-size: 18px !important;
        font-weight: 800 !important;
        color: #1E293B !important;
    }

    #jurnify-user-create .header-sub-text {
        margin: 4px 0 0 !important;
        font-size: 13.5px !important;
        color: #64748B !important;
        font-weight: 500 !important;
    }

    /* ALERT */
    #jurnify-user-create .alert-box-danger {
        display: flex !important;
        align-items: flex-start !important;
        gap: 12px !important;
        margin: 24px 32px 0 !important;
        padding: 16px 20px !important;
        border-radius: 16px !important;
        background: #FEF2F2 !important;
        border: 1px solid #FECACA !important;
        color: #991B1B !important;
        font-size: 13.5px !important;
        line-height: 1.5 !important;
    }

    #jurnify-user-create .icon-danger {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
    }

    #jurnify-user-create .icon-danger svg {
        width: 20px !important;
        height: 20px !important;
    }

    #jurnify-user-create .alert-box-danger strong {
        display: block !important;
        margin-bottom: 4px !important;
        font-weight: 800 !important;
    }

    #jurnify-user-create .alert-box-danger ul {
        margin: 0 !important;
        padding-left: 18px !important;
    }

    #jurnify-user-create .alert-box-danger li {
        margin: 2px 0 !important;
    }

    /* FORM BODY */
    #jurnify-user-create .card-body-box {
        padding: 32px !important;
    }

    #jurnify-user-create .input-grid {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 24px !important;
        width: 100% !important;
    }

    #jurnify-user-create .field-group {
        display: flex !important;
        flex-direction: column !important;
        gap: 6px !important;
        width: 100% !important;
        min-width: 0 !important;
    }

    #jurnify-user-create .field-group[hidden] {
        display: none !important;
    }

    #jurnify-user-create .field-group.full-width {
        grid-column: span 2 !important;
    }

    #jurnify-user-create .field-label {
        font-size: 13.5px !important;
        font-weight: 700 !important;
        color: #334155 !important;
    }

    #jurnify-user-create .req {
        color: #EF4444 !important;
    }

    /* INPUT */
    #jurnify-user-create .custom-input,
    #jurnify-user-create .custom-select {
        width: 100% !important;
        height: 44px !important;
        padding: 0 16px !important;
        background: #FFFFFF !important;
        border: 1px solid #CBD5E1 !important;
        border-radius: 12px !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        color: #0F172A !important;
        outline: none !important;
        transition: all 0.2s ease !important;
    }

    #jurnify-user-create .custom-input:focus,
    #jurnify-user-create .custom-select:focus {
        border-color: #7886C7 !important;
        box-shadow: 0 0 0 4px rgba(120, 134, 199, 0.15) !important;
    }

    #jurnify-user-create .custom-input.is-invalid,
    #jurnify-user-create .custom-select.is-invalid {
        border-color: #DC2626 !important;
    }

    #jurnify-user-create .custom-input::placeholder {
        color: #94A3B8 !important;
    }

    /* PASSWORD */
    #jurnify-user-create .pw-input-wrapper {
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
    }

    #jurnify-user-create .pw-input-wrapper .custom-input {
        padding-right: 48px !important;
    }

    #jurnify-user-create .btn-toggle-eye {
        position: absolute !important;
        top: 50% !important;
        right: 12px !important;
        transform: translateY(-50%) !important;
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        color: #64748B !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 8px !important;
        transition: all 0.2s ease !important;
    }

    #jurnify-user-create .btn-toggle-eye:hover {
        background: #F1F5F9 !important;
        color: #2D336B !important;
    }

    #jurnify-user-create .btn-toggle-eye svg {
        width: 19px !important;
        height: 19px !important;
    }

    /* HINT & ERROR */
    #jurnify-user-create .field-hint {
        font-size: 12.5px !important;
        color: #64748B !important;
        font-weight: 500 !important;
        line-height: 1.4 !important;
    }

    #jurnify-user-create .err-text {
        font-size: 12.5px !important;
        color: #DC2626 !important;
        font-weight: 700 !important;
        line-height: 1.4 !important;
    }

    /* INFO NOTE */
    #jurnify-user-create .info-note-card {
        background: #F8FAFC !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px !important;
        padding: 16px 20px !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        font-size: 13.5px !important;
        color: #475569 !important;
        font-weight: 500 !important;
        line-height: 1.5 !important;
    }

    #jurnify-user-create .icon-info {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        color: #4F46E5 !important;
    }

    #jurnify-user-create .icon-info svg {
        width: 21px !important;
        height: 21px !important;
    }

    /* FOOTER */
    #jurnify-user-create .card-footer-box {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 12px !important;
        padding: 20px 32px !important;
        background: #FAFBFD !important;
        border-top: 1px solid #E2E8F0 !important;
    }

    #jurnify-user-create .btn-submit-custom,
    #jurnify-user-create .btn-cancel-custom {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        height: 44px !important;
        padding: 0 24px !important;
        border-radius: 12px !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        font-family: 'Manrope', sans-serif !important;
        cursor: pointer !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
    }

    #jurnify-user-create .btn-submit-custom {
        border: none !important;
        background: linear-gradient(135deg, #7886C7 0%, #2D336B 100%) !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(45, 51, 107, 0.12) !important;
    }

    #jurnify-user-create .btn-submit-custom:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 7px 18px rgba(45, 51, 107, 0.20) !important;
    }

    #jurnify-user-create .btn-cancel-custom {
        border: 1px solid #CBD5E1 !important;
        background: #FFFFFF !important;
        color: #475569 !important;
    }

    #jurnify-user-create .btn-cancel-custom:hover {
        background: #F1F5F9 !important;
        color: #334155 !important;
        border-color: #CBD5E1 !important;
    }

    #jurnify-user-create .btn-submit-custom svg {
        width: 18px !important;
        height: 18px !important;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        #jurnify-user-create {
            max-width: 100% !important;
        }

        #jurnify-user-create .card-header-box {
            padding: 22px 20px !important;
        }

        #jurnify-user-create .card-body-box {
            padding: 22px 20px !important;
        }

        #jurnify-user-create .input-grid {
            grid-template-columns: 1fr !important;
        }

        #jurnify-user-create .field-group.full-width {
            grid-column: span 1 !important;
        }

        #jurnify-user-create .alert-box-danger {
            margin-left: 20px !important;
            margin-right: 20px !important;
        }

        #jurnify-user-create .card-footer-box {
            padding: 18px 20px !important;
            flex-direction: column-reverse !important;
        }

        #jurnify-user-create .btn-submit-custom,
        #jurnify-user-create .btn-cancel-custom {
            width: 100% !important;
        }
    }
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const username = document.getElementById('username');
    const usernameError = document.getElementById('usernameError');

    const password = document.getElementById('password');
    const passwordError = document.getElementById('passwordError');

    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    const role = document.getElementById('role');

    const guruContainer = document.getElementById('guruContainer');
    const idGuru = document.getElementById('id_guru');
    const guruError = document.getElementById('guruError');

    const form = document.getElementById('addUserForm');

    // Mencegah spasi pada username
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

            usernameError.innerText =
                'Spasi otomatis dihapus. Username tidak boleh menggunakan spasi.';

            usernameError.style.display = 'block';
        }

        this.value = this.value.toLowerCase();
    });

    username.addEventListener('blur', function () {
        if (!/\s/.test(this.value)) {
            usernameError.style.display = 'none';
        }
    });

    // Toggle password
    togglePassword.addEventListener('click', function () {
        if (password.type === 'password') {
            password.type = 'text';

            eyeIcon.innerHTML = `
                <path d="M3 3l18 18"/>
                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18.3 18.3 0 0 1-3.1 4.4"/>
                <path d="M6.6 6.6C3.8 8.5 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 3.4-.6"/>
            `;

            this.title = 'Sembunyikan password';
            this.setAttribute('aria-label', 'Sembunyikan password');
        } else {
            password.type = 'password';

            eyeIcon.innerHTML = `
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                <circle cx="12" cy="12" r="3"/>
            `;

            this.title = 'Tampilkan password';
            this.setAttribute('aria-label', 'Tampilkan password');
        }
    });

    // Validasi password
    password.addEventListener('input', function () {
        passwordError.style.display =
            this.value.length > 0 && this.value.length < 8
                ? 'block'
                : 'none';
    });

    // Field Guru
    function updateGuruField() {
        const needsGuru = ['Guru', 'Admin', 'Staff Piket'].includes(role.value);

        if (needsGuru) {
            guruContainer.hidden = false;
            guruContainer.style.display = 'flex';
            idGuru.required = true;
        } else {
            guruContainer.hidden = true;
            guruContainer.style.display = 'none';
            idGuru.required = false;
            idGuru.value = '';
            guruError.style.display = 'none';
        }
    }

    role.addEventListener('change', function () {
        updateGuruField();
    });

    updateGuruField();

    // Validasi sebelum submit
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

        if (
            ['Guru', 'Admin', 'Staff Piket'].includes(role.value) &&
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