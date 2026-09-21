@extends('layouts.sekretaris')
@section('title', 'Dashboard Sekretaris')
@section('page-title', 'Dashboard Sekretaris')
@section('page-subtitle', 'Kelola validasi dan jurnal pembelajaran')
@section('content')

{{-- Font Manrope --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="dashboard-page">

    {{-- WELCOME BANNER --}}
    <section class="welcome-banner">
        <div class="welcome-avatar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </div>

        <div class="welcome-content">
            <h2>Selamat datang, {{ auth()->user()->nama_user ?? 'Sekretaris' }}</h2>
            <p>Pantau dan verifikasi kehadiran pembelajaran kelas secara berkala.</p>
        </div>
    </section>

    {{-- SUMMARY METRICS --}}
    <section class="summary-metrics">

        {{-- SUDAH DIVALIDASI --}}
        <div class="metric-card validated">
            <div class="metric-top">
                <span>Sudah Divalidasi</span>

                <div class="metric-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <div class="metric-bottom">
                <strong>{{ $jurnalsTervalidasiHariIni }}</strong>
                <span>Tervalidasi hari ini</span>
            </div>
        </div>

        {{-- MENUNGGU VALIDASI --}}
        <div class="metric-card pending">
            <div class="metric-top">
                <span>Menunggu Validasi</span>

                <div class="metric-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                    </svg>
                </div>
            </div>

            <div class="metric-bottom">
                <strong>{{ $jurnalsMenunggu }}</strong>
                <span>Segera validasi</span>
            </div>
        </div>

        {{-- ACTION CARD --}}
        <a href="{{ route('sekretaris.isi-jurnal') }}" class="metric-card action">
            <div class="metric-top">
                <span>Isi Jurnal Guru</span>

                <div class="metric-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>

            <div class="metric-bottom">
                <strong>Buat Baru</strong>
                <span>Buat jurnal atas nama guru</span>
            </div>
        </a>

    </section>

    {{-- RINGKASAN JURNAL --}}
    <section class="journal-section">

        <div class="section-header">
            <div>
                <h3>Ringkasan Jurnal</h3>
                <p>Daftar jurnal yang menunggu validasi Anda.</p>
            </div>

            <a href="{{ route('sekretaris.validasi-jurnal') }}" class="view-all">
                Lihat semua
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="journal-list">

            @forelse($jurnalTerbaru as $jurnal)

                <div class="journal-card">

                    <div class="journal-top">

                        <div class="journal-info">

                            <div class="class-badge">
                                {{ $jurnal->kelas->nama_kelas ?? '-' }}
                            </div>

                            <div class="journal-title">
                                <h4>{{ $jurnal->guru->nama_guru ?? '-' }}</h4>

                                <div class="journal-meta">
                                    <span>
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2h14"/>
                                        </svg>
                                        {{ $jurnal->tanggal?->format('d M Y') ?? '-' }}
                                    </span>

                                    <span class="separator">•</span>

                                    <span>
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Jam {{ $jurnal->jamMulai->jam_ke ?? '-' }} - {{ $jurnal->jamSelesai->jam_ke ?? '-' }}
                                    </span>
                                </div>
                            </div>

                        </div>

                        <span class="status-badge">
                            <span class="status-dot"></span>
                            Menunggu
                        </span>

                    </div>

                    <div class="journal-detail">
                        <span>
                            <strong>Materi:</strong> {{ $jurnal->materi ?? '-' }}
                        </span>

                        <a href="{{ route('sekretaris.validasi-jurnal') }}">
                            Detail
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                </div>

            @empty

                <div class="empty-state">
                    <div class="empty-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <strong>Tidak ada jurnal yang menunggu validasi</strong>
                    <p>Semua jurnal sudah diproses dengan baik.</p>
                </div>

            @endforelse

        </div>

    </section>

</div>

<style>
/* GLOBAL STYLING & FONT MANROPE */
.dashboard-page,
.dashboard-page * {
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    box-sizing: border-box;
}

.dashboard-page {
    width: 100%;
}

/* WELCOME BANNER */
.welcome-banner {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 24px 28px;
    margin-bottom: 24px;
    background: linear-gradient(135deg, #2D336B 0%, #47539B 100%);
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(45, 51, 107, .12);
    animation: fadeInUp .4s cubic-bezier(.16, 1, .3, 1) both;
}

.welcome-avatar {
    width: 58px;
    height: 58px;
    flex: 0 0 58px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, .15);
    color: #FFFFFF;
    border: 2px solid rgba(255, 255, 255, .3);
}

.welcome-avatar svg {
    width: 28px;
    height: 28px;
}

.welcome-content {
    min-width: 0;
}

.welcome-content h2 {
    margin: 0;
    color: #FFFFFF;
    font-size: 22px;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: -.4px;
}

.welcome-content p {
    margin: 4px 0 0;
    color: rgba(255, 255, 255, .8);
    font-size: 13.5px;
    font-weight: 500;
}

/* SUMMARY METRICS */
.summary-metrics {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 32px;
    animation: fadeInUp .4s .06s cubic-bezier(.16, 1, .3, 1) both;
}

.metric-card {
    min-height: 135px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px 22px;
    border-radius: 16px;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(15, 23, 42, .03);
    transition: transform .25s ease, box-shadow .25s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, .08);
}

.metric-card.validated {
    background: #DCFCE7;
    color: #14532D;
    border: 1px solid #BBF7D0;
}

.metric-card.pending {
    background: #FEF3C7;
    color: #78350F;
    border: 1px solid #FDE68A;
}

.metric-card.action {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    color: #2D336B;
}

.metric-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.metric-top > span {
    font-size: 13.5px;
    font-weight: 800;
}

.metric-icon {
    width: 32px;
    height: 32px;
    flex: 0 0 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.metric-card.validated .metric-icon {
    border-radius: 10px;
    background: #16A34A;
    color: #FFFFFF;
}

.metric-card.pending .metric-icon {
    border-radius: 10px;
    background: #D97706;
    color: #FFFFFF;
}

.metric-card.action .metric-icon {
    border-radius: 10px;
    background: #EEF2FF;
    color: #2D336B;
}

.metric-icon svg {
    width: 18px;
    height: 18px;
}

.metric-bottom strong {
    display: block;
    margin-top: 10px;
    font-size: 32px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -.6px;
}

.metric-card.action .metric-bottom strong {
    font-size: 18px;
    color: #2D336B;
}

.metric-bottom span {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    font-weight: 600;
    opacity: .85;
}

/* JOURNAL SECTION */
.journal-section {
    animation: fadeInUp .4s .12s cubic-bezier(.16, 1, .3, 1) both;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
}

.section-header h3 {
    margin: 0;
    color: #0F172A;
    font-size: 19px;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: -.3px;
}

.section-header p {
    margin: 3px 0 0;
    color: #64748B;
    font-size: 13px;
    font-weight: 500;
}

.view-all {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border: 1px solid #CBD5E1;
    border-radius: 10px;
    background: #FFFFFF;
    color: #475569;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    transition: all .2s ease;
}

.view-all svg {
    width: 14px;
    height: 14px;
    transition: transform .2s ease;
}

.view-all:hover {
    border-color: #2D336B;
    color: #2D336B;
    background: #F8FAFC;
}

.view-all:hover svg {
    transform: translateX(3px);
}

.journal-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.journal-card {
    padding: 18px 20px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, .02);
    transition: all .2s ease;
}

.journal-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 6px 16px rgba(15, 23, 42, .05);
    transform: translateY(-1px);
}

