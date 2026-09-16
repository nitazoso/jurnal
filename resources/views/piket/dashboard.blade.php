@extends('layouts.piket')

@section('title', 'Dashboard Staff Piket')

@section('content')
<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <p>Selamat datang, {{ auth()->user()->nama_user ?? '-' }}</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card primary">
        <div class="label">Jurnal Hari Ini</div>
        <div class="value">{{ $jurnals->count() }}</div>
    </div>

    <div class="stat-card secondary">
        <div class="label">Dispen Hari Ini</div>
        <div class="value">{{ $dispens->count() }}</div>
    </div>

    <div class="stat-card accent">
        <div class="label">Piket Hari Ini</div>
        <div class="value">{{ $piketHariIni->count() }}</div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h3>Jadwal Piket Hari Ini</h3>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Guru</th>
                <th>Shift</th>
                <th>Jam</th>
                <th>Jabatan</th>
                <th>Posisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($piketHariIni as $jadwal)
                <tr>
                    <td>{{ $jadwal->guru->nama_guru ?? '-' }}</td>
                    <td>{{ $jadwal->shift }}</td>
                    <td>{{ $jadwal->jam_mulai ?? '-' }} - {{ $jadwal->jam_selesai ?? '-' }}</td>
                    <td>{{ $jadwal->jenis_tugas ?? '-' }}</td>
                    <td>{{ $jadwal->posisi ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-text">Belum ada jadwal piket hari ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="panel">
    <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Jurnal Terbaru</h3>
        <a href="{{ route('piket.jurnal.index') }}" style="font-size:13px; color:#2d4ed8; text-decoration:none; font-weight:600;">
            Lihat lainnya
        </a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Guru</th>
                <th>Kelas</th>
                <th>Materi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnals as $jurnal)
                <tr>
                    <td>{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $jurnal->guru->nama_guru ?? '-' }}</td>
                    <td>{{ $jurnal->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $jurnal->materi ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-text">Belum ada jurnal.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
