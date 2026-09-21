@extends('layouts.guru')

@section('title', 'Detail Jurnal - Jurnify')
@section('page-title', 'Detail Jurnal')
@section('page-subtitle', 'Lihat detail jurnal pembelajaran')

@section('content')

{{-- Font Manrope --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="jurnal-detail-container">

    {{-- HERO HEADER --}}
    <section class="jurnal-hero-card">
        <div class="jurnal-hero-content">
            <div class="jurnal-hero-badge-wrap">
                <span class="jurnal-hero-badge">DETAIL AKTIVITAS</span>

                {{-- STATUS BADGE --}}
                @if(($jurnal->status_validasi_guru ?? 'Menunggu') === 'Menunggu')
                    <span class="status-badge status-menunggu">
                        <span class="status-dot warning"></span>
                        Menunggu Validasi
                    </span>
                @elseif(($jurnal->status_validasi_guru ?? '') === 'Valid')
                    <span class="status-badge status-valid">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="m4.5 12.75 6 6 9-13.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Sudah Divalidasi
                    </span>
                @else
                    <span class="status-badge status-default">
                        {{ $jurnal->status_validasi_guru ?? '-' }}
                    </span>
                @endif
            </div>

            <h1 class="jurnal-title">
                {{ $jurnal->jadwal->mapel->nama_mapel ?? 'Detail Jurnal' }}
            </h1>

            <p class="jurnal-subtitle">
                Kelas {{ $jurnal->kelas->nama_kelas ?? '-' }} &bull; {{ $jurnal->tanggal ? $jurnal->tanggal->translatedFormat('l, d F Y') : '-' }}
            </p>
        </div>
    </section>

    {{-- GRID ATAS (INFORMASI & KEHADIRAN) --}}
    <div class="jurnal-grid-top">
        
        {{-- INFORMASI PEMBELAJARAN --}}
        <div class="jurnal-card" style="animation-delay: 0.05s;">
            <h2 class="card-heading">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                Informasi Pembelajaran
            </h2>

            <div class="info-items-grid">
                <div class="info-item">
                    <span class="info-label">Tanggal</span>
                    <div class="info-val">{{ $jurnal->tanggal ? $jurnal->tanggal->format('d M Y') : '-' }}</div>
                </div>

                <div class="info-item">
                    <span class="info-label">Kelas</span>
                    <div class="info-val">{{ $jurnal->kelas->nama_kelas ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <span class="info-label">Mata Pelajaran</span>
                    <div class="info-val">{{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <span class="info-label">Jam Ke</span>
                    <div class="info-val">
                        Jam {{ $jurnal->jamMulai->jam_ke ?? '-' }}-{{ $jurnal->jamSelesai->jam_ke ?? '-' }}
                        <span class="time-sub">({{ $jurnal->jamMulai->jam ?? '-' }} - {{ $jurnal->jamSelesai->jam ?? '-' }})</span>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label">Guru Pengajar</span>
                    <div class="info-val">{{ $jurnal->guru->nama_guru ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <span class="info-label">Status Kehadiran Guru</span>
                    <div>
                        <span class="guru-pill">
                            {{ $jurnal->status_guru ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KEHADIRAN SISWA --}}
        <div class="jurnal-card" style="animation-delay: 0.1s;">
            <h2 class="card-heading">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6 0 3.375 3.375 0 0 1 6 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                Kehadiran Siswa
            </h2>

            <div class="attendance-grid">
                <div class="att-box hadir">
                    <div class="att-number-hadir">{{ $jurnal->jml_hadir ?? 0 }}</div>
                    <div class="att-label-hadir">
                        <span class="att-dot success"></span>
                        Siswa Hadir
                    </div>
                </div>

                <div class="att-box absen">
                    <div class="att-number-absen">{{ $jurnal->jml_tidak_hadir ?? 0 }}</div>
                    <div class="att-label-absen">
                        <span class="att-dot danger"></span>
                        Tidak Hadir
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- MATERI PEMBELAJARAN --}}
    <div class="jurnal-card section-card" style="animation-delay: 0.15s;">
        <h2 class="card-heading">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-8.5A2.75 2.75 0 0 0 16.75 3H7.25A2.75 2.75 0 0 0 4.5 5.75v12.5A2.75 2.75 0 0 0 7.25 21h9.5a2.75 2.75 0 0 0 2.75-2.75v-1.5" />
                <path stroke-linecap="round" d="M8 7h8M8 11h8M8 15h4" />
            </svg>
            Materi Pembelajaran
        </h2>
        <div class="text-content-box">
            {{ $jurnal->materi ?? 'Tidak ada catatan materi.' }}
        </div>
    </div>

    {{-- TUGAS --}}
    <div class="jurnal-card section-card" style="animation-delay: 0.2s;">
        <h2 class="card-heading">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
            </svg>
            Tugas / Penugasan
        </h2>

        <div class="task-status-row">
            <span class="task-status-label">Status Penugasan:</span>
            <span class="task-status-badge">
                {{ $jurnal->ada_tugas ?? 'Tidak Ada' }}
            </span>
        </div>

        @if($jurnal->deskripsi_tugas)
            <div class="task-box">
                {{ $jurnal->deskripsi_tugas }}
            </div>
        @else
            <div class="text-content-box empty-text">
                Tidak ada deskripsi penugasan yang diberikan pada sesi ini.
            </div>
        @endif
    </div>

    {{-- CATATAN UMUM --}}
    @if($jurnal->catatan_umum)
        <div class="jurnal-card section-card" style="animation-delay: 0.25s;">
            <h2 class="card-heading">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Catatan Umum Sesi
            </h2>
            <div class="text-content-box">
                {{ $jurnal->catatan_umum }}
            </div>
        </div>
    @endif

    {{-- CATATAN REVISI --}}
    @if($jurnal->catatan_revisi)
        <div class="revision-card" style="animation-delay: 0.3s;">
            <div class="revision-header">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                Catatan Evaluasi / Revisi
            </div>
            <div class="revision-body">
                {{ $jurnal->catatan_revisi }}
            </div>
        </div>
    @endif

    {{-- BUTTON KEMBALI --}}
    <div class="action-footer">
        <a href="{{ route('guru.jurnal.index') }}" class="btn-back-link">
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Kembali ke Daftar Jurnal
        </a>
    </div>

</div>

<style>
    /* GLOBAL CONTAINER & FONT */
    .jurnal-detail-container,
    .jurnal-detail-container * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .jurnal-detail-container {
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 22px;
        animation: pageIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* ANIMATIONS */
    @keyframes pageIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes cardUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* HERO HEADER CARD */
    .jurnal-hero-card {
        position: relative;
        width: 100%;
        padding: 30px 28px;
        background: linear-gradient(135deg, #A9B5DF 0%, #BFC9EA 100%);
        border: 1px solid rgba(255, 255, 255, 0.65);
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(45, 51, 107, 0.08);
        overflow: hidden;
        isolation: isolate;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .jurnal-hero-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(45, 51, 107, 0.12);
    }

    .jurnal-hero-card::before {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: 80px;
        bottom: -90px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        pointer-events: none;
        z-index: -1;
    }

    .jurnal-hero-card::after {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        right: -80px;
        top: -100px;
        border-radius: 50%;
        background: rgba(45, 51, 107, 0.06);
        pointer-events: none;
        z-index: -1;
    }

    .jurnal-hero-badge-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .jurnal-hero-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 14px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.85);
        color: #2D336B;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.4px;
        box-shadow: 0 2px 8px rgba(45, 51, 107, 0.06);
    }

    .jurnal-title {
        margin: 0;
        color: #2D336B;
        font-size: 24px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -0.4px;
    }

    .jurnal-subtitle {
        margin: 6px 0 0 0;
        color: #475569;
        font-size: 13.5px;
        font-weight: 600;
    }

    /* STATUS BADGES */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-menunggu {
        background: #FEF3C7;
        color: #D97706;
        border: 1px solid #FDE68A;
    }

    .status-valid {
        background: #DCFCE7;
        color: #16A34A;
        border: 1px solid #BBF7D0;
    }

    .status-default {
        background: #F1F5F9;
        color: #64748B;
        border: 1px solid #E2E8F0;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-dot.warning { background: #D97706; }
    .status-dot.success { background: #16A34A; }

    /* GRID & CARDS */
    .jurnal-grid-top {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
    }

    .jurnal-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(45, 51, 107, 0.03);
        animation: cardUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .jurnal-card:hover {
        transform: translateY(-2px);
        border-color: #CBD5E1;
        box-shadow: 0 8px 22px rgba(45, 51, 107, 0.07);
    }

    .card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 18px 0;
        color: #2D336B;
        font-size: 16px;
        font-weight: 800;
        border-bottom: 1px solid #F1F5F9;
        padding-bottom: 12px;
        letter-spacing: -0.2px;
    }

    .card-heading svg {
        width: 20px;
        height: 20px;
        color: #2D336B;
        flex-shrink: 0;
    }

    /* INFO ITEMS GRID */
    .info-items-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px 14px;
    }

    .info-label {
        display: block;
        color: #94A3B8;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-val {
        color: #0F172A;
        font-size: 14px;
        font-weight: 700;
        margin-top: 3px;
        word-break: break-word;
    }

    .time-sub {
        display: block;
        color: #64748B;
        font-size: 12px;
        font-weight: 600;
        margin-top: 1px;
    }

    .guru-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
        background: #EEF2FF;
        border: 1px solid #E0E7FF;
        color: #2D336B;
        font-size: 12px;
        font-weight: 800;
        margin-top: 4px;
    }

    /* ATTENDANCE BOXES */
    .attendance-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-top: 8px;
    }

    .att-box {
        padding: 22px 16px;
        border-radius: 14px;
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .att-box:hover {
        transform: translateY(-2px);
    }

    .att-box.hadir {
        background: #F0FDF4;
        border: 1px solid #DCFCE7;
    }

    .att-box.absen {
        background: #FEF2F2;
        border: 1px solid #FEE2E2;
    }

    .att-number-hadir { color: #16A34A; font-size: 34px; font-weight: 800; line-height: 1; }
    .att-number-absen { color: #DC2626; font-size: 34px; font-weight: 800; line-height: 1; }

    .att-label-hadir, .att-label-absen {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 8px;
        font-size: 12.5px;
        font-weight: 700;
    }

    .att-label-hadir { color: #15803D; }
    .att-label-absen { color: #B91C1C; }

    .att-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }
    .att-dot.success { background: #16A34A; }
    .att-dot.danger { background: #DC2626; }

    /* CONTENT BOXES */
    .text-content-box {
        padding: 16px 18px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        color: #334155;
        font-size: 13.5px;
        line-height: 1.7;
        font-weight: 500;
        white-space: pre-line;
    }

    .text-content-box.empty-text {
        color: #94A3B8;
    }

    .task-status-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
    }

    .task-status-label {
        color: #94A3B8;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-status-badge {
        color: #2D336B;
        font-size: 12px;
        font-weight: 800;
        background: #EEF2FF;
        border: 1px solid #E0E7FF;
        padding: 3px 10px;
        border-radius: 6px;
    }

    .task-box {
        padding: 16px 18px;
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 12px;
        color: #92400E;
        font-size: 13.5px;
        line-height: 1.7;
        font-weight: 600;
        white-space: pre-line;
    }

    /* REVISION CARD */
    .revision-card {
        background: #FFFBEB;
        border: 1px solid #FCD34D;
        border-radius: 18px;
        padding: 20px 24px;
        animation: cardUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .revision-header {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #B45309;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .revision-header svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .revision-body {
        color: #78350F;
        font-size: 13.5px;
        line-height: 1.6;
        font-weight: 600;
        white-space: pre-line;
    }

    /* ACTION FOOTER & BUTTON */
    .action-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    }

    .btn-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: 1px solid #CBD5E1;
        border-radius: 12px;
        background: #FFFFFF;
        color: #334155;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
        transition: all 0.2s ease;
    }

    .btn-back-link svg {
        width: 16px;
        height: 16px;
        transition: transform 0.2s ease;
    }

    .btn-back-link:hover {
        background: #2D336B;
        color: #FFFFFF;
        border-color: #2D336B;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(45, 51, 107, 0.15);
    }

    .btn-back-link:hover svg {
        transform: translateX(-3px);
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 992px) {
        .jurnal-grid-top {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .jurnal-hero-card {
            padding: 24px 20px;
            border-radius: 16px;
        }

        .jurnal-title {
            font-size: 20px;
        }

        .jurnal-card {
            padding: 18px 20px;
        }

        .info-items-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .action-footer {
            justify-content: stretch;
        }

        .btn-back-link {
            width: 100%;
            justify-content: center;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .jurnal-detail-container,
        .jurnal-hero-card,
        .jurnal-card,
        .btn-back-link,
        .btn-back-link svg,
        .att-box {
            animation: none;
            transition: none;
        }
    }
</style>

@endsection