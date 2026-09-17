@extends('layouts.admin')

@section('title', 'Manajemen Jadwal')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<style>
    .ts-control {
        border-radius: 0.75rem !important;
        padding: 0.625rem 0.875rem !important;
        border-color: #e2e8f0 !important;
        font-size: 0.875rem !important;
    }

    .ts-wrapper.focus .ts-control {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2) !important;
    }

    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')

<div
    class="min-h-screen bg-slate-50/50 px-4 pb-4 pt-0 sm:px-6 sm:pb-6 sm:pt-0"
    x-data="jadwalManager()"
>

    <div class="w-full space-y-6">

        <!-- HEADER BANNER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 transition-all duration-300">

            @if(session('success'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm transition"
                >
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

        </div>


        <!-- MAIN CONTAINER -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6">

            <!-- TOOLBAR KELAS -->
            <div class="space-y-4">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                    <p class="text-xs sm:text-sm text-slate-500 font-medium">
                        Tentukan kelas yang ingin Anda atur atau tinjau jadwal pelajarannya
                    </p>

                    <div class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 text-xs font-bold px-3.5 py-2 rounded-xl">
                        <i class="fa-regular fa-calendar text-slate-400"></i>

                        <span>
                            Tahun Ajaran:
                            <strong class="text-slate-800">
                                2026/2027 Semester Ganjil
                            </strong>
                        </span>
                    </div>

                </div>


                <!-- PILIHAN KELAS -->
                <div class="space-y-4">

                    <div class="flex flex-wrap items-center gap-3 pt-1">

                        @foreach($kelases->take(6) as $kls)

                            <a
                                href="{{ route('admin.jadwal.index', ['id_kelas' => $kls->id_kelas]) }}"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-2 shadow-sm hover:scale-105
                                {{ $selectedKelasId == $kls->id_kelas
                                    ? 'bg-blue-600 text-white shadow-blue-500/20'
                                    : 'bg-slate-100 hover:bg-slate-200 text-slate-600'
                                }}"
                            >

                                @if($selectedKelasId == $kls->id_kelas)
                                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                @endif

                                {{ $kls->nama_kelas }}

                            </a>

                        @endforeach


                        <!-- DROPDOWN KELAS LAINNYA -->
                        <div class="w-60">

                            <select
                                id="select-kelas-dropdown"
                                class="text-xs font-bold"
                                placeholder="Kelas Lainnya..."
                            >

                                <option value="">
                                    Kelas Lainnya...
                                </option>

                                @foreach($kelases as $kls)

                                    <option
                                        value="{{ route('admin.jadwal.index', ['id_kelas' => $kls->id_kelas]) }}"
                                    >
                                        {{ $kls->nama_kelas }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    @if($selectedKelas)

                        <div class="p-4 sm:p-5 bg-blue-50/60 border border-blue-100 rounded-2xl flex items-center gap-4 transition-all duration-300">

                            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                                {{ substr($selectedKelas->nama_kelas, 0, 3) }}
                            </div>

                            <div>

                                <h2 class="text-base font-extrabold text-slate-800">
                                    Kelas {{ $selectedKelas->nama_kelas }}
                                </h2>

                                <p class="text-xs text-slate-500 font-medium">
                                    Monitoring dan atur alokasi mata pelajaran mingguan.
                                </p>

                            </div>

                        </div>

                    @endif

                </div>


                <!-- TABEL JADWAL MINGGUAN -->
                <div class="border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">

                    <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">

                        <div>

                            <h3 class="text-sm font-bold text-slate-800">
                                Jadwal Pelajaran Mingguan
                            </h3>

                            <p class="text-xs text-slate-400 mt-0.5">
                                Klik slot kosong atau sesi pelajaran untuk mengelola
                            </p>

                        </div>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-left border-collapse text-xs">

                            <thead>

                                <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200/80">

                                    <th class="p-4 w-32 text-center border-r border-slate-100">
                                        JAM KE / WAKTU
                                    </th>

                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)

                                        <th class="p-4 text-center min-w-[200px] border-r border-slate-100 last:border-r-0">
                                            {{ $hari }}
                                        </th>

                                    @endforeach

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-100">

                                @foreach($jamPels as $jamKe)

                                    <tr class="hover:bg-slate-50/30 transition-colors">

                                        <!-- KOLOM JAM KE -->
                                        <td class="p-4 text-center font-bold text-slate-600 bg-slate-50/40 border-r border-slate-100">

                                            <span class="block text-sm text-slate-800 font-extrabold">
                                                Jam {{ $jamKe }}
                                            </span>

                                        </td>


                                        <!-- HARI SENIN - JUMAT -->
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)

                                            @php

                                                // Kelompok hari
                                                $klpHari = ($hari === 'Jumat')
                                                    ? 'Jumat'
                                                    : 'Senin-Kamis';

                                                // Detail master jam
                                                $jamObj = $jamPelsGrouped[$jamKe][$klpHari] ?? null;

                                                // Batas jam setiap hari
                                                $maxJam = $maxJamPerHari[$hari] ?? 11;

                                                $isBatasJam = $jamKe > $maxJam;

                                                // Cari jadwal
                                                $matchJadwal = $jadwals->first(function($item) use ($hari, $jamKe) {

                                                    return $item->hari === $hari
                                                        && optional($item->jamMulai)->jam_ke <= $jamKe
                                                        && optional($item->jamSelesai)->jam_ke >= $jamKe;

                                                });

                                            @endphp


                                            <td class="p-2.5 border-r border-slate-100 last:border-r-0 vertical-top">


                                                <!-- 1. MELEBIHI BATAS JAM -->
                                                @if($isBatasJam)

                                                    <div class="p-4 bg-slate-100/60 border border-slate-200/50 rounded-2xl text-center text-slate-300 min-h-[88px] flex items-center justify-center cursor-not-allowed select-none">

                                                        <span class="text-[11px] font-semibold text-slate-400/80">
                                                            Selesai
                                                        </span>

                                                    </div>


                                                <!-- 2. JADWAL TERISI -->
                                                @elseif($matchJadwal)

                                                    <div
                                                        class="p-4 bg-blue-50/80 border border-blue-200 rounded-2xl relative group/card cursor-pointer transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/10 hover:scale-[1.01] hover:bg-blue-100/80 min-h-[88px] flex flex-col justify-between"

                                                        @click="openEditModal({
                                                            id_jadwal: '{{ $matchJadwal->id_jadwal }}',
                                                            id_guru: '{{ $matchJadwal->id_guru }}',
                                                            id_mapel: '{{ $matchJadwal->id_mapel }}',
                                                            id_jam_mulai: '{{ $matchJadwal->id_jam_mulai }}',
                                                            id_jam_selesai: '{{ $matchJadwal->id_jam_selesai }}',
                                                            hari: '{{ $matchJadwal->hari }}',
                                                            mapel_nama: '{{ $matchJadwal->mapel?->nama_mapel }}',
                                                            guru_nama: '{{ $matchJadwal->guru?->nama_guru }}',
                                                            jam_mulai_ke: '{{ $matchJadwal->jamMulai?->jam_ke }}',
                                                            jam_selesai_ke: '{{ $matchJadwal->jamSelesai?->jam_ke }}',
                                                            waktu: '{{ $matchJadwal->jamMulai?->jam_mulai }} - {{ $matchJadwal->jamSelesai?->jam_selesai }}'
                                                        })"
                                                    >

                                                        <div class="flex items-start justify-between gap-2">

                                                            <div>

                                                                <span class="font-extrabold text-blue-950 text-xs sm:text-sm leading-snug block">
                                                                    {{ $matchJadwal->mapel?->nama_mapel }}
                                                                </span>

                                                                @if($jamObj)

                                                                    <span class="text-[10px] text-blue-500 font-semibold">

                                                                        (
                                                                        {{ \Carbon\Carbon::parse($jamObj->jam_mulai)->format('H:i') }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($jamObj->jam_selesai)->format('H:i') }}
                                                                        )

                                                                    </span>

                                                                @endif

                                                            </div>


                                                            <div class="flex items-center gap-1 opacity-0 group-hover/card:opacity-100 transition-opacity shrink-0">

                                                                <button
                                                                    type="button"
                                                                    class="w-6 h-6 bg-blue-600 text-white rounded-lg flex items-center justify-center text-[10px] hover:bg-blue-700 shadow-sm"
                                                                >
                                                                    <i class="fa-solid fa-pen"></i>
                                                                </button>


                                                                <button
                                                                    type="button"

                                                                    @click.stop="openDeleteModal({
                                                                        id_jadwal: '{{ $matchJadwal->id_jadwal }}',
                                                                        mapel_nama: '{{ $matchJadwal->mapel?->nama_mapel }}',
                                                                        hari: '{{ $matchJadwal->hari }}',
                                                                        jam_range: '{{ $matchJadwal->jamMulai?->jam_ke }} - {{ $matchJadwal->jamSelesai?->jam_ke }}'
                                                                    })"

                                                                    class="w-6 h-6 bg-rose-600 text-white rounded-lg flex items-center justify-center text-[10px] hover:bg-rose-700 shadow-sm"
                                                                >
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>

                                                            </div>

                                                        </div>


                                                        <div class="pt-2 flex items-center gap-1.5 border-t border-blue-200/60 mt-2">

                                                            <i class="fa-regular fa-user text-[10px] text-blue-600"></i>

                                                            <p class="text-[11px] font-bold text-blue-800/90 truncate">
                                                                {{ $matchJadwal->guru?->nama_guru }}
                                                            </p>

                                                        </div>

                                                    </div>


                                                <!-- 3. ISTIRAHAT -->
                                                @elseif($jamObj && $jamObj->jenis === 'istirahat')

                                                    <div class="p-3 bg-amber-50/80 border border-amber-200 rounded-2xl text-center text-amber-900 min-h-[88px] flex flex-col items-center justify-center gap-1">

                                                        <div class="flex items-center gap-1.5 text-amber-700 font-bold text-xs">

                                                            <i class="fa-solid fa-mug-hot"></i>

                                                            <span>
                                                                ISTIRAHAT
                                                            </span>

                                                        </div>


                                                        <span class="text-[10px] text-amber-600 font-medium">

                                                            {{ \Carbon\Carbon::parse($jamObj->jam_mulai)->format('H:i') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($jamObj->jam_selesai)->format('H:i') }}

                                                        </span>

                                                    </div>


                                                <!-- 4. SLOT KOSONG -->
                                                @else

                                                    <div
                                                        @click="openCreateModal(
                                                            '{{ $hari }}',
                                                            '{{ $jamObj?->id_jam }}',
                                                            '{{ $jamKe }}'
                                                        )"

                                                        class="p-4 border-2 border-dashed border-slate-200/80 rounded-2xl text-center text-slate-300 hover:border-blue-400 hover:bg-blue-50/30 hover:text-blue-500 cursor-pointer transition-all duration-200 flex flex-col items-center justify-center min-h-[88px] gap-1"
                                                    >

                                                        <i class="fa-solid fa-plus text-sm"></i>

                                                        @if($jamObj)

                                                            <span class="text-[10px] text-slate-400">

                                                                {{ \Carbon\Carbon::parse($jamObj->jam_mulai)->format('H:i') }}

                                                            </span>

                                                        @endif

                                                    </div>

                                                @endif

                                            </td>

                                        @endforeach

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <!-- MODAL EDIT / TAMBAH JADWAL -->
        <div
            x-show="showEditModal"

            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"

            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"

            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"

            x-cloak
        >

            <div
                @click.away="showEditModal = false"
                class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 border border-slate-100 relative"
            >

                <!-- HEADER MODAL -->
                <div class="flex items-start justify-between">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-lg shadow-md shadow-blue-500/20">

                            <i class="fa-solid fa-pen-to-square"></i>

                        </div>


                        <div>

                            <h3
                                class="text-base font-bold text-slate-800"
                                x-text="isEdit ? 'Edit Sesi Jadwal' : 'Tambah Sesi Jadwal'"
                            ></h3>

                            <p class="text-xs text-slate-400">
                                Memperbarui sesi pada kelas terpilih
                            </p>

                        </div>

                    </div>


                    <span
                        class="bg-blue-100 text-blue-700 font-bold text-xs px-3 py-1 rounded-xl"
                        x-text="activeData.hari + ' • Jam ' + activeData.jam_mulai_ke"
                    ></span>

                </div>


                <!-- FORM -->
                <form
                    :action="isEdit
                        ? '{{ url('admin/jadwal') }}/' + activeData.id_jadwal
                        : '{{ route('admin.jadwal.store') }}'"

                    method="POST"

                    class="space-y-4 text-xs"
                >

                    @csrf


                    <template x-if="isEdit">

                        <input
                            type="hidden"
                            name="_method"
                            value="PUT"
                        >

                    </template>


                    <input
                        type="hidden"
                        name="id_kelas"
                        value="{{ $selectedKelasId }}"
                    >

                    <input
                        type="hidden"
                        name="semester"
                        value="Ganjil"
                    >

                    <input
                        type="hidden"
                        name="tahun_ajaran"
                        value="2026/2027"
                    >

                    <input
                        type="hidden"
                        name="hari"
                        :value="activeData.hari"
                    >


                    <!-- MATA PELAJARAN -->
                    <div>

                        <label class="block font-bold text-slate-700 mb-1.5">

                            Mata Pelajaran

                            <span class="text-rose-500">*</span>

                        </label>


                        <select
                            id="select-mapel"
                            name="id_mapel"
                            class="w-full"
                        >

                            @foreach($mapels as $mapel)

                                <option value="{{ $mapel->id_mapel }}">
                                    {{ $mapel->nama_mapel }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- GURU PENGAMPU -->
                    <div>

                        <label class="block font-bold text-slate-700 mb-1.5">

                            Guru Pengampu

                            <span class="text-rose-500">*</span>

                        </label>


                        <select
                            id="select-guru"
                            name="id_guru"
                            class="w-full"
                        >

                            @foreach($gurus as $guru)

                                <option value="{{ $guru->id_guru }}">
                                    {{ $guru->nama_guru }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- RANGE JAM -->
<div
    class="grid grid-cols-2 gap-3"
    x-data="{
        get filteredJamList() {

            let klpHari = activeData.hari === 'Jumat'
                ? 'Jumat'
                : 'Senin-Kamis';

            return Object.values(jamPelsMap)
                .map(group => group[klpHari] ?? null)
                .filter(item =>
                    item !== null &&
                    item !== undefined &&
                    item.id_jam &&
                    item.jam_ke
                )
                .sort((a, b) => a.jam_ke - b.jam_ke);
        }
    }"

                    >

                        <!-- MULAI JAM -->
                        <div>

                            <label class="block font-bold text-slate-700 mb-1.5">
                                Mulai Jam Ke-
                            </label>


                            <select
                                name="id_jam_mulai"

                                x-model="activeData.id_jam_mulai"

                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >

                                <template
                                    x-for="item in filteredJamList"
                                    :key="'mulai-' + item.id_jam"
                                >

                                    <option
                                        :value="item.id_jam"

                                        x-text="
                                            'Jam Ke-' +
                                            item.jam_ke +
                                            ' (' +
                                            (
                                                item.jam_mulai
                                                    ? item.jam_mulai.substring(0,5)
                                                    : ''
                                            ) +
                                            ')'
                                        "
                                    ></option>

                                </template>

                            </select>

                        </div>


                        <!-- SELESAI JAM -->
                        <div>

                            <label class="block font-bold text-slate-700 mb-1.5">
                                Selesai Jam Ke-
                            </label>


                            <select
                                name="id_jam_selesai"

                                x-model="activeData.id_jam_selesai"

                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >

                                <template
                                    x-for="item in filteredJamList"
                                    :key="'selesai-' + item.id_jam"
                                >

                                    <option
                                        :value="item.id_jam"

                                        x-text="
                                            'Jam Ke-' +
                                            item.jam_ke +
                                            ' (' +
                                            (
                                                item.jam_selesai
                                                    ? item.jam_selesai.substring(0,5)
                                                    : ''
                                            ) +
                                            ')'
                                        "
                                    ></option>

                                </template>

                            </select>

                        </div>

                    </div>


                    <!-- BUTTON -->
                    <div class="pt-3 space-y-2">

                        <button
                            type="submit"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2"
                        >

                            <i class="fa-solid fa-check"></i>

                            Simpan Perubahan Jadwal

                        </button>


                        <div class="grid grid-cols-2 gap-2">

                            <button
                                type="button"
                                @click="showEditModal = false"

                                class="py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-center"
                            >
                                Batal
                            </button>


                            <template x-if="isEdit">

                                <button
                                    type="button"

                                    @click="
                                        showEditModal = false;
                                        showDeleteModal = true
                                    "

                                    class="py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-xl transition text-center flex items-center justify-center gap-1.5"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                    Hapus Sesi

                                </button>

                            </template>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        <!-- MODAL HAPUS -->
        <div
            x-show="showDeleteModal"

            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"

            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"

            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"

            x-cloak
        >

            <div
                @click.away="showDeleteModal = false"

                class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl text-center space-y-5 border border-slate-100 relative"
            >

                <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto text-rose-500 text-2xl animate-bounce">

                    <i class="fa-solid fa-trash-can"></i>

                </div>


                <div class="space-y-1">

                    <h3 class="text-xl font-extrabold text-slate-800">
                        Hapus Jadwal
                    </h3>

                    <p class="text-xs text-slate-500 font-medium">
                        Apakah Anda yakin ingin menghapus jadwal ini?
                    </p>

                </div>


                <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl flex items-center justify-center gap-2 text-xs font-bold text-slate-700">

                    <span x-text="activeData.mapel_nama"></span>

                    <span class="text-slate-300">
                        •
                    </span>

                    <span x-text="activeData.hari"></span>

                    <span class="text-slate-300">
                        •
                    </span>

                    <span>
                        Jam
                        (
                        <span x-text="activeData.jam_range"></span>
                        )
                    </span>

                </div>


                <p class="text-[11px] font-bold text-rose-500">
                    Tindakan ini tidak dapat dibatalkan.
                </p>


                <div class="grid grid-cols-2 gap-3 pt-2">

                    <button
                        type="button"

                        @click="showDeleteModal = false"

                        class="py-3 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition"
                    >
                        Batal
                    </button>


                    <form
                        :action="'{{ url('admin/jadwal') }}/' + activeData.id_jadwal"
                        method="POST"
                    >

                        @csrf

                        @method('DELETE')


                        <button
                            type="submit"
                            class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition"
                        >
                            Hapus
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>


<script>

let selectMapelInstance;
let selectGuruInstance;
let selectKelasInstance;


function jadwalManager() {

    return {

        showEditModal: false,

        showDeleteModal: false,

        isEdit: false,


        /*
         * Data jam dari Laravel.
         *
         * Bentuk data:
         *
         * {
         *     1: {
         *         "Senin-Kamis": {...},
         *         "Jumat": {...}
         *     },
         *     2: {
         *         "Senin-Kamis": {...},
         *         "Jumat": {...}
         *     }
         * }
         */

        jamPelsMap: @json($jamPelsGrouped),


        activeData: {

            id_jadwal: '',

            id_guru: '',

            id_mapel: '',

            id_jam_mulai: '',

            id_jam_selesai: '',

            hari: '',

            mapel_nama: '',

            guru_nama: '',

            jam_mulai_ke: '',

            jam_selesai_ke: '',

            jam_range: '',

            waktu: ''

        },


        /*
         * EDIT JADWAL
         */
        openEditModal(data) {

            this.isEdit = true;


            this.activeData = {
                ...this.activeData,
                ...data
            };


            // Set mapel
            if (selectMapelInstance) {

                selectMapelInstance.setValue(
                    data.id_mapel
                );

            }


            // Set guru
            if (selectGuruInstance) {

                selectGuruInstance.setValue(
                    data.id_guru
                );

            }


            this.showEditModal = true;

        },


        /*
         * TAMBAH JADWAL
         *
         * Hari berasal langsung dari slot
         * yang diklik.
         */
        openCreateModal(hari, idJam, jamKe) {

            this.isEdit = false;


            this.activeData = {

                id_jadwal: '',

                id_guru: '',

                id_mapel: '',

                id_jam_mulai: idJam,

                id_jam_selesai: idJam,
                hari: hari,

                jam_mulai_ke: jamKe,

                jam_selesai_ke: jamKe,

                mapel_nama: '',

                guru_nama: '',

                jam_range: '',

                waktu: ''

            };

            if (selectMapelInstance) {

                selectMapelInstance.clear();

            }

            if (selectGuruInstance) {

                selectGuruInstance.clear();

            }
            this.showEditModal = true;

        },

        openDeleteModal(data) {

            this.activeData = {
                ...this.activeData,
                ...data
            };


            this.showDeleteModal = true;

        }

    }

}


document.addEventListener(
    "DOMContentLoaded",
    function()
    {

        /*
         * DROPDOWN KELAS
         */
        selectKelasInstance = new TomSelect(
            "#select-kelas-dropdown",
            {

                create: false,

                onChange: function(url) {

                    if (url) {

                        window.location.href = url;

                    }

                }

            }
        );


        /*
         * DROPDOWN MAPEL
         */
        selectMapelInstance = new TomSelect(
            "#select-mapel",
            {
                create: false
            }
        );


        /*
         * DROPDOWN GURU
         */
        selectGuruInstance = new TomSelect(
            "#select-guru",
            {
                create: false
            }
        );

    }
);

</script>

@endpush