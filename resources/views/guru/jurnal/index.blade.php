@extends('layouts.guru')

@section('title', 'Daftar Jurnal - Jurnify')
@section('page-title', 'Daftar Jurnal')
@section('page-subtitle', 'Pantau seluruh catatan jurnal yang telah Anda kirimkan ke kurikulum.')

@section('content')

@php
    $kelasOptions = $jurnals
        ->map(fn ($jurnal) => $jurnal->kelas?->nama_kelas)
        ->filter()
        ->unique()
        ->sort()
        ->values();

    $bulanOptions = $jurnals
        ->map(fn ($jurnal) => $jurnal->tanggal?->format('Y-m'))
        ->filter()
        ->unique()
        ->sortDesc()
        ->values();
@endphp

{{-- Font Manrope --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="journal-page">

    {{-- HERO HEADER --}}
    <section class="journal-hero">
        <span class="journal-hero-badge">
            RIWAYAT AKTIVITAS
        </span>

        <h1 class="journal-hero-title">
            Daftar Jurnal Mengajar
        </h1>

        <p class="journal-hero-text">
            Pantau seluruh catatan jurnal yang telah Anda kirimkan ke kurikulum secara berkala dan terstruktur.
        </p>
    </section>

    {{-- FILTER BAR --}}
    <section class="journal-filter-bar">

        {{-- SEARCH --}}
        <div class="journal-search">
            <svg class="journal-search-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <input type="text" id="journalSearch" placeholder="Cari materi atau topik jurnal..." autocomplete="off">
        </div>

        {{-- MONTH FILTER --}}
        <div class="journal-filter-select">
            <select id="monthFilter" aria-label="Pilih Bulan">
                <option value="">Semua Bulan</option>

                @foreach($bulanOptions as $bulan)
                    <option value="{{ $bulan }}">
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}
                    </option>
                @endforeach
            </select>

            <svg class="journal-select-arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        {{-- CLASS FILTER --}}
        <div class="journal-filter-select">
            <select id="classFilter" aria-label="Pilih Kelas">
                <option value="">Semua Kelas</option>

                @foreach($kelasOptions as $kelas)
                    <option value="{{ $kelas }}">
                        {{ $kelas }}
                    </option>
                @endforeach
            </select>

            <svg class="journal-select-arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

    </section>

    {{-- JOURNAL LIST --}}
    <div class="journal-list" id="journalList">

        @forelse($jurnals as $jurnal)

            @php
                $status = $jurnal->status_validasi_guru ?? '-';

                if ($status === 'Menunggu') {
                    $statusClass = 'status-menunggu';
                    $statusText = 'Menunggu Validasi';
                } elseif ($status === 'Valid') {
                    $statusClass = 'status-valid';
                    $statusText = 'Sudah Divalidasi';
                } else {
                    $statusClass = 'status-default';
                    $statusText = $status;
                }

                $tanggalFilter = $jurnal->tanggal?->format('Y-m') ?? '';
                $kelasFilter = $jurnal->kelas?->nama_kelas ?? '';
                $jamMulai = $jurnal->jamMulai?->jam_ke ?? '-';
                $jamSelesai = $jurnal->jamSelesai?->jam_ke ?? '-';
                $materi = $jurnal->materi ?? '-';
                $mapel = $jurnal->jadwal?->mapel?->nama_mapel ?? '-';
            @endphp

            <article
                class="journal-card"
                data-material="{{ strtolower($materi . ' ' . $mapel . ' ' . $kelasFilter) }}"
                data-month="{{ $tanggalFilter }}"
                data-class="{{ $kelasFilter }}"
            >

                {{-- LEFT: DATE & TIME --}}
                <div class="journal-date-group">
                    <div class="journal-calendar">
                        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="journal-date">
                            {{ $jurnal->tanggal?->format('d M Y') ?? '-' }}
                        </h2>

                        <p class="journal-time">
                            Jam Ke {{ $jamMulai }}-{{ $jamSelesai }}
                        </p>
                    </div>
                </div>

                {{-- CENTER: CLASS & MATERIAL --}}
                <div class="journal-info">
                    <div class="journal-class-row">
                        <span class="journal-class-label">Kelas Rombel:</span>
                        <span class="journal-class">
                            {{ $kelasFilter ?: '-' }}
                        </span>
                    </div>

                    <div class="journal-mapel-label">
                        Mata Pelajaran & Materi
                    </div>

                    <p class="journal-material" title="{{ $materi }}">
                        {{ $materi }}
                    </p>
                    
                    @if($mapel !== '-')
                        <p class="journal-mapel">
                            {{ $mapel }}
                        </p>
                    @endif
                </div>

                {{-- RIGHT: STATUS & ACTION --}}
                <div class="journal-action-group">
                    <span class="journal-status {{ $statusClass }}">
                        @if($status === 'Valid')
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="m4.5 12.75 6 6 9-13.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @else
                            <span class="journal-status-dot"></span>
                        @endif

                        {{ $statusText }}
                    </span>

                    <a class="journal-detail" href="{{ route('guru.jurnal.show', $jurnal->id_jurnal) }}">
                        <span>Lihat Detail</span>
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="m8.25 4.5 7.5 7.5-7.5 7.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

            </article>

        @empty

            <div class="journal-empty">
                <div class="journal-empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M19.5 14.25v-8.5A2.75 2.75 0 0 0 16.75 3H7.25A2.75 2.75 0 0 0 4.5 5.75v12.5A2.75 2.75 0 0 0 7.25 21h9.5a2.75 2.75 0 0 0 2.75-2.75v-1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 7h8M8 11h8M8 15h4" stroke-linecap="round"/>
                    </svg>
                </div>

                <p class="journal-empty-title">
                    Belum ada jurnal
                </p>

                <p class="journal-empty-text">
                    Jurnal mengajar yang Anda buat akan tampil di sini.
                </p>
            </div>

        @endforelse

    </div>

    {{-- NO SEARCH RESULT --}}
    <div class="journal-no-result" id="noResult">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <p>Tidak ada jurnal yang sesuai dengan pencarian atau filter.</p>
    </div>

    {{-- PAGINATION / ENTRY INFO --}}
    @if($jurnals->count() > 0)
        <footer class="journal-pagination">
            <p class="journal-entry-count">
                Menampilkan <strong id="visibleCount">{{ $jurnals->count() }}</strong> dari <strong>{{ $jurnals->count() }}</strong> entri
            </p>

            <div class="journal-pagination-box">
                <button type="button" class="journal-page-btn" disabled aria-label="Halaman Sebelumnya">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="m15.75 19.5-7.5-7.5 7.5-7.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <button type="button" class="journal-page-btn active" aria-current="page">
                    1
                </button>
            </div>
        </footer>
    @endif

</div>

<style>
    /* GLOBAL RESET & FONT */
    .journal-page,
    .journal-page * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .journal-page {
        width: 100%;
        color: #1E293B;
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    /* HERO HEADER */
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

    /* FILTER BAR */
    .journal-filter-bar {
        display: flex;
        align-items: center;
        gap: 14px;
        animation: fadeUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) 0.05s both;
    }

    .journal-search {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .journal-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        width: 18px;
        height: 18px;
        transform: translateY(-50%);
        color: #94A3B8;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .journal-search input {
        width: 100%;
        height: 44px;
        padding: 0 16px 0 42px;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        outline: none;
        background: #FFFFFF;
        color: #0F172A;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 2px 7px rgba(15, 23, 42, 0.025);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .journal-search input::placeholder {
        color: #94A3B8;
        font-weight: 500;
    }

    .journal-search input:focus {
        border-color: #2D336B;
        box-shadow: 0 0 0 3px rgba(45, 51, 107, 0.1);
    }

    .journal-search:focus-within .journal-search-icon {
        color: #2D336B;
    }

    .journal-filter-select {
        position: relative;
        width: 190px;
        flex-shrink: 0;
    }

    .journal-filter-select select {
        width: 100%;
        height: 44px;
        appearance: none;
        -webkit-appearance: none;
        padding: 0 38px 0 14px;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        outline: none;
        background: #FFFFFF;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 2px 7px rgba(15, 23, 42, 0.025);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .journal-filter-select select:focus {
        border-color: #2D336B;
        box-shadow: 0 0 0 3px rgba(45, 51, 107, 0.1);
    }

    .journal-select-arrow {
        position: absolute;
        right: 13px;
        top: 50%;
        width: 16px;
        height: 16px;
        transform: translateY(-50%);
        color: #94A3B8;
        pointer-events: none;
    }

    /* JOURNAL LIST */
    .journal-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .journal-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 22px;
        padding: 18px 22px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(45, 51, 107, 0.03);
        opacity: 0;
        transform: translateY(10px);
        animation: journalCardIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .journal-card:nth-child(1) { animation-delay: 0.04s; }
    .journal-card:nth-child(2) { animation-delay: 0.08s; }
    .journal-card:nth-child(3) { animation-delay: 0.12s; }
    .journal-card:nth-child(4) { animation-delay: 0.16s; }
    .journal-card:nth-child(5) { animation-delay: 0.20s; }
    .journal-card:nth-child(6) { animation-delay: 0.24s; }
    .journal-card:nth-child(7) { animation-delay: 0.28s; }
    .journal-card:nth-child(8) { animation-delay: 0.32s; }
    .journal-card:nth-child(n+9) { animation-delay: 0.35s; }

    .journal-card:hover {
        transform: translateY(-2px);
        border-color: #CBD5E1;
        box-shadow: 0 8px 22px rgba(45, 51, 107, 0.07);
    }

    /* LEFT SECTION */
    .journal-date-group {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 235px;
        flex-shrink: 0;
    }

    .journal-calendar {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #EEF2FF;
        border: 1px solid #E0E7FF;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2D336B;
        flex-shrink: 0;
        transition: transform 0.25s ease, background-color 0.25s ease;
    }

    .journal-card:hover .journal-calendar {
        transform: scale(1.05);
        background: #E0E7FF;
    }

    .journal-calendar svg {
        width: 22px;
        height: 22px;
    }

    .journal-date {
        margin: 0;
        color: #0F172A;
        font-size: 14.5px;
        line-height: 1.35;
        font-weight: 800;
    }

    .journal-time {
        margin: 3px 0 0;
        color: #64748B;
        font-size: 12px;
        line-height: 1.45;
        font-weight: 600;
        white-space: nowrap;
    }

    /* CENTER SECTION */
    .journal-info {
        flex: 1;
        min-width: 0;
        padding: 2px 24px;
        border-left: 1px solid #F1F5F9;
        border-right: 1px solid #F1F5F9;
    }

    .journal-class-row {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 6px;
    }

    .journal-class-label {
        color: #94A3B8;
        font-size: 11px;
        font-weight: 600;
    }

    .journal-class {
        display: inline-flex;
        align-items: center;
        padding: 3px 9px;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        background: #F8FAFC;
        color: #2D336B;
        font-size: 11px;
        line-height: 1;
        font-weight: 800;
    }

    .journal-mapel-label {
        margin-bottom: 2px;
        color: #94A3B8;
        font-size: 9.5px;
        line-height: 1.4;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .journal-material {
        margin: 0;
        color: #2D336B;
        font-size: 15px;
        line-height: 1.45;
        font-weight: 800;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .journal-mapel {
        margin: 2px 0 0;
        color: #64748B;
        font-size: 12px;
        font-weight: 600;
    }

    /* RIGHT SECTION */
    .journal-action-group {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 18px;
        min-width: 220px;
        flex-shrink: 0;
    }

    .journal-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 28px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 11px;
        line-height: 1;
        font-weight: 800;
        white-space: nowrap;
    }

    .journal-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-menunggu {
        background: #FEF3C7;
        border: 1px solid #FDE68A;
        color: #D97706;
    }

    .status-menunggu .journal-status-dot {
        background: #D97706;
    }

    .status-valid {
        background: #DCFCE7;
        border: 1px solid #BBF7D0;
        color: #16A34A;
    }

    .status-valid .journal-status-dot {
        background: #16A34A;
    }

    .status-default {
        background: #F1F5F9;
        border: 1px solid #E2E8F0;
        color: #64748B;
    }

    .status-default .journal-status-dot {
        background: #94A3B8;
    }

    .journal-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #2D336B;
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 800;
        white-space: nowrap;
        transition: color 0.2s ease;
    }

    .journal-detail svg {
        width: 15px;
        height: 15px;
        transition: transform 0.2s ease;
    }

    .journal-detail:hover {
        color: #1E234A;
    }

    .journal-detail:hover svg {
        transform: translateX(3px);
    }

    /* EMPTY STATES */
    .journal-empty {
        padding: 55px 24px;
        border: 1px dashed #CBD5E1;
        border-radius: 16px;
        background: #FFFFFF;
        text-align: center;
        animation: fadeUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .journal-empty-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 14px;
        border-radius: 14px;
        background: #EEF2FF;
        color: #2D336B;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .journal-empty-icon svg {
        width: 25px;
        height: 25px;
    }

    .journal-empty-title {
        margin: 0;
        color: #0F172A;
        font-size: 15px;
        font-weight: 800;
    }

    .journal-empty-text {
        margin: 5px 0 0;
        color: #64748B;
        font-size: 12.5px;
        font-weight: 500;
    }

    .journal-no-result {
        display: none;
        padding: 40px 20px;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        background: #FFFFFF;
        text-align: center;
    }

    .journal-no-result.show {
        display: block;
        animation: fadeUp 0.25s ease both;
    }

    .journal-no-result svg {
        width: 32px;
        height: 32px;
        margin-bottom: 10px;
        color: #94A3B8;
    }

    .journal-no-result p {
        margin: 0;
        color: #64748B;
        font-size: 13px;
        font-weight: 600;
    }

    /* PAGINATION */
    .journal-pagination {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: 10px;
        padding-top: 2px;
    }

    .journal-entry-count {
        margin: 0 0 10px;
        color: #64748B;
        font-size: 12px;
        font-weight: 600;
    }

    .journal-entry-count strong {
        color: #0F172A;
        font-weight: 800;
    }

    .journal-pagination-box {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(45, 51, 107, 0.03);
    }

    .journal-page-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #64748B;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .journal-page-btn:hover:not(:disabled) {
        background: #F1F5F9;
        color: #2D336B;
        transform: translateY(-1px);
    }

    .journal-page-btn.active {
        background: #2D336B;
        color: #FFFFFF;
        box-shadow: 0 2px 6px rgba(45, 51, 107, 0.18);
    }

    .journal-page-btn:disabled {
        color: #CBD5E1;
        cursor: not-allowed;
    }

    .journal-page-btn svg {
        width: 15px;
        height: 15px;
    }

    /* ANIMATIONS & RESPONSIVE */
    @keyframes journalCardIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

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

    @media (max-width: 1100px) {
        .journal-date-group {
            min-width: 205px;
        }

        .journal-info {
            padding: 2px 18px;
        }

        .journal-action-group {
            min-width: 195px;
            gap: 12px;
        }

        .journal-status {
            font-size: 10px;
            padding: 5px 10px;
        }
    }

    @media (max-width: 767px) {
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

        .journal-filter-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .journal-filter-select {
            width: 100%;
        }

        .journal-card {
            align-items: stretch;
            flex-direction: column;
            gap: 14px;
            padding: 16px;
        }

        .journal-date-group {
            min-width: 0;
        }

        .journal-info {
            padding: 12px 0;
            border-top: 1px solid #F1F5F9;
            border-bottom: 1px solid #F1F5F9;
            border-left: 0;
            border-right: 0;
        }

        .journal-action-group {
            min-width: 0;
            justify-content: space-between;
        }

        .journal-material {
            white-space: normal;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .journal-card,
        .journal-hero,
        .journal-filter-bar,
        .journal-empty {
            animation: none;
            opacity: 1;
            transform: none;
        }

        .journal-card,
        .journal-calendar,
        .journal-detail svg,
        .journal-page-btn {
            transition: none;
        }
    }
</style>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('journalSearch');
        const monthFilter = document.getElementById('monthFilter');
        const classFilter = document.getElementById('classFilter');
        const journalCards = Array.from(document.querySelectorAll('.journal-card'));
        const noResult = document.getElementById('noResult');
        const visibleCount = document.getElementById('visibleCount');

        if (!searchInput || !monthFilter || !classFilter) {
            return;
        }

        function filterJournals() {
            const searchValue = searchInput.value.trim().toLowerCase();
            const monthValue = monthFilter.value;
            const classValue = classFilter.value;
            let visible = 0;

            journalCards.forEach(function (card) {
                const material = card.dataset.material || '';
                const month = card.dataset.month || '';
                const journalClass = card.dataset.class || '';

                const matchesSearch = searchValue === '' || material.includes(searchValue);
                const matchesMonth = monthValue === '' || month === monthValue;
                const matchesClass = classValue === '' || journalClass === classValue;

                const shouldShow = matchesSearch && matchesMonth && matchesClass;

                if (shouldShow) {
                    card.style.display = '';

                    requestAnimationFrame(function () {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });

                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount) {
                visibleCount.textContent = visible;
            }

            if (noResult) {
                noResult.classList.toggle(
                    'show',
                    visible === 0 && journalCards.length > 0
                );
            }
        }

        searchInput.addEventListener('input', filterJournals);
        monthFilter.addEventListener('change', filterJournals);
        classFilter.addEventListener('change', filterJournals);
    });
</script>
@endsection