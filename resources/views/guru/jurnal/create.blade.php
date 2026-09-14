@extends('layouts.guru')

@section('title', 'Isi Jurnal Mengajar - Jurnify')

@section('content')

<div class="card">

    <div class="card-title">
        Isi Jurnal Mengajar
    </div>

    <div class="card-subtitle">
        Pilih jadwal mengajar yang ingin diisi
    </div>

    <br>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($jadwals as $jadwal)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $jadwal->hari }}
                        </td>

                        <td>
                            {{ $jadwal->kelas->nama_kelas ?? '-' }}
                        </td>

                        <td>
                            {{ $jadwal->mapel->nama_mapel ?? '-' }}
                        </td>

                        <td>
                            {{ $jadwal->jamMulai->jam_ke ?? '-' }}
                            -
                            {{ $jadwal->jamSelesai->jam_ke ?? '-' }}
                        </td>

                        <td>

                            <a href="{{ route('guru.jurnal.form', $jadwal->id_jadwal) }}">
                                Isi Jurnal
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" style="text-align: center; padding: 30px;">
                            Belum ada jadwal mengajar.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection