@extends('layouts.admin')

@section('title', 'Detail Jurnal - Jurnify')
@section('page-title', 'Detail Jurnal')

@section('content')
<div class="jurnal-page">

    <!-- TOP BAR / ACTION -->
    <div class="jurnal-topbar">
        <div>
            <h1 class="jurnal-title">Detail Jurnal Pembelajaran</h1>
            <p class="jurnal-subtitle">Informasi lengkap dan catatan hasil pelaksanaan sesi kelas.</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn-back">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Kembali</span>
        </a>
    </div>

    <!-- MAIN GRID CONTAINER -->
    <div class="jurnal-grid">

        <!-- KIRI: INFORMASI UTAMA & STATUS -->
        <div class="jurnal-card sidebar-card">
            <div class="card-header">
                <span class="material-symbols-outlined icon-title">info</span>
                <h2 class="card-title">Informasi Sesi</h2>
            </div>

            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Tanggal Pelaksanaan</span>
                    <span class="info-value">{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Guru Pengampu</span>
                    <span class="info-value">{{ $jurnal->guru->nama_guru ?? '-' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Mata Pelajaran & Kelas</span>
                    <span class="info-value">
                        {{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}
                        <span class="badge-kelas">{{ $jurnal->kelas->nama_kelas ?? '-' }}</span>
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Jam Ke-</span>
                    <span class="info-value">Jam {{ $jurnal->jamMulai->jam_ke ?? '-' }} - {{ $jurnal->jamSelesai->jam_ke ?? '-' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Status Kehadiran Guru</span>
                    <div>
                        <span class="badge-status-guru">
                            <span class="status-dot"></span>
                            {{ $jurnal->status_guru ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="info-item border-none">
                    <span class="info-label">Rekap Kehadiran Siswa</span>
                    <div class="attendance-box">
                        <div class="att-item att-hadir">
                            <span class="att-num">{{ $jurnal->jml_hadir ?? 0 }}</span>
                            <span class="att-label">Hadir</span>
                        </div>
                        <div class="att-item att-absen">
                            <span class="att-num">{{ $jurnal->jml_tidak_hadir ?? 0 }}</span>
                            <span class="att-label">Absen</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KANAN: KONTEN JURNAL (MATERI, TUGAS, CATATAN) -->
        <div class="main-content-wrapper">
            
            <!-- Materi Pembelajaran -->
            <div class="jurnal-card content-card">
                <div class="card-header border-b">
                    <div class="header-icon bg-blue-light">
                        <span class="material-symbols-outlined text-blue">menu_book</span>
                    </div>
                    <div>
                        <h2 class="card-title">Materi Pembelajaran</h2>
                        <p class="card-subtitle">Rincian pembahasan dan materi yang disampaikan di kelas</p>
                    </div>
                </div>
                <div class="card-body">
                    <p class="body-text">{{ $jurnal->materi ?? 'Tidak ada catatan materi yang diisikan.' }}</p>
                </div>
            </div>

            <!-- Tugas / Penugasan -->
            @if($jurnal->ada_tugas || $jurnal->deskripsi_tugas)
                <div class="jurnal-card content-card">
                    <div class="card-header border-b">
                        <div class="header-icon bg-amber-light">
                            <span class="material-symbols-outlined text-amber">assignment</span>
                        </div>
                        <div>
                            <h2 class="card-title">Tugas & Penugasan</h2>
                            <p class="card-subtitle">Instruksi atau PR yang diberikan kepada siswa</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="body-text">{{ $jurnal->deskripsi_tugas ?? 'Ada penugasan untuk sesi ini.' }}</p>
                    </div>
                </div>
            @endif

            <!-- Catatan Admin / Evaluasi -->
            @if($jurnal->catatan_revisi || $jurnal->catatan_umum)
                <div class="jurnal-card content-card alert-card">
                    <div class="card-header border-b">
                        <div class="header-icon bg-red-light">
                            <span class="material-symbols-outlined text-red">rate_review</span>
                        </div>
                        <div>
                            <h2 class="card-title text-red-dark">Catatan Evaluasi / Revisi</h2>
                            <p class="card-subtitle text-red-sub">Umpan balik atau catatan evaluasi dari admin</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="body-text text-red-body">{{ $jurnal->catatan_revisi ?: $jurnal->catatan_umum }}</p>
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

@push('styles')
<style>
    .jurnal-page {
        padding: 0 10px 10px 10px;
        max-width: 1440px;
        margin: 0 auto;
        animation: pageIn 0.55s ease both;
        font-family: 'Manrope', sans-serif;
    }

    /* TOPBAR */
    .jurnal-topbar {
        background: #FFFFFF;
        border-radius: 14px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        box-shadow: 0 2px 12px rgba(48, 54, 111, 0.03);
        margin-bottom: 20px;
    }

    .jurnal-title {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #30366F;
        line-height: 1.2;
    }

    .jurnal-subtitle {
        margin: 4px 0 0 0;
        font-size: 13px;
        color: #62708F;
        font-weight: 500;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #F0F3FA;
        color: #30366F;
        font-size: 13px;
        font-weight: 700;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.25s ease;
        border: 1px solid #E1E4EC;
    }

    .btn-back:hover {
        background: #7886C7;
        color: #FFFFFF;
        border-color: #7886C7;
        transform: translateY(-2px);
    }

    .btn-back .material-symbols-outlined {
        font-size: 18px;
    }

    /* GRID LAYOUT */
    .jurnal-grid {
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 20px;
        align-items: start;
    }

    /* CARD SYSTEM */
    .jurnal-card {
        background: #FFFFFF;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(48, 54, 111, 0.03);
        border: 1px solid #E1E4EC;
        overflow: hidden;
    }

    .sidebar-card {
        padding: 20px;
    }

    .main-content-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* CARD HEADERS */
    .card-header {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header.border-b {
        padding: 16px 20px;
        border-bottom: 1px solid #E1E4EC;
    }

    .icon-title {
        color: #7886C7;
        font-size: 22px;
    }

    .card-title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #30366F;
    }

    .card-subtitle {
        margin: 2px 0 0 0;
        font-size: 12px;
        color: #62708F;
    }

    .header-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .bg-blue-light { background: #EEF2FF; }
    .text-blue { color: #4338CA; }

    .bg-amber-light { background: #FFFBEB; }
    .text-amber { color: #D97706; }

    .bg-red-light { background: #FEF2F2; }
    .text-red { color: #DC2626; }

    /* SIDEBAR INFO LIST */
    .info-list {
        margin-top: 16px;
        display: flex;
        flex-direction: column;
    }

    .info-item {
        padding: 12px 0;
        border-bottom: 1px solid #F0F3FA;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-item.border-none {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-label {
        font-size: 11px;
        font-weight: 700;
        color: #62708F;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #30366F;
    }

    .badge-kelas {
        display: inline-block;
        background: #B4BFE5;
        color: #30366F;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 4px;
    }

    .badge-status-guru {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #EEF2FF;
        color: #4338CA;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #4338CA;
    }

    /* REKAP KEHADIRAN BOX */
    .attendance-box {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 6px;
    }

    .att-item {
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        display: flex;
        flex-direction: column;
    }

    .att-hadir {
        background: #F0FDF4;
        border: 1px solid #DCFCE7;
    }

    .att-absen {
        background: #FEF2F2;
        border: 1px solid #FEE2E2;
    }

    .att-num {
        font-size: 18px;
        font-weight: 800;
    }

    .att-hadir .att-num { color: #16A34A; }
    .att-absen .att-num { color: #DC2626; }

    .att-label {
        font-size: 11px;
        font-weight: 700;
        color: #62708F;
    }

    /* CARD BODY CONTENT */
    .card-body {
        padding: 20px;
    }

    .body-text {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
        color: #4A5568;
        white-space: pre-line;
        font-weight: 500;
    }

    /* ALERT REVISI CARD */
    .alert-card {
        border-color: #FCA5A5;
        background: #FFF5F5;
    }

    .text-red-dark { color: #991B1B; }
    .text-red-sub { color: #B91C1C; }
    .text-red-body { color: #7F1D1D; }

    /* ANIMATION & RESPONSIVE */
    @keyframes pageIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 992px) {
        .jurnal-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .jurnal-page {
            padding: 0 12px 12px 12px;
        }

        .jurnal-topbar {
            flex-direction: column;
            align-items: flex-start;
            padding: 16px;
        }

        .btn-back {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush