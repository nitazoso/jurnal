@extends('layouts.piket')

@section('title', 'Jurnal Kelas - Jurnify')
@section('page-title', 'Jurnal Kelas')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
        }

        .jurnal-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
        }

        .stat-card {
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background: #f8fafc;
        }

        .table-row td:first-child {
            border-left: 3px solid transparent;
            transition: border-color 0.2s ease;
        }

        .table-row:hover td:first-child {
            border-left-color: #1B234A;
        }

        .search-input {
            transition: all 0.2s ease;
        }

        .search-input:focus {
            border-color: #1B234A;
            box-shadow: 0 0 0 3px rgba(27, 35, 74, 0.08);
            outline: none;
        }

        .fade-in {
            animation: fadeInUp 0.35s ease both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
    </style>
@endpush


@section('content')

<div class="p-4 sm:p-6 lg:p-8 space-y-6">

    {{-- HEADER --}}
    <div class="fade-in">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <div class="flex items-center gap-2 mb-1">

                    <span class="material-symbols-outlined text-[#1B234A]">
                        menu_book
                    </span>

                    <h1 class="text-2xl font-bold text-slate-800">
                        Jurnal Kelas
                    </h1>

                </div>

                <p class="text-sm text-slate-500">
                    Lihat laporan jurnal mengajar berdasarkan kelas.
                </p>

            </div>

        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- TOTAL JURNAL --}}
        <div class="jurnal-card stat-card p-5 fade-in">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Total Jurnal
                    </p>

                    <p class="text-2xl font-bold text-slate-800 mt-1">
                        {{ $totalJurnal ?? 0 }}
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Jurnal telah disetujui
                    </p>

                </div>

                <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">

                    <span class="material-symbols-outlined text-blue-600">
                        description
                    </span>

                </div>

            </div>

        </div>


        {{-- TOTAL KELAS --}}
        <div class="jurnal-card stat-card p-5 fade-in">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Total Kelas
                    </p>

                    <p class="text-2xl font-bold text-slate-800 mt-1">
                        {{ $totalKelas ?? 0 }}
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Kelas terdaftar
                    </p>

                </div>

                <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center">

                    <span class="material-symbols-outlined text-purple-600">
                        groups
                    </span>

                </div>

            </div>

        </div>


        {{-- JURNAL HARI INI --}}
        <div class="jurnal-card stat-card p-5 fade-in">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Jurnal Hari Ini
                    </p>

                    <p class="text-2xl font-bold text-slate-800 mt-1">
                        {{ $jurnalHariIni ?? 0 }}
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Jurnal yang telah disetujui
                    </p>

                </div>

                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">

                    <span class="material-symbols-outlined text-green-600">
                        today
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- FILTER --}}
    <div class="jurnal-card p-5 fade-in">

        <div class="flex items-center gap-2 mb-4">

            <span class="material-symbols-outlined text-[#1B234A]">
                filter_alt
            </span>

            <h2 class="font-semibold text-slate-800">
                Filter Jurnal
            </h2>

        </div>


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
        placeholder="Cari materi, guru, atau kelas..."
        class="search-input w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700"
    >

