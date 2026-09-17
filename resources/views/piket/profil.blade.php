@extends('layouts.piket')

@section('title', 'Profil Staff Piket')

@section('content')
<div class="page-header">
    <div>
        <h2>Profil</h2>
        <p>Informasi akun staff piket.</p>
    </div>
</div>

<div class="profile-box">
    <div class="profile-header">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->guru?->nama_guru ?? auth()->user()->nama_user ?? 'U', 0, 1)) }}</div>
        <div>
            <p class="profile-name">{{ auth()->user()->guru?->nama_guru ?? auth()->user()->nama_user ?? '-' }}</p>
            <span class="profile-role">{{ auth()->user()->role ?? '-' }}</span>
        </div>
    </div>

    <div class="profile-list">
        <div class="profile-item">
            <label>Nama</label>
            <div>{{ auth()->user()->guru?->nama_guru ?? auth()->user()->nama_user ?? '-' }}</div>
        </div>

        <div class="profile-item">
            <label>Username</label>
            <div>{{ auth()->user()->username ?? '-' }}</div>
        </div>

        <div class="profile-item">
            <label>Role</label>
            <div>{{ auth()->user()->role ?? '-' }}</div>
        </div>

        <div class="profile-item">
            <label>NIP</label>
            <div>{{ auth()->user()->guru?->nip ?? '-' }}</div>
        </div>
    </div>

    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Anda yakin ingin logout?');">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>
@endsection
