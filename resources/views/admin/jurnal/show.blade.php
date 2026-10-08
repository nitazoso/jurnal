@extends('layouts.admin')

@section('title', 'Detail Jurnal - Jurnify')
@section('page-title', 'Detail Jurnal')
@section('page-subtitle', 'Informasi lengkap dan catatan pelaksanaan sesi pembelajaran')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<div class="jurnal-detail-page">

    {{-- TOP BAR --}}
    <div class="jurnal-topbar">
        <div class="topbar-info">
            <h1 class="jurnal-title">Detail Jurnal Pembelajaran</h1>
            <p class="jurnal-subtitle">Informasi lengkap dan catatan hasil pelaksanaan sesi kelas.</p>
        </div>

        <a href="{{ url()->previous() }}" class="btn-back">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Kembali</span>
        </a>
    </div>

    {{-- MAIN GRID --}}
    <div class="jurnal-grid">

        {{-- ================================
             KIRI: INFORMASI UTAMA
        ================================= --}}
        <div class="jurnal-card sidebar-card">

            <div class="card-header-inner">
                <span class="material-symbols-outlined icon-title">info</span>
                <h2 class="card-title">Informasi Sesi</h2>
            </div>

            <div class="info-list">

                {{-- TANGGAL --}}
                <div class="info-item">
                    <span class="info-label">Tanggal Pelaksanaan</span>
                    <span class="info-value">
                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}
                    </span>
                </div>

                {{-- GURU --}}
                <div class="info-item">
                    <span class="info-label">Guru Pengampu</span>
                    <span class="info-value">
                        {{ $jurnal->guru->nama_guru ?? $jurnal->user->nama_user ?? '-' }}
                    </span>
                </div>

                {{-- MAPEL & KELAS --}}
                <div class="info-item">
                    <span class="info-label">Mata Pelajaran & Kelas</span>
                    <span class="info-value mapel-kelas-wrapper">
                        <span>
                            {{ $jurnal->jadwal->mapel->nama_mapel ?? $jurnal->mapel->nama_mapel ?? '-' }}
                        </span>
                        <span class="badge-kelas">
                            {{ $jurnal->kelas->nama_kelas ?? '-' }}
                        </span>
                    </span>
                </div>

                {{-- JAM --}}
                <div class="info-item">
                    <span class="info-label">Jam Ke-</span>
                    <span class="info-value">
                        Jam {{ $jurnal->jamMulai->jam_ke ?? $jurnal->jam_ke_mulai ?? '-' }}
                        - {{ $jurnal->jamSelesai->jam_ke ?? $jurnal->jam_ke_selesai ?? '-' }}
                    </span>
                </div>

                {{-- STATUS GURU --}}
                <div class="info-item">
                    <span class="info-label">Status Kehadiran Guru</span>
                    <div>
                        <span class="badge-status-guru">
                            <span class="status-dot"></span>
                            {{ $jurnal->status_guru ?? 'Hadir' }}
                        </span>
                    </div>
                </div>

                {{-- REKAP KEHADIRAN --}}
                <div class="info-item border-none">
                    <span class="info-label">Rekap Kehadiran Siswa</span>

                    @php
                        $detailAbsensi = collect($jurnal->detailAbsensis ?? []);
                        $siswaTidakHadir = $detailAbsensi
                            ->filter(fn ($detail) => strtolower((string) ($detail->status ?? '')) !== 'hadir'
                                && strtolower((string) ($detail->keterangan ?? '')) !== 'hadir')
                            ->sortBy(fn ($detail) => $detail->siswa->nama_siswa ?? '')
                            ->values();
                    @endphp

                    <div class="attendance-box">
                        <div class="att-item att-hadir">
                            <span class="att-num">{{ $jurnal->jml_hadir ?? 0 }}</span>
                            <span class="att-label">Hadir</span>
                        </div>

                        <div class="att-item att-absen">
                            <span class="att-num">{{ $jurnal->jml_tidak_hadir ?? 0 }}</span>
                            <span class="att-label">Tidak Hadir</span>
                        </div>

                    </div>

                    <div class="attendance-name-groups">
                        <section class="attendance-name-group is-absent">
                            <h4>Nama siswa tidak hadir</h4>
                            @if($siswaTidakHadir->isNotEmpty())
                                <ol>
                                    @foreach($siswaTidakHadir as $detail)
                                        <li>
                                            {{ $detail->siswa->nama_siswa ?? 'Nama siswa tidak tersedia' }}
                                            <span>{{ $detail->status ?? $detail->keterangan ?? 'Tidak Hadir' }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                            @else
                                <p>Tidak ada siswa yang tercatat tidak hadir.</p>
                            @endif
                        </section>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================================
             KANAN: KONTEN JURNAL
        ================================= --}}
        <div class="main-content-wrapper">

            {{-- MATERI --}}
            <div class="jurnal-card content-card">
                <div class="card-header-inner border-b">
                    <div class="header-icon bg-blue-light">
                        <span class="material-symbols-outlined text-blue">menu_book</span>
                    </div>
                    <div>
                        <h2 class="card-title">Materi Pembelajaran</h2>
                        <p class="card-subtitle">Rincian pembahasan dan materi yang disampaikan di kelas</p>
                    </div>
                </div>

                <div class="card-body">
                    <p class="body-text">
                        {{ $jurnal->materi ?? 'Tidak ada catatan materi yang diisikan.' }}
                    </p>
                </div>
            </div>

            {{-- TUGAS --}}
            @if($jurnal->ada_tugas || $jurnal->deskripsi_tugas)
                <div class="jurnal-card content-card">
                    <div class="card-header-inner border-b">
                        <div class="header-icon bg-amber-light">
                            <span class="material-symbols-outlined text-amber">assignment</span>
                        </div>
                        <div>
                            <h2 class="card-title">Tugas & Penugasan</h2>
                            <p class="card-subtitle">Instruksi atau PR yang diberikan kepada siswa</p>
                        </div>
                    </div>

                    <div class="card-body">
                        <p class="body-text">
                            {{ $jurnal->deskripsi_tugas ?? 'Ada penugasan untuk sesi ini.' }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- CATATAN --}}
            @if($jurnal->catatan_revisi || $jurnal->catatan_umum)
                <div class="jurnal-card content-card alert-card">
                    <div class="card-header-inner border-b">
                        <div class="header-icon bg-red-light">
                            <span class="material-symbols-outlined text-red">rate_review</span>
                        </div>
                        <div>
                            <h2 class="card-title text-red-dark">Catatan</h2>
                            <p class="card-subtitle text-red-sub">Umpan balik atau catatan evaluasi dari sekretaris</p>
                        </div>
                    </div>

                    <div class="card-body">
                        <p class="body-text text-red-body">
                            {{ $jurnal->catatan_revisi ?: $jurnal->catatan_umum }}
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<style>
/* =====================================================
   MATERIAL ICON
===================================================== */
.material-symbols-outlined {
    font-family: 'Material Symbols Outlined' !important;
    font-weight: normal;
    font-style: normal;
    font-size: 20px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    -webkit-font-feature-settings: 'liga';
    -webkit-font-smoothing: antialiased;
}

/* =====================================================
   GLOBAL
===================================================== */
.jurnal-detail-page,
.jurnal-detail-page * {
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    box-sizing: border-box;
}

.jurnal-detail-page {
    width: 100%;
    animation: pageIn 0.45s cubic-bezier(.16, 1, .3, 1) both;
}

/* =====================================================
   TOPBAR
===================================================== */
.jurnal-topbar {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 24px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03);
    margin-bottom: 24px;
}

.topbar-info {
    min-width: 0;
}

.jurnal-title {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: #2D336B;
    line-height: 1.2;
    letter-spacing: -0.4px;
}

.jurnal-subtitle {
    margin: 4px 0 0;
    font-size: 13.5px;
    color: #64748B;
    font-weight: 500;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    background: #FFFFFF;
    color: #475569;
    font-size: 13.5px;
    font-weight: 700;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid #CBD5E1;
    white-space: nowrap;
    flex-shrink: 0;
}

.btn-back:hover {
    background: #F1F5F9;
    color: #0F172A;
    transform: translateY(-1px);
}

/* =====================================================
   GRID
===================================================== */
.jurnal-grid {
    display: grid;
    grid-template-columns: 360px minmax(0, 1fr);
    gap: 24px;
    align-items: start;
}

/* =====================================================
   CARD
===================================================== */
.jurnal-card {
    background: #FFFFFF;
    border-radius: 20px;
    box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03);
    border: 1px solid #E2E8F0;
    overflow: hidden;
}

.sidebar-card {
    padding: 24px;
}

.main-content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 24px;
    min-width: 0;
}

/* =====================================================
   CARD HEADER
===================================================== */
.card-header-inner {
    display: flex;
    align-items: center;
    gap: 14px;
}

.card-header-inner.border-b {
    padding: 20px 24px;
    background: #FAFAFC;
    border-bottom: 1px solid #F1F5F9;
}

.icon-title {
    color: #7886C7;
    font-size: 24px !important;
}

.card-title {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    color: #1E293B;
}

.card-subtitle {
    margin: 2px 0 0;
    font-size: 12.5px;
    color: #64748B;
    font-weight: 500;
    line-height: 1.5;
}

.header-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.header-icon .material-symbols-outlined {
    font-size: 22px !important;
}

.bg-blue-light { background: #EEF2FF; }
.text-blue { color: #4F46E5; }

.bg-amber-light { background: #FFFBEB; }
.text-amber { color: #D97706; }

.bg-red-light { background: #FEF2F2; }
.text-red { color: #DC2626; }

/* =====================================================
   INFO LIST
===================================================== */
.info-list {
    margin-top: 16px;
    display: flex;
    flex-direction: column;
}

.info-item {
    padding: 14px 0;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item.border-none {
    border-bottom: none;
    padding-bottom: 0;
}

.info-label {
    font-size: 12px;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.info-value {
    font-size: 14.5px;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.45;
}

.mapel-kelas-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.badge-kelas {
    display: inline-block;
    background: #F0F3FF;
    color: #2D336B;
    padding: 3px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 800;
}

/* =====================================================
   STATUS GURU
===================================================== */
.badge-status-guru {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: #EEF2FF;
    color: #4F46E5;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #4F46E5;
}

/* =====================================================
   ATTENDANCE
===================================================== */
.attendance-box {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 8px;
}

.attendance-button {
    border: none;
    cursor: pointer;
    font-family: inherit;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.attendance-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
}

.attendance-button:active {
    transform: translateY(0);
}

.att-item {
    padding: 12px;
    border-radius: 14px;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.att-hadir {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
}

.att-absen {
    background: #FEF2F2;
    border: 1px solid #FECACA;
}

.att-num {
    font-size: 20px;
    font-weight: 800;
}

.att-hadir .att-num { color: #166534; }
.att-absen .att-num { color: #991B1B; }

.att-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748B;
}

.att-click {
    margin-top: 5px;
    font-size: 10px;
    font-weight: 700;
    color: #64748B;
}

.attendance-name-groups { display:grid; gap:12px; margin-top:14px; }
.attendance-name-group { padding:13px 14px; border:1px solid #BBF7D0; border-radius:12px; background:#F8FFF9; }
.attendance-name-group.is-absent { border-color:#FECACA; background:#FFFAFA; }
.attendance-name-group h4 { margin:0 0 8px; color:#166534; font-size:11px; font-weight:850; }
.attendance-name-group.is-absent h4 { color:#991B1B; }
.attendance-name-group ol { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:6px 18px; margin:0; padding-left:20px; color:#334155; font-size:11px; }
.attendance-name-group li { min-width:0; padding-left:2px; overflow-wrap:anywhere; }
.attendance-name-group li span { display:inline-block; margin-left:4px; color:#b91c1c; font-size:10px; font-weight:750; }
.attendance-name-group p { margin:0; color:#64748B; font-size:11px; }

/* =====================================================
   CONTENT
===================================================== */
.card-body {
    padding: 24px;
}

.body-text {
    margin: 0;
    font-size: 14.5px;
    line-height: 1.65;
    color: #334155;
    white-space: pre-line;
    font-weight: 500;
}

/* =====================================================
   ALERT
===================================================== */
.alert-card {
    border-color: #FECACA;
    background: #FEF2F2;
}

.alert-card .card-header-inner.border-b {
    background: #FEF2F2;
    border-bottom-color: #FECACA;
}

.text-red-dark { color: #991B1B; }
.text-red-sub { color: #B91C1C; }
.text-red-body { color: #7F1D1D; }

/* =====================================================
   ANIMATION
===================================================== */
@keyframes pageIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 992px) {
    .jurnal-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .jurnal-topbar {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
    }

    .btn-back {
        width: 100%;
    }

    .sidebar-card {
        padding: 20px;
    }

    .card-header-inner.border-b {
        padding: 18px 20px;
    }

    .card-body {
        padding: 20px;
    }

    .attendance-box {
        grid-template-columns: 1fr;
    }

}
</style>



@endsection
