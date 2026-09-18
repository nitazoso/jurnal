@extends('layouts.admin')
@section('title', 'Profil - Jurnify')
@section('page-title', 'Profil')

@section('content')

<div class="profile-page">

    {{-- HERO PROFILE --}}
    <div class="profile-hero">

        <button type="button" class="profile-edit-btn" title="Edit Profil">
            <span class="material-symbols-outlined">edit</span>
        </button>

        <div class="profile-avatar">
            <span class="material-symbols-outlined">person</span>
        </div>

        <h1 class="profile-name">
            {{ auth()->user()->nama_user ?? 'Admin' }}
        </h1>

    </div>

    {{-- PROFILE INFORMATION --}}
    <div class="profile-info-card">

        <div class="profile-info-item">
            <span class="profile-label">Nama Lengkap</span>
            <span class="profile-value">
                {{ auth()->user()->nama_user ?? '-' }}
            </span>
        </div>

        <div class="profile-info-item">
            <span class="profile-label">Email</span>
            <span class="profile-value">
                {{ auth()->user()->email ?? '-' }}
            </span>
        </div>

        <div class="profile-info-item">
            <span class="profile-label">Nomor Telepon</span>
            <span class="profile-value">
                {{ auth()->user()->no_hp ?? '-' }}
            </span>
        </div>

    </div>

    {{-- LOGOUT --}}
    <form action="{{ route('logout') }}" method="POST" class="logout-form" onsubmit="return confirm('Anda yakin ingin logout?');">
        @csrf
        <button type="submit" class="logout-btn">
            <span>Keluar</span>
            <span class="material-symbols-outlined logout-icon">logout</span>
        </button>
    </form> 
</div>

@endsection

@push('styles')
<style>
    .profile-page {
        padding: 10px;
        max-width: 1440px;
        margin: 0 auto;
        animation: profilePageIn 0.55s ease both;
    }

    /* =========================
       HERO
    ========================= */

    .profile-hero {
        position: relative;
        min-height: 307px;
        background: #B4BFE5;
        border-radius: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 32px;
        overflow: hidden;
    }

    .profile-hero::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        top: -130px;
        left: -80px;
        pointer-events: none;
    }

    .profile-edit-btn {
        position: absolute;
        top: 24px;
        right: 24px;
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        background: rgba(120, 134, 199, 0.35);
        color: #30366F;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        transition:
            background 0.25s ease,
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }

    .profile-edit-btn:hover {
        background: rgba(120, 134, 199, 0.55);
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(48, 54, 111, 0.12);
    }

    .profile-edit-btn:active {
        transform: scale(0.94);
    }

    .profile-edit-btn .material-symbols-outlined {
        font-size: 22px;
        transition: transform 0.25s ease;
    }

    .profile-edit-btn:hover .material-symbols-outlined {
        transform: rotate(-8deg);
    }

    /* =========================
       AVATAR
    ========================= */

    .profile-avatar {
        width: 170px;
        height: 170px;
        border-radius: 50%;
        background: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        overflow: hidden;
        position: relative;
        z-index: 1;
        box-shadow: 0 8px 20px rgba(48, 54, 111, 0.08);
        animation: avatarIn 0.7s ease both;
        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease;
    }

    .profile-avatar:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 12px 25px rgba(48, 54, 111, 0.13);
    }

    .profile-avatar .material-symbols-outlined {
        font-size: 82px;
        color: #7886C7;
        transition: transform 0.35s ease;
    }

    .profile-avatar:hover .material-symbols-outlined {
        transform: scale(1.06);
    }

    /* =========================
       NAME
    ========================= */

    .profile-name {
        margin: 0;
        color: #30366F;
        font-size: 36px;
        line-height: 1.2;
        font-weight: 700;
        position: relative;
        z-index: 1;
        animation: nameIn 0.65s ease 0.15s both;
    }

    /* =========================
       INFORMATION CARD
    ========================= */

    .profile-info-card {
        margin-top: 32px;
        background: #FFFFFF;
        border-radius: 14px;
        padding: 15px 32px;
        box-shadow: 0 2px 12px rgba(48, 54, 111, 0.03);
        animation: cardIn 0.65s ease 0.2s both;
        transition:
            box-shadow 0.3s ease,
            transform 0.3s ease;
    }

    .profile-info-card:hover {
        box-shadow: 0 6px 20px rgba(48, 54, 111, 0.06);
    }

    .profile-info-item {
        min-height: 90px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-bottom: 1px solid #E1E4EC;
        position: relative;
        transition: padding-left 0.25s ease;
    }

    .profile-info-item:last-child {
        border-bottom: none;
    }

    .profile-info-item:hover {
        padding-left: 5px;
    }

    .profile-label {
        color: #62708F;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 5px;
        transition: color 0.25s ease;
    }

    .profile-info-item:hover .profile-label {
        color: #7886C7;
    }

    .profile-value {
        color: #30366F;
        font-size: 16px;
        font-weight: 600;
    }

    /* =========================
       LOGOUT
    ========================= */

    .logout-form {
        margin-top: 32px;
        animation: cardIn 0.65s ease 0.3s both;
    }

    .logout-btn {
        width: 100%;
        min-height: 96px;
        border: none;
        border-radius: 14px;
        background: #FFCBA9;
        color: #D93434;
        text-align: left;
        padding: 0 26px;
        font-family: 'Manrope', sans-serif;
        font-size: 19px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition:
            background 0.25s ease,
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }

    .logout-btn:hover {
        background: #FFC09A;
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(217, 52, 52, 0.08);
    }

    .logout-btn:active {
        transform: scale(0.99);
    }

    .logout-icon {
        font-size: 23px;
        opacity: 0;
        transform: translateX(-8px);
        transition:
            opacity 0.25s ease,
            transform 0.25s ease;
    }

    .logout-btn:hover .logout-icon {
        opacity: 1;
        transform: translateX(0);
    }

    /* =========================
       ANIMATIONS
    ========================= */

    @keyframes profilePageIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes avatarIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes nameIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes cardIn {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 768px) {
        .profile-page {
            padding: 24px 20px;
        }

        .profile-hero {
            min-height: 280px;
        }

        .profile-avatar {
            width: 140px;
            height: 140px;
        }

        .profile-name {
            font-size: 30px;
        }

        .profile-info-card {
            padding: 12px 22px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .profile-page,
        .profile-avatar,
        .profile-name,
        .profile-info-card,
        .logout-form {
            animation: none;
        }

        .profile-edit-btn,
        .profile-avatar,
        .profile-info-item,
        .logout-btn,
        .logout-icon {
            transition: none;
        }
    }
</style>
@endpush
>>>>>>> frontend
