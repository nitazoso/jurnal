@extends('layouts.admin')

@section('title', 'Dashboard - Jurnify')

@section('page-title', 'Dashboard')

@section('content')

<div class="stat-card">
    <div class="stat-title">Aktivitas Jurnal Hari Ini</div>

    <div class="stat-value">
        <strong>{{ $jurnalHariIni }}</strong>
        <span>Terisi</span>
    </div>

    <span class="today-badge">Hari Ini</span>
</div>

<div class="stat-card">
    <div class="stat-title">Jumlah Guru</div>

    <div class="stat-value">
        <strong>{{ $jumlahGuru }}</strong>
        <span>Terdaftar</span>
    </div>

    <div class="stat-icon">
        <span class="material-symbols-outlined">person</span>
    </div>
</div>

<div class="stat-card">
    <div class="stat-title">Jumlah Kelas</div>

    <div class="stat-value">
        <strong>{{ $jumlahKelas }}</strong>
        <span>Kelas</span>
    </div>

    <div class="stat-icon">
        <span class="material-symbols-outlined">meeting_room</span>
    </div>
</div>

<div class="activity-header">
    <h3 class="activity-title">
        Aktivitas Jurnal Terkini
    </h3>

    <p class="activity-description">
        Daftar jurnal pembelajaran dan status validasi.
    </p>
</div>

<div class="table-wrapper">
    <table>

        <thead>
            <tr>
                <th>NO</th>
                <th>TANGGAL</th>
                <th>MATERI</th>
                <th>KEHADIRAN</th>
                <th>STATUS GURU</th>
                <th>VALIDASI</th>
            </tr>
        </thead>

        <tbody>

            @forelse($jurnalTerbaru as $jurnal)

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $jurnal->tanggal->format('d M Y') }}
                    </td>

                    <td>
                        {{ $jurnal->materi }}
                    </td>

                    <td>
                        {{ $jurnal->jml_hadir }} hadir
                        <br>
                        <small>
                            {{ $jurnal->jml_tidak_hadir }} tidak hadir
                        </small>
                    </td>

                    <td>
                        {{ $jurnal->status_guru }}
                    </td>

                    <td>
                        @if($jurnal->status_validasi_guru === 'Disetujui')

                            <span class="status valid">
                                Disetujui
                            </span>

                        @elseif($jurnal->status_validasi_guru === 'Menunggu')

                            <span class="status waiting">
                                Menunggu
                            </span>

                        @elseif($jurnal->status_validasi_guru === 'Ditolak')

                            <span class="status rejected">
                                Ditolak
                            </span>

                        @else

                            <span class="status waiting">
                                {{ $jurnal->status_validasi_guru }}
                            </span>

                        @endif
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="6" class="empty">
                        Belum ada data jurnal.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>
</div>

@endsection