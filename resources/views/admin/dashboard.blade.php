@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')

<style>
    /* ============================================================
       ANIMATION
    ============================================================ */
    @keyframes pageFadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeDown {
        from {
            opacity: 0;
            transform: translateY(-7px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes cardUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes tableUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulseSoft {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    /* ============================================================
       STATISTICS
    ============================================================ */
    .stats {
        animation: pageFadeIn .45s ease both;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
        animation: cardUp .45s ease both;
        transition:
            transform .25s cubic-bezier(.16, 1, .3, 1),
            box-shadow .25s ease,
            border-color .25s ease;
    }

    .stat-card:nth-child(2) {
        animation-delay: .06s;
    }

    .stat-card:nth-child(3) {
        animation-delay: .12s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px -5px rgba(27, 35, 74, .08);
        border-color: #cbd5e1;
    }

    .stat-icon {
        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.06);
        box-shadow: 0 4px 10px rgba(48, 54, 111, .12);
    }

    .stat-card .stat-icon span {
        transition:
            transform .25s ease,
            color .25s ease;
    }

    .stat-card:hover .stat-icon span {
        transform: scale(1.15) rotate(-5deg);
        color: #1B234A;
    }

    .today-badge {
        transition:
            transform .2s ease,
            background .2s ease;
    }

    .stat-card:hover .today-badge {
        transform: translateY(-1px);
        animation: pulseSoft 1.2s infinite ease-in-out;
    }

    /* ============================================================
       ACTIVITY CARD
    ============================================================ */
    .activity-card {
        padding: 24px 28px;
        background: #ffffff;
        border-radius: 16px;
        animation: cardUp .5s ease .1s both;
        transition: box-shadow .25s ease;
    }

    .activity-card:hover {
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, .05);
    }

    .activity-header {
        animation: fadeDown .45s ease .15s both;
    }

    /* ============================================================
       FILTER BAR
    ============================================================ */
    .filters-container {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        margin-bottom: 22px;
        animation: fadeDown .45s ease .2s both;
    }

    /* ============================================================
       SEARCH
    ============================================================ */
    .search-box {
        position: relative;
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .search-box input {
        width: 100%;
        height: 42px;
        padding: 10px 14px 10px 40px;
        background: #f1f5f9;
        border: 1px solid transparent;
        border-radius: 10px;
        font-size: 13px;
        color: #334155;
        outline: none;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background-color .2s ease;
    }

    .search-box input:focus {
        background-color: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .15);
    }

    .search-box:focus-within {
        border-color: #7886c7;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, .1);
    }

    .search-icon {
        position: absolute;
        left: 13px;
        color: #94a3b8;
        font-size: 19px;
        pointer-events: none;
        transition:
            transform .2s ease,
            color .2s ease;
    }

    .search-box:focus-within .search-icon {
        color: #30366f;
        transform: scale(1.08);
    }

    /* ============================================================
       FILTER BAGIAN KANAN
    ============================================================ */
    .filter-controls-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }

    /* ============================================================
       SELECT & DATE
    ============================================================ */
    .filter-select {
        height: 42px;
        padding: 10px 14px;
        background: #f1f5f9;
        border: 1px solid transparent;
        border-radius: 10px;
        font-size: 13px;
        color: #475569;
        outline: none;
        cursor: pointer;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background-color .2s ease,
            transform .2s ease;
    }

    .filter-select:hover {
        background-color: #e2e8f0;
        border-color: #c4c7d2;
    }

    .filter-select:focus {
        background-color: #ffffff;
        border-color: #7886c7;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, .1);
        outline: none;
    }

    select[name="status"] {
        flex: 1.4;
        min-width: 180px;
    }

    select[name="kelas_id"] {
        flex: 1;
        min-width: 145px;
    }

    input[name="tanggal"] {
        flex: 1;
        min-width: 150px;
    }

    /* ============================================================
       RESET
    ============================================================ */
    .btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 42px;
        padding: 10px 18px;
        min-width: 140px;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition:
            background .2s ease,
            color .2s ease,
            border-color .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .btn-reset span.material-symbols-outlined {
        font-size: 18px;
    }

    .btn-reset:hover {
        background-color: #ffe4e6;
        border-color: #fecdd3;
        color: #e11d48;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(225, 29, 72, .1);
    }

    /* ============================================================
       TABLE
    ============================================================ */
    .table-wrapper {
        animation: tableUp .5s ease .25s both;
    }

    tbody tr {
        animation: fadeInUp .35s cubic-bezier(.16, 1, .3, 1) both;
        transition:
            background-color .18s ease,
            transform .18s ease;
    }

    tbody tr:hover {
        background-color: #fafbff !important;
    }

    tbody tr[data-href] {
        cursor: pointer;
    }

    .teacher {
        transition: color .18s ease;
    }

    tbody tr:hover .teacher {
        color: #30366f !important;
    }

    /* ============================================================
       ACTION BUTTON
    ============================================================ */
    .action {
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition:
            transform .2s ease,
            background .2s ease,
            color .2s ease;
    }

    .action:hover {
        background-color: #e0e7ff;
        color: #3730a3;
        transform: scale(1.08);
    }

    .action:active {
        transform: scale(.95);
    }

    .action .material-symbols-outlined {
        transition: transform .2s ease;
    }

    .action:hover .material-symbols-outlined {
        transform: scale(1.08);
    }

    /* ============================================================
       STATUS BADGE
    ============================================================ */
    .status {
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .3px;
        display: inline-block;
        transition:
        transform .18s ease,
        box-shadow .18s ease;
    }

    tbody tr:hover .status {
        transform: translateY(-1px);
    }

    .status.disetujui {
        background-color: #dcfce7;
        color: #15803d;
    }

    .status.menunggu {
        background-color: #fef9c3;
        color: #a16207;
    }

    .status.ditolak {
        background-color: #ffe4e6;
        color: #be123c;
    }

    /* ============================================================
       CLASS BADGE
    ============================================================ */
    .class-badge {
        background-color: #f1f5f9;
        color: #334155;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        transition:
            transform .18s ease,
            background .2s ease,
            box-shadow .18s ease;
    }

    tbody tr:hover .class-badge {
        background-color: #e2e8f0;
        transform: translateY(-1px);
    }

    /* ============================================================
       BOTTOM / PAGINATION
    ============================================================ */
    .bottom {
        animation: fadeUp .45s ease .3s both;
    }

    .bottom a,
    .bottom button {
        transition:
            transform .18s ease,
            background .18s ease,
            color .18s ease;
    }

    .bottom a:hover,
    .bottom button:hover {
        transform: translateY(-1px);
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 1100px) {
        .filters-container {
            flex-wrap: wrap;
        }

        .search-box {
            width: 100%;
            max-width: 350px;
        }

        .filter-controls-right {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 1000px) {
        .filters-container {
            flex-wrap: wrap;
        }

        .search-box {
            flex-basis: 100%;
            max-width: none;
        }

        .filter-controls-right {
            flex: 1;
        }
    }

    @media (max-width: 700px) {
        .activity-card {
            padding: 18px;
        }

        .filters-container {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            width: 100%;
            max-width: none;
        }

        .filter-controls-right {
            width: 100%;
            flex-wrap: wrap;
        }

        select[name="status"],
        select[name="kelas_id"],
        input[name="tanggal"] {
            flex: 1;
            min-width: 140px;
        }

        .btn-reset {
            flex: 1;
        }
    }

    /* ============================================================
       REDUCED MOTION
    ============================================================ */
    @media (prefers-reduced-motion: reduce) {
        .stats,
        .stat-card,
        .activity-card,
        .activity-header,
        .filters-container,
        .table-wrapper,
        .bottom,
        tbody tr {
            animation: none;
        }

        .stat-card,
        .stat-icon,
        .today-badge,
        .search-box,
        .filter-select,
        .btn-reset,
        tbody tr,
        .class-badge,
        .status,
        .action,
        .action .material-symbols-outlined,
        .bottom a,
        .bottom button {
            transition: none;
        }
    }
</style>

@endpush

@section('content')

<!-- ============================================================
     SECTION STATISTIK
============================================================ -->

<section class="stats">


<div class="stat-card">

    <div class="stat-title">
        Aktivitas Jurnal Hari Ini
    </div>

    <div class="stat-value">
        <strong>
            {{ $totalJurnalHariIni }}
        </strong>

        <span>
            Terisi
        </span>
    </div>

    <span class="today-badge">
        Hari Ini
    </span>

</div>

<div class="stat-card">

    <div class="stat-title">
        Jumlah Guru
    </div>

    <div class="stat-value">
        <strong>
            {{ $totalGuru }}
        </strong>

        <span>
            Terdaftar
        </span>
    </div>

    <div class="stat-icon">
        <span class="material-symbols-outlined">
            person_add
        </span>
    </div>

</div>

<div class="stat-card">

    <div class="stat-title">
        Jumlah Kelas
    </div>

    <div class="stat-value">
        <strong>
            {{ $totalKelas }}
        </strong>

        <span>
            Kelas
        </span>
    </div>

    <div class="stat-icon">
        <span class="material-symbols-outlined">
            meeting_room
        </span>
    </div>

</div>


</section>

<!-- ============================================================
     SECTION AKTIVITAS JURNAL
============================================================ -->

<section class="activity-card">


<div class="activity-header">

    <h3 class="activity-title">
        Aktivitas Jurnal Terkini
    </h3>

    <p class="activity-description">
        Daftar entri jurnal pembelajaran harian dan status validasi kurikulum.
    </p>

</div>


<!-- ========================================================
     FILTER
========================================================= -->
<form
    action="{{ route('admin.dashboard') }}"
    method="GET"
    class="filters-container"
>

    <!-- SEARCH -->
    <div class="search-box">

        <span class="material-symbols-outlined search-icon">
            search
        </span>

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari jurnal, nama guru, kelas, mapel..."
        >

    </div>


    <div class="filter-controls-right">

        <!-- STATUS -->
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">
                Semua Status Validasi
            </option>

            <option value="Valid" {{ request('status') == 'Valid' ? 'selected' : '' }}>
                Valid
            </option>

            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>
                Menunggu
            </option>

            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>
                Ditolak
            </option>

        </select>


        <!-- KELAS -->
        <select name="kelas_id" class="filter-select" onchange="this.form.submit()">

            <option value="">
                Semua Kelas
            </option>

            @foreach($kelases as $kelas)

                <option value="{{ $kelas->id_kelas }}" {{ request('kelas_id') == $kelas->id_kelas ? 'selected' : '' }}>
                    {{ $kelas->nama_kelas }}
                </option>

            @endforeach

        </select>


        <!-- TANGGAL -->
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="filter-select" onchange="this.form.submit()">

        <!-- RESET -->
        <a href="{{ route('admin.dashboard') }}" class="btn-reset" title="Reset Semua Filter">
            <span class="material-symbols-outlined">
                restart_alt
            </span>

            <span>
                Reset Filter
            </span>
        </a>
    </div>
</form>


<!-- ========================================================
     TABLE
========================================================= -->
<div class="table-wrapper">

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>
                    TANGGAL &<br>
                    JAM
                </th>
                <th>
                    GURU
                </th>
                <th>
                    MATA<br>
                    PELAJARAN
                </th>
                <th>
                    KELAS
                </th>
                <th>
                    KEHADIRAN
                </th>
                <th>
                    STATUS<br>
                    VALIDASI
                </th>
                <th>
                    AKSI
                </th>
            </tr>
        </thead>

        <tbody>
            @forelse($jurnals as $index => $item)
                <tr data-href="{{ route('admin.jurnal.show', $item) }}" style="animation-delay: {{ $index * 0.04 }};">

                    <td class="number">
                        {{ $jurnals->firstItem() + $index }}
                    </td>

                    <td>
                        <span class="date">
                            {{ $item->created_at->format('d M Y') }}
                        </span>

                        <span class="time" style=" display: block; font-size: 11px; color: #94a3b8;">
                            {{ $item->jam_ke }}
                        </span>
                    </td>

                    <td
                        class="teacher"
                        style="
                            font-weight: 600;
                            color: #1e293b;
                        "
                    >
                        {{ $item->guru?->nama_guru ?? '-' }}
                    </td>

                    <td class="subject">
                        {{ $item->jadwal->mapel->nama_mapel ?? '-' }}
                    </td>

                    <td>
                        <span class="class-badge">
                            {{ $item->kelas?->nama_kelas ?? '-' }}
                        </span>
                    </td>

                    <td class="attendance">
                        <strong>
                            {{ $item->jml_hadir ?? 0 }}/{{ ($item->jml_hadir ?? 0) + ($item->jml_tidak_hadir ?? 0) }}
                        </strong>
                    </td>

                    <td>
                        <span class="status {{ strtolower($item->status_validasi_guru) }}">
                            {{ $item->status_validasi_guru }}
                        </span>
                    </td>

            <td>
                <a href="{{ route('admin.jurnal.show', $item) }}" class="action" aria-label="Lihat detail jurnal">
                    <span class="material-symbols-outlined">visibility</span>
                </a>
            </td>

                </tr>

            @empty

                <tr>
                    <td
                        colspan="8"
                        style="
                            text-align: center;
                            padding: 32px;
                            color: #94a3b8;
                        "
                    >
                        Belum ada data jurnal yang sesuai dengan filter.
                    </td>
                </tr>

            @endforelse
        </tbody>
    </table>

</div>
<!-- ========================================================
     PAGINATION
========================================================= -->
<div
    class="bottom"
    style="margin-top: 20px;"
>
    {{ $jurnals->links() }}
</div>


</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('tbody tr[data-href]').forEach(function (row) {
                row.addEventListener('click', function (event) {
                    if (event.target.closest('a, button, input, select, textarea, label, option')) {
                        return;
                    }

                    window.location.href = row.dataset.href;
                });
            });
        });
    </script>
@endpush

@endsection