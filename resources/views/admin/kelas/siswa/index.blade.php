@extends('layouts.admin')

@section('title', 'Daftar Siswa')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Daftar Siswa
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Kelas {{ $kelas->nama_kelas }}
            </p>
        </div>

        <div class="flex gap-3">
            <a
                href="{{ route('admin.kelas.index') }}"
                class="px-4 py-2.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">
                ← Kembali
            </a>

            <a
                href="{{ route('admin.kelas.siswa.create', $kelas->id_kelas) }}"
                class="px-4 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                + Tambah Siswa
            </a>
        </div>
    </div>

    {{-- Informasi kelas --}}
    <div class="bg-white border border-slate-200 rounded-xl p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div>
                <p class="text-sm text-slate-500">
                    Nama Kelas
                </p>

                <p class="font-semibold text-slate-800 mt-1">
                    {{ $kelas->nama_kelas }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Wali Kelas
                </p>

                <p class="font-semibold text-slate-800 mt-1">
                    {{ $kelas->waliKelas->nama_guru ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Jumlah Siswa
                </p>

                <p class="font-semibold text-slate-800 mt-1">
                    {{ $siswas->count() }} siswa
                </p>
            </div>

        </div>

    </div>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr>

                        <th class="py-4 px-6 text-left text-sm font-semibold text-slate-600">
                            No
                        </th>

                        <th class="py-4 px-6 text-left text-sm font-semibold text-slate-600">
                            NIS
                        </th>

                        <th class="py-4 px-6 text-left text-sm font-semibold text-slate-600">
                            Nama Siswa
                        </th>

                        <th class="py-4 px-6 text-center text-sm font-semibold text-slate-600">
                            Jenis Kelamin
                        </th>

                        <th class="py-4 px-6 text-center text-sm font-semibold text-slate-600">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($siswas as $index => $siswa)

                        <tr class="hover:bg-slate-50">

                            <td class="py-4 px-6 text-slate-600">
                                {{ $index + 1 }}
                            </td>

                            <td class="py-4 px-6 text-slate-700">
                                {{ $siswa->nis }}
                            </td>

                            <td class="py-4 px-6 font-medium text-slate-800">
                                {{ $siswa->nama_siswa }}
                            </td>

                            <td class="py-4 px-6 text-center">

                                @if($siswa->jenis_kelamin === 'L')
                                    Laki-laki
                                @else
                                    Perempuan
                                @endif

                            </td>

                            <td class="py-4 px-6">

                                <div class="flex justify-center gap-2">

                                    <a
                                        href="{{ route('admin.kelas.siswa.edit', [$kelas->id_kelas, $siswa->id_siswa]) }}"
                                        class="px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.kelas.siswa.destroy', [$kelas->id_kelas, $siswa->id_siswa]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">

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

                            <td colspan="5" class="py-10 text-center text-slate-500">
                                Belum ada siswa di kelas ini.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection