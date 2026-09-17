@extends('layouts.guru')
@section('title', 'Profil Guru - Jurnify')
@section('page-title', 'Profil')
@section('page-subtitle', 'Informasi Data Diri & Akun Pendidik')
@section('content')

<style>
    .profile-page {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* PROFILE HERO */
    .profile-hero {
        position: relative;
        min-height: 300px;
        background: linear-gradient(135deg, #A9B5DF 0%, #BFC9EA 100%);
        border: 1px solid rgba(255, 255, 255, 0.65);
        border-radius: 24px;
        box-shadow: 0 8px 24px rgba(45, 51, 107, 0.08);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        overflow: hidden;
        isolation: isolate;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .profile-hero::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        top: -110px;
        left: -70px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.16);
        pointer-events: none;
        z-index: -1;
    }

    .profile-hero::after {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        right: -130px;
        bottom: -170px;
        border-radius: 50%;
        background: rgba(45, 51, 107, 0.07);
        pointer-events: none;
        z-index: -1;
    }

    .profile-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 32px rgba(45, 51, 107, 0.11);
    }

    .profile-edit {
        position: absolute;
        top: 24px;
        right: 24px;
        width: 40px;
        height: 40px;
        padding: 8px;
        border: 1px solid rgba(255, 255, 255, 0.45);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        color: rgba(45, 51, 107, 0.8);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition:
            background-color 0.2s ease,
            color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .profile-edit:hover {
        background: rgba(255, 255, 255, 0.6);
        color: #2D336B;
        transform: rotate(-4deg) scale(1.06);
        box-shadow: 0 5px 14px rgba(45, 51, 107, 0.1);
    }

    .profile-edit:active {
        transform: scale(0.96);
    }

    .profile-edit svg {
        width: 24px;
        height: 24px;
    }

    .profile-avatar {
        position: relative;
        width: 128px;
        height: 128px;
        margin-bottom: 20px;
        border: 5px solid rgba(255, 255, 255, 0.72);
        border-radius: 999px;
        background: #0891B2;
        color: rgba(255, 255, 255, 0.9);
        box-shadow:
            0 8px 20px rgba(45, 51, 107, 0.14),
            0 0 0 8px rgba(255, 255, 255, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease;
    }

    .profile-avatar::after {
        content: "";
        position: absolute;
        inset: -8px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: inherit;
        pointer-events: none;
    }

    .profile-hero:hover .profile-avatar {
        transform: translateY(-3px);
        box-shadow:
            0 12px 26px rgba(45, 51, 107, 0.18),
            0 0 0 8px rgba(255, 255, 255, 0.14);
    }

    .profile-avatar svg {
        width: 64px;
        height: 64px;
        opacity: 0.3;
        fill: white;
    }

    .profile-name {
        margin: 0 0 12px;
        color: #2D336B;
        font-size: 30px;
        line-height: 1.3;
        font-weight: 700;
        letter-spacing: -0.5px;
        text-shadow: 0 1px 1px rgba(255, 255, 255, 0.2);
    }

    .profile-role {
        display: inline-flex;
        align-items: center;
        padding: 6px 24px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.75);
        color: #4F5D96;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(45, 51, 107, 0.08);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .profile-role:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(45, 51, 107, 0.11);
    }

    /* PERSONAL DETAILS */
    .personal-card {
        padding: 32px;
        background: white;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 18px;
        box-shadow: 0 4px 14px rgba(45, 51, 107, 0.045);
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            border-color 0.25s ease;
    }

    .personal-card:hover {
        transform: translateY(-2px);
        border-color: rgba(169, 181, 223, 0.65);
        box-shadow: 0 10px 24px rgba(45, 51, 107, 0.07);
    }

    .personal-field {
        padding: 8px 0;
        transition: transform 0.2s ease;
    }

    .personal-field:hover {
        transform: translateX(3px);
    }

    .personal-label {
        margin: 0 0 6px;
        color: #9CA3AF;
        font-size: 13px;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .personal-field:hover .personal-label {
        color: #7886C7;
    }

    .personal-value {
        margin: 0;
        color: #2D336B;
        font-size: 17px;
        font-weight: 700;
        word-break: break-word;
    }

    .personal-divider {
        margin: 20px 0;
        border: 0;
        border-top: 1px solid #F1F3F7;
    }

    /* LOGOUT */
    .logout-wrapper {
        padding-top: 0;
    }

    .logout-button {
        width: 100%;
        padding: 15px 20px;
        border: 1px solid rgba(201, 74, 43, 0.08);
        border-radius: 16px;
        background: #FED0B5;
        color: #C94A2B;
        font-family: 'Manrope', sans-serif;
        font-size: 15px;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
        transition:
            background-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
    }

    .logout-button:hover {
        background: #FDC2A0;
        border-color: rgba(201, 74, 43, 0.14);
        transform: translateY(-2px);
        box-shadow: 0 7px 16px rgba(201, 74, 43, 0.1);
    }

    .logout-button:active {
        transform: translateY(0) scale(0.99);
        box-shadow: none;
    }

    .profile-hero,
    .personal-card,
    .logout-wrapper {
        animation: profileFadeUp 0.45s ease both;
    }

    .personal-card {
        animation-delay: 0.06s;
    }

    .logout-wrapper {
        animation-delay: 0.12s;
    }

    @keyframes profileFadeUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (min-width: 768px) and (max-width: 1100px) {
        .profile-page {
            padding: 0;
        }

        .profile-hero {
            padding: 32px;
        }
    }

    @media (max-width: 767px) {
        .profile-page {
            max-width: none;
            padding: 0 0 32px;
            gap: 16px;
        }

        .profile-hero {
            min-height: 270px;
            padding: 30px 20px 28px;
            border-radius: 20px;
        }

        .profile-edit {
            top: 14px;
            right: 14px;
        }

        .profile-edit svg {
            width: 20px;
            height: 20px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            margin-bottom: 16px;
            border-width: 4px;
        }

        .profile-avatar svg {
            width: 52px;
            height: 52px;
        }

        .profile-name {
            margin-bottom: 12px;
            font-size: 23px;
        }

        .profile-role {
            padding: 7px 18px;
            border-radius: 10px;
            font-size: 11px;
        }

        .personal-card {
            padding: 22px 20px;
            border-radius: 16px;
        }

        .personal-field {
            padding: 3px 0;
        }

        .personal-label {
            margin-bottom: 6px;
            font-size: 12px;
        }

        .personal-value {
            font-size: 15px;
        }

        .personal-divider {
            margin: 18px 0;
        }

        .logout-button {
            padding: 14px;
            border-radius: 14px;
            font-size: 14px;
        }
    }

    @media (max-width: 420px) {
        .profile-hero {
            min-height: 255px;
            padding: 26px 16px 24px;
        }

        .profile-avatar {
            width: 92px;
            height: 92px;
        }

        .profile-avatar svg {
            width: 48px;
            height: 48px;
        }

        .profile-name {
            font-size: 21px;
        }

        .personal-card {
            padding: 20px 18px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .profile-hero,
        .profile-avatar,
        .profile-edit,
        .profile-role,
        .personal-card,
        .personal-field,
        .logout-button {
            transition: none;
            animation: none;
        }
    }
</style>

@php
    $user = auth()->user();
@endphp

<div class="profile-page">

    {{-- PROFILE HERO --}}
    <section class="profile-hero">

        {{-- Edit --}}
        <button
            class="profile-edit"
            type="button"
            aria-label="Edit Profil"
            title="Edit Profil"
        >
            <svg
                fill="none"
                stroke="currentColor"
                stroke-width="2.2"
                viewBox="0 0 24 24"
            >
                <path
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </button>

        {{-- Avatar --}}
        <div class="profile-avatar">
            <svg viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
        </div>

        {{-- Name --}}
        <h3 class="profile-name">
            {{ $user->nama_user ?? '-' }}
        </h3>

        {{-- Role --}}
        <span class="profile-role">
            {{ strtoupper($user->role ?? 'GURU') }}
        </span>

    </section>

    {{-- PERSONAL DETAILS --}}
    <section class="personal-card">

        {{-- Nama --}}
        <div class="personal-field">
            <p class="personal-label">
                Nama Lengkap
            </p>

            <p class="personal-value">
                {{ $user->nama_user ?? '-' }}
            </p>
        </div>

        <hr class="personal-divider">

        {{-- Username --}}
        <div class="personal-field">
            <p class="personal-label">
                Username
            </p>

            <p class="personal-value">
                {{ $user->username ?? '-' }}
            </p>
        </div>

        <hr class="personal-divider">

        {{-- Role --}}
        <div class="personal-field">
            <p class="personal-label">
                Role
            </p>

            <p class="personal-value">
                {{ $user->role ?? '-' }}
            </p>
        </div>

        <hr class="personal-divider">

        {{-- Nomor Telepon --}}
        <div class="personal-field">
            <p class="personal-label">
                Nomor Telepon
            </p>

            <p class="personal-value">
                {{ $user->no_telepon ?? $user->nomor_telepon ?? '-' }}
            </p>
        </div>

    </section>

    {{-- LOGOUT --}}
    <div class="logout-wrapper">

        <form
            action="{{ route('logout') }}"
            method="POST"
            onsubmit="return confirm('Anda yakin ingin logout?');"
        >
            @csrf

            <button
                class="logout-button"
                type="submit"
            >
                Keluar
            </button>

        </form>

    </div>

</div>

@endsection