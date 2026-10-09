@extends('layouts.admin')

@section('title', 'Pengaturan Jam Pelajaran')
@section('page-title', 'Pengaturan Jam Pelajaran')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
>
@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | STATUS KELOMPOK HARI
    |--------------------------------------------------------------------------
    */

    $adaSeninKamis = $adaSeninKamis ?? false;
    $adaJumat = $adaJumat ?? false;

    $semuaSudahAda = $adaSeninKamis && $adaJumat;

    /*
    | Tentukan pilihan awal.
    | Kalau Senin-Kamis sudah ada, otomatis pilih Jumat.
    | Kalau Jumat sudah ada, otomatis pilih Senin-Kamis.
    | Kalau keduanya belum ada, default Senin-Kamis.
    */

    if (!$adaSeninKamis) {
        $defaultKlpHari = 'Senin-Kamis';
    } elseif (!$adaJumat) {
        $defaultKlpHari = 'Jumat';
    } else {
        $defaultKlpHari = 'Senin-Kamis';
    }

    $oldKlpHari = old('klp_hari', $defaultKlpHari);

    /*
    | Jangan sampai old input memilih kelompok yang ternyata sudah ada.
    | Misalnya user submit Senin-Kamis lalu gagal, sementara data sudah ada.
    */

    if ($oldKlpHari === 'Senin-Kamis' && $adaSeninKamis) {
        $oldKlpHari = !$adaJumat ? 'Jumat' : 'Senin-Kamis';
    }

    if ($oldKlpHari === 'Jumat' && $adaJumat) {
        $oldKlpHari = !$adaSeninKamis ? 'Senin-Kamis' : 'Jumat';
    }
@endphp


<div
    class="w-full p-3 sm:p-5 bg-slate-50 min-h-screen"
    x-data="jamScheduler()"
>

    <div class="w-full space-y-5">


        {{-- =====================================================
            HEADER
        ====================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div>

                <h1
                    class="text-2xl font-extrabold text-slate-800 tracking-tight"
                >
                    Pengaturan Jam Pelajaran
                </h1>

                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Tentukan jam masuk, jam pulang, dan durasi.
                    Sistem akan menghitung jadwal secara otomatis.
                </p>

            </div>

        </div>



        {{-- =====================================================
            STATUS KELOMPOK HARI
        ====================================================== --}}

        @if($semuaSudahAda)

            <div
                class="bg-amber-50 border border-amber-200
                       rounded-2xl p-4 sm:p-5"
            >

                <div class="flex items-start gap-3">

                    <div
                        class="w-10 h-10 rounded-xl bg-amber-100
                               text-amber-600 flex items-center
                               justify-center shrink-0"
                    >
                        <i class="fa-solid fa-circle-info"></i>
                    </div>

                    <div>

                        <h3 class="font-bold text-amber-800 text-sm">
                            Semua jadwal sudah tersedia
                        </h3>

                        <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                            Jadwal untuk <b>Senin-Kamis</b> dan
                            <b>Jumat</b> sudah tersimpan di database.
                            Hapus salah satu jadwal terlebih dahulu
                            jika ingin membuat konfigurasi baru.
                        </p>

                    </div>

                </div>

            </div>

        @else

            <div
                class="bg-blue-50 border border-blue-100
                       rounded-2xl p-4 sm:p-5"
            >

                <div class="flex items-start gap-3">

                    <div
                        class="w-10 h-10 rounded-xl bg-blue-100
                               text-blue-600 flex items-center
                               justify-center shrink-0"
                    >
                        <i class="fa-solid fa-circle-info"></i>
                    </div>

                    <div>

                        <h3 class="font-bold text-blue-800 text-sm">
                            Kelompok hari yang tersedia
                        </h3>

                        <p class="text-xs text-blue-700 mt-1 leading-relaxed">

                            @if($adaSeninKamis)

                                Jadwal <b>Senin-Kamis</b> sudah ada.
                                Saat ini hanya kelompok <b>Jumat</b>
                                yang dapat ditambahkan.

                            @elseif($adaJumat)

                                Jadwal <b>Jumat</b> sudah ada.
                                Saat ini hanya kelompok <b>Senin-Kamis</b>
                                yang dapat ditambahkan.

                            @else

                                Belum ada jadwal yang tersimpan.
                                Kamu dapat membuat konfigurasi
                                Senin-Kamis atau Jumat.

                            @endif

                        </p>

                    </div>

                </div>

            </div>

        @endif



        {{-- =====================================================
            FORM
        ====================================================== --}}
        <form
            action="{{ route('admin.jam.store') }}"
            method="POST"
        >

            @csrf


            <div
                class="grid grid-cols-1 lg:grid-cols-12
                       gap-5 items-start"
            >


                {{-- =================================================
                    SISI KIRI
                ================================================== --}}
                <div
                    class="lg:col-span-7 bg-white rounded-2xl
                           p-5 sm:p-6 shadow-sm
                           border border-slate-200/80 space-y-6"
                >


                    {{-- =============================================
                        1. KELOMPOK HARI
                    ============================================== --}}
                    <div>

                        <label
                            class="block text-xs font-bold uppercase
                                   tracking-wider text-slate-400 mb-2"
                        >
                            1. Kelompok Hari
                        </label>


                        <div class="grid grid-cols-2 gap-3">


                            {{-- =====================================
                                SENIN - KAMIS
                            ====================================== --}}
                            <label
                                class="{{ $adaSeninKamis
                                    ? 'cursor-not-allowed'
                                    : 'cursor-pointer'
                                }}"
                            >

                                <input
                                    type="radio"
                                    name="klp_hari"
                                    value="Senin-Kamis"
                                    x-model="klpHari"

                                    {{ $adaSeninKamis ? 'disabled' : '' }}

                                    class="peer hidden"
                                >


                                <div
                                    class="p-3.5 border-2 rounded-xl
                                           text-center font-bold text-sm
                                           transition

                                           {{ $adaSeninKamis
                                                ? 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed'
                                                : 'peer-checked:border-blue-600 peer-checked:bg-blue-50/50 peer-checked:text-blue-600 text-slate-500 border-slate-200 hover:border-slate-300'
                                           }}"
                                >

                                    <div
                                        class="flex items-center
                                               justify-center gap-2"
                                    >

                                        <span>
                                            Senin – Kamis
                                        </span>

                                        @if($adaSeninKamis)

                                            <i
                                                class="fa-solid fa-lock
                                                       text-[10px]"
                                            ></i>

                                        @endif

                                    </div>


                                    @if($adaSeninKamis)

                                        <span
                                            class="block text-[10px]
                                                   text-rose-500 mt-1"
                                        >
                                            Sudah tersedia
                                        </span>

                                    @endif

                                </div>

                            </label>



                            {{-- =====================================
                                JUMAT
                            ====================================== --}}
                            <label
                                class="{{ $adaJumat
                                    ? 'cursor-not-allowed'
                                    : 'cursor-pointer'
                                }}"
                            >

                                <input
                                    type="radio"
                                    name="klp_hari"
                                    value="Jumat"
                                    x-model="klpHari"

                                    {{ $adaJumat ? 'disabled' : '' }}

                                    class="peer hidden"
                                >


                                <div
                                    class="p-3.5 border-2 rounded-xl
                                           text-center font-bold text-sm
                                           transition

                                           {{ $adaJumat
                                                ? 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed'
                                                : 'peer-checked:border-blue-600 peer-checked:bg-blue-50/50 peer-checked:text-blue-600 text-slate-500 border-slate-200 hover:border-slate-300'
                                           }}"
                                >

                                    <div
                                        class="flex items-center
                                               justify-center gap-2"
                                    >

                                        <span>
                                            Jumat
                                        </span>

                                        @if($adaJumat)

                                            <i
                                                class="fa-solid fa-lock
                                                       text-[10px]"
                                            ></i>

                                        @endif

                                    </div>


                                    @if($adaJumat)

                                        <span
                                            class="block text-[10px]
                                                   text-rose-500 mt-1"
                                        >
                                            Sudah tersedia
                                        </span>

                                    @endif

                                </div>

                            </label>

                        </div>


                        {{-- ERROR VALIDASI KELOMPOK --}}
                        @error('klp_hari')

                            <p
                                class="text-xs text-rose-600
                                       font-semibold mt-2"
                            >
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    <hr class="border-slate-100">



                    {{-- =============================================
                        2. JAM MASUK & JAM PULANG
                    ============================================== --}}
                    <div>

                        <label
                            class="block text-xs font-bold uppercase
                                   tracking-wider text-slate-400 mb-2"
                        >
                            2. Jam Masuk & Jam Pulang
                        </label>


                        <div class="grid grid-cols-2 gap-4">


                            {{-- JAM MASUK --}}
                            <div
                                class="bg-slate-50 border border-slate-200
                                       rounded-xl p-3 flex justify-between
                                       items-center"
                            >

                                <div class="w-full">

                                    <span
                                        class="block text-[11px] font-bold
                                               text-slate-400 uppercase
                                               tracking-wide"
                                    >
                                        Jam Masuk
                                    </span>

                                    <input
                                        type="time"
                                        name="jam_masuk"
                                        x-model="jamMasuk"
                                        step="60"
                                        class="w-full text-xl
                                               font-extrabold text-slate-800
                                               bg-transparent
                                               focus:outline-none mt-1"
                                    >

                                </div>

                            </div>



                            {{-- JAM PULANG --}}
                            <div
                                class="bg-slate-50 border border-slate-200
                                       rounded-xl p-3 flex justify-between
                                       items-center"
                            >

                                <div class="w-full">

                                    <span
                                        class="block text-[11px] font-bold
                                               text-slate-400 uppercase
                                               tracking-wide"
                                    >
                                        Jam Pulang
                                    </span>

                                    <input
                                        type="time"
                                        name="jam_pulang"
                                        x-model="jamPulang"
                                        step="60"
                                        class="w-full text-xl
                                               font-extrabold text-slate-800
                                               bg-transparent
                                               focus:outline-none mt-1"
                                    >

                                </div>

                            </div>

                        </div>

                    </div>



                    <hr class="border-slate-100">



                    {{-- =============================================
                        3. MODE DURASI PELAJARAN
                    ============================================== --}}
                    <div>

                        <label
                            class="block text-xs font-bold uppercase
                                   tracking-wider text-slate-400 mb-2"
                        >
                            3. Mode Durasi Pelajaran
                        </label>


                        <div class="space-y-2.5">


                            {{-- SERAGAM --}}
                            <label
                                class="flex items-start gap-3 p-3
                                       border rounded-xl cursor-pointer
                                       hover:bg-slate-50/80 transition"
                                :class="modeDurasi === 'seragam'
                                    ? 'border-blue-500 bg-blue-50/20'
                                    : 'border-slate-200'"
                            >

                                <input
                                    type="radio"
                                    name="mode_durasi"
                                    value="seragam"
                                    x-model="modeDurasi"
                                    class="mt-1 text-blue-600
                                           focus:ring-blue-500"
                                >

                                <div>

                                    <span
                                        class="block font-bold text-sm
                                               text-slate-800"
                                    >
                                        Durasi Seragam
                                    </span>

                                    <span
                                        class="block text-xs
                                               text-slate-500"
                                    >
                                        Semua jam pelajaran memiliki
                                        durasi menit yang sama.
                                    </span>

                                </div>

                            </label>



                            {{-- FLEKSIBEL --}}
                            <label
                                class="flex items-start gap-3 p-3
                                       border rounded-xl cursor-pointer
                                       hover:bg-slate-50/80 transition"
                                :class="modeDurasi === 'fleksibel'
                                    ? 'border-blue-500 bg-blue-50/20'
                                    : 'border-slate-200'"
                            >

                                <input
                                    type="radio"
                                    name="mode_durasi"
                                    value="fleksibel"
                                    x-model="modeDurasi"
                                    class="mt-1 text-blue-600
                                           focus:ring-blue-500"
                                >

                                <div>

                                    <span
                                        class="block font-bold text-sm
                                               text-slate-800"
                                    >
                                        Durasi Fleksibel Per Jam
                                    </span>

                                    <span
                                        class="block text-xs
                                               text-slate-500"
                                    >
                                        Atur durasi spesifik untuk jam
                                        pelajaran tertentu
                                        (misal: Jam ke-1 45m,
                                        lainnya 40m).
                                    </span>

                                </div>

                            </label>



                            {{-- SESUAIKAN PULANG --}}
                            <label
                                class="flex items-start gap-3 p-3
                                       border rounded-xl cursor-pointer
                                       hover:bg-slate-50/80 transition"
                                :class="modeDurasi === 'sesuaikan_pulang'
                                    ? 'border-blue-500 bg-blue-50/20'
                                    : 'border-slate-200'"
                            >

                                <input
                                    type="radio"
                                    name="mode_durasi"
                                    value="sesuaikan_pulang"
                                    x-model="modeDurasi"
                                    class="mt-1 text-blue-600
                                           focus:ring-blue-500"
                                >

                                <div>

                                    <span
                                        class="block font-bold text-sm
                                               text-slate-800"
                                    >
                                        Sesuaikan Otomatis Ke Jam Pulang
                                    </span>

                                    <span
                                        class="block text-xs
                                               text-slate-500"
                                    >
                                        Sistem akan menyesuaikan durasi
                                        jam terakhir agar pas berakhir
                                        di jam pulang.
                                    </span>

                                </div>

                            </label>

                        </div>



                        {{-- INPUT DURASI --}}
                        <div
                            class="mt-4 bg-slate-50 p-3.5 rounded-xl
                                   border border-slate-200
                                   flex items-center justify-between"
                        >

                            <span
                                class="text-sm font-semibold
                                       text-slate-700"
                            >
                                Durasi Standar Pelajaran:
                            </span>

                            <div class="flex items-center gap-2">

                                <input
                                    type="number"
                                    name="durasi_jp"
                                    x-model.number="durasiJp"
                                    min="5"
                                    class="w-20 text-center font-bold
                                           text-base border
                                           border-slate-300 rounded-lg
                                           py-1 px-2 focus:ring-2
                                           focus:ring-blue-500
                                           focus:outline-none bg-white"
                                >

                                <span
                                    class="text-xs font-semibold
                                           text-slate-500"
                                >
                                    menit
                                </span>

                            </div>

                        </div>

                    </div>



                    <hr class="border-slate-100">



                    {{-- =============================================
                        4. PENGATURAN ISTIRAHAT
                    ============================================== --}}
                    <div>

                        <div
                            class="flex items-center justify-between mb-3"
                        >

                            <label
                                class="block text-xs font-bold
                                       uppercase tracking-wider
                                       text-slate-400"
                            >
                                4. Istirahat
                            </label>

                            <button
                                type="button"
                                @click="addIstirahat()"
                                class="text-xs text-blue-600
                                       hover:text-blue-700 font-bold
                                       flex items-center gap-1 transition"
                            >
                                + Tambah Istirahat
                            </button>

                        </div>


                        <div class="space-y-2.5">

                            <template
                                x-for="(ist, index) in istirahatList"
                                :key="index"
                            >

                                <div
                                    class="bg-amber-50/60
                                           border border-amber-200/80
                                           rounded-xl p-3 flex items-center
                                           justify-between"
                                >

                                    <div class="flex items-center gap-2">

                                        <span
                                            class="w-6 h-6
                                                   bg-amber-200/80
                                                   text-amber-800 text-xs
                                                   font-bold rounded-full
                                                   flex items-center
                                                   justify-center"
                                            x-text="index + 1"
                                        ></span>

                                        <span
                                            class="text-xs font-bold
                                                   text-slate-700"
                                            x-text="'Istirahat ' + (index + 1)"
                                        ></span>

                                    </div>


                                    <div
                                        class="flex items-center gap-3"
                                    >

                                        <div
                                            class="flex items-center gap-1.5
                                                   text-xs"
                                        >

                                            <span
                                                class="text-slate-500"
                                            >
                                                Setelah Jam ke
                                            </span>

                                            <input
                                                type="number"
                                                :name="'istirahat['+index+'][setelah_jam]'"
                                                x-model.number="ist.setelah_jam"
                                                min="1"
                                                class="w-12 text-center
                                                       font-bold
                                                       border
                                                       border-slate-300
                                                       rounded py-0.5 px-1
                                                       bg-white
                                                       focus:ring-1
                                                       focus:ring-blue-500
                                                       focus:outline-none"
                                            >

                                        </div>


                                        <div
                                            class="flex items-center gap-1.5
                                                   text-xs"
                                        >

                                            <span
                                                class="text-slate-500"
                                            >
                                                Durasi
                                            </span>

                                            <input
                                                type="number"
                                                :name="'istirahat['+index+'][durasi]'"
                                                x-model.number="ist.durasi"
                                                min="1"
                                                class="w-14 text-center
                                                       font-bold
                                                       border
                                                       border-slate-300
                                                       rounded py-0.5 px-1
                                                       bg-white
                                                       focus:ring-1
                                                       focus:ring-blue-500
                                                       focus:outline-none"
                                            >

                                            <span class="text-slate-400">
                                                mnt
                                            </span>

                                        </div>


                                        <button
                                            type="button"
                                            @click="removeIstirahat(index)"
                                            class="text-slate-400
                                                   hover:text-red-500
                                                   transition"
                                        >

                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>

                                        </button>

                                    </div>

                                </div>

                            </template>

                        </div>

                    </div>



                    {{-- =============================================
                        TOMBOL SIMPAN
                    ============================================== --}}

                    @if($semuaSudahAda)

                        <button
                            type="button"
                            disabled
                            class="w-full bg-slate-300 text-slate-500
                                   font-bold py-3.5 px-4 rounded-xl
                                   cursor-not-allowed flex items-center
                                   justify-center gap-2"
                        >
                            <i class="fa-solid fa-lock"></i>
                            Semua Kelompok Hari Sudah Tersedia
                        </button>

                    @else

                        <button
                            type="submit"
                            class="w-full bg-blue-600
                                   hover:bg-blue-700 text-white
                                   font-bold py-3.5 px-4 rounded-xl
                                   shadow-md hover:shadow-lg
                                   transition flex items-center
                                   justify-center gap-2"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Konfigurasi Jadwal
                        </button>

                    @endif

                </div>



                {{-- =================================================
                    SISI KANAN
                ================================================== --}}
                <div class="lg:col-span-5 space-y-4">


                    {{-- =============================================
                        CARD RINGKASAN
                    ============================================== --}}
                    <div
                        class="bg-slate-900 text-white rounded-2xl
                               p-5 shadow-md space-y-4"
                    >

                        <h3
                            class="font-bold text-xs tracking-wider
                                   text-slate-300 uppercase"
                        >
                            Ringkasan Jadwal
                        </h3>


                        <div
                            class="grid grid-cols-2 gap-3 text-xs
                                   border-y border-slate-800 py-3"
                        >

                            <div>

                                <span
                                    class="text-slate-400 block mb-0.5"
                                >
                                    Kelompok Hari
                                </span>

                                <span
                                    class="font-bold text-base
                                           text-blue-400"
                                    x-text="klpHari"
                                ></span>

                            </div>


                            <div>

                                <span
                                    class="text-slate-400 block mb-0.5"
                                >
                                    Jam Masuk
                                </span>

                                <span
                                    class="font-mono text-base
                                           font-bold text-blue-400"
                                    x-text="jamMasuk"
                                ></span>

                            </div>


                            <div>

                                <span
                                    class="text-slate-400 block mb-0.5"
                                >
                                    Jam Pulang Target
                                </span>

                                <span
                                    class="font-mono text-base
                                           font-bold text-blue-400"
                                    x-text="jamPulang"
                                ></span>

                            </div>


                            <div>

                                <span
                                    class="text-slate-400 block mb-0.5"
                                >
                                    Total Istirahat
                                </span>

                                <span
                                    class="font-semibold text-amber-400"
                                    x-text="totalIstirahatMenit + ' Menit'"
                                ></span>

                            </div>


                            <div>

                                <span
                                    class="text-slate-400 block mb-0.5"
                                >
                                    Hasil Kalkulasi JP
                                </span>

                                <span
                                    class="font-bold text-emerald-400"
                                    x-text="generatedSlots.filter(s => s.jenis === 'pelajaran').length + ' Jam'"
                                ></span>

                            </div>

                        </div>



                        {{-- WARNING --}}
                        <div
                            x-show="sisaMenit !== 0 && modeDurasi !== 'sesuaikan_pulang'"
                            class="p-3 bg-amber-500/10
                                   border border-amber-500/30
                                   rounded-xl text-xs space-y-2"
                        >

                            <div
                                class="flex items-start gap-2
                                       text-amber-300"
                            >

                                <svg
                                    class="w-4 h-4 text-amber-400
                                           shrink-0 mt-0.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    />
                                </svg>

                                <div>

                                    <span class="font-bold">
                                        Jadwal belum pas dengan jam pulang.
                                    </span>

                                    <p
                                        class="mt-0.5 text-slate-300"
                                        x-text="'Terdapat sisa waktu ' + Math.abs(sisaMenit) + ' menit dari target jam pulang (' + jamPulang + ').'"
                                    ></p>

                                </div>

                            </div>


                            <button
                                type="button"
                                @click="modeDurasi = 'sesuaikan_pulang'"
                                class="w-full py-1.5 bg-amber-500
                                       hover:bg-amber-600
                                       text-slate-900 font-bold
                                       rounded-lg text-center
                                       transition"
                            >
                                Sesuaikan Otomatis Ke Jam Pulang
                            </button>

                        </div>

                    </div>



                    {{-- =============================================
                        PREVIEW TABEL
                    ============================================== --}}
                    <div
                        class="bg-white rounded-2xl
                               border border-slate-200/80
                               shadow-sm overflow-hidden"
                    >

                        <div
                            class="p-4 bg-slate-50 border-b
                                   border-slate-200 flex items-center
                                   justify-between"
                        >

                            <h4
                                class="font-bold text-slate-800 text-sm"
                            >
                                Preview Live Schedule
                            </h4>

                            <span
                                class="text-[10px] bg-blue-100
                                       text-blue-700 px-2 py-0.5
                                       rounded-full font-bold"
                            >
                                Otomatis
                            </span>

                        </div>


                        <div class="max-h-[500px] overflow-y-auto">

                            <table
                                class="w-full text-left border-collapse
                                       text-xs"
                            >

                                <thead>

                                    <tr
                                        class="bg-slate-100/70
                                               text-slate-600
                                               font-bold border-b
                                               border-slate-200"
                                    >

                                        <th class="p-3 text-center w-16">
                                            Jam
                                        </th>

                                        <th class="p-3">
                                            Waktu Mulai - Selesai
                                        </th>

                                        <th
                                            class="p-3 text-center w-24"
                                        >
                                            Durasi
                                        </th>

                                    </tr>

                                </thead>


                                <tbody
                                    class="divide-y divide-slate-100"
                                >

                                    <template
                                        x-for="(slot, idx) in generatedSlots"
                                        :key="idx"
                                    >

                                        <tr
                                            :class="slot.jenis === 'istirahat'
                                                ? 'bg-amber-50/70 font-semibold text-amber-900'
                                                : 'hover:bg-slate-50/80'"
                                        >

                                            <td
                                                class="p-3 text-center
                                                       font-bold"
                                            >

                                                <span
                                                    x-show="slot.jenis === 'pelajaran'"
                                                    x-text="slot.jamKe"
                                                    class="text-slate-700"
                                                ></span>

                                                <span
                                                    x-show="slot.jenis === 'istirahat'"
                                                    class="text-[10px]
                                                           bg-amber-200/80
                                                           text-amber-800
                                                           px-1.5 py-0.5
                                                           rounded font-bold"
                                                >
                                                    Istirahat
                                                </span>

                                            </td>


                                            <td
                                                class="p-3 font-mono
                                                       text-slate-700"
                                                x-text="slot.jamMulai + ' - ' + slot.jamSelesai"
                                            ></td>


                                            <td
                                                class="p-3 text-center"
                                            >

                                                <template
                                                    x-if="modeDurasi === 'fleksibel' && slot.jenis === 'pelajaran'"
                                                >

                                                    <input
                                                        type="number"
                                                        :name="'durasi_khusus['+slot.jamKe+']'"
                                                        x-model.number="slot.durasi"
                                                        min="1"
                                                        class="w-12 text-center
                                                               border
                                                               border-slate-300
                                                               rounded py-0.5
                                                               bg-white
                                                               font-bold
                                                               text-blue-600
                                                               focus:ring-1
                                                               focus:ring-blue-500
                                                               focus:outline-none"
                                                    >

                                                </template>


                                                <template
                                                    x-if="modeDurasi !== 'fleksibel' || slot.jenis === 'istirahat'"
                                                >

                                                    <span
                                                        x-text="slot.durasi + ' mnt'"
                                                    ></span>

                                                </template>

                                            </td>

                                        </tr>

                                    </template>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>



{{-- =========================================================
    ALPINE JS
========================================================== --}}
<script
    src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
    defer
></script>


<script>

function jamScheduler() {

    return {

        /*
        |--------------------------------------------------------------------------
        | DATA DARI DATABASE
        |--------------------------------------------------------------------------
        */

        adaSeninKamis: @json($adaSeninKamis),
        adaJumat: @json($adaJumat),


        /*
        |--------------------------------------------------------------------------
        | KELOMPOK HARI AWAL
        |--------------------------------------------------------------------------
        */

        klpHari: @json($oldKlpHari),


        /*
        |--------------------------------------------------------------------------
        | JAM DEFAULT
        |--------------------------------------------------------------------------
        */

        jamMasuk: '07:00',
        jamPulang: '15:00',


        /*
        |--------------------------------------------------------------------------
        | MODE DURASI
        |--------------------------------------------------------------------------
        */

        modeDurasi: 'seragam',

        durasiJp: 40,


        /*
        |--------------------------------------------------------------------------
        | ISTIRAHAT
        |--------------------------------------------------------------------------
        */

        istirahatList: [
            {
                setelah_jam: 5,
                durasi: 30
            }
        ],


        /*
        |--------------------------------------------------------------------------
        | INIT
        |--------------------------------------------------------------------------
        */

        init() {

            /*
            |--------------------------------------------------------------------------
            | Atur konfigurasi berdasarkan kelompok hari awal
            |--------------------------------------------------------------------------
            */

            this.aturDefaultHari(this.klpHari);


            /*
            |--------------------------------------------------------------------------
            | Ketika kelompok hari berubah
            |--------------------------------------------------------------------------
            */

            this.$watch('klpHari', (value) => {

                this.aturDefaultHari(value);

            });

        },


        /*
        |--------------------------------------------------------------------------
        | DEFAULT BERDASARKAN HARI
        |--------------------------------------------------------------------------
        */

        aturDefaultHari(value) {

            if (value === 'Jumat') {

                this.jamMasuk = '07:00';

                this.jamPulang = '15:00';

                this.durasiJp = 30;

                this.istirahatList = [
                    {
                        setelah_jam: 4,
                        durasi: 30
                    }
                ];

            } else {

                this.jamMasuk = '07:00';

                this.jamPulang = '15:00';

                this.durasiJp = 40;

                this.istirahatList = [
                    {
                        setelah_jam: 5,
                        durasi: 30
                    }
                ];

            }

        },


        /*
        |--------------------------------------------------------------------------
        | TAMBAH ISTIRAHAT
        |--------------------------------------------------------------------------
        */

        addIstirahat() {

            const lastJam =
                this.istirahatList.length > 0
                    ? this.istirahatList[
                        this.istirahatList.length - 1
                    ].setelah_jam + 3
                    : 5;


            this.istirahatList.push({

                setelah_jam: lastJam,

                durasi: 15

            });

        },


        /*
        |--------------------------------------------------------------------------
        | HAPUS ISTIRAHAT
        |--------------------------------------------------------------------------
        */

        removeIstirahat(index) {

            this.istirahatList.splice(index, 1);

        },


        /*
        |--------------------------------------------------------------------------
        | TIME → MENIT
        |--------------------------------------------------------------------------
        */

        timeToMin(timeStr) {

            if (!timeStr) {
                return 0;
            }

            const [h, m] =
                timeStr.split(':').map(Number);

            return h * 60 + m;

        },


        /*
        |--------------------------------------------------------------------------
        | MENIT → TIME
        |--------------------------------------------------------------------------
        */

        minToTime(min) {

            const h =
                Math.floor(min / 60)
                    .toString()
                    .padStart(2, '0');

            const m =
                (min % 60)
                    .toString()
                    .padStart(2, '0');

            return `${h}:${m}`;

        },


        /*
        |--------------------------------------------------------------------------
        | TOTAL ISTIRAHAT
        |--------------------------------------------------------------------------
        */

        get totalIstirahatMenit() {

            return this.istirahatList.reduce(

                (acc, curr) =>
                    acc + (parseInt(curr.durasi) || 0),

                0

            );

        },


        /*
        |--------------------------------------------------------------------------
        | GENERATE SLOT
        |--------------------------------------------------------------------------
        */

        get generatedSlots() {

            let start =
                this.timeToMin(this.jamMasuk);

            let end =
                this.timeToMin(this.jamPulang);


            if (end <= start) {

                return [];

            }


            let current = start;

            let jamKe = 1;

            let slots = [];


            /*
            |--------------------------------------------------------------------------
            | MAP ISTIRAHAT
            |--------------------------------------------------------------------------
            */

            let istirahatMap = {};


            this.istirahatList.forEach(ist => {

                if (
                    ist.setelah_jam &&
                    ist.durasi
                ) {

                    istirahatMap[ist.setelah_jam] =
                        parseInt(ist.durasi);

                }

            });


            /*
            |--------------------------------------------------------------------------
            | GENERATE
            |--------------------------------------------------------------------------
            */

            while (current < end) {

                let durasi =
                    parseInt(this.durasiJp) || 40;


                /*
                |--------------------------------------------------------------------------
                | MODE SESUAIKAN PULANG
                |--------------------------------------------------------------------------
                */

                if (
                    this.modeDurasi === 'sesuaikan_pulang' &&
                    current + durasi > end
                ) {

                    durasi =
                        end - current;

                }


                /*
                |--------------------------------------------------------------------------
                | Jangan melewati jam pulang
                |--------------------------------------------------------------------------
                */

                if (current + durasi > end) {

                    break;

                }


                let startStr =
                    this.minToTime(current);

                let endStr =
                    this.minToTime(current + durasi);


                /*
                |--------------------------------------------------------------------------
                | SLOT PELAJARAN
                |--------------------------------------------------------------------------
                */

                slots.push({

                    jenis: 'pelajaran',

                    jamKe: jamKe,

                    jamMulai: startStr,

                    jamSelesai: endStr,

                    durasi: durasi

                });


                current += durasi;


                /*
                |--------------------------------------------------------------------------
                | CEK ISTIRAHAT
                |--------------------------------------------------------------------------
                */

                if (istirahatMap[jamKe]) {

                    let durasiIst =
                        parseInt(
                            istirahatMap[jamKe]
                        );


                    if (
                        current + durasiIst <= end
                    ) {

                        slots.push({

                            jenis: 'istirahat',

                            jamKe: null,

                            jamMulai:
                                this.minToTime(current),

                            jamSelesai:
                                this.minToTime(
                                    current + durasiIst
                                ),

                            durasi: durasiIst

                        });


                        current += durasiIst;

                    }

                }


                jamKe++;

            }


            return slots;

        },


        /*
        |--------------------------------------------------------------------------
        | SISA WAKTU
        |--------------------------------------------------------------------------
        */

        get sisaMenit() {

            let start =
                this.timeToMin(
                    this.jamMasuk
                );

            let end =
                this.timeToMin(
                    this.jamPulang
                );


            let slots =
                this.generatedSlots;


            if (
                slots.length === 0
            ) {

                return 0;

            }


            let lastSlot =
                slots[slots.length - 1];


            let lastTime =
                this.timeToMin(
                    lastSlot.jamSelesai
                );


            return end - lastTime;

        }

    }

}

</script>

@endsection