.journal-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
}

.journal-info {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.class-badge {
    width: 44px;
    height: 44px;
    flex: 0 0 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4px;
    border-radius: 12px;
    background: #EEF2FF;
    color: #2D336B;
    font-size: 12px;
    font-weight: 800;
    text-align: center;
    line-height: 1.15;
}

.journal-title {
    min-width: 0;
}

.journal-title h4 {
    margin: 0;
    color: #0F172A;
    font-size: 15px;
    font-weight: 800;
}

.journal-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 4px;
    color: #64748B;
    font-size: 12px;
    font-weight: 500;
}

.journal-meta span {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.journal-meta svg {
    width: 14px;
    height: 14px;
    color: #94A3B8;
}

.separator {
    color: #CBD5E1;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex: 0 0 auto;
    padding: 5px 12px;
    border: 1px solid #FDE68A;
    border-radius: 999px;
    background: #FEF3C7;
    color: #92400E;
    font-size: 12px;
    font-weight: 700;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #D97706;
    animation: pulseDot 1.8s infinite;
}

.journal-detail {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 14px;
    padding: 10px 14px;
    border-radius: 10px;
    background: #F8FAFC;
}

.journal-detail > span {
    min-width: 0;
    color: #334155;
    font-size: 13px;
    line-height: 1.4;
}

.journal-detail strong {
    color: #0F172A;
    font-weight: 700;
}

.journal-detail a {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    flex: 0 0 auto;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: color .2s ease;
}

.journal-detail a:hover {
    color: #2D336B;
}

.journal-detail a svg {
    width: 14px;
    height: 14px;
}

.empty-state {
    padding: 40px 20px;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    background: #FFFFFF;
    text-align: center;
}

.empty-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    border-radius: 50%;
    background: #DCFCE7;
    color: #16A34A;
}

.empty-icon svg {
    width: 22px;
    height: 22px;
}

.empty-state strong {
    display: block;
    color: #0F172A;
    font-size: 14px;
    font-weight: 800;
}

.empty-state p {
    margin: 4px 0 0;
    color: #64748B;
    font-size: 13px;
}

/* ANIMATION & RESPONSIVE */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulseDot {
    0% {
        box-shadow: 0 0 0 0 rgba(217, 119, 6, .5);
    }
    70% {
        box-shadow: 0 0 0 5px rgba(217, 119, 6, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(217, 119, 6, 0);
    }
}

@media (max-width: 1000px) {
    .summary-metrics {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .metric-card.action {
        grid-column: span 2;
    }
}

@media (max-width: 767px) {
    .welcome-banner {
        padding: 20px;
        gap: 16px;
    }

    .welcome-avatar {
        width: 48px;
        height: 48px;
        flex-basis: 48px;
    }

    .welcome-content h2 {
        font-size: 19px;
    }

    .summary-metrics {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .metric-card.action {
        grid-column: auto;
    }

    .section-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
    }

    .view-all {
        width: 100%;
        justify-content: center;
    }

    .journal-top {
        flex-direction: column;
    }

    .status-badge {
        align-self: flex-start;
    }
}

@media (max-width: 420px) {
    .welcome-banner {
        align-items: flex-start;
    }

    .metric-card {
        padding: 18px;
    }

    .metric-bottom strong {
        font-size: 28px;
    }

    .journal-card {
        padding: 16px;
    }

    .journal-detail {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .journal-detail a {
        align-self: flex-end;
    }
}

@media (prefers-reduced-motion: reduce) {
    .welcome-banner,
    .summary-metrics,
    .journal-section {
        animation: none;
    }

    .metric-card,
    .journal-card,
    .view-all {
        transition: none;
    }

    .status-dot {
        animation: none;
    }
}
</style>

@endsection