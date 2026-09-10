@extends('layouts.admin')

@section('title', 'Edit Guru - Jurnify')
@section('page-title', 'Edit Guru')

@section('content')

<div class="p-8">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Guru
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Perbarui data guru.
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

        <form
            action="{{ route('admin.guru.update', $guru->id_guru) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            {{-- NIP --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    NIP
                </label>

                <input
                    type="text"
                    name="nip"
                    value="{{ old('nip', $guru->nip) }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                >
            </div>

            {{-- NAMA --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Nama Guru
                </label>

                <input
                    type="text"
                    name="nama_guru"
                    value="{{ old('nama_guru', $guru->nama_guru) }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                >
            </div>

            {{-- NO HP --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    No. HP
                </label>

                <input
                    type="text"
                    name="no_hp"
                    value="{{ old('no_hp', $guru->no_hp) }}"
                    placeholder="Contoh: 081234567890"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            {{-- BUTTON --}}
            <div class="flex items-center gap-3">

                <a
                    href="{{ route('admin.guru.index') }}"
                    class="px-5 py-3 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection