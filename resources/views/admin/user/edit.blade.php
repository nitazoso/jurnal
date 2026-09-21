@extends('layouts.admin')

@section('title', 'Edit User - Jurnify')
@section('page-title', 'Edit Data User')
@section('page-subtitle', 'Perbarui informasi akun pengguna di dalam sistem Jurnify')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div id="jurnify-user-edit" class="user-edit-page">

    {{-- BREADCRUMB --}}
    <nav class="breadcrumb-nav">
        <a href="{{ route('admin.user.index') }}">Manajemen User</a>

        <span class="breadcrumb-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

        <span class="current">Edit User</span>
    </nav>

    {{-- VALIDATION ERROR --}}
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
                <strong>Data belum dapat diperbarui:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- MAIN CARD --}}
    <div class="main-form-card">

        {{-- CARD HEADER --}}
        <div class="card-header-box">
            <div class="header-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </div>

            <div>
                <h2 class="header-title-text">Perbarui Informasi Pengguna</h2>
                <p class="header-sub-text">
                    Ubah informasi akun, role, dan penugasan pengguna di bawah ini.
                </p>
            </div>
        </div>

        {{-- PROFILE INFO --}}
        <div class="profile-box">
            <div class="avatar">
                {{ strtoupper(substr($user->nama_user, 0, 1)) }}
            </div>

            <div class="profile-info">
                <h3>{{ $user->nama_user }}</h3>
                <p>Akun pengguna Jurnify</p>
            </div>
        </div>

        <div class="section-divider"></div>

        {{-- FORM --}}
        <form
            id="editUserForm"
            action="{{ route('admin.user.update', $user->id_user) }}"
            method="POST"
            autocomplete="off"
        >
            @csrf
            @method('PUT')

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
                            value="{{ old('username', $user->username) }}"
                            autocomplete="new-password"
                            autocapitalize="none"
                            autocorrect="off"
                            spellcheck="false"
                            data-lpignore="true"
                            data-1p-ignore="true"
                            data-protonpass-ignore="true"
                            required
                        >

                        <span id="usernameError" class="err-text" style="display:none;">
                            Username tidak boleh menggunakan spasi.
                        </span>

                        @error('username')
                            <span class="err-text">{{ $message }}</span>
                        @enderror

                        <span class="field-hint">
                            Username digunakan untuk login. Tidak boleh menggunakan spasi.
                        </span>
                    </div>

                    {{-- NAMA USER --}}
                    <div class="field-group">
                        <label class="field-label" for="nama_user">
                            Nama Lengkap & Gelar <span class="req">*</span>
                        </label>

                        <input
                            type="text"
                            id="nama_user"
                            name="nama_user"
                            class="custom-input @error('nama_user') is-invalid @enderror"
                            value="{{ old('nama_user', $user->nama_user) }}"
                            autocomplete="off"
                            required
                        >

                        @error('nama_user')
                            <span class="err-text">{{ $message }}</span>
                        @enderror

                        <span class="field-hint">
                            Nama lengkap pemilik akun.
                        </span>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="field-group">
                        <label class="field-label" for="password">
                            Password Baru
                        </label>

                        <div class="pw-input-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="custom-input @error('password') is-invalid @enderror"
                                placeholder="Kosongkan jika tidak ingin mengubah"
                                autocomplete="new-password"
                                autocapitalize="none"
                                autocorrect="off"
                                spellcheck="false"
                                data-lpignore="true"
                                data-1p-ignore="true"
                                data-protonpass-ignore="true"
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

                        <span id="passwordError" class="err-text" style="display:none;">
                            Password minimal 8 karakter.
                        </span>

                        @error('password')
                            <span class="err-text">{{ $message }}</span>
                        @enderror

                        <span class="field-hint">
                            Kosongkan jika password tidak ingin diubah. Jika diisi, minimal 8 karakter.
                        </span>
                    </div>

                    {{-- ROLE --}}
                    <div class="field-group">
                        <label class="field-label" for="role">
                            Role / Hak Akses <span class="req">*</span>
                        </label>

                        <select
                            id="role"
                            name="role"
                            class="custom-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="">-- Pilih Role --</option>

                            <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>
                                Admin
                            </option>

                            <option value="Guru" {{ old('role', $user->role) == 'Guru' ? 'selected' : '' }}>
                                Guru
                            </option>

                            <option value="Kesiswaan" {{ old('role', $user->role) == 'Kesiswaan' ? 'selected' : '' }}>
                                Kesiswaan
                            </option>

                            <option value="Sekretaris" {{ old('role', $user->role) == 'Sekretaris' ? 'selected' : '' }}>
                                Sekretaris
                            </option>

                            <option value="Staff Piket" {{ old('role', $user->role) == 'Staff Piket' ? 'selected' : '' }}>
                                Staff Piket
                            </option>
                        </select>

                        @error('role')
                            <span class="err-text">{{ $message }}</span>
                        @enderror

                        <span class="field-hint">
                            Role menentukan hak akses pengguna di sistem.
                        </span>
                    </div>

                    {{-- WHATSAPP KESISWAAN --}}
                    <div class="field-group" id="waContainer" style="display:none;">
                        <label class="field-label" for="no_wa">
                            Nomor WhatsApp Kesiswaan
                        </label>

                        <input
                            type="text"
                            name="no_wa"
                            id="no_wa"
                            value="{{ old('no_wa', $user->no_wa) }}"
                            placeholder="Contoh: 628123456789"
                            inputmode="tel"
                            class="custom-input @error('no_wa') is-invalid @enderror"
                        >

                        @error('no_wa')
                            <span class="err-text">{{ $message }}</span>
                        @enderror

                        <span class="field-hint">
                            Digunakan untuk notifikasi dan tautan WhatsApp pengajuan dispen.
                        </span>
                    </div>

                    {{-- DATA GURU --}}
                    <div class="field-group" id="guruContainer" style="display:none;">
                        <label class="field-label" for="id_guru">
                            Data Guru
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
                                    {{ old('id_guru', $user->id_guru) == $guru->id_guru ? 'selected' : '' }}
                                >
                                    {{ $guru->nama_guru }}
                                    @if ($guru->nip)
                                        - NIP {{ $guru->nip }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <span id="guruError" class="err-text" style="display:none;">
                            Silakan pilih data Guru.
                        </span>

                        @error('id_guru')
                            <span class="err-text">{{ $message }}</span>
                        @enderror

                        <span class="field-hint">
                            Hubungkan akun Guru atau Staff Piket dengan data guru yang sudah terdaftar.
                        </span>
                    </div>

                    {{-- DATA KELAS --}}
                    <div class="field-group full-width">
                        <label class="field-label" for="id_kelas">
                            Data Kelas
                        </label>

                        <select
                            id="id_kelas"
                            name="id_kelas"
                            class="custom-select @error('id_kelas') is-invalid @enderror"
                        >
                            <option value="">Tidak terhubung ke kelas</option>

                            @foreach ($kelases as $kelas)
                                <option
                                    value="{{ $kelas->id_kelas }}"
                                    {{ old('id_kelas', $user->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}
                                >
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_kelas')
                            <span class="err-text">{{ $message }}</span>
                        @enderror

                        <span class="field-hint">
                            Pilih kelas jika akun ini memiliki keterkaitan dengan kelas tertentu.
                        </span>
                    </div>

                    {{-- INFO NOTE --}}
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
                                Password hanya akan diperbarui jika kolom password baru diisi.
                                Jika dikosongkan, password lama tetap digunakan.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- FOOTER --}}
            <div class="card-footer-box">

                <a href="{{ route('admin.user.index') }}" class="btn-cancel-custom">
                    Batal
                </a>

                <button type="submit" class="btn-submit-custom">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12l4 4L19 6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    <span>Simpan Perubahan</span>
                </button>

            </div>

        </form>

    </div>

</div>

<style>
    #jurnify-user-edit,
    #jurnify-user-edit * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        box-sizing: border-box !important;
    }

    #jurnify-user-edit {
        width: 100% !important;
        max-width: 1100px !important;
        margin: 0 auto !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 20px !important;
    }

    /* BREADCRUMB */
    #jurnify-user-edit .breadcrumb-nav {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        font-size: 13.5px !important;
        color: #64748B !important;
        font-weight: 500 !important;
    }

    #jurnify-user-edit .breadcrumb-nav a {
        color: #7886C7 !important;
        text-decoration: none !important;
        font-weight: 700 !important;
    }

    #jurnify-user-edit .breadcrumb-nav a:hover {
        color: #2D336B !important;
    }

    #jurnify-user-edit .breadcrumb-nav .current {
        color: #1E293B !important;
        font-weight: 700 !important;
    }

    #jurnify-user-edit .breadcrumb-icon {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #94A3B8 !important;
    }

    #jurnify-user-edit .breadcrumb-icon svg {
        width: 16px !important;
        height: 16px !important;
    }

    /* ALERT */
    #jurnify-user-edit .alert-box-danger {
        display: flex !important;
        align-items: flex-start !important;
        gap: 12px !important;
        padding: 16px 20px !important;
        border-radius: 16px !important;
        background: #FEF2F2 !important;
        border: 1px solid #FECACA !important;
        color: #991B1B !important;
        font-size: 13.5px !important;
        line-height: 1.5 !important;
    }

    #jurnify-user-edit .icon-danger {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
    }

    #jurnify-user-edit .icon-danger svg {
        width: 20px !important;
        height: 20px !important;
    }

    #jurnify-user-edit .alert-box-danger strong {
        display: block !important;
        margin-bottom: 4px !important;
        font-weight: 800 !important;
    }

    #jurnify-user-edit .alert-box-danger ul {
        margin: 0 !important;
        padding-left: 18px !important;
    }

    #jurnify-user-edit .alert-box-danger li {
        margin: 2px 0 !important;
    }

    /* MAIN CARD */
    #jurnify-user-edit .main-form-card {
        width: 100% !important;
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03) !important;
        overflow: hidden !important;
    }

    /* CARD HEADER */
    #jurnify-user-edit .card-header-box {
        display: flex !important;
        align-items: center !important;
        gap: 16px !important;
        padding: 28px 32px !important;
        background: #FAFAFC !important;
        border-bottom: 1px solid #E2E8F0 !important;
    }

    #jurnify-user-edit .header-icon-box {
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

    #jurnify-user-edit .header-icon-box svg {
        width: 26px !important;
        height: 26px !important;
    }

    #jurnify-user-edit .header-title-text {
        margin: 0 !important;
        font-size: 18px !important;
        font-weight: 800 !important;
        color: #1E293B !important;
    }

    #jurnify-user-edit .header-sub-text {
        margin: 4px 0 0 !important;
        font-size: 13.5px !important;
        color: #64748B !important;
        font-weight: 500 !important;
    }

    /* PROFILE */
    #jurnify-user-edit .profile-box {
        display: flex !important;
        align-items: center !important;
        gap: 16px !important;
        padding: 24px 32px !important;
        background: #FFFFFF !important;
    }

    #jurnify-user-edit .avatar {
        width: 52px !important;
        height: 52px !important;
        flex-shrink: 0 !important;
        border-radius: 14px !important;
        background: #DCE4FF !important;
        color: #2D336B !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 20px !important;
        font-weight: 800 !important;
    }

    #jurnify-user-edit .profile-info h3 {
        margin: 0 !important;
        font-size: 15px !important;
        font-weight: 800 !important;
        color: #1E293B !important;
    }

    #jurnify-user-edit .profile-info p {
        margin: 4px 0 0 !important;
        font-size: 12.5px !important;
        color: #64748B !important;
        font-weight: 500 !important;
    }

    #jurnify-user-edit .section-divider {
        height: 1px !important;
        background: #E2E8F0 !important;
    }

    /* FORM BODY */
    #jurnify-user-edit .card-body-box {
        padding: 32px !important;
    }

    #jurnify-user-edit .input-grid {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 24px !important;
        width: 100% !important;
    }

    #jurnify-user-edit .field-group {
        display: flex !important;
        flex-direction: column !important;
        gap: 6px !important;
        width: 100% !important;
        min-width: 0 !important;
    }

    #jurnify-user-edit .field-group.full-width {
        grid-column: span 2 !important;
    }

    #jurnify-user-edit .field-label {
        font-size: 13.5px !important;
        font-weight: 700 !important;
        color: #334155 !important;
    }

    #jurnify-user-edit .req {
        color: #EF4444 !important;
    }

    /* INPUT & SELECT */
    #jurnify-user-edit .custom-input,
    #jurnify-user-edit .custom-select {
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

    #jurnify-user-edit .custom-input:hover,
    #jurnify-user-edit .custom-select:hover {
        border-color: #AEB6C5 !important;
    }

    #jurnify-user-edit .custom-input:focus,
    #jurnify-user-edit .custom-select:focus {
        border-color: #7886C7 !important;
        box-shadow: 0 0 0 4px rgba(120, 134, 199, 0.15) !important;
    }

    #jurnify-user-edit .custom-input.is-invalid,
    #jurnify-user-edit .custom-select.is-invalid {
        border-color: #DC2626 !important;
    }

    #jurnify-user-edit .custom-input::placeholder {
        color: #94A3B8 !important;
    }

    /* PASSWORD */
    #jurnify-user-edit .pw-input-wrapper {
        position: relative !important;
        width: 100% !important;
    }

    #jurnify-user-edit .pw-input-wrapper .custom-input {
        padding-right: 48px !important;
    }

    #jurnify-user-edit .btn-toggle-eye {
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

    #jurnify-user-edit .btn-toggle-eye:hover {
        background: #F1F5F9 !important;
        color: #2D336B !important;
    }

    #jurnify-user-edit .btn-toggle-eye svg {
        width: 19px !important;
        height: 19px !important;
    }

    /* HINT & ERROR */
    #jurnify-user-edit .field-hint {
        font-size: 12.5px !important;
        color: #64748B !important;
        font-weight: 500 !important;
        line-height: 1.4 !important;
    }

    #jurnify-user-edit .err-text {
        font-size: 12.5px !important;
        color: #DC2626 !important;
        font-weight: 700 !important;
        line-height: 1.4 !important;
    }

    /* INFO */
    #jurnify-user-edit .info-note-card {
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

    #jurnify-user-edit .icon-info {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        color: #4F46E5 !important;
    }

    #jurnify-user-edit .icon-info svg {
        width: 21px !important;
        height: 21px !important;
    }

    /* FOOTER */
    #jurnify-user-edit .card-footer-box {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 12px !important;
        padding: 20px 32px !important;
        background: #FAFBFD !important;
        border-top: 1px solid #E2E8F0 !important;
    }

    #jurnify-user-edit .btn-submit-custom,
    #jurnify-user-edit .btn-cancel-custom {
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

    #jurnify-user-edit .btn-submit-custom {
        border: none !important;
        background: linear-gradient(135deg, #7886C7 0%, #2D336B 100%) !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(45, 51, 107, 0.12) !important;
    }

    #jurnify-user-edit .btn-submit-custom:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 7px 18px rgba(45, 51, 107, 0.20) !important;
    }

    #jurnify-user-edit .btn-cancel-custom {
        border: 1px solid #CBD5E1 !important;
        background: #FFFFFF !important;
        color: #475569 !important;
    }

    #jurnify-user-edit .btn-cancel-custom:hover {
        background: #F1F5F9 !important;
        color: #334155 !important;
        border-color: #CBD5E1 !important;
    }

    #jurnify-user-edit .btn-submit-custom svg {
        width: 18px !important;
        height: 18px !important;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        #jurnify-user-edit {
            max-width: 100% !important;
        }

        #jurnify-user-edit .card-header-box,
        #jurnify-user-edit .profile-box {
            padding: 22px 20px !important;
        }

        #jurnify-user-edit .card-body-box {
            padding: 22px 20px !important;
        }

        #jurnify-user-edit .input-grid {
            grid-template-columns: 1fr !important;
        }

        #jurnify-user-edit .field-group.full-width {
            grid-column: span 1 !important;
        }

        #jurnify-user-edit .card-footer-box {
            padding: 18px 20px !important;
            flex-direction: column-reverse !important;
        }

        #jurnify-user-edit .btn-submit-custom,
        #jurnify-user-edit .btn-cancel-custom {
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

    const waContainer = document.getElementById('waContainer');
    const noWa = document.getElementById('no_wa');

    const form = document.getElementById('editUserForm');

    // Username
    username.addEventListener('keydown', function (event) {
        if (event.key === ' ') {
            event.preventDefault();

            usernameError.innerText =
                'Username tidak boleh menggunakan spasi.';

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

    // Password validation
    password.addEventListener('input', function () {
        if (this.value.length > 0 && this.value.length < 8) {
            passwordError.style.display = 'block';
        } else {
            passwordError.style.display = 'none';
        }
    });

    // Guru field
    function updateGuruField() {
        const needsGuru =
            role.value === 'Guru' ||
            role.value === 'Staff Piket';

        if (needsGuru) {
            guruContainer.style.display = 'flex';
            idGuru.required = true;
        } else {
            guruContainer.style.display = 'none';
            idGuru.required = false;
            idGuru.value = '';
            guruError.style.display = 'none';
        }
    }

    // WhatsApp Kesiswaan
    function updateWaField() {
        const isKesiswaan = role.value === 'Kesiswaan';

        waContainer.style.display =
            isKesiswaan ? 'flex' : 'none';

        noWa.required = isKesiswaan;

        if (!isKesiswaan) {
            noWa.value = '';
        }
    }

    role.addEventListener('change', function () {
        updateGuruField();
        updateWaField();
    });

    updateGuruField();
    updateWaField();

    // Submit validation
    form.addEventListener('submit', function (event) {
        let valid = true;

        if (username.value.trim() === '') {
            usernameError.innerText = 'Username wajib diisi.';
            usernameError.style.display = 'block';
            valid = false;
        }

        // Password hanya divalidasi jika diisi
        if (
            password.value.length > 0 &&
            password.value.length < 8
        ) {
            passwordError.innerText =
                'Password minimal 8 karakter.';

            passwordError.style.display = 'block';
            valid = false;
        }

        if (role.value === '') {
            alert('Silakan pilih Role / Hak Akses terlebih dahulu.');
            valid = false;
        }

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