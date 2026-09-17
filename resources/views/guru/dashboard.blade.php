@extends('layouts.guru')

@section('title', 'Dashboard - Jurnify')
@section('tahun_ajaran', $tahunAjaran)

@section('content')

<style>
    .guru-dashboard {
        --primary: #2D336B;
        --secondary: #7886C7;
        --accent: #A9B5DF;
        --light-bg: #FBFBFB;
        --soft-blue: #E6ECFC;
        --border: #CCD6FC;
        --text: #1E293B;
        --muted: #64748B;
    }

    .guru-dashboard * {
        box-sizing: border-box;
    }

    .dashboard-page {
        width: 100%;
        max-width: none;
        margin: 0;
        animation: pageFade .45s ease-out;
    }

    @keyframes pageFade {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* TOP SECTION */

    .overview-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    .dashboard-card {
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            border-color .2s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
    }

    .welcome-card {
        min-height: 170px;
        padding: 28px;
        border-radius: 18px;
        background: linear-gradient(110deg, #5966B2, #343D7B);
        color: white;
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(45, 51, 107, .08);
    }

    .welcome-card::before,
    .welcome-card::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, .05);
        pointer-events: none;
    }

    .welcome-card::before {
        width: 175px;
        height: 175px;
        right: -45px;
        bottom: -75px;
    }

    .welcome-card::after {
        width: 120px;
        height: 120px;
        left: -45px;
        top: -50px;
    }

    .welcome-avatar {
        width: 68px;
        height: 68px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #0891B2;
        border: 2px solid rgba(255, 255, 255, .9);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
        transition: transform .25s ease;
    }

    .welcome-card:hover .welcome-avatar {
        transform: scale(1.04);
    }

    .welcome-avatar svg {
        width: 32px;
        height: 32px;
    }

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .welcome-title {
        margin: 0;
        color: white;
        font-size: 23px;
        line-height: 1.35;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .welcome-text {
        margin: 6px 0 0;
        color: rgba(219, 234, 254, .95);
        font-size: 13px;
        line-height: 1.6;
        font-weight: 500;
    }

    /* TOTAL */

    .total-card {
        min-height: 170px;
        padding: 28px;
        border-radius: 18px;
        background: #D2DAFF;
        border: 1px solid #C2CCFC;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(45, 51, 107, .06);
    }

    .total-card::after {
        content: "";
        position: absolute;
        width: 135px;
        height: 135px;
        right: -45px;
        top: -45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .2);
    }

    .total-content {
        position: relative;
        z-index: 1;
    }

    .total-label {
        display: block;
        margin-bottom: 8px;
        color: #475569;
        font-size: 14px;
        font-weight: 700;
    }

    .total-number {
        color: #1E2653;
        font-size: 48px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -1px;
        animation: numberAppear .6s ease-out;
    }

    @keyframes numberAppear {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .total-text {
        display: block;
        margin-top: 7px;
        color: #475569;
        font-size: 12px;
        font-weight: 500;
    }

    .total-icon {
        width: 56px;
        height: 56px;
        flex-shrink: 0;
        border-radius: 16px;
        background: rgba(255, 255, 255, .7);
        border: 1px solid rgba(255, 255, 255, .8);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
        box-shadow: 0 2px 8px rgba(45, 51, 107, .06);
    }

    .total-icon svg {
        width: 28px;
        height: 28px;
        color: var(--primary);
    }

    /* SUMMARY */

    .summary-section {
        width: 100%;
    }

    .summary-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .summary-title {
        margin: 0;
        color: #111827;
        font-size: 21px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .summary-subtitle {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 13px;
        font-weight: 500;
    }

    .see-all {
        color: #4A55A2;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition:
            color .2s ease,
            transform .2s ease;
    }

    .see-all:hover {
        color: var(--primary);
        transform: translateX(2px);
    }

    /* JOURNAL LIST */

    .journal-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .journal-card {
        padding: 20px;
        background: white;
        border: 1px solid rgba(204, 214, 252, .8);
        border-radius: 16px;
        box-shadow: 0 3px 10px rgba(45, 51, 107, .04);
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            border-color .2s ease;
    }

    .journal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(45, 51, 107, .08);
        border-color: var(--accent);
    }

    .journal-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 12px;
    }

    .journal-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .journal-number {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: 11px;
        background: var(--soft-blue);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
    }

    .journal-class {
        color: #111827;
        font-size: 15px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 999px;
        font-size: 11px;
        line-height: 1;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-menunggu {
        background: #FEF3C7;
        color: #92400E;
    }

    .status-valid {
        background: #DCFCE7;
        color: #166534;
    }

    .status-default {
        background: #E2E8F0;
        color: #475569;
    }

    .journal-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-left: 52px;
        margin-bottom: 14px;
        color: #64748B;
        font-size: 11px;
        font-weight: 600;
    }

    .meta-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .meta-item svg {
        width: 14px;
        height: 14px;
        color: #94A3B8;
        flex-shrink: 0;
    }

    .meta-dot {
        color: #CBD5E1;
        font-weight: 800;
    }

    .journal-material {
        min-height: 45px;
        margin-top: 4px;
        padding: 10px 13px;
        border: 1px solid #EEF0F5;
        border-radius: 11px;
        background: #F8F9FD;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .material-name {
        min-width: 0;
        color: #1F2937;
        font-size: 12px;
        font-weight: 700;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .detail-link {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        flex-shrink: 0;
        color: #4A55A2;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition:
            color .2s ease,
            transform .2s ease;
    }

    .detail-link:hover {
        color: var(--primary);
        transform: translateX(2px);
    }

    .detail-arrow {
        font-size: 15px;
        line-height: 1;
    }

    /* EMPTY */

    .empty-state {
        padding: 40px 20px;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 16px;
        text-align: center;
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
    }

    /* TABLET */

    @media (min-width: 768px) and (max-width: 1100px) {
        .dashboard-page {
            padding: 32px;
        }

        .overview-grid {
            gap: 18px;
        }

        .welcome-card,
        .total-card {
            padding: 23px;
        }

        .welcome-title {
            font-size: 20px;
        }

        .total-number {
            font-size: 42px;
        }
    }

    /* MOBILE */

    @media (max-width: 767px) {
        .dashboard-page {
            max-width: none;
            padding: 0;
        }

        .overview-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 28px;
        }

        .welcome-card {
            min-height: 150px;
            padding: 23px 20px;
            border-radius: 18px;
            gap: 15px;
        }

        .welcome-avatar {
            width: 58px;
            height: 58px;
        }

        .welcome-avatar svg {
            width: 28px;
            height: 28px;
        }

        .welcome-title {
            font-size: 18px;
        }

        .welcome-text {
            font-size: 11px;
            line-height: 1.55;
        }

        .total-card {
            min-height: 145px;
            padding: 22px 20px;
            border-radius: 18px;
        }

        .total-number {
            font-size: 42px;
        }

        .total-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
        }

        .summary-header {
            align-items: flex-start;
            gap: 12px;
        }

        .summary-title {
            font-size: 19px;
        }

        .summary-subtitle {
            font-size: 11px;
            line-height: 1.5;
        }

        .see-all {
            font-size: 11px;
            margin-top: 3px;
        }

        .journal-list {
            gap: 12px;
        }

        .journal-card {
            padding: 17px;
            border-radius: 16px;
        }

        .journal-top {
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .journal-number {
            width: 36px;
            height: 36px;
            border-radius: 10px;
        }

        .journal-class {
            font-size: 13px;
        }

        .status {
            padding: 5px 9px;
            font-size: 10px;
        }

        .journal-meta {
            margin-left: 48px;
            gap: 6px;
            margin-bottom: 12px;
            font-size: 10px;
        }

        .meta-item svg {
            width: 13px;
            height: 13px;
        }

        .journal-material {
            padding: 10px 11px;
        }

        .material-name,
        .detail-link {
            font-size: 10px;
        }
    }

    /* SMALL MOBILE */

    @media (max-width: 420px) {
        .dashboard-page {
            padding: 0;
        }

        .welcome-card {
            padding: 20px 17px;
            gap: 13px;
        }

        .welcome-avatar {
            width: 52px;
            height: 52px;
        }

        .welcome-title {
            font-size: 16px;
        }

        .welcome-text {
            font-size: 10px;
        }

        .total-card {
            padding: 20px 17px;
        }

        .total-number {
            font-size: 39px;
        }

        .journal-card {
            padding: 15px;
        }

        .journal-heading {
            gap: 9px;
        }

        .journal-number {
            width: 34px;
            height: 34px;
        }

        .journal-meta {
            margin-left: 43px;
        }

        .journal-material {
            gap: 8px;
        }
    }

    /* REDUCED MOTION */

    @media (prefers-reduced-motion: reduce) {
        .dashboard-page,
        .total-number,
        .dashboard-card,
        .journal-card,
        .welcome-avatar,
        .see-all,
        .detail-link {
            animation: none;
            transition: none;
        }
    }
