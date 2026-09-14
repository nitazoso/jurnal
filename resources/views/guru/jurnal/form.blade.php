@extends('layouts.guru')

@section('title', 'Isi Jurnal Mengajar - Jurnify')

@section('content')

<div class="card">

    <div class="card-title">
        Isi Jurnal Mengajar
    </div>

    <div class="card-subtitle">
        Silakan isi jurnal sesuai kegiatan pembelajaran
    </div>

    <form action="{{ route('guru.jurnal.store') }}" method="POST">
        @csrf

        <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">

        <div>
            <label>Tahun Ajaran</label>
            <input
                type="text"
                value="{{ $jadwal->tahun_ajaran }}"
                readonly
            >
        </div>

        <div>
            <label>Tanggal</label>
            <input
                type="date"
                name="tanggal"
                value="{{ date('Y-m-d') }}"
                required
            >
        </div>

        <div>
            <label>Kelas</label>
            <input
                type="text"
                value="{{ $jadwal->kelas->nama_kelas ?? '-' }}"
                readonly
            >
        </div>

        <div>
            <label>Jam Pelajaran Ke-</label>
            <input
                type="text"
                value="{{ $jadwal->jamMulai->jam_ke ?? '-' }} - {{ $jadwal->jamSelesai->jam_ke ?? '-' }}"
                readonly
            >
        </div>

        <div>
            <label>Mata Pelajaran</label>
            <input
                type="text"
                value="{{ $jadwal->mapel->nama_mapel ?? '-' }}"
                readonly
            >
        </div>

        <div>
            <label>Materi Pembelajaran</label>
            <textarea
                name="materi"
                required
            ></textarea>
        </div>

        <hr>

        <h3>Absensi Siswa</h3>

        <p>
            Jumlah Siswa: {{ $siswa->count() }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($siswa as $item)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->nis }}
                        </td>

                        <td>
                            {{ $item->nama_siswa }}
                        </td>

                        <td>

                            <select name="absensi[{{ $item->id_siswa }}]">

                                <option value="Hadir">
                                    Hadir
                                </option>

                                <option value="Sakit">
                                    Sakit
                                </option>

                                <option value="Izin">
                                    Izin
                                </option>

                                <option value="Alpha">
                                    Alpha
                                </option>

                                <option value="Dispen">
                                    Dispen
                                </option>

                            </select>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" style="text-align: center; padding: 30px;">
                            Belum ada siswa di kelas ini.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

        <hr>

        <div>
            <label>Status Guru</label>

            <select name="status_guru" required>

                <option value="Hadir">
                    Hadir
                </option>

                <option value="Izin">
                    Izin
                </option>

                <option value="Sakit">
                    Sakit
                </option>

                <option value="Tanpa Keterangan">
                    Tanpa Keterangan
                </option>

            </select>
        </div>

        <div>
            <label>Ada Tugas?</label>

            <select name="ada_tugas" required>

                <option value="Tidak">
                    Tidak
                </option>

                <option value="Ya">
                    Ya
                </option>

            </select>
        </div>

        <div>
            <label>Deskripsi Tugas</label>

            <textarea
                name="deskripsi_tugas"
            ></textarea>
        </div>

        <div>
            <label>Catatan</label>

            <textarea
                name="catatan_umum"
            ></textarea>
        </div>

        <br>

        <a href="{{ route('guru.jurnal.create') }}">
            Batal
        </a>

        <button type="submit">
            Simpan Jurnal
        </button>

    </form>

</div>

@endsection