@extends('layouts.guru')

@section('title', 'Dashboard Guru - Jurnify')

@section('tahun_ajaran', $tahunAjaran)

@section('content')

<div class="welcome">
    <div class="welcome-title">
        Selamat datang, {{ auth()->user()->nama_user ?? '-' }}
    </div>

    <div class="welcome-text">
        Kelola jurnal mengajar Anda melalui Jurnify.
    </div>
</div>


<div class="card">
    <div class="card-title">
        Total Jurnal
    </div>

    <div class="card-subtitle">
        Jumlah seluruh jurnal yang telah Anda buat
    </div>

    <div class="total-number">
        {{ $totalJurnal }}
    </div>

    <div class="total-text">
        Jurnal
    </div>
</div>


<div class="card">

    <div class="section-header">
        <div>
            <div class="card-title">
                Ringkasan Jurnal
            </div>

            <div class="card-subtitle">
                Menampilkan 5 jurnal terbaru
            </div>
        </div>

        @if($totalJurnal > 5)
            <a href="{{ route('guru.jurnal.index') }}">
                Lihat Semua Jurnal
            </a>
        @endif
    </div>


    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kelas</th>
                    <th>Jam Ke</th>
                    <th>Mata Pelajaran</th>
                    <th>Materi</th>
                    <th>Status</th>
                </tr>
            </thead>


            <tbody>

                @forelse($jurnals as $jurnal)

                    <tr>

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
                            <a href="{{ route('guru.jurnal.show', $jurnal->id_jurnal) }}">
                                {{ $jurnal->materi }}
                            </a>
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

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px;">
                            Belum ada jurnal.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection