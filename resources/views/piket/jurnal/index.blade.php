@extends('layouts.piket')

@section('title', 'Riwayat Jurnal - Jurnify')

@section('page-title', 'Riwayat Jurnal')

@push('styles')
<style>
    * {
        box-sizing: border-box;
    }

    .jurnal-page {
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
       CARD
    ========================= */

    .jurnal-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    }

    /* =========================
       STAT CARD
    ========================= */

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        transition: all .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.07);
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* =========================
       TAB
    ========================= */

    .tab-wrapper {
        display: inline-flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 14px;
        gap: 3px;
    }

    .tab-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        color: #64748b;
        transition: all .2s ease;
        white-space: nowrap;
    }

    .tab-button:hover {
        color: #30366f;
    }

    .tab-button.active {
        background: #ffffff;
        color: #30366f;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .08);
    }

    .tab-button .material-symbols-outlined {
        font-size: 19px;
    }

    /* =========================
       CLASS SELECTOR
    ========================= */

    .class-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .class-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all .2s ease;
    }

    .class-button:hover {
        background: #e2e8f0;
        transform: translateY(-1px);
    }

    .class-button.active {
        background: #30366f;
        color: #ffffff;
        box-shadow: 0 6px 15px rgba(48, 54, 111, .20);
    }

    .class-button.active .class-dot {
        background: #ffffff;
    }

    .class-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #94a3b8;
    }

    /* =========================
       GURU CARD
    ========================= */

    .guru-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .guru-card {
        display: block;
        padding: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        text-decoration: none;
        background: #ffffff;
        transition: all .2s ease;
    }

    .guru-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(15, 23, 42, .06);
    }

    .guru-card.active {
        border-color: #30366f;
        background: #f8f9ff;
    }

    .guru-avatar {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        background: #eef2ff;
        color: #30366f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* =========================
       SELECTED INFO
    ========================= */

    .selected-info {
        background: #f1f5ff;
        border: 1px solid #dbe4ff;
        border-radius: 20px;
        padding: 18px;
    }

    .selected-icon {
        width: 48px;
        height: 48px;
        border-radius: 15px;
        background: #30366f;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* =========================
       FILTER
    ========================= */

    .filter-input {
        width: 100%;
        height: 42px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        padding: 0 13px;
        font-size: 13px;
        color: #334155;
        outline: none;
        transition: all .2s ease;
    }

    .filter-input:focus {
        background: #ffffff;
        border-color: #30366f;
        box-shadow: 0 0 0 3px rgba(48, 54, 111, .08);
    }

    .search-wrapper {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        color: #94a3b8;
        font-size: 20px;
        line-height: 1;
        pointer-events: none;
    }

    .search-input {
        padding-left: 40px !important;
    }

    /* =========================
       BUTTON
    ========================= */

    .primary-button {
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 16px;
        border-radius: 12px;
        background: #30366f;
        color: #ffffff;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .primary-button:hover {
        background: #252a59;
        transform: translateY(-1px);
    }

    .secondary-button {
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 16px;
        border-radius: 12px;
        background: #ffffff;
        color: #64748b;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        transition: all .2s ease;
    }

    .secondary-button:hover {
        background: #f8fafc;
        color: #334155;
    }

    /* =========================
       TABLE
    ========================= */

    .journal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .journal-table thead {
        background: #f8fafc;
    }

    .journal-table th {
        padding: 14px 18px;
        text-align: left;
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .journal-table td {
        padding: 16px 18px;
        color: #475569;
        font-size: 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .journal-table tbody tr {
        transition: background .15s ease;
    }

    .journal-table tbody tr:hover {
        background: #f8fafc;
    }

    .journal-table tbody tr:last-child td {
        border-bottom: none;
    }

    .date-text {
        font-weight: 800;
        color: #334155;
    }

    .teacher-name {
        font-weight: 800;
        color: #334155;
    }

    .subject-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
    }

    .attendance-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        padding: 5px 9px;
        border-radius: 8px;
        background: #ecfdf5;
        color: #047857;
        font-size: 11px;
        font-weight: 800;
    }

    .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        border-radius: 17px;
        background: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 900px) {
        .guru-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .guru-grid {
            grid-template-columns: 1fr;
        }

        .tab-wrapper {
            width: 100%;
        }

        .tab-button {
            flex: 1;
        }

        .class-button {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endpush


@section('content')

<div class="jurnal-page space-y-6">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <div class="flex items-center gap-2 mb-1">

                <span class="material-symbols-outlined text-[#30366f]">
                    menu_book
                </span>

                <h1 class="text-2xl font-extrabold text-slate-800">
                    Riwayat Jurnal
                </h1>

            </div>

            <p class="text-sm text-slate-500">
                Rekap dan monitoring jurnal mengajar berdasarkan kelas atau guru.
            </p>
        </div>


        {{-- TAB --}}

        <div class="tab-wrapper">

            <a
                href="{{ route('piket.jurnal.index', ['view' => 'kelas']) }}"
                class="tab-button {{ request('view', 'kelas') === 'kelas' ? 'active' : '' }}"
            >
                <span class="material-symbols-outlined">
                    school
                </span>

                Rekap Kelas
            </a>


            <a
                href="{{ route('piket.jurnal.index', ['view' => 'guru']) }}"
                class="tab-button {{ request('view') === 'guru' ? 'active' : '' }}"
            >
                <span class="material-symbols-outlined">
                    person
                </span>

                Rekap Guru
            </a>

        </div>

    </div>


    {{-- =========================================================
         STATISTIK
    ========================================================== --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- TOTAL JURNAL --}}

        <div class="stat-card">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-bold text-slate-500">
                        Total Jurnal
                    </p>

                    <p class="text-2xl font-extrabold text-slate-800 mt-1">
                        {{ $totalJurnal ?? 0 }}
                    </p>

                    <p class="text-[11px] text-slate-400 mt-1">
                        Jurnal telah disetujui
                    </p>

                </div>

                <div class="stat-icon bg-blue-50 text-blue-600">

                    <span class="material-symbols-outlined">
                        description
                    </span>

                </div>

            </div>

        </div>


        {{-- TOTAL KELAS --}}

        <div class="stat-card">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-bold text-slate-500">
                        Total Kelas
                    </p>

                    <p class="text-2xl font-extrabold text-slate-800 mt-1">
                        {{ $totalKelas ?? 0 }}
                    </p>

                    <p class="text-[11px] text-slate-400 mt-1">
                        Kelas terdaftar
                    </p>

                </div>

                <div class="stat-icon bg-purple-50 text-purple-600">

                    <span class="material-symbols-outlined">
                        groups
                    </span>

                </div>

            </div>

        </div>


        {{-- JURNAL HARI INI --}}

        <div class="stat-card">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-bold text-slate-500">
                        Jurnal Hari Ini
                    </p>

                    <p class="text-2xl font-extrabold text-slate-800 mt-1">
                        {{ $jurnalHariIni ?? 0 }}
                    </p>

                    <p class="text-[11px] text-slate-400 mt-1">
                        Jurnal yang telah disetujui
                    </p>

                </div>

                <div class="stat-icon bg-green-50 text-green-600">

                    <span class="material-symbols-outlined">
                        today
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         REKAP KELAS
    ========================================================== --}}

    @if(request('view', 'kelas') === 'kelas')

        {{-- PILIH KELAS --}}

        <div class="jurnal-card p-6">

            <div class="mb-5">

                <h2 class="text-base font-extrabold text-slate-800">
                    Pilih Kelas
                </h2>

                <p class="text-xs text-slate-500 mt-1">
                    Pilih kelas untuk melihat seluruh jurnal mengajar.
                </p>

        <form action="{{ route('piket.jurnal.index') }}"
              method="GET"
              class="grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- SEARCH --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium text-slate-600 mb-1">
                    Cari
                </label>

        <div class="relative">

    <span class="material-symbols-outlined search-icon">
        search
    </span>

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari nama kelas..."
        class="search-input w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700"
    >

</div>

            </div>


            <div class="class-selector">

                @forelse($kelases as $kls)

                    <a
                        href="{{ route('piket.jurnal.index', [
                            'view' => 'kelas',
                            'id_kelas' => $kls->id_kelas
                        ]) }}"
                        class="class-button {{ request('id_kelas') == $kls->id_kelas ? 'active' : '' }}"
                    >

                        @if(request('id_kelas') == $kls->id_kelas)
                            <span class="class-dot"></span>
                        @endif

                        {{ $kls->nama_kelas }}

                    </a>

                @empty

                    <p class="text-sm text-slate-400">
                        Belum ada kelas yang terdaftar.
                    </p>

                @endforelse

            </div>

        </div>


        {{-- DETAIL KELAS --}}

        @if($selectedKelas)

            <div class="selected-info">

                <div class="flex items-center gap-4">

                    <div class="selected-icon">

                        <span class="material-symbols-outlined">
                            school
                        </span>

                    </div>


                    <div class="flex-1">

                        <div class="flex flex-wrap items-center gap-2">

                            <h2 class="text-lg font-extrabold text-slate-800">
                                {{ $selectedKelas->nama_kelas }}
                            </h2>

                            <span class="px-2.5 py-1 rounded-lg bg-white text-[#30366f] text-[10px] font-extrabold border border-blue-100">
                                KELAS
                            </span>

                        </div>

                        <p class="text-xs text-slate-500 mt-1">

                            @if($selectedKelas->waliKelas)

                                Wali Kelas:
                                {{ $selectedKelas->waliKelas->nama_guru }}

                            @else

                                Wali kelas belum ditentukan

                            @endif

                            · {{ $jurnals->count() }} jurnal

                        </p>

                    </div>

                </div>

            </div>


            {{-- FILTER KELAS --}}

            <div class="jurnal-card p-5">

                <form
                    method="GET"
                    action="{{ route('piket.jurnal.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-3"
                >

                    <input
                        type="hidden"
                        name="view"
                        value="kelas"
                    >

                    <input
                        type="hidden"
                        name="id_kelas"
                        value="{{ $selectedKelas->id_kelas }}"
                    >


                    {{-- SEARCH --}}

                    <div class="md:col-span-2">

                        <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5">
                            Cari Jurnal
                        </label>

                        <div class="search-wrapper">

                            <span class="material-symbols-outlined search-icon">
                                search
                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari guru, materi, atau mapel..."
                                class="filter-input search-input"
                            >

                        </div>

                    </div>


                    {{-- BULAN --}}

                    <div>

                        <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5">
                            Bulan
                        </label>

                        <select
                            name="bulan"
                            class="filter-input"
                        >

                            <option value="">
                                Semua Bulan
                            </option>

                            @foreach([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ] as $nomor => $nama)

                                <option
                                    value="{{ $nomor }}"
                                    {{ request('bulan') == $nomor ? 'selected' : '' }}
                                >
                                    {{ $nama }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TAHUN --}}

                    <div>

                        <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5">
                            Tahun
                        </label>

                        <select
                            name="tahun"
                            class="filter-input"
                        >

                            <option value="">
                                Semua Tahun
                            </option>

                            @foreach($tahunList ?? [] as $tahun)

                                <option
                                    value="{{ $tahun }}"
                                    {{ request('tahun') == $tahun ? 'selected' : '' }}
                                >
                                    {{ $tahun }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BUTTON --}}

                    <div class="md:col-span-4 flex justify-end gap-2 pt-1">

                        <a
                            href="{{ route('piket.jurnal.index', [
                                'view' => 'kelas',
                                'id_kelas' => $selectedKelas->id_kelas
                            ]) }}"
                            class="secondary-button"
                        >

                            <span class="material-symbols-outlined text-[18px]">
                                restart_alt
                            </span>

                            Reset

                        </a>


                        <button
                            type="submit"
                            class="primary-button"
                        >

                            <span class="material-symbols-outlined text-[18px]">
                                search
                            </span>

                            Terapkan

                        </button>

                    </div>

                </form>

            </div>


            {{-- TABEL JURNAL KELAS --}}

            <div class="jurnal-card overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-200">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                        <div>

                            <h2 class="text-base font-extrabold text-slate-800">
                                Jurnal Mengajar
                            </h2>

                            <p class="text-xs text-slate-500 mt-1">
                                Daftar jurnal untuk kelas
                                {{ $selectedKelas->nama_kelas }}.
                            </p>

                        </div>


                        <div class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-extrabold">
                            {{ $jurnals->count() }} Jurnal
                        </div>

                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="journal-table">

                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Tanggal</th>

                                <th>Jam</th>

                                <th>Guru</th>

                                <th>Mapel</th>

                                <th>Materi</th>

                                <th class="text-center">
                                    Hadir
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($jurnals as $index => $jurnal)

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>

                                        <span class="date-text">
                                            {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y') }}
                                        </span>

                                    </td>


                                    <td>
                                        {{ $jurnal->jam_ke ?? '-' }}
                                    </td>


                                    <td>

                                        <span class="teacher-name">
                                            {{ $jurnal->guru?->nama_guru ?? '-' }}
                                        </span>

                                    </td>


                                    <td>

                                        <span class="subject-badge">
                                            {{ $jurnal->guru?->mapel ?? '-' }}
                                        </span>

                                    </td>


                                    <td>
                                        {{ $jurnal->materi ?? '-' }}
                                    </td>


                                    <td class="text-center">

                                        <span class="attendance-badge">
                                            {{ $jurnal->jml_hadir ?? 0 }}
                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7">

                                        <div class="empty-state">

                                            <div class="empty-icon">

                                                <span class="material-symbols-outlined text-3xl">
                                                    menu_book
                                                </span>

                                            </div>

                                            <h3 class="text-sm font-extrabold text-slate-700">
                                                Belum Ada Jurnal
                                            </h3>

                                            <p class="text-xs text-slate-400 mt-1">
                                                Belum ada jurnal yang tersedia untuk kelas ini.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @else

            {{-- BELUM PILIH KELAS --}}

            <div class="jurnal-card">

                <div class="empty-state">

                    <div class="empty-icon">

                        <span class="material-symbols-outlined text-3xl">
                            school
                        </span>

                    </div>

                    <h3 class="text-base font-extrabold text-slate-700">
                        Pilih Kelas Terlebih Dahulu
                    </h3>

                    <p class="text-xs text-slate-400 mt-1">
                        Pilih salah satu kelas di atas untuk melihat jurnal mengajar.
                    </p>

                </div>

            </div>

        @endif

    @endif


    {{-- =========================================================
         REKAP GURU
    ========================================================== --}}

    @if(request('view') === 'guru')

        {{-- PILIH GURU --}}

        <div class="jurnal-card p-6">

            <div class="mb-5">

                <h2 class="text-base font-extrabold text-slate-800">
                    Pilih Guru
                </h2>

                <p class="text-xs text-slate-500 mt-1">
                    Pilih guru untuk melihat seluruh jurnal mengajar yang dibuat.
                </p>

            </div>


            <div class="guru-grid">

                @forelse($gurus as $guru)

                    <a
                        href="{{ route('piket.jurnal.index', [
                            'view' => 'guru',
                            'id_guru' => $guru->id_guru
                        ]) }}"
                        class="guru-card {{ request('id_guru') == $guru->id_guru ? 'active' : '' }}"
                    >

                        <div class="flex items-center gap-3">

                            <div class="guru-avatar">

                                <span class="material-symbols-outlined">
                                    person
                                </span>

                            </div>


                            <div class="flex-1 min-w-0">

                                <p class="text-sm font-extrabold text-slate-800 truncate">
                                    {{ $guru->nama_guru }}
                                </p>

                                <p class="text-[11px] text-slate-500 mt-1 truncate">
                                    {{ $guru->mapel ?? 'Guru' }}
                                </p>

                            </div>


                            <span class="material-symbols-outlined text-slate-300">
                                chevron_right
                            </span>

                        </div>

                    </a>

                @empty

                    <div class="col-span-full">

                        <p class="text-sm text-slate-400">
                            Belum ada data guru.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


<<<<<<< HEAD
        {{-- DETAIL GURU --}}

        @if($selectedGuru)

            <div class="selected-info">

                <div class="flex items-center gap-4">

                    <div class="selected-icon">

                        <span class="material-symbols-outlined">
                            person
                        </span>

                    </div>


                    <div class="flex-1">

                        <div class="flex flex-wrap items-center gap-2">

                            <h2 class="text-lg font-extrabold text-slate-800">
                                {{ $selectedGuru->nama_guru }}
                            </h2>

                            <span class="px-2.5 py-1 rounded-lg bg-white text-[#30366f] text-[10px] font-extrabold border border-blue-100">
                                GURU
                            </span>

                        </div>


                        <p class="text-xs text-slate-500 mt-1">

                            {{ $selectedGuru->mapel ?? 'Mata pelajaran belum ditentukan' }}

                            · {{ $jurnals->count() }} jurnal

                        </p>

                    </div>

=======
    @if($kelasTerpilih || request()->filled('search') || request()->filled('bulan') || request()->filled('tahun'))
        <div class="jurnal-card overflow-hidden fade-in">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        {{ $kelasTerpilih ? 'Jurnal Kelas '.$kelasTerpilih->nama_kelas : 'Hasil Pencarian Jurnal' }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Menampilkan jurnal sesuai filter yang dipilih.
                    </p>
>>>>>>> crud-guru
                </div>

            </div>


            {{-- FILTER GURU --}}

            <div class="jurnal-card p-5">

                <form
                    method="GET"
                    action="{{ route('piket.jurnal.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-3"
                >

                    <input
                        type="hidden"
                        name="view"
                        value="guru"
                    >

                    <input
                        type="hidden"
                        name="id_guru"
                        value="{{ $selectedGuru->id_guru }}"
                    >


                    {{-- SEARCH --}}

                    <div class="md:col-span-2">

                        <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5">
                            Cari Jurnal
                        </label>

                        <div class="search-wrapper">

                            <span class="material-symbols-outlined search-icon">
                                search
                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari materi atau kelas..."
                                class="filter-input search-input"
                            >

                        </div>

                    </div>


                    {{-- BULAN --}}

                    <div>

                        <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5">
                            Bulan
                        </label>

                        <select
                            name="bulan"
                            class="filter-input"
                        >

                            <option value="">
                                Semua Bulan
                            </option>

                            @foreach([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ] as $nomor => $nama)

                                <option
                                    value="{{ $nomor }}"
                                    {{ request('bulan') == $nomor ? 'selected' : '' }}
                                >
                                    {{ $nama }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TAHUN --}}

                    <div>

                        <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5">
                            Tahun
                        </label>

                        <select
                            name="tahun"
                            class="filter-input"
                        >

                            <option value="">
                                Semua Tahun
                            </option>

                            @foreach($tahunList ?? [] as $tahun)

                                <option
                                    value="{{ $tahun }}"
                                    {{ request('tahun') == $tahun ? 'selected' : '' }}
                                >
                                    {{ $tahun }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BUTTON --}}

                    <div class="md:col-span-4 flex justify-end gap-2 pt-1">

                        <a
                            href="{{ route('piket.jurnal.index', [
                                'view' => 'guru',
                                'id_guru' => $selectedGuru->id_guru
                            ]) }}"
                            class="secondary-button"
                        >

                            <span class="material-symbols-outlined text-[18px]">
                                restart_alt
                            </span>

                            Reset

                        </a>


                        <button
                            type="submit"
                            class="primary-button"
                        >

                            <span class="material-symbols-outlined text-[18px]">
                                search
                            </span>

                            Terapkan

                        </button>

                    </div>

                </form>

            </div>


            {{-- TABEL JURNAL GURU --}}

            <div class="jurnal-card overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-200">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                        <div>

                            <h2 class="text-base font-extrabold text-slate-800">
                                Jurnal Mengajar
                            </h2>

                            <p class="text-xs text-slate-500 mt-1">
                                Daftar jurnal yang dibuat oleh
                                {{ $selectedGuru->nama_guru }}.
                            </p>

                        </div>


                        <div class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-extrabold">
                            {{ $jurnals->count() }} Jurnal
                        </div>

                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="journal-table">

                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Tanggal</th>

                                <th>Jam</th>

                                <th>Kelas</th>

                                <th>Mapel</th>

                                <th>Materi</th>

                                <th class="text-center">
                                    Hadir
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($jurnals as $index => $jurnal)

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>

                                        <span class="date-text">
                                            {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y') }}
                                        </span>

                                    </td>


                                    <td>
                                        {{ $jurnal->jam_ke ?? '-' }}
                                    </td>


                                    <td>

                                        <span class="subject-badge">
                                            {{ $jurnal->kelas?->nama_kelas ?? '-' }}
                                        </span>

                                    </td>


                                    <td>

                                        <span class="subject-badge">
                                            {{ $selectedGuru->mapel ?? '-' }}
                                        </span>

                                    </td>


                                    <td>
                                        {{ $jurnal->materi ?? '-' }}
                                    </td>


                                    <td class="text-center">

                                        <span class="attendance-badge">
                                            {{ $jurnal->jml_hadir ?? 0 }}
                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7">

                                        <div class="empty-state">

                                            <div class="empty-icon">

                                                <span class="material-symbols-outlined text-3xl">
                                                    menu_book
                                                </span>

                                            </div>

                                            <h3 class="text-sm font-extrabold text-slate-700">
                                                Belum Ada Jurnal
                                            </h3>

                                            <p class="text-xs text-slate-400 mt-1">
                                                Belum ada jurnal yang dibuat oleh guru ini.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @else

            {{-- BELUM PILIH GURU --}}

            <div class="jurnal-card">

                <div class="empty-state">

                    <div class="empty-icon">

                        <span class="material-symbols-outlined text-3xl">
                            person_search
                        </span>

                    </div>

                    <h3 class="text-base font-extrabold text-slate-700">
                        Pilih Guru Terlebih Dahulu
                    </h3>

                    <p class="text-xs text-slate-400 mt-1">
                        Pilih salah satu guru untuk melihat jurnal mengajarnya.
                    </p>

                </div>

            </div>

        @endif

    @endif

</div>

@endsection