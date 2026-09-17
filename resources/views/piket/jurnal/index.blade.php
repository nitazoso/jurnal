@extends('layouts.piket')

@section('title', 'Jurnal Staff Piket')

@section('content')
<div class="page-header">
    <div>
        <h2>Jurnal</h2>
        <p>Daftar jurnal guru yang telah tervalidasi sekretaris.</p>
    </div>
</div>

<form method="GET" action="{{ route('piket.jurnal.index') }}" style="display:grid; grid-template-columns: 1.7fr 1fr 1fr auto; gap:12px; margin-bottom:18px; align-items:center;">
    <div style="display:flex; align-items:center; gap:8px; border:1px solid #d1d5db; border-radius:8px; padding:0 10px; background:white;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru atau kelas..." style="flex:1; padding:10px 0; border:none; outline:none; background:transparent;">
        <button type="submit" style="padding:8px 12px; border:none; border-radius:6px; background:#2d4ed8; color:white; font-weight:600; cursor:pointer;">
            Cari
        </button>
        <a href="{{ route('piket.jurnal.index') }}" style="padding:8px 10px; border:1px solid #d1d5db; border-radius:6px; background:white; color:#374151; text-decoration:none; font-weight:600; text-align:center;">
            Refresh
        </a>
    </div>

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
                        <span style="padding:4px 8px; background:#dcfce7; color:#15803d; border-radius:999px; font-size:12px;">Tervalidasi</span>
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
