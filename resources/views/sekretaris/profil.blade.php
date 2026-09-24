@extends('layouts.sekretaris')

@section('title', 'Profil Sekretaris - Jurnify')
@section('page-title', 'Profil Sekretaris')
@section('page-subtitle', 'Kelola informasi profil akun')

@section('content')

<style>
    .profile-page {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* PROFILE HERO */
    .profile-hero {
        position: relative;
        min-height: 230px;
        padding: 28px 24px;
        background: linear-gradient(135deg, #A9B5DF 0%, #BFC9EA 100%);
        border: 1px solid rgba(255,255,255,.65);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(45,51,107,.06);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        overflow: hidden;
        isolation: isolate;
        transition: transform .25s ease, box-shadow .25s ease;
    }
        top: -90px;
        left: -60px;
        border-radius: 50%;
        background: rgba(255,255,255,.16);
        pointer-events: none;
        z-index: -1;
    }

    .profile-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -100px;
        bottom: -130px;
        border-radius: 50%;
        background: rgba(45,51,107,.06);
        pointer-events: none;
        z-index: -1;
    }

    .profile-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(45,51,107,.09);
    }

    .profile-edit {
        position: absolute;
        top: 18px;
        right: 18px;
        width: 38px;
        height: 38px;
        border: 1px solid rgba(255,255,255,.5);
        border-radius: 50%;
        background: rgba(255,255,255,.2);
        color: #2D336B;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color .2s ease, transform .2s ease, box-shadow .2s ease;
    }

    .profile-edit:hover {
        background: rgba(255,255,255,.8);
        color: #2D336B;
        transform: rotate(-4deg) scale(1.05);
        box-shadow: 0 4px 12px rgba(45,51,107,.1);
    }

    .profile-edit:active {
        transform: scale(.95);
    }

    .profile-edit svg {
        width: 18px;
        height: 18px;
    }

    .profile-avatar {
        position: relative;
        width: 96px;
        height: 96px;
        margin-bottom: 14px;
        border: 4px solid rgba(255,255,255,.85);
        border-radius: 50%;
        background: #0891B2;
        color: rgba(255,255,255,.9);
        box-shadow: 0 6px 16px rgba(45,51,107,.12);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .profile-hero:hover .profile-avatar {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45,51,107,.16);
    }

    .profile-avatar svg {
        width: 48px;
        height: 48px;
        opacity: .4;
        fill: white;
    }

    .profile-name {
        margin: 0 0 8px;
        color: #2D336B;
        font-size: 22px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .profile-role {
        display: inline-flex;
        align-items: center;
        padding: 4px 16px;
        border-radius: 8px;
        background: rgba(255,255,255,.9);
        border: 1px solid rgba(255,255,255,.75);
        color: #4F5D96;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .5px;
        box-shadow: 0 2px 8px rgba(45,51,107,.05);
    }

    /* PERSONAL DETAILS */
    .personal-card {
        padding: 24px;
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        box-shadow: 0 4px 14px rgba(45,51,107,.03);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .personal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45,51,107,.06);
    }

    .personal-field {
        padding: 4px 0;
    }

    .personal-label {
        margin: 0 0 4px;
        color: #94A3B8;
        font-size: 12.5px;
        font-weight: 600;
    }

    .personal-value {
        margin: 0;
        color: #1E293B;
        font-size: 15px;
        font-weight: 700;
        word-break: break-word;
    }

    .personal-divider {
        margin: 14px 0;
        border: 0;
        border-top: 1px solid #F1F5F9;
    }

    /* LOGOUT */
    .logout-wrapper {
        padding-top: 4px;
    }

    .logout-button {
        width: 100%;
        height: 46px;
        border: 1px solid rgba(201,74,43,.12);
        border-radius: 12px;
        background: #FEE2E2;
        color: #991B1B;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
        transition: all .2s ease;
    }

    .logout-button:hover {
        background: #FCA5A5;
        border-color: rgba(201,74,43,.2);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(153,27,27,.12);
    }

    .logout-button:active {
        transform: translateY(0);
    }

    /* ANIMATION */
    .profile-hero,
    .personal-card,
    .logout-wrapper {
        animation: profileFadeUp .4s ease both;
    }

    .personal-card { animation-delay: .05s; }
    .logout-wrapper { animation-delay: .1s; }

    @keyframes profileFadeUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* RESPONSIVE */
    @media (max-width: 767px) {
        .profile-hero {
            min-height: 200px;
            padding: 22px 16px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .profile-avatar svg {
            width: 40px;
            height: 40px;
        }

        .profile-name {
            font-size: 19px;
        }

        .personal-card {
            padding: 18px;
        }

        .personal-value {
            font-size: 14px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .profile-hero,
        .profile-avatar,
        .profile-edit,
        .personal-card,
        .logout-button {
            transition: none;
            animation: none;
        }
    }
</style>

@php
    $user = auth()->user();
    $profileName = $user->nama_user ?? '-';
@endphp

<div class="profile-page">

    {{-- PROFILE HERO --}}
    <section class="profile-hero">

        {{-- Avatar --}}
        <div class="profile-avatar">
            <svg viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
        </div>

        {{-- Name --}}
        <h3 class="profile-name">
            {{ $profileName }}
        </h3>

        {{-- Role --}}
        <span class="profile-role">
            {{ strtoupper($user->role ?? 'SEKRETARIS') }}
        </span>
    </section>

    {{-- PERSONAL DETAILS --}}
    <section class="personal-card">

        {{-- Nama --}}
        <div class="personal-field">
            <p class="personal-label">Nama Lengkap</p>
            <p class="personal-value">{{ $profileName }}</p>
        </div>

        <hr class="personal-divider">

        {{-- Username --}}
        <div class="personal-field">
            <p class="personal-label">Username</p>
            <p class="personal-value">{{ $user->username ?? '-' }}</p>
        </div>

        <hr class="personal-divider">

        {{-- Role --}}
        <div class="personal-field">
            <p class="personal-label">Role</p>
            <p class="personal-value">{{ $user->role ?? 'Sekretaris' }}</p>
        </div>

        <hr class="personal-divider">

    </section>

    {{-- LOGOUT --}}
    <div class="logout-wrapper">
        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Anda yakin ingin keluar?');">
            @csrf
            <button class="logout-button" type="submit">Keluar</button>
        </form>
    </div>

</div>

@endsection