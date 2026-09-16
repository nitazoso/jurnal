@extends('layouts.guru')

@section('title', 'Daftar Jurnal - Jurnify')

@section('content')

<div class="card">

    <div class="section-header">
        <div>
            <div class="card-title">
                Daftar Jurnal
            </div>

            <div class="card-subtitle">
                Semua jurnal mengajar yang telah Anda buat
            </div>
        </div>

        <a href="{{ route('guru.dashboard') }}">
            Dashboard
        </a>
    </div>


    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kelas</th>
                    <th>Jam Ke</th>
                    <th>Mata Pelajaran</th>
                    <th>Materi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>


            <tbody>

                @forelse($jurnals as $jurnal)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $jurnal->tanggal?->format('d M Y') ?? '-' }}
                        </td>

                        <td>
                            {{ $jurnal->kelas->nama_kelas ?? '-' }}
                        </td>

                        <td>
                            {{ $jurnal->jamMulai->jam_ke ?? '-' }}
                            -
                            {{ $jurnal->jamSelesai->jam_ke ?? '-' }}
                        </td>

                        <td>
                            {{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}
                        </td>

                        <td>
                            {{ $jurnal->materi }}
                        </td>

                        <td>

                            @if($jurnal->status_validasi_guru === 'Menunggu')

                                <span class="status status-menunggu">
                                    Menunggu
                                </span>

                            @elseif($jurnal->status_validasi_guru === 'Valid')

                                <span class="status status-valid">
                                    Valid
                                </span>

                            @else

                                <span class="status status-default">
                                    {{ $jurnal->status_validasi_guru ?? '-' }}
                                </span>

                            @endif

                        </td>

                        <td>
                            <a href="{{ route('guru.jurnal.show', $jurnal->id_jurnal) }}">
                                Detail
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" style="text-align: center; padding: 30px;">
                            Belum ada jurnal.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection