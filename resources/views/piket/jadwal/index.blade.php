@extends('layouts.piket')

@section('title', 'Jadwal Piket - Jurnify')
@section('page-title', 'Jadwal Piket')

@section('content')
<div class="activity-header">
    <div>
        <h3 class="activity-title">Jadwal Piket Saya</h3>
        <p class="activity-description">Jadwal piket yang ditugaskan kepada Anda.</p>
    </div>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Guru</th>
                <th>Shift</th>
                <th>Jam</th>
                <th>Tugas</th>
                <th>Posisi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jadwal->tanggal->format('d M Y') }}</td>
                    <td>{{ $jadwal->guru->nama_guru ?? '-' }}</td>
                    <td>{{ $jadwal->shift }}</td>
                    <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                    <td>{{ $jadwal->jenis_tugas }}</td>
                    <td>{{ $jadwal->posisi ?? '-' }}</td>
                    <td>{{ $jadwal->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="empty">Belum ada jadwal piket yang ditugaskan kepada Anda.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
