@extends('layouts.admin')

@section('title', 'Edit Jam Pelajaran - Jurnify')
@section('page-title', 'Edit Jam Pelajaran')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')

@php
    $jamPels = collect($jamPels ?? []);

    $pelajaran = $jamPels
        ->where('jenis', 'pelajaran')
        ->sortBy('jam_ke')
        ->values();

    $istirahatLama = $jamPels
        ->where('jenis', 'istirahat')
        ->sortBy('jam_mulai')
        ->values();

    $jamMasukLama = $pelajaran->first()?->jam_mulai
        ? \Carbon\Carbon::parse($pelajaran->first()->jam_mulai)->format('H:i')
        : '07:00';

    $jamPulangLama = $jamPels->max('jam_selesai')
        ? \Carbon\Carbon::parse($jamPels->max('jam_selesai'))->format('H:i')
        : '15:00';

    $durasiStandarLama = $pelajaran->first()?->durasi_menit ?? 40;

    $adaDurasiBerbeda = $pelajaran
        ->pluck('durasi_menit')
        ->filter()
        ->unique()
        ->count() > 1;
@endphp

<div class="p-4 sm:p-6 lg:p-8 bg-slate-50 min-h-screen">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">

        <div>
            <a href="{{ route('admin.jam.index') }}"
               class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition mb-3">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Jam Pelajaran
            </a>

            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Edit Jadwal {{ $klp_hari }}
            </h1>

            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Atur waktu operasional, durasi pembelajaran, dan waktu istirahat.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 self-start lg:self-center
                    bg-indigo-50 border border-indigo-100
                    text-indigo-700 px-4 py-2.5 rounded-xl
                    text-sm font-bold">

            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>

            {{ $klp_hari }}
        </div>

    </div>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200
                    text-rose-800 px-4 py-3 rounded-xl text-sm">

            <div class="flex items-start gap-3">

                <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5"></i>

                <div>
                    <strong class="font-bold">
                        Data belum bisa disimpan.
                    </strong>

                    <ul class="list-disc ml-5 mt-2 space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    @endif


    <form action="{{ route('admin.jam.update', $klp_hari) }}"
          method="POST"
          id="jamEditForm">

        @csrf
        @method('PUT')


        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

            {{-- =========================================================
                 KOLOM KIRI
            ========================================================== --}}
            <div class="xl:col-span-7 space-y-6">


                {{-- WAKTU SEKOLAH --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-xl
                                        bg-indigo-50 text-indigo-600
                                        flex items-center justify-center">

                                <i class="fa-regular fa-clock text-lg"></i>

                            </div>

                            <div>
                                <h2 class="text-base font-bold text-slate-800">
                                    Waktu Sekolah
                                </h2>

                                <p class="text-xs text-slate-400 mt-0.5">
                                    Tentukan batas awal dan akhir kegiatan belajar.
                                </p>
                            </div>

                        </div>

                    </div>

                    <div class="p-5">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label for="jam_masuk"
                                       class="block text-xs font-bold text-slate-600 mb-2">
                                    Jam Masuk
                                </label>

                                <div class="relative">

                                    <i class="fa-regular fa-clock absolute left-3 top-1/2
                                              -translate-y-1/2 text-indigo-400 text-sm"></i>

                                    <input type="time"
                                           id="jam_masuk"
                                           name="jam_masuk"
                                           value="{{ old('jam_masuk', $jamMasukLama) }}"
                                           required
                                           class="w-full h-11 rounded-xl border border-slate-200
                                                  bg-white pl-10 pr-3 text-sm font-bold
                                                  text-slate-700 outline-none
                                                  focus:border-indigo-400
                                                  focus:ring-4 focus:ring-indigo-50 transition">
                                </div>
                            </div>


                            <div>
                                <label for="jam_pulang"
                                       class="block text-xs font-bold text-slate-600 mb-2">
                                    Jam Pulang
                                </label>

                                <div class="relative">

                                    <i class="fa-regular fa-clock absolute left-3 top-1/2
                                              -translate-y-1/2 text-indigo-400 text-sm"></i>

                                    <input type="time"
                                           id="jam_pulang"
                                           name="jam_pulang"
                                           value="{{ old('jam_pulang', $jamPulangLama) }}"
                                           required
                                           class="w-full h-11 rounded-xl border border-slate-200
                                                  bg-white pl-10 pr-3 text-sm font-bold
                                                  text-slate-700 outline-none
                                                  focus:border-indigo-400
                                                  focus:ring-4 focus:ring-indigo-50 transition">
                                </div>
                            </div>

                        </div>


                        <div class="mt-4 flex items-start gap-2
                                    bg-indigo-50/60 border border-indigo-100
                                    rounded-xl px-4 py-3">

                            <i class="fa-solid fa-circle-info
                                      text-indigo-500 text-xs mt-0.5"></i>

                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Jam pelajaran akan dihitung otomatis dari jam masuk
                                sampai jam pulang dengan memperhitungkan waktu istirahat.
                            </p>

                        </div>

                    </div>
                </div>


                {{-- DURASI JAM PELAJARAN --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-xl
                                        bg-blue-50 text-blue-600
                                        flex items-center justify-center">

                                <i class="fa-solid fa-stopwatch text-sm"></i>

                            </div>

                            <div>
                                <h2 class="text-base font-bold text-slate-800">
                                    Durasi Jam Pelajaran
                                </h2>

                                <p class="text-xs text-slate-400 mt-0.5">
                                    Tentukan bagaimana durasi setiap jam dihitung.
                                </p>
                            </div>

                        </div>

                    </div>

                    <div class="p-5">

                        <div class="space-y-3">

                            {{-- SERAGAM --}}
                            <label class="duration-option block cursor-pointer">

                                <input type="radio"
                                       name="mode_durasi"
                                       value="seragam"
                                       class="hidden"
                                       {{ old('mode_durasi', $adaDurasiBerbeda ? 'fleksibel' : 'seragam') === 'seragam' ? 'checked' : '' }}>

                                <div class="option-box flex items-center gap-3
                                            border border-slate-200 rounded-xl p-4
                                            hover:border-indigo-300 hover:bg-indigo-50/30 transition">

                                    <div class="radio-circle w-4 h-4 rounded-full
                                                border-2 border-slate-300 flex-shrink-0">
                                    </div>

                                    <div>
                                        <div class="text-sm font-bold text-slate-700">
                                            Seragam
                                        </div>

                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            Semua jam pelajaran menggunakan durasi yang sama.
                                        </div>
                                    </div>

                                </div>
                            </label>


                            {{-- FLEKSIBEL --}}
                            <label class="duration-option block cursor-pointer">

                                <input type="radio"
                                       name="mode_durasi"
                                       value="fleksibel"
                                       class="hidden"
                                       {{ old('mode_durasi', $adaDurasiBerbeda ? 'fleksibel' : 'seragam') === 'fleksibel' ? 'checked' : '' }}>

                                <div class="option-box flex items-center gap-3
                                            border border-slate-200 rounded-xl p-4
                                            hover:border-indigo-300 hover:bg-indigo-50/30 transition">

                                    <div class="radio-circle w-4 h-4 rounded-full
                                                border-2 border-slate-300 flex-shrink-0">
                                    </div>

                                    <div>
                                        <div class="text-sm font-bold text-slate-700">
                                            Fleksibel
                                        </div>

                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            Gunakan durasi khusus untuk jam tertentu.
                                        </div>
                                    </div>

                                </div>
                            </label>


                            {{-- SESUAIKAN --}}
                            <label class="duration-option block cursor-pointer">

                                <input type="radio"
                                       name="mode_durasi"
                                       value="sesuaikan_pulang"
                                       class="hidden"
                                       {{ old('mode_durasi') === 'sesuaikan_pulang' ? 'checked' : '' }}>

                                <div class="option-box flex items-center gap-3
                                            border border-slate-200 rounded-xl p-4
                                            hover:border-indigo-300 hover:bg-indigo-50/30 transition">

                                    <div class="radio-circle w-4 h-4 rounded-full
                                                border-2 border-slate-300 flex-shrink-0">
                                    </div>

                                    <div>
                                        <div class="text-sm font-bold text-slate-700">
                                            Sesuaikan dengan Jam Pulang
                                        </div>

                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            Jam terakhir disesuaikan jika sisa waktunya lebih pendek.
                                        </div>
                                    </div>

                                </div>
                            </label>

                        </div>


                        <div class="mt-5 max-w-xs">

                            <label for="durasi_jp"
                                   class="block text-xs font-bold text-slate-600 mb-2">
                                Durasi Standar
                            </label>

                            <div class="flex items-center h-11
                                        border border-slate-200 rounded-xl overflow-hidden
                                        focus-within:border-indigo-400
                                        focus-within:ring-4 focus-within:ring-indigo-50">

                                <input type="number"
                                       id="durasi_jp"
                                       name="durasi_jp"
                                       min="5"
                                       max="180"
                                       value="{{ old('durasi_jp', $durasiStandarLama) }}"
                                       class="w-full h-full border-0 outline-none
                                              text-center text-sm font-bold text-slate-700">

                                <span class="px-4 text-xs text-slate-400">
                                    menit
                                </span>

                            </div>

                        </div>

                    </div>
                </div>


                {{-- PENYESUAIAN KHUSUS --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

                    <div class="p-5 border-b border-slate-100 bg-slate-50/50
                                flex items-center justify-between gap-3">

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-xl
                                        bg-amber-50 text-amber-600
                                        flex items-center justify-center">

                                <i class="fa-solid fa-sliders text-sm"></i>

                            </div>

                            <div>
                                <h2 class="text-base font-bold text-slate-800">
                                    Penyesuaian Khusus
                                </h2>

                                <p class="text-xs text-slate-400 mt-0.5">
                                    Dipakai jika ada jam dengan durasi berbeda.
                                </p>
                            </div>

                        </div>

                        <button type="button"
                                id="addSpecialBtn"
                                class="inline-flex items-center gap-1.5
                                       bg-amber-500 hover:bg-amber-600
                                       text-white text-xs font-bold
                                       px-3 py-2 rounded-xl transition shadow-sm">

                            <i class="fa-solid fa-plus text-[10px]"></i>
                            Tambah

                        </button>

                    </div>


                    <div class="p-5">

                        <div id="specialDurationList" class="space-y-2">

                            @foreach ($pelajaran as $jp)

                                @if (($jp->durasi_menit ?? null)
                                    && (int) $jp->durasi_menit !== (int) $durasiStandarLama)

                                    <div class="special-row flex items-center gap-2
                                                bg-slate-50 border border-slate-200
                                                rounded-xl p-3">

                                        <span class="text-xs font-semibold text-slate-500">
                                            Jam ke-
                                        </span>

                                        <input type="number"
                                               min="1"
                                               max="50"
                                               value="{{ $jp->jam_ke }}"
                                               class="period-number w-16 h-9 rounded-lg
                                                      border border-slate-200
                                                      text-center text-xs font-bold
                                                      outline-none focus:border-indigo-400">

                                        <span class="text-slate-400 text-xs">
                                            →
                                        </span>

                                        <input type="number"
                                               min="5"
                                               max="180"
                                               value="{{ $jp->durasi_menit }}"
                                               data-period="{{ $jp->jam_ke }}"
                                               name="durasi_khusus[{{ $jp->jam_ke }}]"
                                               class="special-minutes w-20 h-9 rounded-lg
                                                      border border-slate-200
                                                      text-center text-xs font-bold
                                                      outline-none focus:border-indigo-400">

                                        <span class="text-xs text-slate-400">
                                            menit
                                        </span>

                                        <button type="button"
                                                class="remove-special ml-auto
                                                       w-8 h-8 rounded-lg
                                                       bg-rose-50 text-rose-500
                                                       hover:bg-rose-100 transition">

                                            <i class="fa-solid fa-xmark text-xs"></i>

                                        </button>

                                    </div>

                                @endif

                            @endforeach

                        </div>


                        <div id="emptySpecial"
                             class="py-8 text-center border border-dashed
                                    border-slate-200 rounded-xl">

                            <i class="fa-regular fa-clock
                                      text-2xl text-slate-300 mb-2"></i>

                            <span class="block text-xs font-semibold text-slate-500">
                                Belum ada penyesuaian khusus.
                            </span>

                            <small class="text-[10px] text-slate-400">
                                Tambahkan jika ada jam tertentu yang durasinya berbeda.
                            </small>

                        </div>

                    </div>
                </div>


                {{-- ISTIRAHAT --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

                    <div class="p-5 border-b border-slate-100 bg-slate-50/50
                                flex items-center justify-between gap-3">

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-xl
                                        bg-emerald-50 text-emerald-600
                                        flex items-center justify-center">

                                <i class="fa-solid fa-mug-hot text-sm"></i>

                            </div>

                            <div>
                                <h2 class="text-base font-bold text-slate-800">
                                    Waktu Istirahat
                                </h2>

                                <p class="text-xs text-slate-400 mt-0.5">
                                    Istirahat dimulai setelah jam pelajaran yang dipilih.
                                </p>
                            </div>

                        </div>


                        <button type="button"
                                id="addBreakBtn"
                                class="inline-flex items-center gap-1.5
                                       bg-emerald-600 hover:bg-emerald-700
                                       text-white text-xs font-bold
                                       px-3 py-2 rounded-xl transition shadow-sm">

                            <i class="fa-solid fa-plus text-[10px]"></i>
                            Tambah Istirahat

                        </button>

                    </div>


                    <div class="p-5">

                        <div id="breakList" class="space-y-2">

                            @foreach ($istirahatLama as $index => $ist)

                                @php
                                    $sebelum = $pelajaran
                                        ->filter(function ($jp) use ($ist) {
                                            return strtotime($jp->jam_selesai)
                                                <= strtotime($ist->jam_mulai);
                                        })
                                        ->sortByDesc('jam_ke')
                                        ->first();
                                @endphp

                                <div class="break-row flex items-center gap-3
                                            bg-emerald-50/40
                                            border border-emerald-100
                                            rounded-xl p-3">

                                    <div class="break-number w-8 h-8 rounded-lg
                                                bg-emerald-100 text-emerald-700
                                                flex items-center justify-center
                                                text-xs font-bold flex-shrink-0">

                                        {{ $index + 1 }}

                                    </div>


                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">

                                        <div>

                                            <label class="block text-[10px]
                                                          font-bold text-slate-500 mb-1.5">
                                                Setelah Jam Ke
                                            </label>

                                            <select name="istirahat[{{ $index }}][setelah_jam]"
                                                    class="break-after w-full h-9
                                                           rounded-lg border border-slate-200
                                                           bg-white px-3 text-xs font-semibold
                                                           text-slate-700 outline-none
                                                           focus:border-emerald-400">

                                                @for ($i = 1; $i <= max(20, $pelajaran->count() + 3); $i++)

                                                    <option value="{{ $i }}"
                                                        {{ (int) ($sebelum?->jam_ke ?? 1) === $i ? 'selected' : '' }}>

                                                        Jam {{ $i }}

                                                    </option>

                                                @endfor

                                            </select>

                                        </div>


                                        <div>

                                            <label class="block text-[10px]
                                                          font-bold text-slate-500 mb-1.5">
                                                Durasi
                                            </label>

                                            <div class="flex items-center h-9
                                                        bg-white rounded-lg
                                                        border border-slate-200 overflow-hidden">

                                                <input type="number"
                                                       name="istirahat[{{ $index }}][durasi]"
                                                       min="1"
                                                       max="180"
                                                       value="{{ $ist->durasi_menit }}"
                                                       class="w-full h-full border-0 outline-none
                                                              text-center text-xs font-bold">

                                                <span class="text-[10px] text-slate-400 px-2">
                                                    menit
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    <button type="button"
                                            class="remove-break
                                                   w-8 h-8 rounded-lg
                                                   bg-rose-50 text-rose-500
                                                   hover:bg-rose-100 transition">

                                        <i class="fa-solid fa-xmark text-xs"></i>

                                    </button>

                                </div>

                            @endforeach

                        </div>


                        <div id="emptyBreak"
                             class="{{ $istirahatLama->isEmpty() ? '' : 'hidden' }}
                                    py-8 text-center border border-dashed
                                    border-slate-200 rounded-xl">

                            <i class="fa-solid fa-mug-hot
                                      text-2xl text-slate-300 mb-2"></i>

                            <span class="block text-xs font-semibold text-slate-500">
                                Belum ada waktu istirahat.
                            </span>

                            <small class="text-[10px] text-slate-400">
                                Tambahkan jika sekolah memiliki jeda KBM.
                            </small>

                        </div>

                    </div>
                </div>

            </div>


            {{-- =========================================================
                 KOLOM KANAN - PREVIEW
            ========================================================== --}}
            <div class="xl:col-span-5 xl:sticky xl:top-6 space-y-4">

                <div class="bg-white rounded-2xl border border-slate-200/80
                            shadow-sm overflow-hidden">

                    {{-- PREVIEW HEADER --}}
                    <div class="p-5 border-b border-slate-100
                                flex items-center justify-between">

                        <div>

                            <span class="text-[10px] font-bold
                                         text-slate-400 uppercase tracking-wider">
                                Preview Jadwal
                            </span>

                            <h2 class="text-lg font-bold text-slate-800 mt-1">
                                {{ $klp_hari }}
                            </h2>

                        </div>

                        <span class="inline-flex items-center gap-1.5
                                     bg-emerald-50 text-emerald-700
                                     px-2.5 py-1 rounded-full
                                     text-[10px] font-bold">

                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>

                            Otomatis

                        </span>

                    </div>


                    {{-- SUMMARY --}}
                    <div class="grid grid-cols-2
                                border-b border-slate-100">

                        <div class="p-4 bg-slate-50 border-r border-slate-100">

                            <span class="block text-[10px] text-slate-400 mb-1">
                                Jam Operasional
                            </span>

                            <strong id="summaryTime"
                                    class="text-sm font-bold text-slate-700">
                                {{ $jamMasukLama }} - {{ $jamPulangLama }}
                            </strong>

                        </div>

                        <div class="p-4 bg-slate-50">

                            <span class="block text-[10px] text-slate-400 mb-1">
                                Total Jam
                            </span>

                            <strong id="summaryTotal"
                                    class="text-sm font-bold text-slate-700">
                                {{ $pelajaran->count() }} Jam
                            </strong>

                        </div>

                    </div>


                    {{-- PREVIEW LIST --}}
                    <div id="schedulePreview"
                         class="max-h-[520px] overflow-y-auto">

                        <div class="p-10 text-center text-xs text-slate-400">
                            Menghitung jadwal...
                        </div>

                    </div>


                    {{-- WARNING --}}
                    <div id="remainingWarning"
                         class="hidden mx-4 mb-4
                                bg-amber-50 border border-amber-200
                                rounded-xl p-3">

                        <div class="flex items-start gap-2">

                            <div class="w-6 h-6 rounded-full
                                        bg-amber-100 text-amber-600
                                        flex items-center justify-center
                                        flex-shrink-0">

                                <i class="fa-solid fa-exclamation text-[10px]"></i>

                            </div>

                            <div>

                                <strong class="block text-xs text-amber-800">
                                    Ada sisa waktu
                                </strong>

                                <p id="warningText"
                                   class="text-[10px] text-amber-700 mt-0.5">
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- INFO CARD --}}
                <div class="bg-indigo-50 border border-indigo-100
                            rounded-2xl p-4">

                    <div class="flex items-start gap-3">

                        <div class="w-9 h-9 rounded-xl
                                    bg-indigo-100 text-indigo-600
                                    flex items-center justify-center
                                    flex-shrink-0">

                            <i class="fa-solid fa-circle-info text-sm"></i>

                        </div>

                        <div>

                            <h3 class="text-xs font-bold text-indigo-900">
                                Perubahan Jadwal
                            </h3>

                            <p class="text-[10px] text-indigo-700
                                      leading-relaxed mt-1">
                                Setelah disimpan, jadwal {{ $klp_hari }}
                                yang lama akan dihitung ulang berdasarkan
                                konfigurasi baru.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- FOOTER ACTION --}}
        <div class="mt-6 pt-5 border-t border-slate-200
                    flex flex-col-reverse sm:flex-row
                    sm:justify-end gap-3">

            <a href="{{ route('admin.jam.index') }}"
               class="inline-flex items-center justify-center gap-2
                      h-11 px-5 rounded-xl
                      border border-slate-200 bg-white
                      text-slate-600 text-sm font-bold
                      hover:bg-slate-50 transition">

                <i class="fa-solid fa-xmark text-xs"></i>
                Batal

            </a>


            <button type="submit"
                    class="inline-flex items-center justify-center gap-2
                           h-11 px-5 rounded-xl
                           bg-indigo-600 hover:bg-indigo-700
                           text-white text-sm font-bold
                           shadow-sm transition">

                <i class="fa-solid fa-check text-xs"></i>
                Simpan Perubahan

            </button>

        </div>

    </form>

</div>


{{-- =========================================================
     STYLE TAMBAHAN
========================================================== --}}
<style>

    .duration-option input:checked + .option-box {
        border-color: #a5b4fc;
        background: #eef2ff;
    }

    .duration-option input:checked + .option-box .radio-circle {
        border-color: #4f46e5;
        position: relative;
    }

    .duration-option input:checked + .option-box .radio-circle::after {
        content: "";
        position: absolute;
        width: 6px;
        height: 6px;
        background: #4f46e5;
        border-radius: 999px;
        top: 3px;
        left: 3px;
    }

    .special-row input:focus,
    .break-row input:focus,
    .break-row select:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(99,102,241,.08);
    }

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('jamEditForm');

    const jamMasuk = document.getElementById('jam_masuk');
    const jamPulang = document.getElementById('jam_pulang');
    const durasiJp = document.getElementById('durasi_jp');

    const preview = document.getElementById('schedulePreview');
    const summaryTime = document.getElementById('summaryTime');
    const summaryTotal = document.getElementById('summaryTotal');

    const warning = document.getElementById('remainingWarning');
    const warningText = document.getElementById('warningText');

    const specialList = document.getElementById('specialDurationList');
    const emptySpecial = document.getElementById('emptySpecial');

    const breakList = document.getElementById('breakList');
    const emptyBreak = document.getElementById('emptyBreak');

    let breakIndex = {{ $istirahatLama->count() }};


    /* =====================================================
       TIME
    ====================================================== */

    function timeToMinutes(value) {

        if (!value) return 0;

        const [h, m] = value.split(':').map(Number);

        return (h * 60) + m;
    }


    function minutesToTime(total) {

        total = Math.max(0, total);

        const h = Math.floor(total / 60) % 24;
        const m = total % 60;

        return String(h).padStart(2, '0')
            + ':'
            + String(m).padStart(2, '0');
    }


    /* =====================================================
       MODE
    ====================================================== */

    function getMode() {

        return document.querySelector(
            'input[name="mode_durasi"]:checked'
        )?.value || 'seragam';

    }


    /* =====================================================
       DURASI KHUSUS
    ====================================================== */

    function getSpecialDurations() {

        const result = {};

        specialList
            .querySelectorAll('.special-row')
            .forEach(row => {

                const period = row.querySelector('.period-number')?.value;
                const duration = row.querySelector('.special-minutes')?.value;

                if (period && duration) {

                    result[parseInt(period)] = parseInt(duration);

                }

            });

        return result;
    }


    /* =====================================================
       ISTIRAHAT
    ====================================================== */

    function getBreaks() {

        const result = [];

        breakList
            .querySelectorAll('.break-row')
            .forEach(row => {

                const after = row.querySelector('.break-after')?.value;

                const duration = row.querySelector(
                    'input[name*="[durasi]"]'
                )?.value;

                if (after && duration) {

                    result.push({
                        after: parseInt(after),
                        duration: parseInt(duration)
                    });

                }

            });

        return result;
    }


    /* =====================================================
       HITUNG JADWAL
    ====================================================== */

    function calculateSchedule() {

        const start = timeToMinutes(jamMasuk.value);
        const end = timeToMinutes(jamPulang.value);

        if (!start || !end || end <= start) {

            preview.innerHTML = `
                <div class="p-10 text-center text-xs text-slate-400">
                    Jam masuk dan jam pulang belum valid.
                </div>
            `;

            return;
        }


        let defaultDuration = parseInt(durasiJp.value) || 40;

        const mode = getMode();
        const special = getSpecialDurations();
        const breaks = getBreaks();

        const breakMap = {};

        breaks.forEach(item => {
            breakMap[item.after] = item.duration;
        });


        let current = start;
        let period = 1;

        const rows = [];

        let safety = 0;


        while (current < end && safety < 100) {

            safety++;

            let duration = defaultDuration;


            if (mode === 'fleksibel' && special[period]) {
                duration = special[period];
            }


            let next = current + duration;


            if (mode === 'sesuaikan_pulang' && next > end) {

                duration = end - current;
                next = end;

            }


            if (next > end) {
                break;
            }


            rows.push({
                type: 'lesson',
                period: period,
                start: minutesToTime(current),
                end: minutesToTime(next),
                duration: duration
            });


            current = next;


            if (breakMap[period]) {

                const breakDuration = breakMap[period];

                const breakEnd = current + breakDuration;


                if (breakEnd <= end) {

                    rows.push({
                        type: 'break',
                        start: minutesToTime(current),
                        end: minutesToTime(breakEnd),
                        duration: breakDuration
                    });

                    current = breakEnd;
                }

            }


            period++;
        }


        renderPreview(rows);


        const totalLessons = rows.filter(
            row => row.type === 'lesson'
        ).length;


        summaryTime.textContent =
            jamMasuk.value + ' - ' + jamPulang.value;

        summaryTotal.textContent =
            totalLessons + ' Jam';


        const remaining = end - current;


        if (remaining > 0) {

            warning.classList.remove('hidden');

            warningText.textContent =
                'Masih tersisa ' + remaining +
                ' menit setelah perhitungan jadwal.';

        } else {

            warning.classList.add('hidden');

        }

    }


    /* =====================================================
       RENDER PREVIEW
    ====================================================== */

    function renderPreview(rows) {

        if (!rows.length) {

            preview.innerHTML = `
                <div class="p-10 text-center text-xs text-slate-400">
                    Belum ada jam yang bisa dihitung.
                </div>
            `;

            return;
        }


        preview.innerHTML = rows.map(row => {

            if (row.type === 'break') {

                return `
                    <div class="flex items-center gap-3
                                px-4 py-3
                                bg-emerald-50/60
                                border-b border-emerald-100">

                        <div class="w-12 text-[10px] font-bold
                                    text-emerald-600">
                            IST.
                        </div>

                        <div class="flex-1
                                    font-mono text-[10px]
                                    font-bold text-emerald-700">

                            ${row.start} – ${row.end}

                        </div>

                        <div class="text-[10px] font-semibold
                                    text-emerald-600">

                            ${row.duration} mnt

                        </div>

                    </div>
                `;
            }


            return `
                <div class="flex items-center gap-3
                            px-4 py-3
                            border-b border-slate-100
                            hover:bg-slate-50">

                    <div class="w-12 text-[10px]
                                font-bold text-indigo-600">

                        Jam ${row.period}

                    </div>

                    <div class="flex-1
                                font-mono text-[10px]
                                font-bold text-slate-600">

                        ${row.start} – ${row.end}

                    </div>

                    <div class="text-[10px]
                                font-semibold text-slate-400">

                        ${row.duration} mnt

                    </div>

                </div>
            `;

        }).join('');

    }


    /* =====================================================
       EMPTY STATE
    ====================================================== */

    function refreshEmptyStates() {

        const hasSpecial =
            specialList.querySelector('.special-row');

        const hasBreak =
            breakList.querySelector('.break-row');


        emptySpecial.classList.toggle(
            'hidden',
            !!hasSpecial
        );

        emptyBreak.classList.toggle(
            'hidden',
            !!hasBreak
        );

    }


    /* =====================================================
       TAMBAH DURASI KHUSUS
    ====================================================== */

    function createSpecialRow() {

        const row = document.createElement('div');

        row.className =
            'special-row flex items-center gap-2 bg-slate-50 ' +
            'border border-slate-200 rounded-xl p-3';


        row.innerHTML = `

            <span class="text-xs font-semibold text-slate-500">
                Jam ke-
            </span>

            <input type="number"
                   min="1"
                   max="50"
                   class="period-number w-16 h-9 rounded-lg
                          border border-slate-200
                          text-center text-xs font-bold
                          outline-none">

            <span class="text-slate-400 text-xs">
                →
            </span>

            <input type="number"
                   min="5"
                   max="180"
                   class="special-minutes w-20 h-9 rounded-lg
                          border border-slate-200
                          text-center text-xs font-bold
                          outline-none">

            <span class="text-xs text-slate-400">
                menit
            </span>

            <button type="button"
                    class="remove-special ml-auto
                           w-8 h-8 rounded-lg
                           bg-rose-50 text-rose-500
                           hover:bg-rose-100">

                <i class="fa-solid fa-xmark text-xs"></i>

            </button>
        `;


        const periodInput =
            row.querySelector('.period-number');

        const durationInput =
            row.querySelector('.special-minutes');


        function updateName() {

            const period = periodInput.value;

            if (period) {

                durationInput.name =
                    `durasi_khusus[${period}]`;

            } else {

                durationInput.removeAttribute('name');

            }

            calculateSchedule();
        }


        periodInput.addEventListener(
            'input',
            updateName
        );

        durationInput.addEventListener(
            'input',
            calculateSchedule
        );


        row.querySelector('.remove-special')
            .addEventListener('click', function () {

                row.remove();

                refreshEmptyStates();
                calculateSchedule();

            });


        specialList.appendChild(row);

        refreshEmptyStates();

    }


    /* =====================================================
       TAMBAH ISTIRAHAT
    ====================================================== */

    function createBreakRow() {

        const row = document.createElement('div');

        row.className =
            'break-row flex items-center gap-3 ' +
            'bg-emerald-50/40 border border-emerald-100 ' +
            'rounded-xl p-3';


        const currentIndex = breakIndex++;


        let options = '';

        for (let i = 1; i <= 30; i++) {

            options += `
                <option value="${i}">
                    Jam ${i}
                </option>
            `;

        }


        row.innerHTML = `

            <div class="break-number w-8 h-8 rounded-lg
                        bg-emerald-100 text-emerald-700
                        flex items-center justify-center
                        text-xs font-bold flex-shrink-0">

                ${breakList.querySelectorAll('.break-row').length + 1}

            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2
                        gap-3 flex-1">

                <div>

                    <label class="block text-[10px]
                                  font-bold text-slate-500 mb-1.5">

                        Setelah Jam Ke

                    </label>

                    <select name="istirahat[${currentIndex}][setelah_jam]"
                            class="break-after w-full h-9
                                   rounded-lg border border-slate-200
                                   bg-white px-3 text-xs
                                   font-semibold text-slate-700">

                        ${options}

                    </select>

                </div>


                <div>

                    <label class="block text-[10px]
                                  font-bold text-slate-500 mb-1.5">

                        Durasi

                    </label>

                    <div class="flex items-center h-9
                                bg-white rounded-lg
                                border border-slate-200">

                        <input type="number"
                               name="istirahat[${currentIndex}][durasi]"
                               min="1"
                               max="180"
                               value="15"
                               class="w-full h-full border-0
                                      outline-none text-center
                                      text-xs font-bold">

                        <span class="text-[10px]
                                     text-slate-400 px-2">

                            menit

                        </span>

                    </div>

                </div>

            </div>


            <button type="button"
                    class="remove-break
                           w-8 h-8 rounded-lg
                           bg-rose-50 text-rose-500
                           hover:bg-rose-100">

                <i class="fa-solid fa-xmark text-xs"></i>

            </button>
        `;


        row.querySelector('.break-after')
            .addEventListener(
                'change',
                calculateSchedule
            );


        row.querySelector('input[type="number"]')
            .addEventListener(
                'input',
                calculateSchedule
            );


        row.querySelector('.remove-break')
            .addEventListener('click', function () {

                row.remove();

                renumberBreaks();

                refreshEmptyStates();
                calculateSchedule();

            });


        breakList.appendChild(row);

        refreshEmptyStates();

    }


    function renumberBreaks() {

        breakList
            .querySelectorAll('.break-number')
            .forEach((number, index) => {

                number.textContent = index + 1;

            });

    }


    /* =====================================================
       BUTTON
    ====================================================== */

    document.getElementById('addSpecialBtn')
        .addEventListener(
            'click',
            createSpecialRow
        );


    document.getElementById('addBreakBtn')
        .addEventListener(
            'click',
            createBreakRow
        );


    /* =====================================================
       SPECIAL ROW LAMA
    ====================================================== */

    specialList
        .querySelectorAll('.remove-special')
        .forEach(button => {

            button.addEventListener(
                'click',
                function () {

                    this.closest('.special-row').remove();

                    refreshEmptyStates();
                    calculateSchedule();

                }
            );

        });


    specialList
        .querySelectorAll('input')
        .forEach(input => {

            input.addEventListener(
                'input',
                function () {

                    const row =
                        this.closest('.special-row');

                    const period =
                        row.querySelector('.period-number')?.value;

                    const duration =
                        row.querySelector('.special-minutes');


                    if (duration && period) {

                        duration.name =
                            `durasi_khusus[${period}]`;

                    }

                    calculateSchedule();

                }
            );

        });


    /* =====================================================
       BREAK LAMA
    ====================================================== */

    breakList
        .querySelectorAll('.remove-break')
        .forEach(button => {

            button.addEventListener(
                'click',
                function () {

                    this.closest('.break-row').remove();

                    renumberBreaks();

                    refreshEmptyStates();
                    calculateSchedule();

                }
            );

        });


    breakList
        .querySelectorAll('select, input')
        .forEach(input => {

            input.addEventListener(
                'input',
                calculateSchedule
            );

            input.addEventListener(
                'change',
                calculateSchedule
            );

        });


    /* =====================================================
       MODE DURASI
    ====================================================== */

    document
        .querySelectorAll('input[name="mode_durasi"]')
        .forEach(input => {

            input.addEventListener(
                'change',
                calculateSchedule
            );

        });


    /* =====================================================
       INPUT UTAMA
    ====================================================== */

    [jamMasuk, jamPulang, durasiJp]
        .forEach(input => {

            input.addEventListener(
                'input',
                calculateSchedule
            );

            input.addEventListener(
                'change',
                calculateSchedule
            );

        });


    /* =====================================================
       SUBMIT
    ====================================================== */

    form.addEventListener(
        'submit',
        function (event) {

            const start =
                timeToMinutes(jamMasuk.value);

            const end =
                timeToMinutes(jamPulang.value);


            if (end <= start) {

                event.preventDefault();

                alert(
                    'Jam pulang harus lebih besar dari jam masuk.'
                );

                return;
            }


            specialList
                .querySelectorAll('.special-row')
                .forEach(row => {

                    const period =
                        row.querySelector('.period-number')?.value;

                    const duration =
                        row.querySelector('.special-minutes');


                    if (period && duration) {

                        duration.name =
                            `durasi_khusus[${period}]`;

                    }

                });

        }
    );

    refreshEmptyStates();
    calculateSchedule();

});

</script>

@endsection