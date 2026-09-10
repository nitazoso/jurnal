@extends('layouts.admin')

@section('title', 'Edit Jadwal - Jurnify')
@section('page-title', 'Edit Jadwal')

@section('content')

<div class="p-8">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Jadwal
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Perbarui data jadwal mengajar.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

        <form action="{{ route('admin.jadwal.update', $jadwal->id_jadwal) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- GURU --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Guru
                    </label>

                    <select
                        name="id_guru"
                        required
                        class="w-full bg-slate-100 border-0 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">-- Pilih Guru --</option>

                        @foreach($gurus as $guru)
                            <option
                                value="{{ $guru->id_guru }}"
                                {{ old('id_guru', $jadwal->id_guru) == $guru->id_guru ? 'selected' : '' }}
                            >
                                {{ $guru->nip }} - {{ $guru->nama_guru }}
                            </option>
                        @endforeach

                    </select>

                    @error('id_guru')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- MAPEL --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Mata Pelajaran
                    </label>

                    <select
                        name="id_mapel"
                        required
                        class="w-full bg-slate-100 border-0 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">-- Pilih Mata Pelajaran --</option>

                        @foreach($mapels as $mapel)
                            <option
                                value="{{ $mapel->id_mapel }}"
                                {{ old('id_mapel', $jadwal->id_mapel) == $mapel->id_mapel ? 'selected' : '' }}
                            >
                                {{ $mapel->nama_mapel }}
                            </option>
                        @endforeach

                    </select>

                    @error('id_mapel')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KELAS --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Kelas
                    </label>

                    <select
                        name="id_kelas"
                        required
                        class="w-full bg-slate-100 border-0 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">-- Pilih Kelas --</option>

                        @foreach($kelases as $kelas)
                            <option
                                value="{{ $kelas->id_kelas }}"
                                {{ old('id_kelas', $jadwal->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}
                            >
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach

                    </select>

                    @error('id_kelas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- HARI --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Hari
                    </label>

                    <select
                        name="hari"
                        required
                        class="w-full bg-slate-100 border-0 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',] as $hari)
                            <option
                                value="{{ $hari }}"
                                {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}
                            >
                                {{ $hari }}
                            </option>
                        @endforeach
                    </select>

                    @error('hari')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- JAM MULAI --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jam Mulai
                    </label>

                    <select
                        name="id_jam_mulai"
                        required
                        class="w-full bg-slate-100 border-0 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">-- Pilih Jam Mulai --</option>

                        @foreach($jamPels as $jam)
                            <option
                                value="{{ $jam->id_jam }}"
                                {{ old('id_jam_mulai', $jadwal->id_jam_mulai) == $jam->id_jam ? 'selected' : '' }}
                            >
                                Jam {{ $jam->jam_ke }} - {{ $jam->jam_mulai }}
                            </option>
                        @endforeach
                    </select>

                    @error('id_jam_mulai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- JAM SELESAI --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jam Selesai
                    </label>

                    <select
                        name="id_jam_selesai"
                        required
                        class="w-full bg-slate-100 border-0 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">-- Pilih Jam Selesai --</option>

                        @foreach($jamPels as $jam)
                            <option
                                value="{{ $jam->id_jam }}"
                                {{ old('id_jam_selesai', $jadwal->id_jam_selesai) == $jam->id_jam ? 'selected' : '' }}
                            >
                                Jam {{ $jam->jam_ke }} - {{ $jam->jam_selesai }}
                            </option>
                        @endforeach
                    </select>

                    @error('id_jam_selesai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SEMESTER --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Semester
                    </label>

                    <select
                        name="semester"
                        required
                        class="w-full bg-slate-100 border-0 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="Ganjil" {{ old('semester', $jadwal->semester) == 'Ganjil' ? 'selected' : '' }}>
                            Ganjil
                        </option>

                        <option value="Genap" {{ old('semester', $jadwal->semester) == 'Genap' ? 'selected' : '' }}>
                            Genap
                        </option>
                    </select>

                    @error('semester')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TAHUN AJARAN --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tahun Ajaran
                    </label>

                    <input
                        type="text"
                        name="tahun_ajaran"
                        value="{{ old('tahun_ajaran', $jadwal->tahun_ajaran) }}"
                        placeholder="Contoh: 2026/2027"
                        required
                        class="w-full bg-slate-100 border-0 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                    @error('tahun_ajaran')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex items-center gap-3 mt-7">

                <a
                    href="{{ route('admin.jadwal.index') }}"
                    class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold transition"
                >
                    Kembali
                </a>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection