@extends('layouts.admin')

@section('title', 'Kelas - Jurnify')
@section('page-title', 'Kelas')

@section('content')

<div class="p-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Manajemen Kelas
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Kelola data kelas dan wali kelas.
            </p>
        </div>

        <a
            href="{{ route('admin.kelas.create') }}"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition"
        >
            <i class="fa-solid fa-plus"></i>
            Tambah Kelas
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- CARD --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        {{-- HEADER CARD --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">

            <div>
                <h3 class="text-lg font-bold text-slate-800">
                    Direktori Kelas
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                    Daftar kelas yang terdaftar di sistem.
                </p>
            </div>

            {{-- SEARCH --}}
            <form
                action="{{ route('admin.kelas.index') }}"
                method="GET"
                class="flex items-center gap-2"
            >
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kelas..."
                        class="w-64 bg-slate-100 border-0 rounded-xl py-2.5 pl-9 pr-4 text-xs outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition"
                >
                    Cari
                </button>

                @if(request('search'))
                    <a
                        href="{{ route('admin.kelas.index') }}"
                        class="text-xs text-slate-500 hover:text-slate-700"
                    >
                        Reset
                    </a>
                @endif
            </form>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="bg-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-6 rounded-l-xl">
                            ID
                        </th>

                        <th class="py-3 px-6">
                            Nama Kelas
                        </th>

                        <th class="py-3 px-6">
                            Wali Kelas
                        </th>

                        <th class="py-3 px-6 text-center">
                            Siswa
                        </th>

                        <th class="py-3 px-6 rounded-r-xl text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-xs">

                    @forelse($kelas as $item)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- ID KELAS --}}
                            <td class="py-4 px-6 font-medium text-slate-600">
                                KLS-{{ str_pad($item->id_kelas, 3, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- NAMA KELAS --}}
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $item->nama_kelas }}
                            </td>

                            {{-- WALI KELAS --}}
                            <td class="py-4 px-6 text-slate-600 font-medium">
                                {{ $item->waliKelas->nama_guru ?? '-' }}
                            </td>

                            {{-- JUMLAH SISWA --}}
                            <td class="py-4 px-6 text-center text-slate-600 font-medium">
                                {{ $item->jumlah_siswa }}
                            </td>

                            {{-- AKSI --}}
<td class="py-4 px-6 text-center">

    <a
        href="{{ route('admin.kelas.siswa.index', $item->id_kelas) }}"
        class="text-green-600 hover:text-green-800 font-semibold mr-3"
    >
        Siswa
    </a>

    <a
        href="{{ route('admin.kelas.edit', $item->id_kelas) }}"
        class="text-indigo-600 hover:text-indigo-800 font-semibold mr-3"
    >
        Edit
    </a>

    <form
        action="{{ route('admin.kelas.destroy', $item->id_kelas) }}"
        method="POST"
        class="inline"
        onsubmit="return confirm('Yakin ingin menghapus kelas ini?')"
    >
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="text-red-600 hover:text-red-800 font-semibold"
        >
            Hapus
        </button>

    </form>

</td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="py-10 text-center text-slate-400"
                            >
                                Belum ada data kelas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection