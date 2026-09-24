@extends('layouts.guru')
@section('title', 'Profil Guru - Jurnify')
@section('page-title', 'Profil')
@section('page-subtitle', 'Informasi Data Diri & Akun Pendidik')
@section('content')

{{-- Font Manrope --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

@php
    $user = auth()->user();
    $profileName = $user->guru?->nama_guru ?? $user->nama_user ?? '-';
@endphp

<div class="profile-page">

    {{-- PROFILE HERO --}}
    <section class="profile-hero">

        {{-- Avatar --}}
        <div class="profile-avatar">
            <svg viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
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
                {{ $profileName }}
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
                {{ ucfirst($user->role ?? '-') }}
            </p>
        </div>

        <hr class="personal-divider">

    </section>

    {{-- EDIT PROFILE FORM --}}
    <section class="personal-card edit-card">
        <div class="section-header">
            <h4 class="section-title">Edit Profil</h4>
        </div>

        <form action="{{ route('guru.profil.update') }}" method="POST" class="profile-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username', $user->username ?? '') }}" required>
                @error('username')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input id="password" name="password" type="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                @error('password')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="submit-btn">Simpan Perubahan</button>
        </form>
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

<style>
    .profile-page,
    .profile-page * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .edit-card {
        padding: 24px;
    }

    .section-header {
        margin-bottom: 16px;
    }

    .section-title {
        margin: 0;
        color: #2D336B;
        font-size: 1.1rem;
        font-weight: 800;
    }

    .profile-form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        color: #2D336B;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .form-group input {
        width: 100%;
        border: 1px solid #D7DDF5;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 0.97rem;
        color: #2D336B;
        background: #F9FAFF;
    }

    .form-group input:focus {
        outline: 2px solid rgba(72, 96, 206, 0.18);
        border-color: #7F8ED8;
        background: white;
    }

    .submit-btn {
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #2D336B 0%, #47539B 100%);
        color: white;
        font-weight: 700;
        padding: 12px 18px;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 10px 18px rgba(45, 51, 107, 0.15);
    }

    .submit-btn:hover {
        transform: translateY(-1px);
    }

    .error-text {
        color: #C81E1E;
        font-weight: 600;
        font-size: 0.8rem;
    }

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
        min-height: 280px;
        padding: 36px 24px;
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
        top: 20px;
        right: 20px;
        width: 42px;
        height: 42px;
        padding: 8px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: #2D336B;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
        transition:
            background-color 0.2s ease,
            color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .profile-edit:hover {
        background: rgba(255, 255, 255, 0.8);
        color: #2D336B;
        transform: rotate(-4deg) scale(1.06);
        box-shadow: 0 5px 14px rgba(45, 51, 107, 0.12);
    }

    .profile-edit:active {
        transform: scale(0.96);
    }

    .profile-edit svg {
        width: 22px;
        height: 22px;
    }

    .profile-avatar {
        position: relative;
        width: 110px;
        height: 110px;
        margin-bottom: 16px;
        border: 4px solid rgba(255, 255, 255, 0.85);
        border-radius: 50%;
        background: linear-gradient(135deg, #2D336B 0%, #47539B 100%);
        color: #FFFFFF;
        box-shadow:
            0 8px 20px rgba(45, 51, 107, 0.14),
            0 0 0 6px rgba(255, 255, 255, 0.2);
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
        inset: -6px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: inherit;
        pointer-events: none;
    }

    .profile-hero:hover .profile-avatar {
        transform: translateY(-3px);
        box-shadow:
            0 12px 26px rgba(45, 51, 107, 0.18),
            0 0 0 6px rgba(255, 255, 255, 0.25);
    }

    .profile-avatar svg {
        width: 56px;
        height: 56px;
        fill: #FFFFFF;
        opacity: 0.9;
    }

    .profile-name {
        margin: 0 0 10px;
        color: #2D336B;
        font-size: 26px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -0.4px;
    }

    .profile-role {
        display: inline-flex;
        align-items: center;
        padding: 6px 20px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.8);
        color: #2D336B;
        font-size: 12.5px;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(45, 51, 107, 0.08);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .profile-role:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(45, 51, 107, 0.12);
    }

    /* PERSONAL DETAILS */
    .personal-card {
        padding: 28px 32px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03);
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            border-color 0.25s ease;
    }

    .personal-card:hover {
        transform: translateY(-2px);
        border-color: #CBD5E1;
        box-shadow: 0 10px 24px rgba(45, 51, 107, 0.06);
    }

    .personal-field {
        padding: 4px 0;
        transition: transform 0.2s ease;
    }

    .personal-field:hover {
        transform: translateX(4px);
    }

    .personal-label {
        margin: 0 0 4px;
        color: #64748B;
        font-size: 12.5px;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .personal-field:hover .personal-label {
        color: #7886C7;
    }

    .personal-value {
        margin: 0;
        color: #0F172A;
        font-size: 16px;
        font-weight: 700;
        word-break: break-word;
    }

    .personal-divider {
        margin: 16px 0;
        border: 0;
        border-top: 1px solid #F1F5F9;
    }

    /* LOGOUT */
    .logout-wrapper {
        padding-top: 0;
    }

    .logout-button {
        width: 100%;
        padding: 15px 20px;
        border: 1px solid rgba(201, 74, 43, 0.15);
        border-radius: 16px;
        background: #FEE2E2;
        color: #DC2626;
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
        background: #FCA5A5;
        border-color: rgba(220, 38, 38, 0.2);
        color: #B91C1C;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.12);
    }

    .logout-button:active {
        transform: translateY(0) scale(0.99);
        box-shadow: none;
    }

    /* ANIMATIONS */
    .profile-hero,
    .personal-card,
    .logout-wrapper {
        animation: profileFadeUp 0.45s cubic-bezier(.16, 1, .3, 1) both;
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

    /* RESPONSIVE */
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
            padding: 0 0 24px;
            gap: 16px;
        }

        .profile-hero {
            min-height: 250px;
            padding: 28px 20px 24px;
            border-radius: 20px;
        }

        .profile-edit {
            top: 14px;
            right: 14px;
            width: 38px;
            height: 38px;
        }

        .profile-edit svg {
            width: 18px;
            height: 18px;
        }

        .profile-avatar {
            width: 96px;
            height: 96px;
            margin-bottom: 14px;
        }

        .profile-avatar svg {
            width: 48px;
            height: 48px;
        }

        .profile-name {
            margin-bottom: 10px;
            font-size: 22px;
        }

        .profile-role {
            padding: 5px 16px;
            font-size: 11.5px;
        }

        .personal-card {
            padding: 20px 18px;
            border-radius: 16px;
        }

        .personal-field {
            padding: 2px 0;
        }

        .personal-label {
            margin-bottom: 4px;
            font-size: 11.5px;
        }

        .personal-value {
            font-size: 14.5px;
        }

        .personal-divider {
            margin: 14px 0;
        }

        .logout-button {
            padding: 14px;
            border-radius: 14px;
            font-size: 14px;
        }
    }

    @media (max-width: 420px) {
        .profile-hero {
            min-height: 230px;
            padding: 24px 16px 20px;
        }

        .profile-avatar {
            width: 84px;
            height: 84px;
        }

        .profile-avatar svg {
            width: 42px;
            height: 42px;
        }

        .profile-name {
            font-size: 20px;
        }

        .personal-card {
            padding: 18px 16px;
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

@endsection