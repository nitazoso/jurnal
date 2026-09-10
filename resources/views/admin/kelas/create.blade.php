@extends('layouts.admin')

@section('title', 'Tambah Kelas - Jurnify')
@section('page-title', 'Tambah Kelas')

@section('content')

<div class="p-8">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Tambah Kelas
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Tambahkan kelas dan tentukan wali kelas.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 max-w-2xl">

        @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf

            {{-- NAMA KELAS --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Nama Kelas
                </label>

                <input
                    type="text"
                    name="nama_kelas"
                    value="{{ old('nama_kelas') }}"
                    placeholder="Contoh: X RPL 1"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                >
            </div>

            {{-- JUMLAH SISWA --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Jumlah Siswa
                </label>

                <input
                    type="number"
                    name="jumlah_siswa"
                    value="{{ old('jumlah_siswa', 0) }}"
                    min="0"
                    placeholder="Contoh: 32"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                >

                <p class="text-xs text-slate-400 mt-2">
                    Masukkan jumlah siswa yang ada di kelas.
                </p>
            </div>

            {{-- WALI KELAS --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Wali Kelas
                </label>

                <select
                    name="wali_kelas"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">
                        -- Pilih Wali Kelas --
                    </option>

                    @foreach ($gurus as $guru)
                        <option
                            value="{{ $guru->id_guru }}"
                            {{ old('wali_kelas') == $guru->id_guru ? 'selected' : '' }}
                        >
                            {{ $guru->nip }} - {{ $guru->nama_guru }}
                        </option>
                    @endforeach

                </select>

                <p class="text-xs text-slate-400 mt-2">
                    Pilih guru yang akan menjadi wali kelas.
                </p>
            </div>

            {{-- BUTTON --}}
            <div class="flex items-center gap-3">

                <a
                    href="{{ route('admin.kelas.index') }}"
                    class="px-5 py-3 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition"
                >
                    Simpan Kelas
                </button>

            </div>

        </form>

    </div>

</div>

@endsection