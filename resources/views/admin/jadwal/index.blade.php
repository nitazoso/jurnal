@extends('layouts.admin')
@section('title', 'Manajemen Jadwal')
@section('page-title', 'Manajemen Jadwal')
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

    .jadwal-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(15, 23, 42, .5);
        -webkit-backdrop-filter: blur(4px);
        backdrop-filter: blur(4px);
    }

    .jadwal-modal-panel {
        position: relative;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        background: #fff !important;
        color: #1e293b;
    }
    .academic-period-dialog { position: fixed; top: 50%; left: 50%; inset: auto; transform: translate(-50%, -50%); width: min(440px, calc(100% - 32px)); max-height: calc(100vh - 32px); margin: 0; padding: 0; border: 1px solid #e2e8f0; border-radius: 18px; color: #1e293b; box-shadow: 0 24px 70px rgba(15,23,42,.25); }
    .academic-period-dialog[open] { position: fixed; inset: 0; margin: auto; transform: none; }
    .academic-period-dialog::backdrop { background: rgba(15,23,42,.5); backdrop-filter: blur(3px); }
    .academic-period-dialog-form { display: grid; gap: 16px; padding: 22px; }
    .academic-period-dialog-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; }
    .academic-period-dialog-heading p { margin: 0 0 5px; color: #6366f1; font-size: 9px; font-weight: 800; letter-spacing: .14em; }
    .academic-period-dialog-heading h2 { margin: 0; color: #202b61; font-size: 18px; font-weight: 800; }
    .academic-period-dialog-heading button { border: 0; background: transparent; color: #64748b; font-size: 26px; line-height: 1; cursor: pointer; }
    .academic-period-dialog-form label { display: grid; gap: 7px; color: #475569; font-size: 12px; font-weight: 700; }
    .academic-period-dialog-form input, .academic-period-dialog-form select { width: 100%; min-height: 42px; padding: 9px 11px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; color: #1e293b; font: inherit; font-size: 13px; }
    .academic-period-dialog-form input:focus, .academic-period-dialog-form select:focus { border-color: #6366f1; outline: 3px solid rgba(99,102,241,.14); }
    .academic-period-dialog-form small { color: #b42318; font-size: 11px; }
    .academic-period-dialog-actions { display: flex; justify-content: flex-end; gap: 8px; padding-top: 2px; }
    .academic-period-dialog-actions button { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; gap: 7px; padding: 0 15px; border: 0; border-radius: 8px; font: inherit; font-size: 12px; font-weight: 800; cursor: pointer; }
    .academic-period-dialog-actions .academic-period-cancel { background: #f1f5f9; color: #475569; }
    .academic-period-dialog-actions .academic-period-save { background: #30366f; color: #fff; }
    @media (max-width: 520px) { .academic-period-dialog-form { padding: 18px; } .academic-period-dialog-actions { flex-direction: column-reverse; } .academic-period-dialog-actions button { width: 100%; } }
</style>
@endpush

@section('content')

<div
    class="min-h-screen bg-slate-50/50 px-4 pb-4 pt-0 sm:px-6 sm:pb-6 sm:pt-0"
    x-data="jadwalManager()"
>

    <div class="w-full space-y-6">

        @if(session('jadwal_import_result'))
            @php
                $importResult = session('jadwal_import_result');
            @endphp
            <div
                x-data="{ open: true }"
                x-show="open"
                x-cloak
                @keydown.escape.window="open = false"
                class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-950/55 p-4 backdrop-blur-sm"
                @click.self="open = false"
            >
                <section class="w-full max-w-xl overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-900/10" role="dialog" aria-modal="true" aria-labelledby="jadwal-import-result-title">
                    <div class="flex items-start gap-4 px-6 pb-5 pt-6 sm:px-7">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $importResult['replacement_aborted'] ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                            <i class="fa-solid {{ $importResult['replacement_aborted'] ? 'fa-triangle-exclamation' : 'fa-circle-check' }} text-xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-indigo-600">Ringkasan impor</p>
                            <h2 id="jadwal-import-result-title" class="mt-1 text-xl font-extrabold text-slate-900">
                                {{ $importResult['replacement_aborted'] ? 'Jadwal belum diganti' : 'Impor jadwal berhasil' }}
                            </h2>
                            <p class="mt-1 text-sm leading-5 text-slate-500">
                                @if($importResult['replacement_aborted'])
                                    Jadwal sebelumnya tetap aman. Perbaiki data yang gagal lalu unggah ulang.
                                @else
                                    Jadwal lama sudah diganti dengan periode {{ $importResult['replaced_periods'] }}.
                                @endif
                            </p>
                        </div>
                        <button type="button" @click="open = false" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup popup">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-3 px-6 sm:px-7">
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                            <p class="text-xs font-bold text-emerald-700">Berhasil disimpan</p>
                            <p class="mt-1 text-3xl font-extrabold text-emerald-800">{{ $importResult['processed'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-rose-100 bg-rose-50 p-4">
                            <p class="text-xs font-bold text-rose-700">Gagal diproses</p>
                            <p class="mt-1 text-3xl font-extrabold text-rose-800">{{ count($importResult['failures']) }}</p>
                        </div>
                    </div>

                    @if(count($importResult['failures']))
                        <div class="px-6 pt-5 sm:px-7">
                            <h3 class="text-sm font-extrabold text-slate-800">Baris yang perlu diperbaiki</h3>
                            <div class="mt-2 max-h-64 divide-y divide-slate-100 overflow-y-auto rounded-2xl border border-slate-200">
                                @foreach($importResult['failures'] as $failure)
                                    <div class="p-3.5">
                                        <div class="flex items-start gap-3">
                                            <span class="shrink-0 rounded-lg bg-slate-100 px-2 py-1 text-[10px] font-extrabold text-slate-600">Baris {{ $failure['row'] }}</span>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-rose-700">{{ $failure['reason'] }}</p>
                                                @if(!empty($failure['source']))
                                                    <p class="mt-1 text-xs leading-5 text-slate-500">Terbaca: {{ $failure['source'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end border-t border-slate-100 bg-slate-50/70 px-6 py-4 sm:px-7">
                        <button type="button" @click="open = false" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 text-sm font-extrabold text-white shadow-sm transition hover:bg-indigo-700">
                            <i class="fa-solid fa-check text-xs"></i> Mengerti
                        </button>
                    </div>
                </section>
            </div>
        @endif

        <!-- HEADER BANNER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 transition-all duration-300">

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.jadwal.import') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700"><i class="fa-solid fa-file-import"></i> Impor dari file</a>
                <a href="{{ route('admin.jadwal.export', ['id_kelas' => $selectedKelasId]) }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-bold text-emerald-700 shadow-sm transition hover:border-emerald-300 hover:bg-emerald-100"><i class="fa-solid fa-file-excel"></i> Ekspor kelas ini</a>
                <a href="{{ route('admin.jadwal.export') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:border-emerald-200 hover:text-emerald-700"><i class="fa-solid fa-download"></i> Ekspor semua jadwal</a>
            </div>

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

                <div class="space-y-3">

                    <form method="GET" class="grid w-full grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="flex min-w-0 flex-col items-start gap-2">
                            <button type="button" data-academic-period-open class="inline-flex min-h-10 w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 text-xs font-bold text-slate-700 shadow-sm transition hover:border-indigo-200 hover:text-indigo-700 lg:justify-start">
                                <i class="fa-solid fa-calendar-days text-indigo-600" aria-hidden="true"></i>
                                Tahun Ajaran {{ $tahunAjaranDefault ?: 'Belum diatur' }}
                            </button>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium">
                                Tentukan kelas yang ingin Anda atur atau tinjau jadwal pelajarannya
                            </p>
                        </div>

                        @if($selectedKelasId)
                            <input type="hidden" name="id_kelas" value="{{ $selectedKelasId }}">
                        @endif

                        <label class="group grid w-full min-w-0 grid-cols-[36px_minmax(0,1fr)_56px_44px] items-center gap-3 cursor-pointer rounded-2xl border border-amber-100 bg-gradient-to-r from-amber-50 via-white to-orange-50 px-3 py-2.5 shadow-sm shadow-amber-100/60 transition-all duration-200 hover:border-amber-200 hover:shadow-md hover:shadow-amber-200/60">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-600 text-white shadow-md shadow-amber-500/30">
                                <i class="fa-solid fa-ban text-base" aria-hidden="true"></i>
                            </span>

                            <span class="flex flex-col text-left leading-tight">
                                <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-amber-500">
                                    Mode Event
                                </span>
                                <span class="text-[11px] font-extrabold text-slate-700">
                                    Pelajaran dinonaktifkan
                                </span>
                            </span>

                            <span class="justify-self-center rounded-md px-1.5 py-1 text-center text-[9px] font-extrabold tracking-wide {{ $allDisabled ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $allDisabled ? 'AKTIF' : 'NONAKTIF' }}
                            </span>

                            <span class="relative inline-block h-6 w-11 shrink-0 justify-self-end">
                                <input type="hidden" name="all_disabled" value="0">
                                <input
                                    type="checkbox"
                                    name="all_disabled"
                                    value="1"
                                    onchange="this.form.submit()"
                                    {{ $allDisabled ? 'checked' : '' }}
                                    class="peer sr-only"
                                >
                                <span class="absolute inset-0 rounded-full bg-rose-200 transition-all duration-300 peer-checked:bg-emerald-500"></span>
                                <span class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow-md transition-transform duration-300 peer-checked:translate-x-[10px]"></span>
                            </span>
                        </label>

                        <label class="group grid w-full min-w-0 grid-cols-[36px_minmax(0,1fr)_56px_44px] items-center gap-3 cursor-pointer rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50 via-white to-blue-50 px-3 py-2.5 shadow-sm shadow-indigo-100/60 transition-all duration-200 hover:border-indigo-200 hover:shadow-md hover:shadow-indigo-200/60">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-500/30">
                                <i class="fa-solid fa-calendar-days text-[11px]"></i>
                            </span>

                            <span class="flex flex-col text-left leading-tight">
                                <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-indigo-500">
                                    Jam Khusus
                                </span>
                                <span class="text-[11px] font-extrabold text-slate-700">
                                    Senin & Jumat +1 jam
                                </span>
                            </span>

                            <span class="justify-self-center rounded-md px-1.5 py-1 text-center text-[9px] font-extrabold tracking-wide {{ $shiftSeninJumat ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $shiftSeninJumat ? 'AKTIF' : 'NONAKTIF' }}
                            </span>

                            <span class="relative inline-block h-6 w-11 shrink-0 justify-self-end">
                                <input type="hidden" name="shift_senin_jumat" value="0">
                                <input
                                    type="checkbox"
                                    name="shift_senin_jumat"
                                    value="1"
                                    onchange="this.form.submit()"
                                    {{ $shiftSeninJumat ? 'checked' : '' }}
                                    class="peer sr-only"
                                >
                                <span class="absolute inset-0 rounded-full bg-rose-200 transition-all duration-300 peer-checked:bg-emerald-500"></span>
                                <span class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow-md transition-transform duration-300 peer-checked:translate-x-[10px]"></span>
                            </span>
                        </label>
                    </form>


                </div>


                <!-- PILIHAN KELAS -->
                <div class="space-y-4">

                    <div class="flex flex-wrap items-center gap-3 pt-1">

                        @foreach($kelases->take(6) as $kls)

                            <a
                                href="{{ route('admin.jadwal.index', [
                                    'id_kelas' => $kls->id_kelas,
                                    'shift_senin_jumat' => $shiftSeninJumat ? 1 : 0,
                                    'all_disabled' => $allDisabled ? 1 : 0,
                                ]) }}"
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
                                        value="{{ route('admin.jadwal.index', [
                                            'id_kelas' => $kls->id_kelas,
                                            'shift_senin_jumat' => $shiftSeninJumat ? 1 : 0,
                                            'all_disabled' => $allDisabled ? 1 : 0,
                                        ]) }}"
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
                                                $shiftThisDay = $shiftSeninJumat && in_array($hari, ['Senin', 'Jumat'], true);
                                                $isKegiatanRutin = ! $shiftThisDay
                                                    && (int) $jamKe === 1
                                                    && in_array($hari, ['Senin', 'Jumat'], true);

                                                $effectiveJamKe = $shiftThisDay ? $jamKe + 1 : $jamKe;

                                                // Batas jam setiap hari
                                                $maxJam = $maxJamPerHari[$hari] ?? 11;
                                                $isBatasJam = $effectiveJamKe > ($shiftThisDay ? $maxJam + 1 : $maxJam);

                                                if ($allDisabled) {
                                                    $matchJadwal = null;
                                                } else {
                                                    $matchJadwal = $jadwals->first(function($item) use ($hari, $effectiveJamKe) {
                                                        return $item->hari === $hari
                                                            && optional($item->jamMulai)->jam_ke <= $effectiveJamKe
                                                            && optional($item->jamSelesai)->jam_ke >= $effectiveJamKe;
                                                    });
                                                }

                                            @endphp


                                            <td class="p-2.5 border-r border-slate-100 last:border-r-0 vertical-top">


                                                <!-- KEGIATAN RUTIN DI SLOT KOSONG JAM PERTAMA -->
                                                @if($isKegiatanRutin && $hari === 'Senin')

                                                    <div class="p-4 bg-indigo-50/80 border border-indigo-200 rounded-2xl text-center text-indigo-900 min-h-[88px] flex flex-col items-center justify-center gap-1 select-none">
                                                        <i class="fa-solid fa-flag text-indigo-500" aria-hidden="true"></i>
                                                        <span class="text-xs font-extrabold sm:text-sm">Upacara / Apel</span>
                                                    </div>

                                                @elseif($isKegiatanRutin && $hari === 'Jumat')

                                                    <div class="p-4 bg-amber-50/80 border border-amber-200 rounded-2xl text-center text-amber-900 min-h-[88px] flex flex-col items-center justify-center gap-1 select-none">
                                                        <i class="fa-solid fa-people-group text-amber-500" aria-hidden="true"></i>
                                                        <span class="text-xs font-extrabold sm:text-sm">Pembiasaan Hari Jumat</span>
                                                    </div>

                                                <!-- MODE EVENT -->
                                                @elseif($allDisabled)

                                                    <div class="p-4 bg-amber-50/80 border border-amber-200 rounded-2xl text-center text-amber-800 min-h-[88px] flex flex-col items-center justify-center gap-1.5 select-none">
                                                        <i class="fa-solid fa-ban text-base" aria-hidden="true"></i>
                                                        <span class="text-[10px] font-extrabold uppercase tracking-[0.14em]">
                                                            Dinonaktifkan
                                                        </span>
                                                    </div>


                                                <!-- 1. MELEBIHI BATAS JAM -->
                                                @elseif($isBatasJam)

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
                                                            semester: '{{ $matchJadwal->semester }}',
                                                            tahun_ajaran: '{{ $matchJadwal->tahun_ajaran }}',
                                                            mapel_nama: '{{ e($matchJadwal->mapel?->nama_mapel) }}',
                                                            guru_nama: '{{ e($matchJadwal->guru?->nama_guru) }}',
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
                                                                        mapel_nama: '{{ e($matchJadwal->mapel?->nama_mapel) }}',
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


        <template x-teleport="body">
        <!-- MODAL EDIT / TAMBAH JADWAL -->
        <div
            x-show="showEditModal"

            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"

            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"

            class="jadwal-modal-backdrop"

            x-cloak
        >

            <div
                @click.away="showEditModal = false"
                class="jadwal-modal-panel bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 border border-slate-100"
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
                        name="hari"
                        :value="activeData.hari"
                    >

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <label class="block text-xs font-bold text-slate-700">
                            Semester
                            <input type="hidden" name="semester" :value="activeData.semester">
                            <select required x-model="activeData.semester" disabled aria-readonly="true" class="mt-1.5 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-600">
                                <option value="" disabled>Pilih semester</option>
                                @foreach(['Ganjil', 'Genap'] as $semester)
                                    <option value="{{ $semester }}">{{ $semester }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block text-xs font-bold text-slate-700">
                            Tahun Ajaran
                            <input type="text" name="tahun_ajaran" x-model="activeData.tahun_ajaran" maxlength="9" required readonly aria-readonly="true" class="mt-1.5 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-600">
                        </label>
                    </div>


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

                            <option value="">-- Pilih Mata Pelajaran --</option>
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

                            <option value="">-- Pilih Guru Pengampu --</option>
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


        </template>


        <template x-teleport="body">
        <!-- MODAL HAPUS -->
        <div
            x-show="showDeleteModal"

            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"

            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"

            class="jadwal-modal-backdrop"

            x-cloak
        >

            <div
                @click.away="showDeleteModal = false"

                class="jadwal-modal-panel bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl text-center space-y-5 border border-slate-100"
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
        </template>

    </div>

</div>

<dialog class="academic-period-dialog" data-academic-period-dialog aria-labelledby="academic-period-title">
                        <form action="{{ route('admin.jadwal.academic-period.update') }}" method="POST" class="academic-period-dialog-form">
                            @csrf
                            @method('PUT')
                            <div class="academic-period-dialog-heading">
                                <div>
                                    <p>TAHUN AJARAN AKTIF</p>
                                    <h2 id="academic-period-title">Pengaturan Tahun Ajaran</h2>
                                </div>
                                <button type="button" data-academic-period-close aria-label="Tutup">&times;</button>
                            </div>
                            <label>
                                <span>Semester</span>
                                <select name="semester" required>
                                    <option value="">Pilih semester</option>
                                    @foreach(['Ganjil', 'Genap'] as $semester)
                                        <option value="{{ $semester }}" @selected(old('semester', $semesterDefault) === $semester)>{{ $semester }}</option>
                                    @endforeach
                                </select>
                                @error('semester')<small>{{ $message }}</small>@enderror
                            </label>
                            <label>
                                <span>Tahun Ajaran</span>
                                <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $tahunAjaranDefault) }}" placeholder="Contoh: 2027/2028" maxlength="9" required>
                                @error('tahun_ajaran')<small>{{ $message }}</small>@enderror
                            </label>
                            <div class="academic-period-dialog-actions">
                                <button type="button" class="academic-period-cancel" data-academic-period-close>Batal</button>
                                <button type="submit" class="academic-period-save"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>Simpan</button>
                            </div>
                        </form>
                    </dialog>
                    <script>
                        (() => {
                            const dialog = document.querySelector('[data-academic-period-dialog]');
                            if (!dialog) return;
                            document.querySelector('[data-academic-period-open]')?.addEventListener('click', () => dialog.showModal());
                            dialog.querySelectorAll('[data-academic-period-close]').forEach(button => button.addEventListener('click', () => dialog.close()));
                            dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
                            @if($errors->has('semester') || $errors->has('tahun_ajaran'))
                                dialog.showModal();
                            @endif
                        })();
                    </script>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>

let selectMapelInstance;
let selectGuruInstance;
let selectKelasInstance;


function jadwalManager() {

    return {

        showEditModal: false,

        showDeleteModal: false,

        isEdit: false,

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

            semester: '',

            tahun_ajaran: '',

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

            // Sync ke TomSelect
            if (selectMapelInstance) {
                selectMapelInstance.setValue(data.id_mapel, true);
            }

            if (selectGuruInstance) {
                selectGuruInstance.setValue(data.id_guru, true);
            }

            this.showEditModal = true;

        },


        /*
         * TAMBAH JADWAL
         */
        openCreateModal(hari, idJam, jamKe) {

            this.isEdit = false;

            this.activeData = {

                id_jadwal: '',

                id_guru: '',

                id_mapel: '',

                id_jam_mulai: idJam || '',

                id_jam_selesai: idJam || '',

                hari: hari,

                jam_mulai_ke: jamKe,

                jam_selesai_ke: jamKe,

                semester: @json($semesterDefault),

                tahun_ajaran: @json($tahunAjaranDefault),

                mapel_nama: '',

                guru_nama: '',

                jam_range: '',

                waktu: ''

            };

            if (selectMapelInstance) {
                selectMapelInstance.clear(true);
            }

            if (selectGuruInstance) {
                selectGuruInstance.clear(true);
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
                create: false,
                onChange: function(value) {
                    // Update AlpineJS state secara manual
                    const rootDiv = document.querySelector('[x-data]');
                    if (rootDiv && Alpine) {
                        Alpine.$data(rootDiv).activeData.id_mapel = value;
                    }
                }
            }
        );


        /*
         * DROPDOWN GURU
         */
        selectGuruInstance = new TomSelect(
            "#select-guru",
            {
                create: false,
                onChange: function(value) {
                    // Update AlpineJS state secara manual
                    const rootDiv = document.querySelector('[x-data]');
                    if (rootDiv && Alpine) {
                        Alpine.$data(rootDiv).activeData.id_guru = value;
                    }
                }
            }
        );

    }
);

</script>

@endpush
