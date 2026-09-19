@extends('layouts.sekretaris')

@section('title', 'Validasi Jurnal')
@section('page-title', 'Validasi Jurnal')
@section('page-subtitle', 'Periksa dan kelola validasi jurnal guru')
@section('content')

{{-- Import Font Manrope jika belum dimuat di layout utama --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="validation-page">

    {{-- PAGE HEADER --}}
    <div class="page-heading">
        <div>
            <h2>Inbox Validasi Jurnal</h2>
            <p>Daftar pengajuan jurnal pembelajaran dari guru untuk diverifikasi.</p>
        </div>
    </div>

    {{-- CLASS / ROLE CONTEXT --}}
    <div class="context-badge">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
        </svg>
        <span>Sekretaris Kelas</span>
    </div>

    {{-- VALIDATION INSTRUCTION --}}
    <section class="instruction-box">
        <div class="instruction-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <div>
            <h3>Validasi Jurnal Guru</h3>
            <p>Periksa data jurnal sebelum menyetujui jurnal pembelajaran yang telah dikirim oleh guru.</p>
        </div>
    </section>

    {{-- FILTER CARD --}}
    <div class="filter-card">
        <form class="filters" method="GET">

            <div class="filter-search">
                <label for="search">Cari Jurnal</label>

                <div class="input-wrap">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/>
                    </svg>

                    <input id="search" class="input" name="search" value="{{ request('search') }}" placeholder="Cari nama guru atau kelas...">
                </div>
            </div>

            <div class="filter-item">
                <label for="kelas_id">Kelas</label>

                <select id="kelas_id" class="select" name="kelas_id">
                    <option value="">Semua kelas</option>

                    @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id_kelas }}" @selected(request('kelas_id') == $kelas->id_kelas)>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="status">Status</label>

                <select id="status" class="select" name="status">
                    <option value="">Semua status</option>

                    @foreach(['Menunggu','Disetujui','Perlu Diperbaiki','Ditolak'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="tanggal">Tanggal</label>

                <input id="tanggal" class="select date-input" name="tanggal" type="date" value="{{ request('tanggal') }}">
            </div>

            <div class="filter-action">
                <button type="submit" class="filter-button">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18M6 12h12m-9 7h6"/>
                    </svg>
                    Filter
                </button>
            </div>

        </form>
    </div>

    {{-- LIST HEADER --}}
    <div class="list-header">
        <div>
            <h3>Daftar Jurnal</h3>
            <p>Kelola dan verifikasi data jurnal yang telah diinput.</p>
        </div>

        <span class="result-count">{{ $jurnals->total() }} Jurnal Ditemukan</span>
    </div>

    {{-- VALIDATION QUEUE --}}
    <section class="journal-list">

        @forelse($jurnals as $index => $jurnal)

            @php
                $statusClass = match($jurnal->status_validasi_guru) {
                    'Disetujui' => 'valid',
                    'Menunggu' => 'pending',
                    'Perlu Diperbaiki' => 'revision',
                    'Ditolak' => 'rejected',
                    default => 'pending',
                };
            @endphp

            <article class="journal-card {{ $jurnal->status_validasi_guru === 'Menunggu' ? 'priority-card' : '' }}">

                {{-- CARD HEADER --}}
                <div class="card-header">

                    <div class="queue-label {{ $jurnal->status_validasi_guru === 'Menunggu' ? 'priority' : '' }}">

                        @if($jurnal->status_validasi_guru === 'Menunggu')
                            <span class="queue-icon">
                                <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </span>
                            <span>Menunggu Validasi</span>
                        @else
                            <span>Jurnal #{{ $jurnals->firstItem() + $index }}</span>
                        @endif

                    </div>

                    <span class="date-created">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $jurnal->tanggal?->format('d M Y') ?? '-' }}
                    </span>

                </div>

                {{-- SUBJECT + STATUS --}}
                <div class="card-main">

                    <div class="teacher-info">

                        <div class="teacher-avatar">
                            {{ strtoupper(substr($jurnal->guru->nama_guru ?? 'G', 0, 1)) }}
                        </div>

                        <div class="teacher-text">
                            <h3>{{ $jurnal->kelas->nama_kelas ?? '-' }}</h3>

                            <p>
                                Guru: <strong>{{ $jurnal->guru->nama_guru ?? '-' }}</strong>
                            </p>
                        </div>

                    </div>

                    <span class="status-badge {{ $statusClass }}">
                        <span class="status-dot"></span>
                        {{ $jurnal->status_validasi_guru }}
                    </span>

                </div>

                {{-- CARD DETAILS --}}
                <div class="card-details">

                    {{-- SCHEDULE --}}
                    <div class="detail-row">
                        <div class="detail-left">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="detail-label">Jam Pelajaran</span>
                        </div>

                        <span class="detail-value">
                            Jam ke-{{ $jurnal->jamMulai->jam_ke ?? '-' }} — {{ $jurnal->jamSelesai->jam_ke ?? '-' }}
                        </span>
                    </div>

                    {{-- ATTENDANCE --}}
                    <div class="detail-row attendance-row">

                        <div class="detail-left">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>

                            <span class="detail-label">Kehadiran Siswa</span>
                        </div>

                        <div class="attendance-count">
                            <span class="hadir">{{ $jurnal->jml_hadir }} Hadir</span>
                            <span class="separator">/</span>
                            <span class="absent">{{ $jurnal->jml_tidak_hadir }} Absen</span>
                        </div>

                    </div>

                    {{-- MATERIAL --}}
                    <div class="detail-row material-row">
                        <div class="detail-left">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8m-8 4h5m-8 4h14a2 2 0 002-2V6a2 2 0 00-2-2H9L5 8v10a2 2 0 002 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 4v4H5"/>
                            </svg>

                            <span class="detail-label">Materi</span>
                        </div>

                        <div class="material-content">
                            {{ $jurnal->materi ?: '-' }}
                        </div>
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="card-action">

                    @if($jurnal->status_validasi_guru === 'Menunggu')

                        <form method="POST" action="{{ route('sekretaris.validasi-jurnal.update', $jurnal) }}" class="approve-form">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="status_validasi_guru" value="Disetujui">

                            <button type="submit" class="approve-button">
                                <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
                                </svg>
                                Review & Validasi Jurnal
                            </button>
                        </form>

                    @else

                        <div class="processed-state">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Sudah diproses</span>

                            @if($jurnal->catatan_revisi)
                                <small>({{ $jurnal->catatan_revisi }})</small>
                            @endif
                        </div>

                    @endif

                </div>

            </article>

        @empty

            <div class="empty-state">

                <div class="empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </div>

                <strong>Belum ada jurnal yang sesuai</strong>
                <p>Coba ubah kata kunci atau sesuaikan filter pencarian Anda.</p>

            </div>

        @endforelse

    </section>

    {{-- PAGINATION --}}
    @if($jurnals->hasPages())
        <div class="pagination-wrap">
            {{ $jurnals->links() }}
        </div>
    @endif

    {{-- FOOTER NOTICE --}}
    <div class="footer-notice">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>

        <p>
            <strong>Catatan:</strong>
            Jurnal yang telah divalidasi akan otomatis dipindahkan ke riwayat validasi dan tidak lagi tampil sebagai jurnal yang menunggu.
        </p>
    </div>

</div>

<style>
/* GLOBAL STYLING & FONT MANROPE */
.validation-page,
.validation-page * {
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    box-sizing: border-box;
}

.validation-page {
    width: 100%;
    animation: fadeInUp .4s cubic-bezier(.16,1,.3,1) both;
}

/* PAGE HEADER */
.page-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.page-heading h2 {
    margin: 0;
    color: #0F172A;
    font-size: 26px;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: -.5px;
}

.page-heading p {
    margin: 6px 0 0;
    color: #64748B;
    font-size: 14.5px;
    font-weight: 500;
}

/* CONTEXT BADGE */
.context-badge {
    width: fit-content;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    padding: 10px 16px;
    border: 1px solid #E0E7FF;
    border-radius: 12px;
    background: #EEF2FF;
    color: #2D336B;
    font-size: 13px;
    font-weight: 700;
}

.context-badge svg {
    width: 18px;
    height: 18px;
}

/* INSTRUCTION BOX */
.instruction-box {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
    padding: 20px 22px;
    border: 1px solid #BAE6FD;
    border-radius: 16px;
    background: #F0F9FF;
}

.instruction-icon {
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 2px;
    border-radius: 50%;
    background: #E0F2FE;
    color: #0369A1;
}

.instruction-icon svg {
    width: 20px;
    height: 20px;
}

.instruction-box h3 {
    margin: 0;
    color: #082F49;
    font-size: 15px;
    font-weight: 800;
}

.instruction-box p {
    margin: 4px 0 0;
    color: #075985;
    font-size: 13.5px;
    line-height: 1.6;
}

/* FILTER CARD */
.filter-card {
    margin-bottom: 28px;
    overflow: hidden;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    background: #FFFFFF;
    box-shadow: 0 4px 12px rgba(15, 23, 42, .03);
}

.filters {
    display: grid;
    grid-template-columns: minmax(240px, 1.6fr) minmax(160px, 1fr) minmax(160px, 1fr) minmax(160px, 1fr) auto;
    align-items: end;
    gap: 16px;
    padding: 22px;
    background: #F8FAFC;
}

.filter-search,
.filter-item {
    min-width: 0;
}

.filters label {
    display: block;
    margin-bottom: 8px;
    color: #475569;
    font-size: 13px;
    font-weight: 700;
}

.input-wrap {
    position: relative;
}

.input-wrap svg {
    position: absolute;
    top: 50%;
    left: 14px;
    width: 18px;
    height: 18px;
    color: #94A3B8;
    transform: translateY(-50%);
    pointer-events: none;
}

.input,
.select {
    width: 100%;
    height: 44px;
    border: 1px solid #CBD5E1;
    border-radius: 10px;
    outline: none;
    background: #FFFFFF;
    color: #1E293B;
    font-size: 13.5px;
    transition: all .2s ease;
}

.input {
    padding: 0 14px 0 42px;
}

.select {
    padding: 0 14px;
    cursor: pointer;
}

.input:focus,
.select:focus {
    border-color: #2D336B;
    box-shadow: 0 0 0 3px rgba(45, 51, 107, .12);
}

.input::placeholder {
    color: #94A3B8;
}

.filter-button {
    height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0 22px;
    border: 0;
    border-radius: 10px;
    background: #2D336B;
    color: #FFFFFF;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}

.filter-button svg {
    width: 18px;
    height: 18px;
}

.filter-button:hover {
    background: #1E234A;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 51, 107, .2);
}

/* LIST HEADER */
.list-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 16px;
}

.list-header h3 {
    margin: 0;
    color: #0F172A;
    font-size: 19px;
    font-weight: 800;
}

.list-header p {
    margin: 4px 0 0;
    color: #64748B;
    font-size: 13.5px;
}

.result-count {
    padding: 8px 14px;
    border-radius: 10px;
    background: #EEF2FF;
    color: #2D336B;
    font-size: 12.5px;
    font-weight: 700;
    white-space: nowrap;
}

/* JOURNAL LIST & CARDS */
.journal-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.journal-card {
    padding: 22px;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    background: #FFFFFF;
    box-shadow: 0 2px 8px rgba(15, 23, 42, .03);
    transition: all .2s ease;
}

.journal-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
    transform: translateY(-2px);
}

.journal-card.priority-card {
    border: 2px solid #F59E0B;
}

/* CARD HEADER */
.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid #F1F5F9;
}

.queue-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #0284C7;
    font-size: 12.5px;
    font-weight: 800;
}

.queue-label.priority {
    color: #B45309;
}

.queue-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #FEF3C7;
    color: #D97706;
}

.queue-icon svg {
    width: 14px;
    height: 14px;
}

.date-created {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #64748B;
    font-size: 12.5px;
    font-weight: 600;
}

.date-created svg {
    width: 15px;
    height: 15px;
    color: #94A3B8;
}

/* CARD MAIN */
.card-main {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding-top: 18px;
}

.teacher-info {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.teacher-avatar {
    width: 46px;
    height: 46px;
    flex: 0 0 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #EEF2FF;
    color: #2D336B;
    font-size: 15px;
    font-weight: 800;
}

.teacher-text {
    min-width: 0;
}

.teacher-text h3 {
    margin: 0;
    color: #0F172A;
    font-size: 18px;
    font-weight: 800;
    line-height: 1.3;
}

.teacher-text p {
    margin: 4px 0 0;
    color: #64748B;
    font-size: 13.5px;
}

.teacher-text strong {
    color: #334155;
}

/* STATUS BADGE */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    flex: 0 0 auto;
    padding: 7px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.status-badge.pending {
    border: 1px solid #FDE68A;
    background: #FFFBEB;
    color: #B45309;
}

.status-badge.pending .status-dot {
    background: #F59E0B;
    animation: pulseDot 1.8s infinite;
}

.status-badge.valid {
    border: 1px solid #BBF7D0;
    background: #F0FDF4;
    color: #15803D;
}

.status-badge.valid .status-dot {
    background: #16A34A;
}

.status-badge.revision {
    border: 1px solid #FED7AA;
    background: #FFF7ED;
    color: #C2410C;
}

.status-badge.revision .status-dot {
    background: #EA580C;
}

.status-badge.rejected {
    border: 1px solid #FECDD3;
    background: #FFF1F2;
    color: #BE123C;
}

.status-badge.rejected .status-dot {
    background: #E11D48;
}

/* CARD DETAILS */
.card-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 18px;
}

