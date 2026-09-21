@extends('layouts.admin')

@section('title', 'Edit User - Jurnify')
@section('page-title', 'Edit Data User')

@section('content')

<div class="edit-page">
    @if ($errors->any())
        <div class="validation-alert">
            <strong>Data belum dapat disimpan.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="breadcrumb">
        <a href="{{ route('admin.user.index') }}">Manajemen User</a>
        <span class="material-symbols-outlined">chevron_right</span>
        <span>Edit User</span>
    </div>

    <section class="edit-card">
        <div class="edit-header">
            <div class="edit-header-info">
                <h3>Perbarui Informasi Pengguna</h3>
                <p>Ubah informasi akun pengguna dan penugasan role.</p>
            </div>
        </div>

        <div class="edit-body">
            <div class="profile-section">
                <div class="avatar-placeholder">
                    {{ strtoupper(substr($user->nama_user, 0, 1)) }}
                </div>
                <div class="avatar-info">
                    <h4>{{ $user->nama_user }}</h4>
                    <p>Akun pengguna Jurnify</p>
                </div>
            </div>

            <form id="editUserForm" action="{{ route('admin.user.update', $user->id_user) }}" method="POST" autocomplete="off" data-form-type="other">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    {{-- USERNAME --}}
                    <div class="form-group">
                        <label class="form-label" for="username">
                            Username <span class="required">*</span>
                        </label>
                        <input type="text" id="username" name="username"
                            class="form-control @error('username') error @enderror"
                            value="{{ old('username', $user->username) }}"
                            autocomplete="new-password"
                            autocapitalize="none"
                            autocorrect="off"
                            spellcheck="false"
                            data-lpignore="true"
                            data-1p-ignore="true"
                            data-protonpass-ignore="true"
                            required>
                        <div id="usernameError" class="error-message" style="display:none;">
                            Username tidak boleh menggunakan spasi.
                        </div>
                        @error('username')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <span class="form-hint">Username digunakan untuk login. Tidak boleh menggunakan spasi.</span>
                    </div>

                    {{-- NAMA USER --}}
                    <div class="form-group">
                        <label class="form-label" for="nama_user">
                            Nama Lengkap & Gelar <span class="required">*</span>
                        </label>
                        <input type="text" id="nama_user" name="nama_user"
                            class="form-control @error('nama_user') error @enderror"
                            value="{{ old('nama_user', $user->nama_user) }}"
                            autocomplete="off"
                            required>
                        @error('nama_user')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <span class="form-hint">Nama lengkap pemilik akun.</span>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-group">
                        <label class="form-label" for="password">Password Baru</label>
                        <div style="position:relative;">
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') error @enderror"
                                placeholder="Kosongkan jika tidak ingin mengubah"
                                autocomplete="new-password"
                                autocapitalize="none"
                                autocorrect="off"
                                spellcheck="false"
                                data-lpignore="true"
                                data-1p-ignore="true"
                                data-protonpass-ignore="true"
                                style="padding-right:45px;">
                            <button type="button"
                                id="togglePassword"
                                title="Tampilkan password"
                                aria-label="Tampilkan password"
                                style="position:absolute; right:5px; top:50%; transform:translateY(-50%); border:none; background:transparent; cursor:pointer; font-size:17px; padding:5px;">
                                👁️
                            </button>
                        </div>
                        <div id="passwordError" class="error-message" style="display:none;">
                            Password minimal 8 karakter.
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <span class="form-hint">Kosongkan jika password tidak ingin diubah. Jika diisi, minimal 8 karakter.</span>
                    </div>

                    {{-- ROLE --}}
                    <div class="form-group">
                        <label class="form-label" for="role">
                            Role / Hak Akses <span class="required">*</span>
                        </label>
                        <select id="role" name="role"
                            class="form-control @error('role') error @enderror"
                            required>
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
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <span class="form-hint">Role menentukan hak akses pengguna.</span>
                    </div>

                    {{-- WHATSAPP KESISWAAN --}}
                    <div class="form-group" id="waContainer" style="display:none;">
                        <label class="form-label" for="no_wa">Nomor WhatsApp Kesiswaan</label>
                        <input type="text" name="no_wa" id="no_wa"
                            value="{{ old('no_wa', $user->no_wa) }}"
                            placeholder="Contoh: 628123456789"
                            inputmode="tel"
                            class="form-control">
                        <span class="form-hint">Digunakan untuk notifikasi dan tautan WhatsApp pengajuan dispen.</span>
                    </div>

                    {{-- DATA GURU --}}
                    <div class="form-group full-width" id="guruContainer" style="display:none;">
                        <label class="form-label" for="id_guru">Data Guru</label>
                        <select id="id_guru" name="id_guru"
                            class="form-control @error('id_guru') error @enderror">
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id_guru }}"
                                    {{ old('id_guru', $user->id_guru) == $guru->id_guru ? 'selected' : '' }}>
                                    {{ $guru->nama_guru }}
                                    @if ($guru->nip)
                                        - NIP {{ $guru->nip }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div id="guruError" class="error-message" style="display:none;">
                            Silakan pilih data Guru.
                        </div>
                        @error('id_guru')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <span class="form-hint">Hubungkan akun Guru atau Staff Piket dengan data guru yang sudah terdaftar.</span>
                    </div>

                    {{-- DATA KELAS --}}
                    <div class="form-group full-width">
                        <label class="form-label" for="id_kelas">Data Kelas</label>
                        <select id="id_kelas" name="id_kelas"
                            class="form-control @error('id_kelas') error @enderror">
                            <option value="">Tidak terhubung ke kelas</option>
                            @foreach ($kelases as $kelas)
                                <option value="{{ $kelas->id_kelas }}"
                                    {{ old('id_kelas', $user->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        <span class="form-hint">Pilih kelas jika akun ini memiliki keterkaitan dengan kelas tertentu.</span>
                        @error('id_kelas')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </form>
        </div>

        <div class="edit-footer">
            <a href="{{ route('admin.user.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" form="editUserForm" class="btn-save">
                Simpan Perubahan
            </button>
        </div>
    </section>
</div>

@endsection

@push('styles')
<style>
    .edit-page {
        max-width: 1080px;
        animation: pageFadeIn .45s ease both;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #70737d;
        margin-bottom: 24px;
        animation: fadeDown .4s ease both;
    }

    .breadcrumb a {
        color: #4169ff;
        text-decoration: none;
        font-weight: 600;
        transition: color .2s;
    }

    .breadcrumb a:hover {
        color: #2d336b;
        text-decoration: underline;
    }

    .breadcrumb .material-symbols-outlined {
        font-size: 16px;
        transition: transform .2s;
    }

    .breadcrumb:hover .material-symbols-outlined {
        transform: translateX(2px);
    }

    .edit-card {
        background: #fff;
        border-radius: 9px;
        box-shadow: 0 1px 4px rgba(0,0,0,.025);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        animation: cardUp .5s ease .08s both;
        transition: box-shadow .25s, transform .25s;
    }

    .edit-card:hover {
        box-shadow: 0 5px 18px rgba(29,44,103,.07);
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
        animation: fadeDown .5s ease .15s both;
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
        flex-shrink: 0;
        transition: transform .25s, box-shadow .25s;
    }

    .avatar-placeholder:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 12px rgba(45,51,107,.12);
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

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        animation: fieldUp .45s ease both;
    }

    .form-group:nth-child(1) { animation-delay: .18s; }
    .form-group:nth-child(2) { animation-delay: .22s; }
    .form-group:nth-child(3) { animation-delay: .26s; }
    .form-group:nth-child(4) { animation-delay: .30s; }
    .form-group:nth-child(5) { animation-delay: .34s; }
    .form-group:nth-child(6) { animation-delay: .38s; }

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
        width: 100%;
        height: 44px;
        padding: 0 14px;
        background: #fbfbfb;
        border: 1px solid #cfd2dc;
        border-radius: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
        color: #1f2937;
        transition: border-color .2s, box-shadow .2s, background .2s, transform .2s;
        outline: none;
        box-sizing: border-box;
    }

    .form-control:hover {
        border-color: #aeb4c4;
        background: #fff;
    }

    .form-control:focus {
        background: #fff;
        border-color: #4169ff;
        box-shadow: 0 0 0 3px rgba(65,105,255,.12);
        transform: translateY(-1px);
    }

    select.form-control {
        cursor: pointer;
    }

    .form-hint {
        font-size: 12px;
        color: #858891;
        transition: color .2s;
    }

    .form-group:focus-within .form-hint {
        color: #626a84;
    }

    .error-message {
        font-size: 12px;
        color: #d00000;
        margin-top: -2px;
        animation: errorIn .3s ease both;
    }

    .form-control.error {
        border-color: #e00000;
    }

    .form-control.error:focus {
        box-shadow: 0 0 0 3px rgba(224,0,0,.1);
    }

    .validation-alert {
        margin-bottom: 20px;
        padding: 14px 16px;
        background: #ffd9d5;
        color: #a9211d;
        border: 1px solid #f4b8b3;
        border-radius: 8px;
        font-size: 13px;
        animation: fadeDown .4s ease both;
    }

    .validation-alert strong {
        display: block;
        margin-bottom: 5px;
    }

    .validation-alert ul {
        margin-left: 18px;
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
        transition: background .2s, border-color .2s, transform .2s;
    }

    .btn-cancel:hover {
        background: #f1f2f5;
        border-color: #b9bdc8;
        transform: translateY(-1px);
    }

    .btn-cancel:active,
    .btn-save:active {
        transform: translateY(0);
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
        transition: background .2s, transform .2s, box-shadow .2s;
    }

    .btn-save:hover {
        background: #1e2450;
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(45,51,107,.2);
    }

    .btn-save .material-symbols-outlined {
        font-size: 18px;
        transition: transform .2s;
    }

    .btn-save:hover .material-symbols-outlined {
        transform: translateX(2px);
    }

    @keyframes pageFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
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
            transform: translateX(-4px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @media (max-width: 800px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: span 1;
        }

        .edit-body {
            padding: 20px;
        }

        .edit-header {
            padding: 20px;
        }

        .edit-footer {
            padding: 18px 20px;
        }
    }

    @media (max-width: 600px) {
        .breadcrumb {
            margin-bottom: 18px;
        }

        .edit-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .profile-section {
            align-items: flex-start;
        }

        .edit-footer {
            flex-direction: column;
            width: 100%;
        }

        .btn-cancel,
        .btn-save {
            width: 100%;
            justify-content: center;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .edit-page,
        .breadcrumb,
        .edit-card,
        .profile-section,
        .form-group,
        .validation-alert {
            animation: none;
        }

        * {
            transition: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const username = document.getElementById('username');
    const usernameError = document.getElementById('usernameError');
    const password = document.getElementById('password');
    const passwordError = document.getElementById('passwordError');
    const togglePassword = document.getElementById('togglePassword');
    const role = document.getElementById('role');
    const guruContainer = document.getElementById('guruContainer');
    const idGuru = document.getElementById('id_guru');
    const waContainer = document.getElementById('waContainer');
    const noWa = document.getElementById('no_wa');
    const guruError = document.getElementById('guruError');
    const form = document.getElementById('editUserForm');

    username.addEventListener('keydown', function(event) {
        if (event.key === ' ') {
            event.preventDefault();
            usernameError.innerText = 'Username tidak boleh menggunakan spasi.';
            usernameError.style.display = 'block';
        }
    });

    username.addEventListener('input', function() {
        if (/\s/.test(this.value)) {
            this.value = this.value.replace(/\s/g, '');
            usernameError.innerText = 'Spasi otomatis dihapus.';
            usernameError.style.display = 'block';
        }

        this.value = this.value.toLowerCase();
    });

    username.addEventListener('blur', function() {
        if (!/\s/.test(this.value)) {
            usernameError.style.display = 'none';
        }
    });

    togglePassword.addEventListener('click', function() {
        if (password.type === 'password') {
            password.type = 'text';
            this.innerText = '🙈';
            this.title = 'Sembunyikan password';
            this.setAttribute('aria-label', 'Sembunyikan password');
        } else {
            password.type = 'password';
            this.innerText = '👁️';
            this.title = 'Tampilkan password';
            this.setAttribute('aria-label', 'Tampilkan password');
        }
    });

    password.addEventListener('input', function() {
        if (this.value.length > 0 && this.value.length < 8) {
            passwordError.style.display = 'block';
        } else {
            passwordError.style.display = 'none';
        }
    });

    function updateGuruField() {
        if (role.value === 'Guru' || role.value === 'Staff Piket') {
            guruContainer.style.display = 'flex'; 
            idGuru.required = true;
        } else {
            guruContainer.style.display = 'none';
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

    form.addEventListener('submit', function(event) {
        let valid = true;

        if (username.value.trim() === '') {
            usernameError.innerText = 'Username wajib diisi.';
            usernameError.style.display = 'block';
            valid = false;
        }

        if (password.value.length > 0 && password.value.length < 8) {
            passwordError.innerText = 'Password minimal 8 karakter.';
            passwordError.style.display = 'block';
            valid = false;
        }

        if (role.value === '') {
            alert('Silakan pilih Role / Hak Akses terlebih dahulu.');
            valid = false;
        }

        // Guru dan Staff Piket wajib terhubung ke data guru
        if ((role.value === 'Guru' || role.value === 'Staff Piket') && idGuru.value === '') {
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