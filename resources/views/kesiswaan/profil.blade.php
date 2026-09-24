@extends('layouts.kesiswaan')

@section('title', 'Profil Kesiswaan - Jurnify')
@section('page-title', 'Profil Kesiswaan')

@section('content')
<div class="card profile-card">
    <h3>Profil Pengguna</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-summary">
        <p><strong>Nama:</strong> {{ auth()->user()->nama_user ?? '-' }}</p>
        <p><strong>Role:</strong> {{ auth()->user()->role ?? '-' }}</p>
    </div>

    <h4>Edit Profil</h4>
    <form method="POST" action="{{ route('kesiswaan.profil.update') }}" class="profile-form">
        @csrf
        @method('PUT')

        <label for="username">Username</label>
        <input id="username" name="username" type="text" value="{{ old('username', auth()->user()->username) }}" required>

        <label for="password">Password Baru</label>
        <input id="password" name="password" type="password" placeholder="Kosongkan jika tidak diubah">

        <label for="password_confirmation">Konfirmasi Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password baru">

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;" onsubmit="return confirm('Anda yakin ingin logout?');">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>

<style>
    .profile-card { max-width: 680px; }
    .profile-card h4 { margin: 28px 0 16px; }
    .profile-summary { border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; }
    .profile-form { display: grid; gap: 8px; }
    .profile-form label { font-weight: 600; margin-top: 8px; }
    .profile-form input { border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; }
    .profile-form button { border: 0; border-radius: 6px; cursor: pointer; margin-top: 12px; padding: 10px 14px; }
    .btn-primary { background: #2563eb; color: #fff; }
    .btn-danger { background: #b91c1c; color: #fff; border: 0; border-radius: 6px; cursor: pointer; padding: 10px 14px; }
    .alert { border-radius: 6px; margin: 12px 0; padding: 12px; }
    .alert-success { background: #dcfce7; color: #166534; }
    .alert-error { background: #fee2e2; color: #991b1b; }
    .alert-error ul { margin: 0; padding-left: 20px; }
</style>
@endsection
