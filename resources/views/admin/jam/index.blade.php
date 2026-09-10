@extends('layouts.admin')

@section('title', 'Jam Pelajaran - Jurnify')
@section('page-title', 'Jam Pelajaran')

@section('content')

<div class="p-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">

        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Jam Pelajaran
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Kelola waktu jam pelajaran sekolah.
            </p>
        </div>

        <a
            href="{{ route('admin.jam.create') }}"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition"
        >
            <i class="fa-solid fa-plus"></i>
            Tambah Jam
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
                    Daftar Jam Pelajaran
                </h3>

                <p class="text-xs text-slate-400 mt-1">
                    Total {{ $totalJam }} jam pelajaran.
                </p>
            </div>


            {{-- SEARCH --}}
            <form
                action="{{ route('admin.jam.index') }}"
                method="GET"
                class="flex items-center gap-2"
            >

                <div class="relative">

                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari jam..."
                        class="w-64 bg-slate-100 border-0 rounded-xl py-2.5 pl-9 pr-4 text-xs outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                </div>

                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold"
                >
                    Cari
                </button>

                @if(request('search'))

                    <a
                        href="{{ route('admin.jam.index') }}"
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

                        <th class="py-3 px-5">
                            No
                        </th>

                        <th class="py-3 px-5">
                            Kelompok Hari
                        </th>

                        <th class="py-3 px-5 text-center">
                            Jam Ke
                        </th>

                        <th class="py-3 px-5">
                            Jenis
                        </th>

                        <th class="py-3 px-5">
                            Waktu
                        </th>

                        <th class="py-3 px-5 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100 text-xs">

                    @forelse($jamPels as $index => $jam)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="py-4 px-5 text-slate-500">
                                {{ $jamPels->firstItem() + $index }}
                            </td>

                            <td class="py-4 px-5 font-semibold text-slate-800">
                                {{ $jam->klp_hari }}
                            </td>

                            <td class="py-4 px-5 text-center font-bold text-indigo-600">
                                {{ $jam->jam_ke }}
                            </td>

                            <td class="py-4 px-5 text-slate-600">
                                {{ $jam->jenis }}
                            </td>

                            <td class="py-4 px-5 text-slate-600">
                                {{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i') }}
                            </td>

                            <td class="py-4 px-5 text-center">

                                <a
                                    href="{{ route('admin.jam.edit', $jam->id_jam) }}"
                                    class="text-indigo-600 hover:text-indigo-800 font-semibold mr-3"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.jam.destroy', $jam->id_jam) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus jam pelajaran ini?')"
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
                                colspan="6"
                                class="py-10 text-center text-slate-400"
                            >
                                Belum ada data jam pelajaran.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($jamPels->hasPages())

            <div class="mt-6">
                {{ $jamPels->links() }}
            </div>

        @endif

    </div>

</div>

@endsection