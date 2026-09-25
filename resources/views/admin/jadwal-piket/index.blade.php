@extends('layouts.admin')

@section('title', 'Jadwal Piket - Jurnify')
@section('page-title', 'Jadwal Piket')
@section('page-subtitle', 'Kelola penugasan jadwal piket guru')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="piket-page">

    {{-- HEADER --}}
    <div class="piket-header">
        <div class="header-text">
            <h1 class="header-title">Jadwal Piket</h1>
            <p class="header-subtitle">
                Kelola penugasan jadwal piket guru.
            </p>
        </div>

        <a href="{{ route('admin.jadwal-piket.create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Tambah Jadwal</span>
        </a>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"/>
                <polyline points="22 4 12 14.01 9 11.01" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="table-card">

        <div class="table-header">
            <div>
                <h2>Daftar Jadwal Piket</h2>
                <p>Data penugasan guru yang telah dijadwalkan.</p>
            </div>

            <div class="total-badge">
                {{ $jadwals->count() }} Jadwal
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-tanggal">Hari / Tanggal</th>
                        <th>Petugas Piket<br>KBM Pagi</th>
                        <th>Koordinator<br>KBM Pagi</th>
                        <th>Petugas Piket<br>KBM Siang</th>
                        <th>Koordinator<br>KBM Siang</th>
                        <th>Piket Waka</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jadwals as $jadwal)
                        <tr>

                            {{-- NO --}}
                            <td class="td-no">
                                {{ $loop->iteration }}
                            </td>

                            {{-- TANGGAL --}}
                            <td class="td-tanggal">
                                <div class="date-wrapper">
                                    <div class="date-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </div>
                                    <span>{{ $jadwal->tanggal?->format('d M Y') ?? '-' }}</span>
                                </div>
                            </td>

                            @foreach ([
                                ['petugasKbmPagi', 'jam_mulai_kbm_pagi', 'jam_selesai_kbm_pagi'],
                                ['koordinatorKbmPagi', 'jam_mulai_koordinator_pagi', 'jam_selesai_koordinator_pagi'],
                                ['petugasKbmSiang', 'jam_mulai_kbm_siang', 'jam_selesai_kbm_siang'],
                                ['koordinatorKbmSiang', 'jam_mulai_koordinator_siang', 'jam_selesai_koordinator_siang'],
                            ] as [$relation, $start, $end])
                                <td>
                                    <strong>{{ $jadwal->{$relation}->nama_guru ?? '-' }}</strong>
                                    <small class="schedule-time">{{ $jadwal->{$start} }} - {{ $jadwal->{$end} }}</small>
                                </td>
                            @endforeach
                            <td>
                                <strong>{{ $jadwal->piketWaka->nama_guru ?? '-' }}</strong>
                            </td>

                            {{-- AKSI --}}
                            <td class="td-aksi">
                                <div class="action-buttons">

                                    <a href="{{ route('admin.jadwal-piket.edit', $jadwal) }}"
                                       class="btn-action edit"
                                       title="Edit Jadwal">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <form action="{{ route('admin.jadwal-piket.destroy', $jadwal) }}"
                                          method="POST"
                                          class="delete-form">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="btn-action delete"
                                                title="Hapus Jadwal"
                                                onclick='openDeleteModal(
                                                    @json(route("admin.jadwal-piket.destroy", $jadwal)),
                                                    @json($jadwal->tanggal?->format("d M Y") ?? "-")
                                                )'>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                <line x1="10" y1="11" x2="10" y2="17"/>
                                                <line x1="14" y1="11" x2="14" y2="17"/>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-content">
                                    <div class="empty-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </div>

                                    <h3>Belum Ada Jadwal</h3>
                                    <p>Belum ada jadwal piket guru yang ditambahkan.</p>

                                    <a href="{{ route('admin.jadwal-piket.create') }}" class="empty-button">
                                        + Tambah Jadwal
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

{{-- DELETE MODAL --}}
<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">

        <div class="delete-modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                <line x1="10" y1="11" x2="10" y2="17"/>
                <line x1="14" y1="11" x2="14" y2="17"/>
            </svg>
        </div>

        <div class="delete-modal-text">
            <h3>Hapus Jadwal Piket?</h3>

            <p>
                Apakah Anda yakin ingin menghapus jadwal piket tanggal
                <strong id="deleteScheduleDate"></strong>?
            </p>

            <span>Data jadwal yang sudah dihapus tidak dapat dikembalikan.</span>
        </div>

        <div class="delete-modal-actions">
            <button type="button"
                    class="delete-modal-cancel"
                    onclick="closeDeleteModal()">
                Batal
            </button>

            <form id="deleteModalForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="delete-modal-confirm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        <line x1="10" y1="11" x2="10" y2="17"/>
                        <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>

    </div>
</div>

<style>
    * {
        box-sizing: border-box;
    }

    .piket-page {
        width: 100%;
        animation: pageFade .35s ease both;
    }

    @keyframes pageFade {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =========================
       HEADER
    ========================= */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .page-title-wrap h1 {
        margin: 0;
        color: #1B234A;
        font-size: 26px;
        font-weight: 800;
    }

    .page-title-wrap p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .btn-primary {
        border: none;
        background: #1B234A;
        color: white;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: .2s;
    }

    .btn-primary:hover {
        background: #30366f;
        transform: translateY(-1px);
    }

    /* =========================
       FILTER
    ========================= */
    .filter-card {
        background: white;
        border-radius: 18px;
        padding: 18px;
        margin-bottom: 20px;
        border: 1px solid #e8ebf2;
        box-shadow: 0 5px 20px rgba(27, 35, 74, .04);
    }

    .filter-form {
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 180px 180px auto;
        gap: 12px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .form-group label {
        color: #334155;
        font-size: 12px;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        height: 42px;
        border: 1px solid #dce1eb;
        border-radius: 10px;
        padding: 0 12px;
        background: white;
        color: #1e293b;
        font-size: 13px;
        outline: none;
        transition: .2s;
    }

    .form-control:focus {
        border-color: #1B234A;
        box-shadow: 0 0 0 3px rgba(27, 35, 74, .08);
    }

    .btn-filter {
        height: 42px;
        border: none;
        border-radius: 10px;
        padding: 0 17px;
        background: #1B234A;
        color: white;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-reset {
        height: 42px;
        padding: 0 15px;
        border-radius: 10px;
        border: 1px solid #dce1eb;
        background: white;
        color: #64748b;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    /* =========================
       CALENDAR
    ========================= */
    .calendar-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e8ebf2;
        box-shadow: 0 5px 25px rgba(27, 35, 74, .05);
        overflow: hidden;
    }

    .calendar-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .calendar-month {
        color: #1B234A;
        font-weight: 800;
        font-size: 18px;
    }

    .calendar-navigation {
        display: flex;
        gap: 8px;
    }

    .calendar-nav-btn {
        width: 38px;
        height: 38px;
        border: 1px solid #e1e5ed;
        border-radius: 10px;
        background: white;
        color: #1B234A;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: .2s;
    }

    .calendar-nav-btn:hover {
        background: #f5f6fa;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .calendar-weekday {
        padding: 13px 8px;
        text-align: center;
        color: #64748b;
        background: #f8f9fc;
        border-bottom: 1px solid #edf0f5;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .calendar-day {
        min-height: 155px;
        padding: 10px;
        border-right: 1px solid #edf0f5;
        border-bottom: 1px solid #edf0f5;
        cursor: pointer;
        transition: .18s;
        position: relative;
        background: white;
    }

    .calendar-day:nth-child(7n) {
        border-right: none;
    }

    .calendar-day:hover {
        background: #fafbfe;
    }

    .calendar-day.other-month {
        background: #fafafa;
        cursor: default;
    }

    .calendar-day.other-month .day-number {
        color: #cbd5e1;
    }

    .calendar-day.today {
        background: #f7f8ff;
    }

    .day-number {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #334155;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .calendar-day.today .day-number {
        background: #1B234A;
        color: white;
        border-radius: 50%;
    }

    .empty-day {
        color: #cbd5e1;
        font-size: 11px;
        padding: 8px 5px;
    }

    /* =========================
       MINI SCHEDULE
    ========================= */
    .schedule-mini {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .schedule-mini-box {
        border: 1px solid #e7eaf1;
        border-radius: 9px;
        padding: 7px;
        background: #fbfcff;
    }

    .schedule-mini-label {
        color: #1B234A;
        font-size: 9px;
        font-weight: 800;
        margin-bottom: 3px;
        text-transform: uppercase;
    }

    .schedule-mini-name {
        color: #475569;
        font-size: 10px;
        line-height: 1.35;
        font-weight: 600;
    }

    .schedule-mini-more {
        margin-top: 4px;
        color: #7c3aed;
        font-size: 9px;
        font-weight: 700;
    }

    /* =========================
       DRAWER OVERLAY
    ========================= */
    .drawer-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .42);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: .25s ease;
        backdrop-filter: blur(2px);
    }

    .drawer-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    /* =========================
       DRAWER
    ========================= */
    .schedule-drawer {
        position: fixed;
        top: 0;
        right: 0;
        width: min(560px, 100vw);
        height: 100vh;
        background: #f8f9fc;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform .3s ease;
        display: flex;
        flex-direction: column;
        box-shadow: -12px 0 35px rgba(15, 23, 42, .15);
    }

    .schedule-drawer.open {
        transform: translateX(0);
    }

    .drawer-header {
        flex-shrink: 0;
        background: white;
        border-bottom: 1px solid #e7eaf0;
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .drawer-header-left {
        min-width: 0;
    }

    .drawer-header-title {
        margin: 0;
        color: #1B234A;
        font-size: 18px;
        font-weight: 800;
    }

    .drawer-header-date {
        margin-top: 4px;
        color: #64748b;
        font-size: 12px;
    }

    .drawer-close {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 10px;
        background: #f1f3f7;
        color: #475569;
        cursor: pointer;
        font-size: 16px;
    }

    .drawer-close:hover {
        background: #e8ebf1;
    }

    .drawer-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .drawer-footer {
        flex-shrink: 0;
        padding: 15px 20px;
        background: white;
        border-top: 1px solid #e7eaf0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* =========================
       DETAIL
    ========================= */
    .detail-section {
        background: white;
        border: 1px solid #e7eaf0;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .detail-section-header {
        padding: 13px 15px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .detail-section-title {
        margin: 0;
        color: #1B234A;
        font-size: 13px;
        font-weight: 800;
    }

    .detail-time {
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
    }

    .detail-section-body {
        padding: 14px 15px;
    }

    .person-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .person-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px;
        border-radius: 10px;
        background: #f8f9fc;
    }

    .person-avatar {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #e9ebf5;
        color: #1B234A;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 800;
    }

    .person-name {
        color: #334155;
        font-size: 12px;
        font-weight: 700;
    }

    .person-role {
        color: #94a3b8;
        font-size: 10px;
        margin-top: 2px;
    }

    .empty-detail {
        color: #94a3b8;
        font-size: 12px;
        padding: 4px 0;
    }

    .detail-note {
        color: #64748b;
        font-size: 12px;
        line-height: 1.6;
        white-space: pre-line;
    }

    /* =========================
       FORM EDIT / CREATE
    ========================= */
    .form-section {
        background: white;
        border: 1px solid #e7eaf0;
        border-radius: 15px;
        padding: 16px;
        margin-bottom: 15px;
    }

    .form-section-title {
        margin: 0 0 13px;
        color: #1B234A;
        font-size: 14px;
        font-weight: 800;
    }

    .form-section-subtitle {
        color: #94a3b8;
        font-size: 11px;
        margin: -6px 0 13px;
    }

    .petugas-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .petugas-row {
        display: flex;
        gap: 7px;
        align-items: center;
    }

    .petugas-row .form-control {
        flex: 1;
    }

    .btn-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-add {
        margin-top: 9px;
        border: 1px dashed #cbd5e1;
        background: #f8fafc;
        color: #475569;
        padding: 9px 12px;
        border-radius: 9px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-add:hover {
        background: #f1f5f9;
    }

    .btn-remove {
        background: #fff1f2;
        color: #e11d48;
    }

    .btn-remove:hover {
        background: #ffe4e6;
    }

    .btn-secondary {
        border: 1px solid #dce1eb;
        background: white;
        color: #475569;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: #f8fafc;
    }

    .btn-save {
        border: none;
        background: #1B234A;
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-save:hover {
        background: #30366f;
    }

    .btn-danger {
        border: none;
        background: #fff1f2;
        color: #e11d48;
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-danger:hover {
        background: #ffe4e6;
    }

    .required {
        color: #e11d48;
    }

    .hidden {
        display: none !important;
    }

    .alert-error {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #be123c;
        padding: 11px 13px;
        border-radius: 10px;
        font-size: 12px;
        margin-bottom: 15px;
    }

    /* =========================
       RESPONSIVE
    ========================= */
    @media (max-width: 900px) {
        .filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .calendar-day {
            min-height: 130px;
        }
    }

    @media (max-width: 640px) {
        .filter-form {
            grid-template-columns: 1fr;
        }

        .calendar-card {
            overflow-x: auto;
        }

        .calendar-grid {
            min-width: 700px;
        }

        .calendar-weekday {
            min-width: 100px;
        }

        .schedule-drawer {
            width: 100vw;
        }
    }
</style>

@section('content')

<div class="piket-page">

    {{-- =========================
         HEADER
    ========================== --}}
    <!-- <div class="page-header">
        <div class="page-title-wrap">
            <h1>Jadwal Piket</h1>
            <p>Kelola jadwal petugas piket berdasarkan tanggal.</p>
        </div>
    </div> -->

    {{-- =========================
         FILTER
    ========================== --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.jadwal-piket.index') }}" class="filter-form">

            <div class="form-group">
                <label for="search">Cari</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    class="form-control"
                    value="{{ request('search') }}"
                    placeholder="Cari nama guru..."
                >
            </div>

            <div class="form-group">
                <label for="shift">Shift</label>
                <select name="shift" id="shift" class="form-control">
                    <option value="">Semua Shift</option>
                    <option value="Pagi" {{ request('shift') === 'Pagi' ? 'selected' : '' }}>
                        Pagi
                    </option>
                    <option value="Siang" {{ request('shift') === 'Siang' ? 'selected' : '' }}>
                        Siang
                    </option>
                    <option value="Waka" {{ request('shift') === 'Waka' ? 'selected' : '' }}>
                        Waka
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="month">Bulan</label>
                <input
                    type="month"
                    name="month"
                    id="month"
                    class="form-control"
                    value="{{ $month }}"
                >
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>

                <a
                    href="{{ route('admin.jadwal-piket.index') }}"
                    class="btn-reset"
                >
                    Reset
                </a>
            </div>

        </form>
    </div>

    {{-- =========================
         CALENDAR
    ========================== --}}
    <div class="calendar-card">

        <div class="calendar-header">
            <div class="calendar-month">
                {{ Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
            </div>

            <div class="calendar-navigation">

                @php
                    $currentMonth = Carbon\Carbon::createFromFormat('Y-m', $month);
                    $previousMonth = $currentMonth->copy()->subMonth()->format('Y-m');
                    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
                @endphp

                <a
                    href="{{ route('admin.jadwal-piket.index', array_merge(request()->except('month'), ['month' => $previousMonth])) }}"
                    class="calendar-nav-btn"
                    title="Bulan sebelumnya"
                >
                    <i class="fas fa-chevron-left"></i>
                </a>

                <a
                    href="{{ route('admin.jadwal-piket.index', array_merge(request()->except('month'), ['month' => $nextMonth])) }}"
                    class="calendar-nav-btn"
                    title="Bulan berikutnya"
                >
                    <i class="fas fa-chevron-right"></i>
                </a>

            </div>
        </div>

        <div class="calendar-grid">

            {{-- HEADER HARI --}}
            @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $weekday)
                <div class="calendar-weekday">
                    {{ $weekday }}
                </div>
            @endforeach

            {{-- HARI --}}
            @foreach ($calendarDays as $day)

                @php
                    /*
                     * $calendarDays dari controller berupa Carbon.
                     * Jadi jangan pakai $day['date'].
                     */
                    $dateKey = $day->format('Y-m-d');

                    $dayData = $calendarData[$dateKey] ?? null;

                    $isCurrentMonth = $day->format('Y-m') === $month;

                    $isToday = $dateKey === now()->format('Y-m-d');
                @endphp

                <div
                    class="
                        calendar-day
                        {{ !$isCurrentMonth ? 'other-month' : '' }}
                        {{ $isToday ? 'today' : '' }}
                    "
                    @if ($isCurrentMonth)
                        onclick="openScheduleDrawer('{{ $dateKey }}')"
                    @endif
                >

                    <div class="day-number">
                        {{ $day->day }}
                    </div>

                    @if ($isCurrentMonth && $dayData)

                        <div class="schedule-mini">

                            {{-- PAGI --}}
                            @if (!empty($dayData['pagi']))

                                <div class="schedule-mini-box">

                                    <div class="schedule-mini-label">
                                        Piket Pagi
                                    </div>

                                    @foreach (($dayData['pagi']['petugas'] ?? []) as $petugas)

                                        <div class="schedule-mini-name">
                                            {{ $petugas['nama'] ?? '-' }}
                                        </div>

                                    @endforeach

                                    @if (!empty($dayData['pagi']['koordinator']))

                                        <div class="schedule-mini-more">
                                            Koord:
                                            {{ $dayData['pagi']['koordinator']['nama'] ?? '-' }}
                                        </div>

                                    @endif

                                </div>

                            @endif

                            {{-- SIANG --}}
                            @if (!empty($dayData['siang']))

                                <div class="schedule-mini-box">

                                    <div class="schedule-mini-label">
                                        Piket Siang
                                    </div>

                                    @foreach (($dayData['siang']['petugas'] ?? []) as $petugas)

                                        <div class="schedule-mini-name">
                                            {{ $petugas['nama'] ?? '-' }}
                                        </div>

                                    @endforeach

                                    @if (!empty($dayData['siang']['koordinator']))

                                        <div class="schedule-mini-more">
                                            Koord:
                                            {{ $dayData['siang']['koordinator']['nama'] ?? '-' }}
                                        </div>

                                    @endif

                                </div>

                            @endif

                            {{-- WAKA --}}
                            @if (!empty($dayData['waka']))

                                <div class="schedule-mini-box">

                                    <div class="schedule-mini-label">
                                        Piket Waka
                                    </div>

                                    <div class="schedule-mini-name">
                                        {{ $dayData['waka']['nama'] ?? '-' }}
                                    </div>

                                </div>

                            @endif

                        </div>

                    @elseif ($isCurrentMonth)

                        <div class="empty-day">
                            Belum ada jadwal
                        </div>

                    @endif

                </div>

            @endforeach

        </div>
    </div>

</div>


{{-- ============================================================
     DRAWER OVERLAY
============================================================ --}}
<div
    id="drawerOverlay"
    class="drawer-overlay"
    onclick="closeScheduleDrawer()"
></div>


{{-- ============================================================
     DRAWER
============================================================ --}}
<div
    id="scheduleDrawer"
    class="schedule-drawer"
>

    {{-- HEADER --}}
    <div class="drawer-header">

        <div class="drawer-header-left">

            <h2
                id="drawerTitle"
                class="drawer-header-title"
            >
                Detail Jadwal
            </h2>

            <div
                id="drawerDate"
                class="drawer-header-date"
            >
                -
            </div>

        </div>

        <button
            type="button"
            class="drawer-close"
            onclick="closeScheduleDrawer()"
        >
            <i class="fas fa-times"></i>
        </button>

    </div>


    {{-- ========================================================
         DRAWER BODY
    ========================================================= --}}
    <div class="drawer-body">

        {{-- ERROR --}}
        @if ($errors->any())

            <div class="alert-error">

                <strong>Terjadi kesalahan:</strong>

                <ul style="margin:6px 0 0 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        {{-- ====================================================
             MODE DETAIL
        ===================================================== --}}
        <div id="detailMode">

            {{-- PAGI --}}
            <div class="detail-section">

                <div class="detail-section-header">

                    <h3 class="detail-section-title">
                        Piket KBM Pagi
                    </h3>

                    <span class="detail-time">
                        07:00 - 11:00
                    </span>

                </div>

                <div class="detail-section-body">

                    <div
                        id="detailPagiPetugas"
                        class="person-list"
                    ></div>

                    <div
                        id="detailPagiKoordinator"
                        style="margin-top:10px;"
                    ></div>

                </div>

            </div>


            {{-- SIANG --}}
            <div class="detail-section">

                <div class="detail-section-header">

                    <h3 class="detail-section-title">
                        Piket KBM Siang
                    </h3>

                    <span class="detail-time">
                        11:00 - 15:00
                    </span>

                </div>

                <div class="detail-section-body">

                    <div
                        id="detailSiangPetugas"
                        class="person-list"
                    ></div>

                    <div
                        id="detailSiangKoordinator"
                        style="margin-top:10px;"
                    ></div>

                </div>

            </div>


            {{-- WAKA --}}
            <div class="detail-section">

                <div class="detail-section-header">

                    <h3 class="detail-section-title">
                        Piket Waka
                    </h3>

                </div>

                <div class="detail-section-body">

                    <div
                        id="detailWaka"
                        class="person-list"
                    ></div>

                </div>

            </div>


            {{-- KETERANGAN --}}
            <div
                id="detailKeteranganSection"
                class="detail-section hidden"
            >

                <div class="detail-section-header">

                    <h3 class="detail-section-title">
                        Keterangan
                    </h3>

                </div>

                <div class="detail-section-body">

                    <div
                        id="detailKeterangan"
                        class="detail-note"
                    ></div>

                </div>

            </div>

        </div>


        {{-- ====================================================
             MODE CREATE
        ===================================================== --}}
        <div
            id="createMode"
            class="hidden"
        >

            <form
                id="createScheduleForm"
                method="POST"
                action="{{ route('admin.jadwal-piket.store') }}"
            >

                @csrf

                <input
                    type="hidden"
                    name="tanggal"
                    id="createTanggal"
                >


                {{-- PAGI --}}
                <div class="form-section">

                    <h3 class="form-section-title">
                        Piket KBM Pagi
                    </h3>

                    <div class="form-section-subtitle">
                        07:00 - 11:00
                    </div>

                    <div
                        id="createPagiPetugas"
                        class="petugas-list"
                    ></div>

                    <button
                        type="button"
                        class="btn-add"
                        onclick="addPetugas('create', 'pagi')"
                    >
                        <i class="fas fa-plus"></i>
                        Tambah Petugas
                    </button>

                    <div
                        class="form-group"
                        style="margin-top:15px;"
                    >

                        <label>
                            Koordinator Piket Pagi
                            <span class="required">*</span>
                        </label>

                        <select
                            name="pagi_koordinator"
                            id="createPagiKoordinator"
                            class="form-control"
                            required
                        >

                            <option value="">
                                Pilih koordinator
                            </option>

                            @foreach ($gurus as $guru)

                                <option value="{{ $guru->id_guru }}">
                                    {{ $guru->nama_guru }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- SIANG --}}
                <div class="form-section">

                    <h3 class="form-section-title">
                        Piket KBM Siang
                    </h3>

                    <div class="form-section-subtitle">
                        11:00 - 15:00
                    </div>

                    <div
                        id="createSiangPetugas"
                        class="petugas-list"
                    ></div>

                    <button
                        type="button"
                        class="btn-add"
                        onclick="addPetugas('create', 'siang')"
                    >
                        <i class="fas fa-plus"></i>
                        Tambah Petugas
                    </button>

                    <div
                        class="form-group"
                        style="margin-top:15px;"
                    >

                        <label>
                            Koordinator Piket Siang
                            <span class="required">*</span>
                        </label>

                        <select
                            name="siang_koordinator"
                            id="createSiangKoordinator"
                            class="form-control"
                            required
                        >

                            <option value="">
                                Pilih koordinator
                            </option>

                            @foreach ($gurus as $guru)

                                <option value="{{ $guru->id_guru }}">
                                    {{ $guru->nama_guru }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- WAKA --}}
                <div class="form-section">

                    <h3 class="form-section-title">
                        Piket Waka
                    </h3>

                    <div class="form-group">

                        <label>
                            Petugas Waka
                        </label>

                        <select
                            name="waka"
                            id="createWaka"
                            class="form-control"
                        >

                            <option value="">
                                Tidak ada
                            </option>

                            @foreach ($gurus as $guru)

                                <option value="{{ $guru->id_guru }}">
                                    {{ $guru->nama_guru }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- KETERANGAN --}}
                <div class="form-section">

                    <h3 class="form-section-title">
                        Keterangan
                    </h3>

                    <textarea
                        name="keterangan"
                        id="createKeterangan"
                        class="form-control"
                        style="height:100px; padding:12px; resize:vertical;"
                        placeholder="Tambahkan keterangan jika diperlukan..."
                    ></textarea>

                </div>

            </form>

        </div>


        {{-- ====================================================
             MODE EDIT
        ===================================================== --}}
        <div
            id="editMode"
            class="hidden"
        >

            <form
                id="editScheduleForm"
                method="POST"
            >

                @csrf
                @method('PUT')

                <input
                    type="hidden"
                    name="tanggal"
                    id="editTanggal"
                >


                {{-- PAGI --}}
                <div class="form-section">

                    <h3 class="form-section-title">
                        Piket KBM Pagi
                    </h3>

                    <div class="form-section-subtitle">
                        07:00 - 11:00
                    </div>

                    <div
                        id="editPagiPetugas"
                        class="petugas-list"
                    ></div>

                    <button
                        type="button"
                        class="btn-add"
                        onclick="addPetugas('edit', 'pagi')"
                    >
                        <i class="fas fa-plus"></i>
                        Tambah Petugas
                    </button>

                    <div
                        class="form-group"
                        style="margin-top:15px;"
                    >

                        <label>
                            Koordinator Piket Pagi
                            <span class="required">*</span>
                        </label>

                        <select
                            name="pagi_koordinator"
                            id="editPagiKoordinator"
                            class="form-control"
                            required
                        >

                            <option value="">
                                Pilih koordinator
                            </option>

                            @foreach ($gurus as $guru)

                                <option value="{{ $guru->id_guru }}">
                                    {{ $guru->nama_guru }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- SIANG --}}
                <div class="form-section">

                    <h3 class="form-section-title">
                        Piket KBM Siang
                    </h3>

                    <div class="form-section-subtitle">
                        11:00 - 15:00
                    </div>

                    <div
                        id="editSiangPetugas"
                        class="petugas-list"
                    ></div>

                    <button
                        type="button"
                        class="btn-add"
                        onclick="addPetugas('edit', 'siang')"
                    >
                        <i class="fas fa-plus"></i>
                        Tambah Petugas
                    </button>

                    <div
                        class="form-group"
                        style="margin-top:15px;"
                    >

                        <label>
                            Koordinator Piket Siang
                            <span class="required">*</span>
                        </label>

                        <select
                            name="siang_koordinator"
                            id="editSiangKoordinator"
                            class="form-control"
                            required
                        >

                            <option value="">
                                Pilih koordinator
                            </option>

                            @foreach ($gurus as $guru)

                                <option value="{{ $guru->id_guru }}">
                                    {{ $guru->nama_guru }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- WAKA --}}
                <div class="form-section">

                    <h3 class="form-section-title">
                        Piket Waka
                    </h3>

                    <div class="form-group">

                        <label>
                            Petugas Waka
                        </label>

                        <select
                            name="waka"
                            id="editWaka"
                            class="form-control"
                        >

                            <option value="">
                                Tidak ada
                            </option>

                            @foreach ($gurus as $guru)

                                <option value="{{ $guru->id_guru }}">
                                    {{ $guru->nama_guru }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- KETERANGAN --}}
                <div class="form-section">

                    <h3 class="form-section-title">
                        Keterangan
                    </h3>

                    <textarea
                        name="keterangan"
                        id="editKeterangan"
                        class="form-control"
                        style="height:100px; padding:12px; resize:vertical;"
                        placeholder="Tambahkan keterangan jika diperlukan..."
                    ></textarea>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================
         DRAWER FOOTER
    ========================================================= --}}
    <div class="drawer-footer">

        {{-- DETAIL FOOTER --}}
        <div
            id="detailFooter"
            style="width:100%; display:flex; justify-content:space-between; gap:8px;"
        >

            <form
                id="deleteScheduleForm"
                method="POST"
                onsubmit="return confirmDeleteSchedule()"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn-danger"
                >
                    <i class="fas fa-trash"></i>
                    Hapus
                </button>

            </form>

            <button
                type="button"
                class="btn-primary"
                onclick="startEditSchedule()"
            >
                <i class="fas fa-pen"></i>
                Edit Jadwal
            </button>

        </div>


        {{-- CREATE FOOTER --}}
        <div
            id="createFooter"
            class="hidden"
            style="width:100%; display:flex; justify-content:flex-end; gap:8px;"
        >

            <button
                type="button"
                class="btn-secondary"
                onclick="closeScheduleDrawer()"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-save"
                onclick="submitCreateSchedule()"
            >
                <i class="fas fa-save"></i>
                Simpan Jadwal
            </button>

        </div>


        {{-- EDIT FOOTER --}}
        <div
            id="editFooter"
            class="hidden"
            style="width:100%; display:flex; justify-content:flex-end; gap:8px;"
        >

            <button
                type="button"
                class="btn-secondary"
                onclick="cancelEditSchedule()"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-save"
                onclick="submitEditSchedule()"
            >
                <i class="fas fa-save"></i>
                Simpan Perubahan
            </button>

        </div>

    </div>

