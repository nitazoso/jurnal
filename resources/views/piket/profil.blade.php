@extends('layouts.piket')

@section('title', 'Profil Staff Piket')
@section('page-title', 'Profil')
@section('page-subtitle', 'Informasi data diri dan akun staff piket')

@section('content')
@php
    $user = auth()->user();
    $profileName = $user->guru?->nama_guru ?? $user->nama_user ?? '-';
@endphp

<div class="piket-profile-page">
    <section class="piket-profile-hero">
        <div class="piket-profile-avatar" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 21v-1a8 8 0 0 1 16 0v1" />
            </svg>
        </div>
        <h2>{{ $profileName }}</h2>
        <span class="piket-profile-role">{{ ucfirst($user->role ?? 'Guru') }}</span>
    </section>

    <section class="piket-profile-card" aria-labelledby="piket-profile-details">
        <div class="piket-profile-card-heading">
            <span class="material-symbols-outlined" aria-hidden="true">badge</span>
            <h3 id="piket-profile-details">Informasi Akun</h3>
        </div>

        <dl class="piket-profile-list">
            <div class="piket-profile-item">
                <dt>Nama Lengkap</dt>
                <dd>{{ $profileName }}</dd>
            </div>
            <div class="piket-profile-item">
                <dt>Username</dt>
                <dd>{{ $user->username ?? '-' }}</dd>
            </div>
            <div class="piket-profile-item">
                <dt>Role</dt>
                <dd>{{ ucfirst($user->role ?? '-') }}</dd>
            </div>
        </dl>
    </section>

    <!-- <div class="piket-profile-actions">
        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Anda yakin ingin logout?');">
            @csrf
            <button type="submit" class="piket-profile-logout">
                <span class="material-symbols-outlined" aria-hidden="true">logout</span>
                Keluar
            </button>
        </form>
    </div> -->
</div>

<style>
    .piket-profile-page {
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #172554;
    }

    .piket-profile-hero {
        display: flex;
        min-height: 220px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 28px 22px;
        border: 1px solid #dfe6fb;
        border-radius: 16px;
        background: linear-gradient(135deg, #e9efff 0%, #f6f8ff 100%);
        text-align: center;
    }

    .piket-profile-avatar {
        display: grid;
        width: 72px;
        height: 72px;
        place-items: center;
        margin-bottom: 14px;
        border: 3px solid #fff;
        border-radius: 50%;
        background: #30366f;
        color: #fff;
        box-shadow: 0 5px 16px rgba(48, 54, 111, .16);
    }

    .piket-profile-avatar svg {
        width: 34px;
        height: 34px;
    }

    .piket-profile-hero h2 {
        max-width: 100%;
        color: #26316c;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.35;
        overflow-wrap: anywhere;
    }

    .piket-profile-role {
        margin-top: 9px;
        padding: 5px 12px;
        border: 1px solid #dce3fa;
        border-radius: 999px;
        background: #fff;
        color: #34428b;
        font-size: 11px;
        font-weight: 800;
    }

    .piket-profile-card {
        padding: 22px 24px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 14px rgba(30, 41, 59, .04);
    }

    .piket-profile-card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 15px;
        border-bottom: 1px solid #edf0f6;
    }

    .piket-profile-card-heading .material-symbols-outlined {
        color: #4757b2;
        font-size: 21px;
    }

    .piket-profile-card-heading h3 {
        color: #202b61;
        font-size: 15px;
        font-weight: 800;
    }

    .piket-profile-list {
        margin: 0;
    }

    .piket-profile-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f2f7;
    }

    .piket-profile-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .piket-profile-item dt {
        color: #71809b;
        font-size: 12px;
        font-weight: 600;
    }

    .piket-profile-item dd {
        margin: 0;
        color: #172554;
        font-size: 13px;
        font-weight: 700;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .piket-profile-actions form {
        display: flex;
        justify-content: flex-end;
    }

    .piket-profile-logout {
        display: inline-flex;
        min-height: 42px;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 18px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fff1f2;
        color: #b42318;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }

    .piket-profile-logout:hover {
        transform: translateY(-1px);
        background: #ffe4e6;
    }

    .piket-profile-logout .material-symbols-outlined {
        font-size: 18px;
    }

    @media (max-width: 600px) {
        .piket-profile-page {
            gap: 14px;
        }

        .piket-profile-hero {
            min-height: 190px;
            padding: 22px 16px;
        }

        .piket-profile-hero h2 {
            font-size: 19px;
        }

        .piket-profile-card {
            padding: 18px 16px;
        }

        .piket-profile-item {
            align-items: flex-start;
            gap: 12px;
        }
    }
</style>
@endsection
