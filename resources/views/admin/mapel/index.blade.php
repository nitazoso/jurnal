@extends('layouts.admin')

@section('title', 'Mata Pelajaran')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Mata Pelajaran
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Kelola data mata pelajaran
            </p>
        </div>

        <a href="{{ route('admin.mapel.create') }}"
           class="px-4 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
            + Tambah Mapel
        </a>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error --}}
    @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Statistik --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
        <p class="text-sm text-slate-500">
            Total Mata Pelajaran
        </p>
        <p class="text-3xl font-bold text-slate-800 mt-1">
            {{ $totalMapel }}
        </p>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.mapel.index') }}" class="mb-5">
        <div class="flex gap-3">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama mata pelajaran..."
                class="flex-1 px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none"
            >

            <button
                type="submit"
                class="px-5 py-2.5 bg-slate-800 text-white rounded-lg hover:bg-slate-900">
                Cari
            </button>

            @if(request('search'))
                <a href="{{ route('admin.mapel.index') }}"
                   class="px-5 py-2.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-slate-600">
                            No
                        </th>

                        <th class="py-4 px-6 text-left text-sm font-semibold text-slate-600">
                            Nama Mata Pelajaran
                        </th>

                        <th class="py-4 px-6 text-center text-sm font-semibold text-slate-600">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($mapels as $index => $mapel)

                        <tr class="hover:bg-slate-50">

                            <td class="py-4 px-6 text-slate-600">
                                {{ $mapels->firstItem() + $index }}
                            </td>

                            <td class="py-4 px-6 font-medium text-slate-800">
                                {{ $mapel->nama_mapel }}
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex justify-center gap-2">

                                    <a
                                        href="{{ route('admin.mapel.edit', $mapel->id_mapel) }}"
                                        class="px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.mapel.destroy', $mapel->id_mapel) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100">
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="py-10 text-center text-slate-500">
                                Belum ada data mata pelajaran.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($mapels->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $mapels->links() }}
            </div>
        @endif

    </div>

</div>

@endsection