</style>

<div class="guru-dashboard">
    <div class="dashboard-page">

        {{-- TOP CARDS --}}
        <section class="overview-grid">

            <div class="welcome-card dashboard-card">

                <div class="welcome-avatar">
                    <svg
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>

                <div class="welcome-content">

                    <h2 class="welcome-title">
                        Selamat datang, {{ auth()->user()->nama_user ?? '-' }}
                    </h2>

                    <p class="welcome-text">
                        Pantau dan kelola aktivitas pembelajaran Anda melalui Jurnify.
                    </p>

                </div>

            </div>

            <div class="total-card dashboard-card">

                <div class="total-content">

                    <span class="total-label">
                        Total Jurnal
                    </span>

                    <div class="total-number">
                        {{ $totalJurnal }}
                    </div>

                    <span class="total-text">
                        Jurnal yang telah dibuat
                    </span>

                </div>

                <div class="total-icon">

                    <svg
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"
                        />
                    </svg>

                </div>

            </div>

        </section>

        {{-- RINGKASAN JURNAL --}}
        <section class="summary-section">

            <div class="summary-header">

                <div>

                    <h2 class="summary-title">
                        Ringkasan Jurnal
                    </h2>

                    <p class="summary-subtitle">
                        Menampilkan 5 jurnal terbaru yang telah Anda buat
                    </p>

                </div>

                @if($totalJurnal > 5)

                    <a
                        class="see-all"
                        href="{{ route('guru.jurnal.index') }}"
                    >
                        Lihat Semua Jurnal →
                    </a>

                @endif

            </div>

            <div class="journal-list">

                @forelse($jurnals as $index => $jurnal)

                    <article class="journal-card">

                        <div class="journal-top">

                            <div class="journal-heading">

                                <div class="journal-number">
                                    {{ $index + 1 }}
                                </div>

                                <span class="journal-class">
                                    {{ $jurnal->kelas->nama_kelas ?? '-' }}
                                </span>

                            </div>

                            {{-- STATUS --}}
                            @if($jurnal->status_validasi_guru === 'Menunggu')

                                <span class="status status-menunggu">
                                    Menunggu
                                </span>

                            @elseif($jurnal->status_validasi_guru === 'Valid')

                                <span class="status status-valid">
                                    Valid
                                </span>

                            @else

                                <span class="status status-default">
                                    {{ $jurnal->status_validasi_guru ?? '-' }}
                                </span>

                            @endif

                        </div>

                        <div class="journal-meta">

                            <span class="meta-item">

                                <svg
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <rect
                                        width="18"
                                        height="18"
                                        x="3"
                                        y="4"
                                        rx="2"
                                    />

                                    <path d="M16 2v4M8 2v4M3 10h18" />
                                </svg>

                                {{ $jurnal->tanggal?->format('d M Y') ?? '-' }}

                            </span>

                            <span class="meta-dot">
                                •
                            </span>

                            <span class="meta-item">

                                <svg
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="10"
                                    />

                                    <polyline points="12 6 12 12 16 14" />
                                </svg>

                                Jam Ke
                                {{ $jurnal->jamMulai->jam_ke ?? '-' }}-{{ $jurnal->jamSelesai->jam_ke ?? '-' }}

                            </span>

                            @if($jurnal->jadwal?->mapel?->nama_mapel)

                                <span class="meta-dot">
                                    •
                                </span>

                                <span class="meta-item">
                                    {{ $jurnal->jadwal->mapel->nama_mapel }}
                                </span>

                            @endif

                        </div>

                        <div class="journal-material">

                            <span
                                class="material-name"
                                title="{{ $jurnal->materi }}"
                            >
                                Materi: {{ $jurnal->materi ?? '-' }}
                            </span>

                            <a
                                class="detail-link"
                                href="{{ route('guru.jurnal.show', $jurnal->id_jurnal) }}"
                            >
                                Detail
                                <span class="detail-arrow">
                                    ›
                                </span>
                            </a>

                        </div>

                    </article>

                @empty

                    <div class="empty-state">
                        Belum ada jurnal yang dibuat.
                    </div>

                @endforelse

            </div>

        </section>

    </div>
</div>

@endsection