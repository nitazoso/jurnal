@extends('layouts.guru')

@section('title', 'Isi Jurnal Mengajar - Jurnify')

@section('page-title', 'Isi Jurnal Mengajar')

@section('page-subtitle', 'Lengkapi data aktivitas pembelajaran di kelas secara berkala.')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>

/* GLOBAL RESET & FONT */

.create-journal-page,
.create-journal-page * {
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    box-sizing: border-box;
}

.create-journal-page {
    width: 100%;
    padding: 0;
    margin: 0;
    color: #1E293B;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* HERO SECTION */

.journal-hero {
    position: relative;
    width: 100%;
    padding: 32px 28px;
    background: linear-gradient(135deg, #A9B5DF 0%, #BFC9EA 100%);
    border: 1px solid rgba(255, 255, 255, 0.65);
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(45, 51, 107, 0.08);
    overflow: hidden;
    isolation: isolate;
    animation: fadeUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.journal-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(45, 51, 107, 0.12);
}

.journal-hero::before {
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
    transition: transform 0.5s ease;
}

.journal-hero::after {
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
    transition: transform 0.5s ease;
}

.journal-hero:hover::before {
    transform: translate(-6px, -4px);
}

.journal-hero:hover::after {
    transform: scale(1.05) rotate(6deg);
}

.journal-hero-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 14px;
    margin-bottom: 12px;
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.85);
    color: #2D336B;
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 0.4px;
    box-shadow: 0 2px 8px rgba(45, 51, 107, 0.06);
    transition: background 0.2s ease, transform 0.2s ease;
}

.journal-hero:hover .journal-hero-badge {
    background: #FFFFFF;
    transform: translateY(-1px);
}

.journal-hero-title {
    margin: 0;
    color: #2D336B;
    font-size: 24px;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: -0.4px;
}

.journal-hero-text {
    max-width: 800px;
    margin: 6px 0 0;
    color: #475569;
    font-size: 13.5px;
    line-height: 1.6;
    font-weight: 500;
}

/* SCHEDULE CARD */

.schedule-card {
    width: 100%;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03);
    overflow: hidden;
    animation: fadeUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) 0.08s both;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.schedule-card:hover {
    transform: translateY(-2px);
    border-color: #CBD5E1;
    box-shadow: 0 10px 24px rgba(45, 51, 107, 0.06);
}

.schedule-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.schedule-card-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.schedule-icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: #EEF2FF;
    color: #2D336B;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.25s ease, background 0.25s ease;
}

.schedule-card:hover .schedule-icon {
    transform: rotate(-4deg) scale(1.05);
    background: #E0E7FF;
}

.schedule-icon svg {
    width: 20px;
    height: 20px;
}

.schedule-card-title h2 {
    margin: 0;
    color: #2D336B;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: -0.2px;
}

.schedule-today {
    display: block;
    margin-top: 3px;
    color: #64748B;
    font-size: 11.5px;
    font-weight: 600;
}

.schedule-card-note {
    color: #64748B;
    font-size: 12.5px;
    font-weight: 600;
    transition: color 0.2s ease;
}

.schedule-card:hover .schedule-card-note {
    color: #2D336B;
}

/* TABLE SECTION */

.schedule-content {
    padding: 16px 20px 20px;
}

.schedule-list {
    display: grid;
    gap: 12px;
}

.schedule-item {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 14px;
    align-items: center;
    padding: 16px;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    background: #fff;
}

.schedule-item-main { min-width: 0; }
.schedule-item-meta { display:flex; flex-wrap:wrap; gap:7px; align-items:center; margin-bottom:8px; }
.schedule-item-number { color:#64748B; font-size:11px; font-weight:800; }
.schedule-item-class { padding:4px 9px; border-radius:7px; background:#EEF2FF; color:#2D336B; font-size:11px; font-weight:800; }
.schedule-item-subject { margin:0; color:#0F172A; font-size:15px; font-weight:800; }
.schedule-item-time { display:flex; flex-wrap:wrap; align-items:center; gap:6px; margin-top:7px; color:#64748B; font-size:12px; font-weight:650; }
.schedule-item-status { display:inline-flex; align-items:center; gap:5px; margin-top:9px; font-size:11px; font-weight:800; }
.schedule-item-status.is-ready { color:#047857; }
.schedule-item-status.is-locked { color:#B45309; }
.schedule-item-status.is-done { color:#475569; }
.schedule-item-action { display:flex; flex-direction:column; align-items:flex-end; gap:7px; }
.journal-action.is-disabled { background:#E2E8F0; color:#64748B; cursor:not-allowed; box-shadow:none; }
.journal-action.is-disabled:hover { transform:none; box-shadow:none; }
.schedule-locked-note { max-width:180px; color:#64748B; font-size:10px; line-height:1.4; text-align:right; }
.schedule-empty { padding:28px 16px; border:1px dashed #CBD5E1; border-radius:12px; color:#64748B; text-align:center; }

.schedule-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}

.schedule-table thead th {
    padding: 10px 16px;
    color: #64748B;
    font-size: 11.5px;
    font-weight: 800;
    text-align: left;
    white-space: nowrap;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.schedule-table thead th:first-child {
    width: 60px;
    text-align: center;
}

.schedule-table thead th:last-child {
    width: 140px;
    text-align: center;
}

.schedule-table tbody td {
    padding: 14px 16px;
    background: #FFFFFF;
    border-top: 1px solid #E2E8F0;
    border-bottom: 1px solid #E2E8F0;
    color: #0F172A;
    font-size: 13.5px;
    font-weight: 600;
    vertical-align: middle;
    white-space: nowrap;
    transition: background 0.2s ease, border-color 0.2s ease;
}

.schedule-table tbody td:first-child {
    border-left: 1px solid #E2E8F0;
    border-radius: 12px 0 0 12px;
    text-align: center;
}

.schedule-table tbody td:last-child {
    border-right: 1px solid #E2E8F0;
    border-radius: 0 12px 12px 0;
    text-align: center;
}

.schedule-table tbody tr {
    transition: transform 0.2s ease;
}

.schedule-table tbody tr:hover {
    transform: translateX(4px);
}

.schedule-table tbody tr:hover td {
    background: #F8FAFC;
    border-color: #CBD5E1;
}

/* TABLE DATA ELEMENTS */

.schedule-number {
    width: 32px;
    height: 32px;
    margin: 0 auto;
    border-radius: 8px;
    background: #F1F5F9;
    color: #2D336B;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 800;
    transition: transform 0.2s ease, background 0.2s ease;
}

.schedule-table tbody tr:hover .schedule-number {
    transform: scale(1.06);
    background: #EEF2FF;
}

.schedule-day {
    color: #0F172A;
    font-weight: 750;
    font-size: 13.5px;
}

.schedule-class {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 8px;
    background: #EEF2FF;
    color: #2D336B;
    font-size: 12.5px;
    font-weight: 800;
    transition: background 0.2s ease, transform 0.2s ease;
}

.schedule-table tbody tr:hover .schedule-class {
    background: #E0E7FF;
    transform: translateY(-1px);
}

.schedule-subject {
    color: #0F172A;
    font-weight: 700;
    font-size: 14px;
}

.schedule-time {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #475569;
    font-size: 12.5px;
    font-weight: 700;
}

.schedule-time svg {
    width: 15px;
    height: 15px;
    color: #2D336B;
    flex-shrink: 0;
    transition: transform 0.25s ease;
}

.schedule-table tbody tr:hover .schedule-time svg {
    transform: rotate(15deg);
}

/* ACTION BUTTON */

.journal-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 9px 16px;
    border-radius: 10px;
    background: #2D336B;
    color: #FFFFFF;
    text-decoration: none;
    font-size: 12.5px;
    font-weight: 800;
    box-shadow: 0 3px 8px rgba(45, 51, 107, 0.12);
    transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
}

.journal-action:hover {
    background: #1E234A;
    color: #FFFFFF;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(45, 51, 107, 0.22);
}

.journal-action:active {
    transform: translateY(0) scale(0.98);
}

.journal-action svg {
    width: 15px;
    height: 15px;
    transition: transform 0.2s ease;
}

.journal-action:hover svg {
    transform: rotate(90deg);
}

/* EMPTY STATE */

.empty-row td {
    padding: 0 !important;
    border: 0 !important;
    background: transparent !important;
}

.empty-state {
    padding: 48px 20px;
    margin: 8px 0;
    border: 1px dashed #CBD5E1;
    border-radius: 14px;
    background: #F8FAFC;
    text-align: center;
}

.empty-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 12px;
    border-radius: 12px;
    background: #EEF2FF;
    color: #2D336B;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon svg {
    width: 24px;
    height: 24px;
}

.empty-title {
    margin: 0;
    color: #0F172A;
    font-size: 14.5px;
    font-weight: 800;
}

.empty-text {
    margin: 4px 0 0;
    color: #64748B;
    font-size: 12.5px;
    font-weight: 500;
}

/* ANIMATIONS & RESPONSIVE */

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 992px) {
    .schedule-table {
        min-width: 760px;
    }
}

@media (max-width: 767px) {

    .create-journal-page {
        gap: 16px;
    }

    .journal-hero {
        padding: 24px 20px;
        border-radius: 16px;
    }

    .journal-hero-title {
        font-size: 20px;
    }

    .journal-hero-text {
        font-size: 12.5px;
    }

    .schedule-card {
        border-radius: 16px;
    }

    .schedule-card-header {
        padding: 16px 18px;
    }

    .schedule-card-note {
        display: none;
    }

    .schedule-content {
        padding: 12px 14px 16px;
    }

    .schedule-item {
        grid-template-columns: minmax(0, 1fr);
        gap: 12px;
        padding: 14px;
    }

    .schedule-item-action {
        align-items: flex-start;
        flex-direction: row;
        flex-wrap: wrap;
    }

    .schedule-locked-note {
        max-width: none;
        text-align: left;
        align-self: center;
    }

    .schedule-today {
        font-size: 10.5px;
    }
}

@media (prefers-reduced-motion: reduce) {

    .journal-hero,
    .schedule-card,
    .schedule-table tbody tr,
    .journal-action,
    .schedule-icon,
    .schedule-time svg,
    .journal-action svg {
        animation: none;
        transition: none;
    }
}

</style>

<div class="create-journal-page">

    @if($allDisabled)

        <section class="schedule-card" role="status">
            <div class="empty-state">
                <div class="empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <path d="m5.6 5.6 12.8 12.8" stroke-linecap="round" />
                    </svg>
                </div>
                <p class="empty-title">Pengisian jurnal sementara dinonaktifkan</p>
                <p class="empty-text">Admin mengaktifkan mode event. Fitur pengisian jurnal akan tersedia kembali setelah mode event dinonaktifkan.</p>
            </div>
        </section>

    @else

    {{-- HERO --}}
    <section class="journal-hero">

        <span class="journal-hero-badge">
            SEMESTER GANJIL
        </span>

        <h1 class="journal-hero-title">
            Isi Jurnal Mengajar
        </h1>

        <p class="journal-hero-text">
            Lengkapi data aktivitas pembelajaran di kelas secara berkala untuk mempermudah monitoring kurikulum dan presensi siswa.
        </p>

    </section>

    {{-- JADWAL --}}
    <section class="schedule-card">

        <div class="schedule-card-header">

            <div class="schedule-card-title">

                <div class="schedule-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="18" height="18" x="3" y="4" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                </div>

                <div>
                    <h2>JADWAL & KELAS</h2>
                    <span class="schedule-today">
                        {{ config('app.jurnal_bebas_testing') ? 'Mode testing · Semua jadwal' : $hariIni }}
                        · {{ $today->translatedFormat('d F Y') }}
                    </span>
                </div>

            </div>

            <span class="schedule-card-note">
                {{ config('app.jurnal_bebas_testing') ? 'Bebas hari & jam' : 'Jadwal Hari Ini' }}
            </span>

        </div>

        <div class="schedule-content">
            @if($jadwals->isEmpty())
                <div class="schedule-empty">
                    Tidak ada jadwal mengajar untuk {{ config('app.jurnal_bebas_testing') ? 'akun ini' : 'hari ini' }}.
                </div>
            @else
                <div class="schedule-list">
                    @foreach($jadwals as $jadwal)
                        @php
                            $sudahDiisiHariIni = $jadwal->jurnal_sudah_diisi_hari_ini;
                            $bisaDiisiSekarang = $jadwal->bisa_diisi_sekarang && !$sudahDiisiHariIni;
                            $jamMulaiDisplay = $jadwal->jamMulai?->jam_mulai ? substr($jadwal->jamMulai->jam_mulai, 0, 5) : '--:--';
                            $jamSelesaiDisplay = $jadwal->jamSelesai?->jam_selesai ? substr($jadwal->jamSelesai->jam_selesai, 0, 5) : '--:--';
                        @endphp
                        <article class="schedule-item">
                            <div class="schedule-item-main">
                                <div class="schedule-item-meta">
                                    <span class="schedule-item-number">Jadwal {{ $loop->iteration }}</span>
                                    <span class="schedule-item-class">{{ $jadwal->kelas->nama_kelas ?? '-' }}</span>
                                </div>
                                <h3 class="schedule-item-subject">{{ $jadwal->mapel->nama_mapel ?? 'Mata pelajaran' }}</h3>
                                <div class="schedule-item-time">
                                    <span>{{ $jadwal->hari }}</span><span>·</span>
                                    <span>{{ $jamMulaiDisplay }}–{{ $jamSelesaiDisplay }}</span><span>·</span>
                                    <span>Jam {{ $jadwal->jamMulai->jam_ke ?? '-' }}–{{ $jadwal->jamSelesai->jam_ke ?? '-' }}</span>
                                </div>
                                @if($sudahDiisiHariIni)
                                    <span class="schedule-item-status is-done">✓ Jurnal hari ini sudah diisi</span>
                                @elseif($bisaDiisiSekarang)
                                    <span class="schedule-item-status is-ready">● {{ $jadwal->jurnal_terlambat ? 'Bisa diisi terlambat' : 'Waktu pengisian tersedia' }}</span>
                                @else
                                    <span class="schedule-item-status is-locked">◷ Belum waktunya mengisi</span>
                                @endif
                            </div>
                            <div class="schedule-item-action">
                                @if($sudahDiisiHariIni)
                                    <a href="{{ route('guru.jurnal.index') }}" class="journal-action">Lihat Jurnal</a>
                                @elseif($bisaDiisiSekarang)
                                    <a href="{{ route('guru.jurnal.form', ['jadwal' => $jadwal->id_jadwal]) }}" class="journal-action">＋ Isi Jurnal</a>
                                @else
                                    <span class="journal-action is-disabled" aria-disabled="true">Belum tersedia</span>
                                    <span class="schedule-locked-note">Tombol aktif mulai {{ $jamMulaiDisplay }}</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

    </section>

    @endif

    @if(!empty($jadwalsTertinggal) && $jadwalsTertinggal->isNotEmpty())
        <section class="schedule-card">
            <div class="schedule-card-header">
                <div class="schedule-card-title">
                    <div class="schedule-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 2" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                    </div>
                    <div>
                        <h2>JURNAL TERLAMBAT</h2>
                        <span class="schedule-today">Jadwal yang lewat waktu tetapi belum diisi</span>
                    </div>
                </div>
            </div>
            <div class="schedule-content">
                <div class="schedule-table-wrapper">
                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Hari</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Jam Pelajaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwalsTertinggal as $jadwal)
                                <tr>
                                    <td><div class="schedule-number">{{ $loop->iteration }}</div></td>
                                    <td><span class="schedule-day">{{ $jadwal->hari }}</span></td>
                                    <td><span class="schedule-class">{{ $jadwal->kelas->nama_kelas ?? '-' }}</span></td>
                                    <td><span class="schedule-subject">{{ $jadwal->mapel->nama_mapel ?? '-' }}</span></td>
                                    <td><span class="schedule-time">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Jam Ke {{ $jadwal->jamMulai->jam_ke ?? '-' }} - {{ $jadwal->jamSelesai->jam_ke ?? '-' }}
                                    </span></td>
                                    <td>
                                        <a href="{{ route('guru.jurnal.form', ['jadwal' => $jadwal->id_jadwal]) }}" class="journal-action">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M12 5v14M5 12h14" stroke-linecap="round" />
                                            </svg>
                                            Isi Jurnal Terlambat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif

</div>

<script>
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
            window.location.reload();
        }
    });

    window.setInterval(function () {
        if (!document.hidden) {
            window.location.reload();
        }
    }, 60000);
</script>

@endsection