</div>


{{-- ============================================================
     JAVASCRIPT
============================================================ --}}
<script>

    /*
    |--------------------------------------------------------------------------
    | DATA DARI CONTROLLER
    |--------------------------------------------------------------------------
    */

    const scheduleData = @json($calendarData);

    const gurus = @json(
        $gurus->map(function ($guru) {
            return [
                'id_guru' => $guru->id_guru,
                'nama' => $guru->nama_guru,
            ];
        })->values()
    );


    let currentDate = null;


    /*
    |--------------------------------------------------------------------------
    | ELEMENT DRAWER
    |--------------------------------------------------------------------------
    */

    const drawer = document.getElementById('scheduleDrawer');
    const overlay = document.getElementById('drawerOverlay');

    const detailMode = document.getElementById('detailMode');
    const createMode = document.getElementById('createMode');
    const editMode = document.getElementById('editMode');

    const detailFooter = document.getElementById('detailFooter');
    const createFooter = document.getElementById('createFooter');
    const editFooter = document.getElementById('editFooter');

    const drawerTitle = document.getElementById('drawerTitle');
    const drawerDate = document.getElementById('drawerDate');


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    function getInitials(name) {

        if (!name) {
            return '?';
        }

        const words = String(name)
            .trim()
            .split(/\s+/)
            .filter(Boolean);

        if (words.length === 1) {
            return words[0].substring(0, 2).toUpperCase();
        }

        return (
            words[0].charAt(0) +
            words[words.length - 1].charAt(0)
        ).toUpperCase();
    }


    function formatDateIndonesia(dateString) {

        if (!dateString) {
            return '-';
        }

        const date = new Date(dateString + 'T00:00:00');

        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }


    function showMode(mode) {

        detailMode.classList.add('hidden');
        createMode.classList.add('hidden');
        editMode.classList.add('hidden');

        detailFooter.classList.add('hidden');
        createFooter.classList.add('hidden');
        editFooter.classList.add('hidden');

        if (mode === 'detail') {

            detailMode.classList.remove('hidden');
            detailFooter.classList.remove('hidden');

        }

        if (mode === 'create') {

            createMode.classList.remove('hidden');
            createFooter.classList.remove('hidden');

        }

        if (mode === 'edit') {

            editMode.classList.remove('hidden');
            editFooter.classList.remove('hidden');

        }
    }


    /*
    |--------------------------------------------------------------------------
    | OPEN / CLOSE DRAWER
    |--------------------------------------------------------------------------
    */

    function openDrawer() {

        overlay.classList.add('show');
        drawer.classList.add('open');

        document.body.style.overflow = 'hidden';
    }


    function closeScheduleDrawer() {

        overlay.classList.remove('show');
        drawer.classList.remove('open');

        document.body.style.overflow = '';

        currentDate = null;
    }


    /*
    |--------------------------------------------------------------------------
    | CLICK TANGGAL
    |--------------------------------------------------------------------------
    */

    function openScheduleDrawer(dateKey) {

        currentDate = dateKey;

        const data = scheduleData[dateKey];

        /*
         * Kalau belum ada jadwal:
         * langsung masuk form tambah.
         */
        if (!data) {

            openCreateDrawer(dateKey);

            return;
        }

        openDetailDrawer(dateKey);
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL DRAWER
    |--------------------------------------------------------------------------
    */

    function openDetailDrawer(dateKey) {

        currentDate = dateKey;

        const data = scheduleData[dateKey];

        drawerTitle.textContent = 'Detail Jadwal Piket';

        drawerDate.textContent = formatDateIndonesia(dateKey);

        showMode('detail');

        renderDetail(data);

        const deleteForm = document.getElementById('deleteScheduleForm');

        deleteForm.action =
            "{{ route('admin.jadwal-piket.destroy', '__DATE__') }}"
            .replace('__DATE__', dateKey);

        openDrawer();
    }


    function renderDetail(data) {

        /*
        |--------------------------------------------------------------------------
        | PAGI PETUGAS
        |--------------------------------------------------------------------------
        */

        const pagiContainer =
            document.getElementById('detailPagiPetugas');

        const pagiPetugas =
            data?.pagi?.petugas ?? [];

        if (pagiPetugas.length === 0) {

            pagiContainer.innerHTML = `
                <div class="empty-detail">
                    Belum ada petugas pagi.
                </div>
            `;

        } else {

            pagiContainer.innerHTML =
                pagiPetugas.map(person => `
                    <div class="person-item">

                        <div class="person-avatar">
                            ${escapeHtml(getInitials(person.nama))}
                        </div>

                        <div>
                            <div class="person-name">
                                ${escapeHtml(person.nama)}
                            </div>

                            <div class="person-role">
                                Petugas Piket KBM Pagi
                            </div>
                        </div>

                    </div>
                `).join('');
        }


        /*
        |--------------------------------------------------------------------------
        | PAGI KOORDINATOR
        |--------------------------------------------------------------------------
        */

        const pagiKoordinator =
            data?.pagi?.koordinator;

        const pagiKoordContainer =
            document.getElementById('detailPagiKoordinator');

        if (pagiKoordinator) {

            pagiKoordContainer.innerHTML = `
                <div class="person-item">

                    <div class="person-avatar">
                        ${escapeHtml(
                            getInitials(pagiKoordinator.nama)
                        )}
                    </div>

                    <div>
                        <div class="person-name">
                            ${escapeHtml(pagiKoordinator.nama)}
                        </div>

                        <div class="person-role">
                            Koordinator Piket Pagi
                        </div>
                    </div>

                </div>
            `;

        } else {

            pagiKoordContainer.innerHTML = `
                <div class="empty-detail">
                    Belum ada koordinator pagi.
                </div>
            `;
        }


        /*
        |--------------------------------------------------------------------------
        | SIANG PETUGAS
        |--------------------------------------------------------------------------
        */

        const siangContainer =
            document.getElementById('detailSiangPetugas');

        const siangPetugas =
            data?.siang?.petugas ?? [];

        if (siangPetugas.length === 0) {

            siangContainer.innerHTML = `
                <div class="empty-detail">
                    Belum ada petugas siang.
                </div>
            `;

        } else {

            siangContainer.innerHTML =
                siangPetugas.map(person => `
                    <div class="person-item">

                        <div class="person-avatar">
                            ${escapeHtml(getInitials(person.nama))}
                        </div>

                        <div>
                            <div class="person-name">
                                ${escapeHtml(person.nama)}
                            </div>

                            <div class="person-role">
                                Petugas Piket KBM Siang
                            </div>
                        </div>

                    </div>
                `).join('');
        }


        /*
        |--------------------------------------------------------------------------
        | SIANG KOORDINATOR
        |--------------------------------------------------------------------------
        */

        const siangKoordinator =
            data?.siang?.koordinator;

        const siangKoordContainer =
            document.getElementById('detailSiangKoordinator');

        if (siangKoordinator) {

            siangKoordContainer.innerHTML = `
                <div class="person-item">

                    <div class="person-avatar">
                        ${escapeHtml(
                            getInitials(siangKoordinator.nama)
                        )}
                    </div>

                    <div>
                        <div class="person-name">
                            ${escapeHtml(siangKoordinator.nama)}
                        </div>

                        <div class="person-role">
                            Koordinator Piket Siang
                        </div>
                    </div>

                </div>
            `;

        } else {

            siangKoordContainer.innerHTML = `
                <div class="empty-detail">
                    Belum ada koordinator siang.
                </div>
            `;
        }


        /*
        |--------------------------------------------------------------------------
        | WAKA
        |--------------------------------------------------------------------------
        */

        const wakaContainer =
            document.getElementById('detailWaka');

        const waka =
            data?.waka;

        if (waka) {

            wakaContainer.innerHTML = `
                <div class="person-item">

                    <div class="person-avatar">
                        ${escapeHtml(getInitials(waka.nama))}
                    </div>

                    <div>
                        <div class="person-name">
                            ${escapeHtml(waka.nama)}
                        </div>

                        <div class="person-role">
                            Piket Waka
                        </div>
                    </div>

                </div>
            `;

        } else {

            wakaContainer.innerHTML = `
                <div class="empty-detail">
                    Belum ada petugas Waka.
                </div>
            `;
        }


        /*
        |--------------------------------------------------------------------------
        | KETERANGAN
        |--------------------------------------------------------------------------
        */

        const keteranganSection =
            document.getElementById('detailKeteranganSection');

        const keterangan =
            data?.keterangan ?? '';

        if (keterangan) {

            keteranganSection.classList.remove('hidden');

            document.getElementById('detailKeterangan').textContent =
                keterangan;

        } else {

            keteranganSection.classList.add('hidden');

        }
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    function openCreateDrawer(dateKey = null) {

        currentDate = dateKey || null;

        drawerTitle.textContent = 'Tambah Jadwal Piket';

        drawerDate.textContent =
            currentDate
                ? formatDateIndonesia(currentDate)
                : 'Pilih tanggal untuk jadwal piket';

        showMode('create');

        resetCreateForm();

        if (currentDate) {

            document.getElementById('createTanggal').value =
                currentDate;

        }

        openDrawer();
    }


    function resetCreateForm() {

        const form =
            document.getElementById('createScheduleForm');

        form.reset();

        document.getElementById('createPagiPetugas').innerHTML = '';
        document.getElementById('createSiangPetugas').innerHTML = '';

        addPetugas('create', 'pagi');
        addPetugas('create', 'siang');
    }


    /*
    |--------------------------------------------------------------------------
    | ADD PETUGAS
    |--------------------------------------------------------------------------
    */

    function addPetugas(mode, shift, selectedId = '') {

        const containerId =
            mode === 'create'
                ? `create${capitalize(shift)}Petugas`
                : `edit${capitalize(shift)}Petugas`;

        const container =
            document.getElementById(containerId);

        const inputName =
            shift === 'pagi'
                ? 'pagi_petugas[]'
                : 'siang_petugas[]';

        const row = document.createElement('div');

        row.className = 'petugas-row';

        row.innerHTML = `
            <select
                name="${inputName}"
                class="form-control petugas-select"
                required
            >
                <option value="">
                    Pilih petugas
                </option>

                ${gurus.map(guru => `
                    <option
                        value="${guru.id_guru}"
                        ${String(guru.id_guru) === String(selectedId) ? 'selected' : ''}
                    >
                        ${escapeHtml(guru.nama)}
                    </option>
                `).join('')}
            </select>

            <button
                type="button"
                class="btn-icon btn-remove"
                onclick="removePetugas(this)"
                title="Hapus petugas"
            >
                <i class="fas fa-trash"></i>
            </button>
        `;

        container.appendChild(row);

        updatePetugasOptions(mode, shift);
    }


    function removePetugas(button) {

        const row = button.closest('.petugas-row');

        if (!row) {
            return;
        }

        const container = row.parentElement;

        /*
         * Minimal satu petugas.
         */
        if (container.children.length <= 1) {

            const select =
                row.querySelector('select');

            if (select) {
                select.value = '';
            }

            return;
        }

        row.remove();
    }


    function capitalize(value) {

        return value.charAt(0).toUpperCase() + value.slice(1);
    }


    /*
    |--------------------------------------------------------------------------
    | CEGAH GURU YANG SAMA DI PETUGAS
    |--------------------------------------------------------------------------
    */

    function updatePetugasOptions(mode, shift) {

        const containerId =
            mode === 'create'
                ? `create${capitalize(shift)}Petugas`
                : `edit${capitalize(shift)}Petugas`;

        const container =
            document.getElementById(containerId);

        if (!container) {
            return;
        }

        const selects =
            container.querySelectorAll('.petugas-select');

        const selectedValues = [];

        selects.forEach(select => {

            if (select.value) {
                selectedValues.push(select.value);
            }

        });

        selects.forEach(select => {

            const currentValue = select.value;

            Array.from(select.options).forEach(option => {

                if (!option.value) {
                    return;
                }

                option.disabled =
                    selectedValues.includes(option.value) &&
                    option.value !== currentValue;

            });

        });
    }


    document.addEventListener('change', function(event) {

        if (
            event.target.classList.contains('petugas-select')
        ) {

            const select =
                event.target;

            const container =
                select.closest('.petugas-list');

            if (!container) {
                return;
            }

            const id =
                container.id;

            const mode =
                id.startsWith('create')
                    ? 'create'
                    : 'edit';

            const shift =
                id.toLowerCase().includes('pagi')
                    ? 'pagi'
                    : 'siang';

            updatePetugasOptions(mode, shift);
        }
    });


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    function startEditSchedule() {

        if (!currentDate) {
            return;
        }

        const data =
            scheduleData[currentDate];

        if (!data) {
            return;
        }

        drawerTitle.textContent =
            'Edit Jadwal Piket';

        drawerDate.textContent =
            formatDateIndonesia(currentDate);

        showMode('edit');

        document.getElementById('editTanggal').value =
            currentDate;


        /*
        |--------------------------------------------------------------------------
        | ACTION FORM
        |--------------------------------------------------------------------------
        */

        document.getElementById('editScheduleForm').action =
            "{{ route('admin.jadwal-piket.update', '__DATE__') }}"
                .replace('__DATE__', currentDate);


        /*
        |--------------------------------------------------------------------------
        | PAGI PETUGAS
        |--------------------------------------------------------------------------
        */

        const pagiContainer =
            document.getElementById('editPagiPetugas');

        pagiContainer.innerHTML = '';

        const pagiPetugas =
            data?.pagi?.petugas ?? [];

        if (pagiPetugas.length > 0) {

            pagiPetugas.forEach(person => {

                addPetugas(
                    'edit',
                    'pagi',
                    person.id_guru
                );

            });

        } else {

            addPetugas('edit', 'pagi');

        }


        /*
        |--------------------------------------------------------------------------
        | KOORDINATOR PAGI
        |--------------------------------------------------------------------------
        */

        document.getElementById('editPagiKoordinator').value =
            data?.pagi?.koordinator?.id_guru ?? '';


        /*
        |--------------------------------------------------------------------------
        | SIANG PETUGAS
        |--------------------------------------------------------------------------
        */

        const siangContainer =
            document.getElementById('editSiangPetugas');

        siangContainer.innerHTML = '';

        const siangPetugas =
            data?.siang?.petugas ?? [];

        if (siangPetugas.length > 0) {

            siangPetugas.forEach(person => {

                addPetugas(
                    'edit',
                    'siang',
                    person.id_guru
                );

            });

        } else {

            addPetugas('edit', 'siang');

        }


        /*
        |--------------------------------------------------------------------------
        | KOORDINATOR SIANG
        |--------------------------------------------------------------------------
        */

        document.getElementById('editSiangKoordinator').value =
            data?.siang?.koordinator?.id_guru ?? '';


        /*
        |--------------------------------------------------------------------------
        | WAKA
        |--------------------------------------------------------------------------
        */

        document.getElementById('editWaka').value =
            data?.waka?.id_guru ?? '';


        /*
        |--------------------------------------------------------------------------
        | KETERANGAN
        |--------------------------------------------------------------------------
        */

        document.getElementById('editKeterangan').value =
            data?.keterangan ?? '';


        updatePetugasOptions('edit', 'pagi');
        updatePetugasOptions('edit', 'siang');
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL EDIT
    |--------------------------------------------------------------------------
    */

    function cancelEditSchedule() {

        if (!currentDate) {
            return;
        }

        openDetailDrawer(currentDate);
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT CREATE
    |--------------------------------------------------------------------------
    */

    function submitCreateSchedule() {

        const form =
            document.getElementById('createScheduleForm');

        const tanggal =
            document.getElementById('createTanggal').value;

        if (!tanggal) {

            alert('Tanggal jadwal belum dipilih.');

            return;
        }

        /*
         * Pastikan petugas ada.
         */

        const pagiPetugas =
            document.querySelectorAll(
                '#createPagiPetugas .petugas-select'
            );

        const siangPetugas =
            document.querySelectorAll(
                '#createSiangPetugas .petugas-select'
            );

        for (const select of pagiPetugas) {

            if (!select.value) {

                alert('Pilih semua petugas Piket Pagi.');

                return;
            }
        }

        for (const select of siangPetugas) {

            if (!select.value) {

                alert('Pilih semua petugas Piket Siang.');

                return;
            }
        }

        form.submit();
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT EDIT
    |--------------------------------------------------------------------------
    */

    function submitEditSchedule() {

        const form =
            document.getElementById('editScheduleForm');

        const pagiPetugas =
            document.querySelectorAll(
                '#editPagiPetugas .petugas-select'
            );

        const siangPetugas =
            document.querySelectorAll(
                '#editSiangPetugas .petugas-select'
            );

        for (const select of pagiPetugas) {

            if (!select.value) {

                alert('Pilih semua petugas Piket Pagi.');

                return;
            }
        }

        for (const select of siangPetugas) {

            if (!select.value) {

                alert('Pilih semua petugas Piket Siang.');

                return;
            }
        }

        form.submit();
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    function confirmDeleteSchedule() {

        return confirm(
            'Yakin ingin menghapus seluruh jadwal piket pada tanggal ini?'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            closeScheduleDrawer();

        }

    });

</script>

@endsection