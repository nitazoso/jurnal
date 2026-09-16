@extends('layouts.kesiswaan')

@section('title', 'Profil Kesiswaan - Jurnify')
@section('page-title', 'Profil Kesiswaan')

@section('content')
<div class="card">
    <h3>Profil Pengguna</h3>
    <p><strong>Nama:</strong> {{ auth()->user()->nama_user ?? '-' }}</p>
    <p><strong>Username:</strong> {{ auth()->user()->username ?? '-' }}</p>
    <p><strong>Role:</strong> {{ auth()->user()->role ?? '-' }}</p>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;" onsubmit="return confirm('Anda yakin ingin logout?');">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@endsection
