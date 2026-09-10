@extends('layouts.admin')

@section('title', 'Tambah Mata Pelajaran')

@section('content')

<div class="p-6">

    <div class="max-w-2xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">
                Tambah Mata Pelajaran
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Tambahkan data mata pelajaran baru
            </p>
        </div>

        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('admin.mapel.store') }}"
            method="POST"
            class="bg-white rounded-xl border border-slate-200 p-6">

            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Nama Mata Pelajaran
                </label>

                <input
                    type="text"
                    name="nama_mapel"
                    value="{{ old('nama_mapel') }}"
                    placeholder="Contoh: Pemrograman Web"
                    required
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none"
                >
            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('admin.mapel.index') }}"
                    class="px-5 py-2.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection