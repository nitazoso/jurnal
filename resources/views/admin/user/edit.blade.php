@extends('layouts.admin')

@section('title', 'Edit User - Jurnify')
@section('page-title', 'Edit User')

@section('content')

<h2>Edit User</h2>

<p>
    Perbarui data akun pengguna Jurnify.
</p>

<hr>

@if ($errors->any())
    <div style="
        background:#ffe5e5;
        color:#b42318;
        padding:12px 15px;
        margin-bottom:20px;
        border-radius:6px;
    ">
        <strong>Terjadi kesalahan:</strong>

        <ul style="margin:8px 0 0 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form
    action="{{ route('admin.user.update', $user->id_user) }}"
    method="POST"
    autocomplete="off"
    data-form-type="other"
>

    @csrf
    @method('PUT')


    {{-- USERNAME --}}

    <div style="margin-bottom:20px;">

        <label for="username">
            <strong>Username</strong>
        </label>

        <br>

        <input
            type="text"
            name="username"
            id="username"
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

        <div
            id="usernameError"
            style="
                color:#d92d20;
                font-size:13px;
                display:none;
                margin-top:5px;
            "
        >
            Username tidak boleh menggunakan spasi.
        </div>

        <div style="
            color:#666;
            font-size:12px;
            margin-top:5px;
        ">
            Username digunakan untuk login.
            Tidak boleh menggunakan spasi.
        </div>

    </div>



    {{-- NAMA USER --}}

    <div style="margin-bottom:20px;">

        <label for="nama_user">
            <strong>Nama User</strong>
        </label>

        <br>

        <input
            type="text"
            name="nama_user"
            id="nama_user"
            value="{{ old('nama_user', $user->nama_user) }}"
            autocomplete="off"
            required
        >

        <div style="
            color:#666;
            font-size:12px;
            margin-top:5px;
        ">
            Nama lengkap pemilik akun.
        </div>

    </div>



    {{-- PASSWORD --}}

    <div style="margin-bottom:20px;">

        <label for="password">
            <strong>Password Baru</strong>
        </label>

        <br>

        <div style="
            position:relative;
            display:inline-block;
        ">

            <input
                type="password"
                name="password"
                id="password"
                placeholder="Kosongkan jika tidak ingin mengubah"
                autocomplete="new-password"
                autocapitalize="none"
                autocorrect="off"
                spellcheck="false"
                data-lpignore="true"
                data-1p-ignore="true"
                data-protonpass-ignore="true"
                style="padding-right:45px;"
            >

            <button
                type="button"
                id="togglePassword"
                title="Tampilkan password"
                aria-label="Tampilkan password"
                style="
                    position:absolute;
                    right:5px;
                    top:50%;
                    transform:translateY(-50%);
                    border:none;
                    background:transparent;
                    cursor:pointer;
                    font-size:17px;
                    padding:5px;
                "
            >
                👁️
            </button>

        </div>

        <div
            id="passwordError"
            style="
                color:#d92d20;
                font-size:13px;
                display:none;
                margin-top:5px;
            "
        >
            Password minimal 6 karakter.
        </div>

        <div style="
            color:#666;
            font-size:12px;
            margin-top:5px;
        ">
            Kosongkan jika password tidak ingin diubah.
        </div>

    </div>



    {{-- ROLE --}}

    <div style="margin-bottom:20px;">

        <label for="role">
            <strong>Role / Hak Akses</strong>
        </label>

        <br>

        <select
            name="role"
            id="role"
            required
        >

            <option value="">
                -- Pilih Role --
            </option>

            <option
                value="Admin"
                {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}
            >
                Admin
            </option>

            <option
                value="Guru"
                {{ old('role', $user->role) == 'Guru' ? 'selected' : '' }}
            >
                Guru
            </option>

            <option
                value="Sekretaris"
                {{ old('role', $user->role) == 'Sekretaris' ? 'selected' : '' }}
            >
                Sekretaris
            </option>

            <option
                value="Staff Piket"
                {{ old('role', $user->role) == 'Staff Piket' ? 'selected' : '' }}
            >
                Staff Piket
            </option>

        </select>

        <div style="
            color:#666;
            font-size:12px;
            margin-top:5px;
        ">
            Role menentukan hak akses pengguna.
        </div>

    </div>



    {{-- DATA GURU --}}

    <div
        id="guruContainer"
        style="
            display:none;
            margin-bottom:20px;
        "
    >

        <label for="id_guru">
            <strong>Data Guru</strong>
        </label>

        <br>

        <select
            name="id_guru"
            id="id_guru"
        >

            <option value="">
                -- Pilih Guru --
            </option>

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

        <div
            id="guruError"
            style="
                color:#d92d20;
                font-size:13px;
                display:none;
                margin-top:5px;
            "
        >
            Silakan pilih data Guru.
        </div>

        <div style="
            color:#666;
            font-size:12px;
            margin-top:5px;
        ">
            Hubungkan akun dengan data guru yang sudah terdaftar.
        </div>

    </div>



    {{-- BUTTON --}}

    <div style="margin-top:25px;">

        <button
            type="submit"
            id="submitButton"
        >
            Simpan Perubahan
        </button>

        <a
            href="{{ route('admin.user.index') }}"
            style="margin-left:10px;"
        >
            Batal
        </a>

    </div>

