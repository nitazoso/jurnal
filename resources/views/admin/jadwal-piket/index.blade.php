@extends('layouts.admin')

@section('title', 'Jadwal Piket - Jurnify')
@section('page-title', 'Jadwal Piket')

@section('content')
<div class="activity-header">
    <div>
        <h3 class="activity-title">Jadwal Piket</h3>
        <p class="activity-description">Kelola penugasan jadwal piket guru.</p>
    </div>
    <a href="{{ route('admin.jadwal-piket.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Tanggal</th><th>Guru</th><th>Shift</th><th>Jam</th>
                <th>Tugas</th><th>Posisi</th><th>Keterangan</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jadwal->tanggal?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $jadwal->guru->nama_guru ?? '-' }}</td>
                    <td>{{ $jadwal->shift }}</td>
                    <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                    <td>{{ $jadwal->jenis_tugas }}</td>
                    <td>{{ $jadwal->posisi ?? '-' }}</td>
                    <td>{{ $jadwal->keterangan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.jadwal-piket.edit', $jadwal) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.jadwal-piket.destroy', $jadwal) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus jadwal piket ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="empty">Belum ada jadwal piket.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
