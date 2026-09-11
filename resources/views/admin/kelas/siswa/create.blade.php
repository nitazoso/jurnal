@extends('layouts.admin')

@section('title', 'Tambah Siswa')

@section('content')

<div class="p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            Tambah Siswa
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Menambahkan siswa ke kelas {{ $kelas->nama_kelas }}
        </p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-6">

        <form
            action="{{ route('admin.kelas.siswa.store', $kelas->id_kelas) }}"
            method="POST"
        >

            @csrf

            <div class="space-y-5">

                {{-- NIS --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        NIS
                    </label>

                    <input
                        type="text"
                        name="nis"
                        value="{{ old('nis') }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Masukkan NIS"
                        required
                    >

                    @error('nis')
                        <p class="text-sm text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Siswa
                    </label>

                    <input
                        type="text"
                        name="nama_siswa"
                        value="{{ old('nama_siswa') }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Masukkan nama siswa"
                        required
                    >

                    @error('nama_siswa')
                        <p class="text-sm text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Jenis Kelamin
                    </label>

                    <select
                        name="jenis_kelamin"
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5"
                        required
                    >
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>
                            Laki-laki
                        </option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>

                    @error('jenis_kelamin')
                        <p class="text-sm text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3 pt-3">

                    <a
                        href="{{ route('admin.kelas.siswa.index', $kelas->id_kelas) }}"
                        class="px-5 py-2.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700"
                    >
                        Simpan Siswa
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection