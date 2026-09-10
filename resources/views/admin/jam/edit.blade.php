@extends('layouts.admin')

@section('title', 'Edit Jam Pelajaran - Jurnify')
@section('page-title', 'Edit Jam Pelajaran')

@section('content')

<div class="p-8">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Jam Pelajaran
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Perbarui data jam pelajaran.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <form
            action="{{ route('admin.jam.update', $jamPel->id_jam) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- KELOMPOK HARI --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Kelompok Hari
                    </label>

                    <select
                        name="klp_hari"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        required
                    >
                        <option value="Senin-Kamis"
                            {{ old('klp_hari', $jamPel->klp_hari) == 'Senin-Kamis' ? 'selected' : '' }}>
                            Senin-Kamis
                        </option>

                        <option value="Jumat"
                            {{ old('klp_hari', $jamPel->klp_hari) == 'Jumat' ? 'selected' : '' }}>
                            Jumat
                        </option>
                    </select>

                    @error('klp_hari')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- JAM KE --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jam Ke
                    </label>

                    <input
                        type="number"
                        name="jam_ke"
                        value="{{ old('jam_ke', $jamPel->jam_ke) }}"
                        min="1"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        required
                    >

                    @error('jam_ke')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- JENIS --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jenis
                    </label>

                    <select
                        name="jenis"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        required
                    >
                        <option value="Pelajaran"
                            {{ old('jenis', $jamPel->jenis) == 'Pelajaran' ? 'selected' : '' }}>
                            Pelajaran
                        </option>

                        <option value="Istirahat"
                            {{ old('jenis', $jamPel->jenis) == 'Istirahat' ? 'selected' : '' }}>
                            Istirahat
                        </option>
                    </select>

                    @error('jenis')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- JAM MULAI --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jam Mulai
                    </label>

                    <input
                        type="time"
                        name="jam_mulai"
                        value="{{ old('jam_mulai', \Carbon\Carbon::parse($jamPel->jam_mulai)->format('H:i')) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        required
                    >

                    @error('jam_mulai')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- JAM SELESAI --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jam Selesai
                    </label>

                    <input
                        type="time"
                        name="jam_selesai"
                        value="{{ old('jam_selesai', \Carbon\Carbon::parse($jamPel->jam_selesai)->format('H:i')) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        required
                    >

                    @error('jam_selesai')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>


            {{-- TOMBOL --}}
            <div class="flex gap-3 mt-6">

                <a
                    href="{{ route('admin.jam.index') }}"
                    class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-sm font-semibold"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection