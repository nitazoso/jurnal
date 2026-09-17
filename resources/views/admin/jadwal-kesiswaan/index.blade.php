@extends('layouts.admin')

@section('title', 'Jadwal Kesiswaan - Jurnify')
@section('page-title', 'Jadwal Kesiswaan')

@section('content')
<div class="activity-header">
    <div>
        <h3 class="activity-title">Jadwal Kesiswaan</h3>
        <p class="activity-description">Atur petugas yang berwenang melakukan ACC dispen setiap hari.</p>
    </div>
    <a href="{{ route('admin.jadwal-kesiswaan.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Petugas Kesiswaan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jadwal->tanggal->format('d M Y') }}</td>
                    <td>{{ $jadwal->tanggal->translatedFormat('l') }}</td>
                    <td>{{ $jadwal->user->nama_user ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.jadwal-kesiswaan.edit', $jadwal) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.jadwal-kesiswaan.destroy', $jadwal) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus jadwal kesiswaan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">Belum ada jadwal kesiswaan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