</div>

            </div>


            {{-- BULAN --}}
            <div>

                <label class="block text-sm font-medium text-slate-600 mb-1">
                    Bulan
                </label>

                <select
                    name="bulan"
                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:border-[#1B234A]"
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

                <label class="block text-sm font-medium text-slate-600 mb-1">
                    Tahun
                </label>

                <select
                    name="tahun"
                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:border-[#1B234A]"
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
            <div class="md:col-span-4 flex justify-end gap-2">

                <a
                    href="{{ route('piket.jurnal.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 transition"
                >

                    <span class="material-symbols-outlined text-[20px]">
                        restart_alt
                    </span>

                    Reset

                </a>


                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#1B234A] text-white text-sm font-medium hover:bg-[#151b3a] transition"
                >

                    <span class="material-symbols-outlined text-[20px]">
                        search
                    </span>

                    Terapkan

                </button>

            </div>

        </form>

    </div>


    {{-- DIREKTORI KELAS --}}
    <div class="jurnal-card overflow-hidden fade-in">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b border-slate-200">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Direktori Kelas
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Pilih kelas untuk melihat laporan jurnal mengajar.
                    </p>

                </div>


                <div class="flex items-center gap-2">

                    <span class="w-2 h-2 rounded-full bg-green-500"></span>

                    <span class="text-sm text-slate-500">
                        {{ $kelases->count() }} kelas
                    </span>

                </div>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Kelas
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Wali Kelas
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Jumlah Siswa
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Jurnal
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($kelases as $index => $item)

                        @php
                            $jumlahJurnal = $jumlahJurnalPerKelas[$item->id_kelas] ?? 0;
                        @endphp

                        <tr class="table-row">

                            {{-- NO --}}
                            <td class="px-6 py-5">

                                <span class="text-sm font-semibold text-slate-400">
                                    {{ $index + 1 }}
                                </span>

                            </td>


                            {{-- KELAS --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl bg-[#1B234A] flex items-center justify-center">

                                        <span class="material-symbols-outlined text-white">
                                            school
                                        </span>

                                    </div>


                                    <div>

                                        <p class="font-semibold text-slate-800">
                                            {{ $item->nama_kelas }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-0.5">
                                            KLS-{{ str_pad($item->id_kelas, 3, '0', STR_PAD_LEFT) }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- WALI KELAS --}}
                            <td class="px-6 py-5">

                                @if($item->waliKelas)

                                    <div class="flex items-center gap-2">

                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">

                                            <span class="material-symbols-outlined text-slate-500 text-[18px]">
                                                person
                                            </span>

                                        </div>

                                        <span class="text-sm text-slate-700">
                                            {{ $item->waliKelas->nama_guru }}
                                        </span>

                                    </div>

                                @else

                                    <span class="text-sm text-slate-400">
                                        Belum ditentukan
                                    </span>

                                @endif

                            </td>


                            {{-- JUMLAH SISWA --}}
                            <td class="px-6 py-5 text-center">

                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-sm font-medium">

                                    <span class="material-symbols-outlined text-[18px]">
                                        groups
                                    </span>

                                    {{ $item->jumlah_siswa ?? 0 }}

                                </span>

                            </td>


                            {{-- JUMLAH JURNAL --}}
                            <td class="px-6 py-5 text-center">

                                @if($jumlahJurnal > 0)

                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 text-green-700 text-sm font-semibold">

                                        <span class="material-symbols-outlined text-[18px]">
                                            menu_book
                                        </span>

                                        {{ $jumlahJurnal }}

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-400 text-sm">

                                        <span class="material-symbols-outlined text-[18px]">
                                            menu_book
                                        </span>

                                        0

                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td class="px-6 py-5 text-center">

                                @if($jumlahJurnal > 0)

                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#1B234A] text-white text-sm font-medium hover:bg-[#151b3a] transition"
                                    >

                                        <span class="material-symbols-outlined text-[18px]">
                                            visibility
                                        </span>

                                        Lihat Jurnal

                                    </button>

                                @else

                                    <button
                                        type="button"
                                        disabled
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 text-slate-400 text-sm font-medium cursor-not-allowed"
                                    >

                                        <span class="material-symbols-outlined text-[18px]">
                                            visibility_off
                                        </span>

                                        Belum Ada

                                    </button>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-6 py-16 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">

                                        <span class="material-symbols-outlined text-3xl text-slate-400">
                                            school
                                        </span>

                                    </div>

                                    <h3 class="text-base font-semibold text-slate-700">
                                        Belum Ada Data Kelas
                                    </h3>

                                    <p class="text-sm text-slate-400 mt-1">
                                        Belum ada kelas yang terdaftar di sistem.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection