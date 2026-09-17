@extends('layouts.sekretaris')
@section('title', 'Profil Sekretaris')
@section('header', 'Profil')
@section('content')
<div class="page-heading"><h2>Profil Saya</h2><p>Informasi akun sekretaris.</p></div>
<div class="card profile-card"><div class="profile-hero"><span class="avatar">{{ auth()->user()?->initials() ?: 'SK' }}</span><div><h3>{{ auth()->user()->nama_user ?? 'Sekretaris' }}</h3><p><span class="badge info">Sekretaris</span></p></div></div><div class="detail"><span>Nama lengkap</span><strong>{{ auth()->user()->nama_user ?? '-' }}</strong></div><div class="detail"><span>Username</span><strong>{{ auth()->user()->username ?? '-' }}</strong></div><div class="detail"><span>Role</span><strong>{{ auth()->user()->role ?? 'Sekretaris' }}</strong></div><form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Anda yakin ingin logout?');">@csrf<button class="button logout" type="submit">↪ Keluar dari akun</button></form></div>
@endsection