.detail-row {
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 16px;
    border: 1px solid #F1F5F9;
    border-radius: 12px;
    background: #F8FAFC;
    color: #334155;
    font-size: 13.5px;
    font-weight: 600;
}

.detail-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 0 0 auto;
}

.detail-left svg {
    width: 18px;
    height: 18px;
    color: #64748B;
}

.detail-label {
    color: #64748B;
    font-weight: 700;
    font-size: 13px;
}

.detail-value {
    color: #1E293B;
    font-weight: 700;
}

/* ATTENDANCE COUNT */
.attendance-count {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    font-weight: 700;
}

.attendance-count .hadir {
    padding: 4px 10px;
    border: 1px solid #BBF7D0;
    border-radius: 8px;
    background: #DCFCE7;
    color: #15803D;
}

.attendance-count .absent {
    padding: 4px 10px;
    border: 1px solid #FECDD3;
    border-radius: 8px;
    background: #FFE4E6;
    color: #BE123C;
}

.attendance-count .separator {
    color: #CBD5E1;
}

/* MATERIAL ROW */
.material-row {
    align-items: flex-start;
}

.material-content {
    color: #334155;
    font-weight: 600;
    line-height: 1.5;
    text-align: right;
}

/* ACTION BUTTONS */
.card-action {
    margin-top: 18px;
}

.approve-form {
    width: 100%;
}

.approve-button {
    width: 100%;
    height: 46px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0 20px;
    border: 0;
    border-radius: 12px;
    background: #2D336B;
    color: #FFFFFF;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(45, 51, 107, .15);
    transition: all .2s ease;
}

.approve-button svg {
    width: 18px;
    height: 18px;
}

.approve-button:hover {
    background: #1E234A;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(45, 51, 107, .25);
}

.processed-state {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 46px;
    padding: 10px 16px;
    border-radius: 12px;
    background: #F1F5F9;
    color: #64748B;
    font-size: 13px;
    font-weight: 700;
}

.processed-state svg {
    width: 18px;
    height: 18px;
    color: #94A3B8;
}

.processed-state small {
    color: #475569;
    font-size: 12.5px;
    font-weight: 500;
}

/* EMPTY STATE */
.empty-state {
    padding: 56px 20px;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    background: #FFFFFF;
    text-align: center;
}

.empty-icon {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    border-radius: 50%;
    background: #EEF2FF;
    color: #2D336B;
}

.empty-icon svg {
    width: 26px;
    height: 26px;
}

.empty-state strong {
    display: block;
    color: #1E293B;
    font-size: 15px;
    font-weight: 700;
}

.empty-state p {
    margin: 6px 0 0;
    color: #64748B;
    font-size: 13.5px;
}

.pagination-wrap {
    padding: 20px 0 0;
}

/* FOOTER NOTICE */
.footer-notice {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-top: 24px;
    padding: 18px 20px;
    border: 1px solid #E0E7FF;
    border-radius: 16px;
    background: #EEF2FF;
}

.footer-notice > svg {
    width: 20px;
    height: 20px;
    flex: 0 0 20px;
    margin-top: 2px;
    color: #2D336B;
}

.footer-notice p {
    margin: 0;
    color: #334155;
    font-size: 13px;
    line-height: 1.6;
}

.footer-notice strong {
    color: #1E293B;
}

/* KEYFRAMES */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulseDot {
    0% {
        box-shadow: 0 0 0 0 rgba(245, 158, 11, .4);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(245, 158, 11, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
    }
}

/* MEDIA QUERIES */
@media (max-width: 1150px) {
    .filters {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .filter-search {
        grid-column: 1 / -1;
    }
}

@media (max-width: 767px) {
    .page-heading h2 {
        font-size: 22px;
    }
    .filters {
        grid-template-columns: 1fr;
        padding: 18px;
    }
    .filter-search {
        grid-column: auto;
    }
    .filter-button {
        width: 100%;
    }
    .list-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
    }
    .card-main {
        align-items: flex-start;
        flex-direction: column;
    }
    .status-badge {
        align-self: flex-start;
    }
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
    .material-content {
        text-align: left;
    }
}

@media (max-width: 420px) {
    .instruction-box {
        padding: 16px;
    }
    .journal-card {
        padding: 18px;
    }
    .teacher-text h3 {
        font-size: 16px;
    }
    .card-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 8px;
    }
}
</style>

@endsection