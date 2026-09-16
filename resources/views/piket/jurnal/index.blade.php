@extends('layouts.piket')

@section('title', 'Jurnal Staff Piket')

@section('content')
<div class="page-header">
    <div>
        <h2>Jurnal</h2>
        <p>Daftar jurnal guru dan status validasi.</p>
    </div>
</div>

<form method="GET" action="{{ route('piket.jurnal.index') }}" style="display:grid; grid-template-columns: 1.7fr 1fr 1fr 1fr auto; gap:12px; margin-bottom:18px; align-items:center;">
    <div style="display:flex; align-items:center; gap:8px; border:1px solid #d1d5db; border-radius:8px; padding:0 10px; background:white;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru atau kelas..." style="flex:1; padding:10px 0; border:none; outline:none; background:transparent;">
        <button type="submit" style="padding:8px 12px; border:none; border-radius:6px; background:#2d4ed8; color:white; font-weight:600; cursor:pointer;">
            Cari
        </button>
        <a href="{{ route('piket.jurnal.index') }}" style="padding:8px 10px; border:1px solid #d1d5db; border-radius:6px; background:white; color:#374151; text-decoration:none; font-weight:600; text-align:center;">
            Refresh
        </a>
    </div>

    <select name="status" style="padding:10px 12px; border:1px solid #d1d5db; border-radius:8px;">
        <option value="">Semua Status</option>
        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        <option value="Perlu Diperbaiki" {{ request('status') == 'Perlu Diperbaiki' ? 'selected' : '' }}>Perlu Diperbaiki</option>
    </select>

    <select name="kelas_id" style="padding:10px 12px; border:1px solid #d1d5db; border-radius:8px;">
        <option value="">Semua Kelas</option>
        @foreach($kelases as $kelas)
            <option value="{{ $kelas->id_kelas }}" {{ request('kelas_id') == $kelas->id_kelas ? 'selected' : '' }}>
                {{ $kelas->nama_kelas }}
            </option>
        @endforeach
    </select>

    <input type="date" name="tanggal" value="{{ request('tanggal') }}" style="padding:10px 12px; border:1px solid #d1d5db; border-radius:8px;">
</form>

<div class="panel">
    <div class="panel-header">
        <h3>Jurnal Guru</h3>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Guru</th>
                <th>Kelas</th>
                <th>Jam</th>
                <th>Materi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnals as $jurnal)
                <tr>
                    <td>{{ $jurnal->guru->nama_guru ?? '-' }}</td>
                    <td>{{ $jurnal->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $jurnal->jamMulai->jam_ke ?? '-' }} - {{ $jurnal->jamSelesai->jam_ke ?? '-' }}</td>
                    <td>{{ $jurnal->materi ?? '-' }}</td>
                    <td>
                        @if($jurnal->status_validasi_guru === 'Menunggu')
                            <span style="padding:4px 8px; background:#dbeafe; color:#1d4ed8; border-radius:999px; font-size:12px;">Menunggu</span>
                        @elseif($jurnal->status_validasi_guru === 'Disetujui')
                            <span style="padding:4px 8px; background:#dcfce7; color:#15803d; border-radius:999px; font-size:12px;">Disetujui</span>
                        @elseif($jurnal->status_validasi_guru === 'Ditolak')
                            <span style="padding:4px 8px; background:#fee2e2; color:#b91c1c; border-radius:999px; font-size:12px;">Ditolak</span>
                        @else
                            <span style="padding:4px 8px; background:#fef3c7; color:#92400e; border-radius:999px; font-size:12px;">{{ $jurnal->status_validasi_guru }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-text">Belum ada jurnal yang sesuai filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
