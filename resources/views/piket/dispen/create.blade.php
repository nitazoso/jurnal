@extends('layouts.admin')

@section('content')

<div class="p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Dispen
        </h1>
        <p class="text-gray-500 mt-1">
            Tambahkan data dispensasi siswa
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">

        <form action="{{ route('piket.dispen.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">
                    Siswa
                </label>

                <select name="id_siswa"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                        required>

                    <option value="">-- Pilih Siswa --</option>

                    @foreach ($siswa as $item)
                        <option value="{{ $item->id_siswa }}"
                            {{ old('id_siswa') == $item->id_siswa ? 'selected' : '' }}>
                            {{ $item->nama_siswa }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">
                    Tanggal
                </label>

                <input type="date"
                       name="tanggal"
                       value="{{ old('tanggal', date('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2"
                       required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                <div>
                    <label class="block mb-2 font-medium text-gray-700">
                        Jam Mulai
                    </label>

                    <select name="id_jam_mulai"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2"
                            required>

                        <option value="">-- Pilih Jam Mulai --</option>

                        @foreach ($jamPels as $jam)
                            <option value="{{ $jam->id_jam }}"
                                {{ old('id_jam_mulai') == $jam->id_jam ? 'selected' : '' }}>
                                Jam ke-{{ $jam->jam_ke }}
                                ({{ substr($jam->jam_mulai, 0, 5) }} -
                                {{ substr($jam->jam_selesai, 0, 5) }})
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">
                        Jam Selesai
                    </label>

                    <select name="id_jam_selesai"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2"
                            required>

                        <option value="">-- Pilih Jam Selesai --</option>

                        @foreach ($jamPels as $jam)
                            <option value="{{ $jam->id_jam }}"
                                {{ old('id_jam_selesai') == $jam->id_jam ? 'selected' : '' }}>
                                Jam ke-{{ $jam->jam_ke }}
                                ({{ substr($jam->jam_mulai, 0, 5) }} -
                                {{ substr($jam->jam_selesai, 0, 5) }})
                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="mb-6">
                <label class="block mb-2 font-medium text-gray-700">
                    Alasan Dispen
                </label>

                <textarea name="alasan"
                          rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2"
                          placeholder="Contoh: Mengikuti kegiatan lomba sekolah"
                          required>{{ old('alasan') }}</textarea>
            </div>

            <div class="flex gap-3">

                <a href="{{ route('piket.dispen.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700">
                    Kembali
                </a>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-[#30366f] text-white">
                    Simpan Dispen
                </button>

            </div>

        </form>

    </div>

</div>

@endsection