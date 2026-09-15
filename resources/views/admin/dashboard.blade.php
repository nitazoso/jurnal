@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    .stats {
        animation: pageFadeIn .45s ease both;
    }

    .stat-card {
        animation: cardUp .45s ease both;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .stat-card:nth-child(2) {
        animation-delay: .06s;
    }

    .stat-card:nth-child(3) {
        animation-delay: .12s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 18px rgba(0, 0, 0, 0.06);
    }

    .stat-icon {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.06);
        box-shadow: 0 4px 10px rgba(48, 54, 111, 0.12);
    }

    .today-badge {
        transition: transform .2s ease, background .2s ease;
    }

    .stat-card:hover .today-badge {
        transform: translateY(-1px);
    }

    .activity-card {
        animation: cardUp .5s ease .1s both;
        transition: box-shadow .25s ease;
    }

    .activity-card:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    .activity-header {
        animation: fadeDown .45s ease .15s both;
    }

    .filters {
        animation: fadeDown .45s ease .2s both;
    }

    .search-box {
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .search-box:focus-within {
        border-color: #7886c7;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, 0.1);
    }

    .search-box .material-symbols-outlined {
        transition: transform .2s ease, color .2s ease;
    }

    .search-box:focus-within .material-symbols-outlined {
        color: #30366f;
        transform: scale(1.08);
    }

    .filter-select {
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .filter-select:hover {
        border-color: #c4c7d2;
    }

    .filter-select:focus {
        border-color: #7886c7;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, 0.1);
        outline: none;
    }

    .btn-reset {
        transition: background .2s ease, color .2s ease, transform .2s ease;
    }

    .btn-reset:hover {
        background: #eef0f5;
        transform: translateY(-1px);
    }

    .table-wrapper {
        animation: tableUp .5s ease .25s both;
    }

    table tbody tr {
        transition: background .18s ease, transform .18s ease;
    }

    table tbody tr:hover {
        background: #fafbff;
    }

    .teacher {
        transition: color .18s ease;
    }

    table tbody tr:hover .teacher {
        color: #30366f;
    }

    .class-badge,
    .status {
        transition: transform .18s ease, box-shadow .18s ease;
    }

    table tbody tr:hover .class-badge,
    table tbody tr:hover .status {
        transform: translateY(-1px);
    }

    .action {
        transition: transform .2s ease, background .2s ease, color .2s ease;
    }

    .action:hover {
        transform: scale(1.08);
    }

    .action .material-symbols-outlined {
        transition: transform .2s ease;
    }

    .action:hover .material-symbols-outlined {
        transform: scale(1.08);
    }

    .bottom {
        animation: fadeUp .45s ease .3s both;
    }

    .bottom a,
    .bottom button {
        transition: transform .18s ease, background .18s ease, color .18s ease;
    }

    .bottom a:hover,
    .bottom button:hover {
        transform: translateY(-1px);
    }

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

    @media (prefers-reduced-motion: reduce) {
        .stats,
        .stat-card,
        .activity-card,
        .activity-header,
        .filters,
        .table-wrapper,
        .bottom {
            animation: none;
        }

        .stat-card,
        .stat-icon,
        .today-badge,
        .search-box,
        .filter-select,
        .btn-reset,
        table tbody tr,
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

<section class="stats">

    <div class="stat-card">
        <div class="stat-title">Aktivitas Jurnal Hari Ini</div>

        <div class="stat-value">
            <strong>{{ $totalJurnalHariIni }}</strong>
            <span>Terisi</span>
        </div>

        <span class="today-badge">Hari Ini</span>
    </div>

    <div class="stat-card">
        <div class="stat-title">Jumlah Guru</div>

        <div class="stat-value">
            <strong>{{ $totalGuru }}</strong>
            <span>Terdaftar</span>
        </div>

        <div class="stat-icon">
            <span class="material-symbols-outlined">person_add</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-title">Jumlah Kelas</div>

        <div class="stat-value">
            <strong>{{ $totalKelas }}</strong>
            <span>Kelas</span>
        </div>

        <div class="stat-icon">
            <span class="material-symbols-outlined">meeting_room</span>
        </div>
    </div>

</section>

<section class="activity-card">

    <div class="activity-header">
        <h3 class="activity-title">Aktivitas Jurnal Terkini</h3>
        <p class="activity-description">
            Daftar entri jurnal pembelajaran harian dan status validasi kurikulum.
        </p>
    </div>

    <form action="{{ route('admin.dashboard') }}" method="GET" class="filters">

        {{-- Input Search --}}
        <div class="search-box">
            <span class="material-symbols-outlined">search</span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari jurnal, nama guru, kelas, mapel..."
            >
        </div>

        {{-- Filter Status --}}
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status Validasi</option>
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

        {{-- Filter Kelas --}}
        <select name="kelas_id" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kelas</option>

            @foreach($kelases as $kelas)
                <option
                    value="{{ $kelas->id }}"
                    {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}
                >
                    {{ $kelas->nama_kelas }}
                </option>
            @endforeach
        </select>

        {{-- Filter Tanggal --}}
        <input
            type="date"
            name="tanggal"
            value="{{ request('tanggal') }}"
            class="filter-select"
            onchange="this.form.submit()"
        >

        {{-- Reset Filter --}}
        @if(request()->anyFilled(['search', 'status', 'kelas_id', 'tanggal']))
            <a
                href="{{ route('admin.dashboard') }}"
                class="btn-reset"
                style="padding: 8px 12px; font-size: 14px; text-decoration: none;"
            >
                Reset Filter
            </a>
        @endif

    </form>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL &<br>JAM</th>
                    <th>GURU</th>
                    <th>MATA<br>PELAJARAN</th>
                    <th>KELAS</th>
                    <th>KEHADIRAN</th>
                    <th>STATUS<br>VALIDASI</th>
                    <th>AKSI</th>
                </tr>
            </thead>

            <tbody>

                @forelse($jurnals as $index => $item)

                    <tr>
                        <td class="number">
                            {{ $jurnals->firstItem() + $index }}
                        </td>

                        <td>
                            <span class="date">
                                {{ $item->created_at->format('d M Y') }}
                            </span>

                            <span class="time">
                                {{ $item->jam_ke }}
                            </span>
                        </td>

                        <td class="teacher">
                            {{ $item->guru->nama }}
                        </td>

                        <td class="subject">
                            {{ $item->jadwal->mapel->nama_mapel ?? '-' }}
                        </td>

                        <td>
                            <span class="class-badge">
                                {{ $item->kelas->nama_kelas }}
                            </span>
                        </td>

                        <td class="attendance">
                            <strong>
                                {{ $item->jumlah_hadir }}/{{ $item->total_siswa }}
                            </strong>
                        </td>

                        <td>
                            <span class="status {{ strtolower($item->status) }}">
                                {{ $item->status }}
                            </span>
                        </td>

                        <td>
                            <button class="action">
                                <span class="material-symbols-outlined">
                                    visibility
                                </span>
                            </button>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" style="text-align: center;">
                            Belum ada data jurnal.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="bottom">
        {{ $jurnals->links() }}
    </div>

</section>

@endsection