</form>



<script>

    // ==========================================
    // ELEMENT
    // ==========================================

    const username =
        document.getElementById('username');

    const usernameError =
        document.getElementById('usernameError');

    const password =
        document.getElementById('password');

    const passwordError =
        document.getElementById('passwordError');

    const togglePassword =
        document.getElementById('togglePassword');

    const role =
        document.getElementById('role');

    const guruContainer =
        document.getElementById('guruContainer');

    const idGuru =
        document.getElementById('id_guru');

    const guruError =
        document.getElementById('guruError');

    const form =
        document.querySelector('form');



    // ==========================================
    // USERNAME
    // ==========================================

    username.addEventListener('keydown', function(event) {

        if (event.key === ' ') {

            event.preventDefault();

            usernameError.innerText =
                'Username tidak boleh menggunakan spasi.';

            usernameError.style.display =
                'block';

        }

    });


    username.addEventListener('input', function() {

        if (/\s/.test(this.value)) {

            this.value =
                this.value.replace(/\s/g, '');

            usernameError.innerText =
                'Spasi otomatis dihapus.';

            usernameError.style.display =
                'block';

        }

        this.value =
            this.value.toLowerCase();

    });


    username.addEventListener('blur', function() {

        if (!/\s/.test(this.value)) {

            usernameError.style.display =
                'none';

        }

    });



    // ==========================================
    // SHOW / HIDE PASSWORD
    // ==========================================

    togglePassword.addEventListener('click', function() {

        if (password.type === 'password') {

            password.type = 'text';

            this.innerText = '🙈';

            this.title =
                'Sembunyikan password';

        } else {

            password.type = 'password';

            this.innerText = '👁️';

            this.title =
                'Tampilkan password';

        }

    });



    // ==========================================
    // PASSWORD VALIDATION
    // ==========================================

    password.addEventListener('input', function() {

        if (
            this.value.length > 0 &&
            this.value.length < 6
        ) {

            passwordError.style.display =
                'block';

        } else {

            passwordError.style.display =
                'none';

        }

    });



    // ==========================================
    // ROLE → GURU
    // ==========================================

    function updateGuruField() {

        if (role.value === 'Guru') {

            guruContainer.style.display =
                'block';

            idGuru.required =
                true;

        } else {

            guruContainer.style.display =
                'none';

            idGuru.required =
                false;

            idGuru.value =
                '';

            guruError.style.display =
                'none';

        }

    }


    role.addEventListener(
        'change',
        updateGuruField
    );


    // Jalankan saat halaman dibuka
    updateGuruField();



    // ==========================================
    // VALIDATION
    // ==========================================

    form.addEventListener('submit', function(event) {

        let valid = true;


        // Username
        if (username.value.trim() === '') {

            usernameError.innerText =
                'Username wajib diisi.';

            usernameError.style.display =
                'block';

            valid = false;

        }


        // Password
        // Hanya dicek jika diisi
        if (
            password.value.length > 0 &&
            password.value.length < 6
        ) {

            passwordError.style.display =
                'block';

            valid = false;

        }


        // Role
        if (role.value === '') {

            alert(
                'Silakan pilih Role / Hak Akses terlebih dahulu.'
            );

            valid = false;

        }


        // Guru
        if (
            role.value === 'Guru' &&
            idGuru.value === ''
        ) {

            guruError.style.display =
                'block';

            valid = false;

        }


        if (!valid) {

            event.preventDefault();

        }

    });

</script>

@endsection