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

    {{-- HEADER CARD --}}
    <div class="jurnal-header-card">
        <div>
            <h1 class="jurnal-title">Detail Jurnal</h1>
            <p class="jurnal-subtitle">
                {{ $jurnal->kelas->nama_kelas ?? '-' }} &bull; {{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}
            </p>
        </div>

        {{-- STATUS BADGE --}}
        @if(($jurnal->status_validasi_guru ?? 'Menunggu') === 'Menunggu')
            <span class="status-badge status-menunggu">
                <span class="status-dot warning"></span>
                Menunggu Validasi
            </span>
        @elseif(($jurnal->status_validasi_guru ?? '') === 'Valid')
            <span class="status-badge status-valid">
                <span class="status-dot success"></span>
                Valid
            </span>
        @else
            <span class="status-badge status-default">
                {{ $jurnal->status_validasi_guru ?? '-' }}
            </span>
        @endif
    </div>

    {{-- GRID ATAS (INFORMASI & KEHADIRAN) --}}
    <div class="jurnal-grid-top">
        
        {{-- INFORMASI PEMBELAJARAN --}}
        <div class="jurnal-card" style="animation-delay: 0.05s;">
            <h2 class="card-heading">Informasi Pembelajaran</h2>

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
                    <span class="info-label">Jam Pelajaran</span>
                    <div class="info-val">
                        Jam Ke-{{ $jurnal->jamMulai->jam_ke ?? '-' }} ({{ $jurnal->jamMulai->jam ?? '-' }} - {{ $jurnal->jamSelesai->jam ?? '-' }})
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
            <h2 class="card-heading">Kehadiran Siswa</h2>

            <div class="attendance-grid">
                <div class="att-box hadir">
                    <div class="att-number-hadir">{{ $jurnal->jml_hadir ?? 0 }}</div>
                    <div class="att-label-hadir">Hadir</div>
                </div>

                <div class="att-box absen">
                    <div class="att-number-absen">{{ $jurnal->jml_tidak_hadir ?? 0 }}</div>
                    <div class="att-label-absen">Tidak Hadir</div>
                </div>
            </div>
        </div>

    </div>

    {{-- MATERI PEMBELAJARAN --}}
    <div class="jurnal-card section-card" style="animation-delay: 0.15s;">
        <h2 class="card-heading">Materi Pembelajaran</h2>
        <div class="text-content-box">
            {{ $jurnal->materi ?? 'Tidak ada catatan materi.' }}
        </div>
    </div>

    {{-- TUGAS --}}
    <div class="jurnal-card section-card" style="animation-delay: 0.2s;">
        <h2 class="card-heading">Tugas / Penugasan</h2>

        <div class="task-status-row">
            <span class="task-status-label">Status Tugas:</span>
            <span class="task-status-badge">
                {{ $jurnal->ada_tugas ?? 'Tidak' }}
            </span>
        </div>

        @if($jurnal->deskripsi_tugas)
            <div class="task-box">
                {{ $jurnal->deskripsi_tugas }}
            </div>
        @else
            <div class="text-content-box empty-text">
                Tidak ada deskripsi tugas yang diberikan.
            </div>
        @endif
    </div>

    {{-- CATATAN UMUM --}}
    @if($jurnal->catatan_umum)
        <div class="jurnal-card section-card" style="animation-delay: 0.25s;">
            <h2 class="card-heading">Catatan Umum</h2>
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
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 40px;
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

    /* HEADER CARD */
    .jurnal-header-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
        background: #FFFFFF;
        padding: 22px 26px;
        border-radius: 18px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    }

    .jurnal-title {
        margin: 0;
        color: #2D336B;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: -0.4px;
    }

    .jurnal-subtitle {
        margin: 4px 0 0 0;
        color: #64748B;
        font-size: 13.5px;
        font-weight: 500;
    }

    /* STATUS BADGES */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-menunggu {
        background: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    .status-valid {
        background: #DCFCE7;
        color: #14532D;
        border: 1px solid #BBF7D0;
    }

    .status-default {
        background: #F1F5F9;
        color: #475569;
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
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .jurnal-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
        animation: cardUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .jurnal-card:hover {
        transform: translateY(-2px);
        border-color: #CBD5E1;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }

    .section-card {
        margin-bottom: 20px;
    }

    .card-heading {
        margin: 0 0 18px 0;
        color: #2D336B;
        font-size: 16px;
        font-weight: 800;
        border-bottom: 1px solid #F1F5F9;
        padding-bottom: 12px;
        letter-spacing: -0.2px;
    }

    /* INFO ITEMS GRID */
    .info-items-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px 14px;
    }

    .info-label {
        display: block;
        color: #64748B;
        font-size: 11.5px;
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

    .guru-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
        background: #EEF2FF;
        color: #2D336B;
        font-size: 12px;
        font-weight: 700;
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
        transition: transform 0.2s ease;
    }

    .att-box:hover {
        transform: scale(1.02);
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

    .att-label-hadir { margin-top: 8px; color: #15803D; font-size: 12.5px; font-weight: 700; }
    .att-label-absen { margin-top: 8px; color: #B91C1C; font-size: 12.5px; font-weight: 700; }

    /* CONTENT BOXES */
    .text-content-box {
        padding: 16px 18px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        color: #334155;
        font-size: 14px;
        line-height: 1.7;
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
        color: #64748B;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-status-badge {
        color: #2D336B;
        font-size: 12.5px;
        font-weight: 800;
        background: #EEF2FF;
        padding: 3px 10px;
        border-radius: 6px;
    }

    .task-box {
        padding: 16px 18px;
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 12px;
        color: #92400E;
        font-size: 14px;
        line-height: 1.7;
        white-space: pre-line;
    }

    /* REVISION CARD */
    .revision-card {
        background: #FFFBEB;
        border: 1px solid #FCD34D;
        border-radius: 18px;
        padding: 20px 24px;
        margin-bottom: 20px;
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
        font-size: 14px;
        line-height: 1.6;
        font-weight: 600;
        white-space: pre-line;
    }

    /* ACTION FOOTER & BUTTON */
    .action-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 24px;
    }

    .btn-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border: 1px solid #CBD5E1;
        border-radius: 12px;
        background: #FFFFFF;
        color: #334155;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 2px 4px rgba(15, 23, 42, 0.04);
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
    @media (max-width: 767px) {
        .jurnal-header-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 18px 20px;
            gap: 12px;
        }

        .jurnal-title {
            font-size: 19px;
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