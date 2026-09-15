@extends('layouts.admin')

@section('title', 'Manajemen Jam Pelajaran - Jurnify')
@section('page-title', 'Jam Pelajaran')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
>
@endpush

@section('content')

{{-- =========================================================
    CEK DATA KELOMPOK HARI
========================================================== --}}
@php
    $groupedJam = $jamPels->groupBy('klp_hari');

    $adaSeninKamis = $groupedJam->has('Senin-Kamis');
    $adaJumat = $groupedJam->has('Jumat');

    // TRUE kalau kedua kelompok sudah memiliki jadwal
    $semuaKelompokSudahAda = $adaSeninKamis && $adaJumat;
@endphp


<div class="p-4 sm:p-6 lg:p-8 bg-slate-50 min-h-screen space-y-6">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Manajemen Jam Pelajaran
            </h1>

            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola waktu operasional KBM dan sesi istirahat harian sekolah.
            </p>
        </div>


        {{-- =====================================================
            TOMBOL TAMBAH
        ====================================================== --}}
        @if(!$semuaKelompokSudahAda)

            <a
                href="{{ route('admin.jam.create') }}"
                class="inline-flex items-center justify-center gap-2
                       bg-indigo-600 hover:bg-indigo-700
                       text-white px-4 py-2.5 rounded-xl
                       text-sm font-bold shadow-sm hover:shadow transition"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Jadwal Baru
            </a>

        @else

            <button
                type="button"
                disabled
                class="inline-flex items-center justify-center gap-2
                       bg-slate-300 text-slate-500
                       px-4 py-2.5 rounded-xl
                       text-sm font-bold cursor-not-allowed"
                title="Jadwal Senin-Kamis dan Jumat sudah tersedia"
            >
                <i class="fa-solid fa-check text-xs"></i>
                Semua Jadwal Sudah Dibuat
            </button>

        @endif

    </div>


    {{-- =========================================================
        NOTIFIKASI SUCCESS
    ========================================================== --}}
    @if(session('success'))

        <div
            class="bg-emerald-50 border border-emerald-200
                   text-emerald-800 px-4 py-3 rounded-xl
                   text-sm flex items-center justify-between shadow-sm"
        >

            <div class="flex items-center gap-3">

                <div
                    class="w-8 h-8 rounded-lg bg-emerald-100
                           flex items-center justify-center shrink-0"
                >
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                </div>

                <div>
                    <p class="font-bold">
                        Berhasil
                    </p>

                    <p class="text-xs text-emerald-700 mt-0.5">
                        {{ session('success') }}
                    </p>
                </div>

            </div>

            <button
                type="button"
                onclick="this.parentElement.remove()"
                class="text-emerald-500 hover:text-emerald-700 transition"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

    @endif


    {{-- =========================================================
        NOTIFIKASI ERROR
    ========================================================== --}}
    @if(session('error'))

        <div
            class="bg-rose-50 border border-rose-200
                   text-rose-800 px-4 py-4 rounded-xl
                   text-sm flex items-start justify-between
                   gap-4 shadow-sm"
        >

            <div class="flex items-start gap-3">

                <div
                    class="w-9 h-9 rounded-lg bg-rose-100
                           flex items-center justify-center shrink-0"
                >
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                </div>

                <div>

                    <p class="font-bold">
                        Tidak dapat menambahkan jadwal
                    </p>

                    <p class="text-xs text-rose-700 mt-1 leading-relaxed">
                        {{ session('error') }}
                    </p>

                </div>

            </div>

            <button
                type="button"
                onclick="this.parentElement.remove()"
                class="text-rose-400 hover:text-rose-600 transition shrink-0"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

    @endif

    {{-- =========================================================
        INFO TAMBAH JADWAL
    ========================================================== --}}
    @if($semuaKelompokSudahAda)

        <div
            class="bg-amber-50 border border-amber-200
                   rounded-xl px-4 py-3 flex items-start gap-3"
        >

            <div
                class="w-8 h-8 rounded-lg bg-amber-100
                       flex items-center justify-center shrink-0"
            >
                <i class="fa-solid fa-info text-amber-600"></i>
            </div>

            <div>

                <p class="text-sm font-bold text-amber-800">
                    Semua kelompok hari sudah memiliki jadwal
                </p>

                <p class="text-xs text-amber-700 mt-0.5">
                    Jadwal baru tidak dapat ditambahkan.
                    Jika ingin membuat ulang salah satu kelompok,
                    hapus jadwal lama terlebih dahulu.
                </p>

            </div>

        </div>

    @else

        <div
            class="bg-indigo-50 border border-indigo-100
                   rounded-xl px-4 py-3 flex items-start gap-3"
        >

            <div
                class="w-8 h-8 rounded-lg bg-indigo-100
                       flex items-center justify-center shrink-0"
            >
                <i class="fa-solid fa-circle-info text-indigo-600"></i>
            </div>

            <div>

                <p class="text-sm font-bold text-indigo-800">
                    Konfigurasi jadwal
                </p>

                <p class="text-xs text-indigo-700 mt-0.5">

                    @if(!$adaSeninKamis && !$adaJumat)

                        Belum ada jadwal. Kamu dapat membuat
                        konfigurasi untuk Senin-Kamis atau Jumat.

                    @elseif(!$adaSeninKamis)

                        Jadwal Jumat sudah tersedia.
                        Kelompok Senin-Kamis masih dapat ditambahkan.

                    @elseif(!$adaJumat)

                        Jadwal Senin-Kamis sudah tersedia.
                        Kelompok Jumat masih dapat ditambahkan.

                    @endif

                </p>

            </div>

        </div>

    @endif



    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        @foreach($groupedJam as $klpHari => $items)

            @php

                $isJumat = strtolower($klpHari) === 'jumat';

                $pelajaranCount = $items
                    ->where('jenis', '!=', 'istirahat')
                    ->count();

                $istirahatCount = $items
                    ->where('jenis', 'istirahat')
                    ->count();

                $jamMulaiAwal = $items->first()
                    ? \Carbon\Carbon::parse(
                        $items->first()->jam_mulai
                    )->format('H:i')
                    : '07:00';

                $jamSelesaiAkhir = $items->last()
                    ? \Carbon\Carbon::parse(
                        $items->last()->jam_selesai
                    )->format('H:i')
                    : '15:00';

            @endphp


            <div
                class="bg-white rounded-2xl border border-slate-200/80
                       shadow-sm p-5 space-y-4"
            >

                {{-- HEADER --}}
                <div class="flex items-center gap-3">

                    <div
                        class="w-10 h-10 rounded-xl
                               flex items-center justify-center
                               {{ $isJumat
                                    ? 'bg-emerald-50 text-emerald-600'
                                    : 'bg-indigo-50 text-indigo-600'
                               }}"
                    >
                        <i
                            class="{{ $isJumat
                                ? 'fa-regular fa-clock'
                                : 'fa-regular fa-calendar-check'
                            }} text-lg"
                        ></i>
                    </div>

                    <div>

                        <span
                            class="text-[10px] font-bold text-slate-400
                                   uppercase tracking-wider block"
                        >
                            Pola Hari Aktif
                        </span>

                        <h3 class="text-base font-bold text-slate-800">
                            {{ $klpHari }}
                        </h3>

                    </div>

                </div>


                {{-- STATISTIK --}}
                <div
                    class="grid grid-cols-2 gap-3 bg-slate-50 p-3
                           rounded-xl border border-slate-100 text-xs"
                >

                    <div>

                        <span class="text-slate-400 block mb-0.5">
                            Total Pembelajaran
                        </span>

                        <span class="font-bold text-slate-800 text-sm">
                            {{ $pelajaranCount }} Sesi
                        </span>

                    </div>


                    <div>

                        <span class="text-slate-400 block mb-0.5">
                            Waktu Istirahat
                        </span>

                        <span class="font-bold text-slate-800 text-sm">
                            {{ $istirahatCount }} Sesi
                        </span>

                    </div>

                </div>


                {{-- RANGE --}}
                <div
                    class="flex items-center justify-between text-xs
                           pt-1 border-t border-slate-100"
                >

                    <span
                        class="flex items-center gap-1.5
                               font-medium text-slate-500"
                    >

                        <span
                            class="w-2 h-2 rounded-full
                                   {{ $isJumat
                                        ? 'bg-emerald-500'
                                        : 'bg-indigo-500'
                                   }}"
                        ></span>

                        Rentang Operasional KBM

                    </span>

                    <span
                        class="font-mono font-bold text-slate-700
                               bg-slate-100 px-2.5 py-1 rounded-md"
                    >
                        {{ $jamMulaiAwal }} – {{ $jamSelesaiAkhir }}
                    </span>

                </div>

            </div>

        @endforeach

    </div>



    {{-- =========================================================
        TABLE
    ========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        @forelse($groupedJam as $klpHari => $items)

            @php

                $isJumat = strtolower($klpHari) === 'jumat';

                $firstJam = $items->first()
                    ? \Carbon\Carbon::parse(
                        $items->first()->jam_mulai
                    )->format('H:i')
                    : '07:00';

                $lastJam = $items->last()
                    ? \Carbon\Carbon::parse(
                        $items->last()->jam_selesai
                    )->format('H:i')
                    : '15:00';

            @endphp


            <div
                class="bg-white rounded-2xl border border-slate-200/80
                       shadow-sm overflow-hidden flex flex-col"
            >

                {{-- CARD HEADER --}}
                <div
                    class="p-5 border-b border-slate-100
                           bg-slate-50/50 flex items-center
                           justify-between gap-3"
                >

                    <div>

                        <div class="flex items-center gap-2">

                            <h3 class="text-base font-bold text-slate-800">
                                Jadwal {{ $klpHari }}
                            </h3>

                            <span
                                class="text-[11px] font-bold px-2 py-0.5
                                       rounded-md
                                       {{ $isJumat
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-indigo-100 text-indigo-700'
                                       }}"
                            >
                                {{ $items->count() }} Sesi Total
                            </span>

                        </div>

                        <p class="text-xs text-slate-400 mt-1">
                            Rentang: {{ $firstJam }} – {{ $lastJam }}
                        </p>

                    </div>


                    {{-- ACTION --}}
                    <div class="flex items-center gap-2">

                        {{-- EDIT --}}
                        <a
                            href="{{ route('admin.jam.edit', ['klp_hari' => $klpHari]) }}"
                            class="inline-flex items-center gap-1.5
                                   bg-amber-500 hover:bg-amber-600
                                   text-white text-xs font-bold
                                   px-3 py-2 rounded-xl transition shadow-sm"
                        >
                            <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                            Edit
                        </a>


                        {{-- HAPUS --}}
                        <form
                            action="{{ route('admin.jam.destroy', ['klp_hari' => $klpHari]) }}"
                            method="POST"
                            onsubmit="return confirm('Apakah kamu yakin ingin menghapus seluruh jadwal {{ $klpHari }}?')"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="inline-flex items-center gap-1.5
                                       bg-rose-600 hover:bg-rose-700
                                       text-white text-xs font-bold
                                       px-3 py-2 rounded-xl
                                       transition shadow-sm"
                            >
                                <i class="fa-solid fa-trash text-[10px]"></i>
                                Hapus
                            </button>

                        </form>

                    </div>

                </div>


                {{-- TABLE --}}
                <div class="overflow-x-auto">

                    <table class="w-full text-left border-collapse text-xs">

                        <thead>

                            <tr
                                class="bg-slate-100/70 text-[10px]
                                       font-extrabold text-slate-400
                                       uppercase tracking-wider
                                       border-b border-slate-200/60"
                            >

                                <th class="py-3 px-4">
                                    Jam Ke-
                                </th>

                                <th class="py-3 px-4">
                                    Waktu
                                </th>

                                <th class="py-3 px-4 text-center">
                                    Durasi
                                </th>

                                <th class="py-3 px-4">
                                    Tipe / Keterangan
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach($items as $jam)

                                @php

                                    $isIstirahat =
                                        strtolower($jam->jenis) === 'istirahat';

                                    $mulai =
                                        \Carbon\Carbon::parse(
                                            $jam->jam_mulai
                                        );

                                    $selesai =
                                        \Carbon\Carbon::parse(
                                            $jam->jam_selesai
                                        );

                                    $durasi =
                                        $mulai->diffInMinutes(
                                            $selesai
                                        );

                                @endphp


                                <tr
                                    class="transition
                                    {{ $isIstirahat
                                        ? ($isJumat
                                            ? 'bg-emerald-50/50 font-semibold'
                                            : 'bg-amber-50/60 font-semibold')
                                        : 'hover:bg-slate-50/80'
                                    }}"
                                >

                                    {{-- JAM --}}
                                    <td
                                        class="py-3.5 px-4
                                               font-bold text-slate-800"
                                    >

                                        @if($isIstirahat)

                                            <span
                                                class="flex items-center gap-1.5
                                                       {{ $isJumat
                                                            ? 'text-emerald-700'
                                                            : 'text-amber-800'
                                                       }}"
                                            >
                                                <i
                                                    class="fa-regular fa-clock text-xs"
                                                ></i>

                                                Istirahat
                                            </span>

                                        @else

                                            <span>
                                                Jam {{ $jam->jam_ke }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- WAKTU --}}
                                    <td
                                        class="py-3.5 px-4
                                               font-mono font-bold
                                               text-slate-600"
                                    >

                                        {{ $mulai->format('H:i') }}
                                        –
                                        {{ $selesai->format('H:i') }}

                                    </td>


                                    {{-- DURASI --}}
                                    <td
                                        class="py-3.5 px-4 text-center
                                               font-semibold text-slate-500"
                                    >
                                        {{ $durasi }} mnt
                                    </td>


                                    {{-- TIPE --}}
                                    <td class="py-3.5 px-4">

                                        @if($isIstirahat)

                                            <span
                                                class="inline-block
                                                       text-[11px] font-bold
                                                       px-2.5 py-0.5 rounded-full
                                                       {{ $isJumat
                                                            ? 'bg-emerald-200/60 text-emerald-800'
                                                            : 'bg-amber-200/60 text-amber-800'
                                                       }}"
                                            >
                                                {{ ucfirst($jam->jenis) }}
                                                ({{ $durasi }}m)
                                            </span>

                                        @else

                                            <span
                                                class="inline-block
                                                       text-[11px] font-bold
                                                       px-2.5 py-0.5 rounded-md
                                                       bg-slate-100
                                                       text-slate-600"
                                            >
                                                KBM ({{ $durasi }}m)
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- FOOTER --}}
                <div
                    class="p-3 bg-slate-50 border-t border-slate-100
                           text-center text-[11px] text-slate-400
                           font-medium"
                >
                    — Sesi {{ $klpHari }}
                    selesai pada pukul {{ $lastJam }} —
                </div>

            </div>

        @empty


            {{-- =================================================
                EMPTY STATE
            ================================================== --}}
            <div
                class="col-span-full bg-white rounded-2xl p-12
                       text-center border border-slate-200/80"
            >

                <i
                    class="fa-regular fa-folder-open
                           text-4xl text-slate-300 mb-3"
                ></i>

                <h3 class="text-base font-bold text-slate-700">
                    Belum Ada Data Jam Pelajaran
                </h3>

                <p
                    class="text-xs text-slate-400 mt-1
                           max-w-sm mx-auto"
                >
                    Silakan buat jam pelajaran baru atau atur
                    konfigurasi jadwal sekolah kamu.
                </p>

                <a
                    href="{{ route('admin.jam.create') }}"
                    class="inline-flex items-center gap-2
                           bg-indigo-600 hover:bg-indigo-700
                           text-white text-xs font-bold
                           px-4 py-2.5 rounded-xl mt-4 transition"
                >
                    <i class="fa-solid fa-plus"></i>
                    Buat Jam Pelajaran
                </a>

            </div>

        @endforelse

    </div>

</div>

@endsection