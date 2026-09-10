@extends('layouts.admin')

@section('title', 'Edit Siswa - Jurnify')
@section('page-title', 'Edit Siswa')

@section('content')

<div class="p-8">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Siswa
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Edit data siswa kelas {{ $kelas->nama_kelas }}
        </p>
    </div>


    {{-- FORM --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <form
            action="{{ route('admin.kelas.siswa.update', [$kelas->id_kelas, $siswa->id_siswa]) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="space-y-5">

                {{-- NIS --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        NIS
                    </label>

                    <input
                        type="text"
                        name="nis"
                        value="{{ old('nis', $siswa->nis) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    >

                    @error('nis')
                        <p class="text-sm text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- NAMA --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama Siswa
                    </label>

                    <input
                        type="text"
                        name="nama_siswa"
                        value="{{ old('nama_siswa', $siswa->nama_siswa) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    >

                    @error('nama_siswa')
                        <p class="text-sm text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- JENIS KELAMIN --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jenis Kelamin
                    </label>

                    <select
                        name="jenis_kelamin"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    >

                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                            Perempuan
                        </option>

                    </select>

                    @error('jenis_kelamin')
                        <p class="text-sm text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- TOMBOL --}}
                <div class="flex gap-3 pt-3">

                    <a
                        href="{{ route('admin.kelas.siswa.index', $kelas->id_kelas) }}"
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

            </div>

        </form>

    </div>

</div>

@endsection