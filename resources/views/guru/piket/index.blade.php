@extends('layouts.guru')

@section('title', 'Jadwal Piket - Jurnify')
@section('tahun_ajaran', '2026/2027 Ganjil')

@section('content')
<div class="card">
    <div class="section-header">
        <div>
            <div class="card-title">Jadwal Piket Saya</div>
            <div class="card-subtitle">Tugas piket yang diberikan kepada Anda.</div>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Shift</th>
                    <th>Jam</th>
                    <th>Posisi / Tugas</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jadwal)
                    <tr>
                        <td>{{ $jadwal->tanggal->format('d M Y') }}</td>
                        <td>{{ $jadwal->tanggal->translatedFormat('l') }}</td>
                        <td>{{ $jadwal->shift }}</td>
                        <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td>{{ $jadwal->jenis_tugas }}{{ $jadwal->posisi ? ' / '.$jadwal->posisi : '' }}</td>
                        <td>{{ $jadwal->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 30px;">Belum ada jadwal piket yang diberikan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
