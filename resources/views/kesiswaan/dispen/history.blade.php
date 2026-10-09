@extends('layouts.kesiswaan')

@section('title', 'Riwayat Dispen - Jurnify')
@section('page-title', 'Riwayat Dispen')

@section('content')
<h2>Riwayat Dispen</h2>
<p><a href="{{ route('kesiswaan.dispen.index') }}">Kembali ke persetujuan dispen</a></p>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Siswa</th>
            <th>Tanggal Dispen</th>
            <th>Alasan</th>
            <th>Status</th>
            <th>Diproses</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dispens as $dispen)
            <tr>
                <td>{{ $dispen->siswa->nama_siswa ?? '-' }}</td>
                <td>{{ $dispen->tanggal?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $dispen->alasan ?: '-' }}</td>
                <td>{{ ucfirst($dispen->status) }}</td>
                <td>
                    {{ $dispen->disetujui_pada?->format('d-m-Y H:i') ?? '-' }}
                    <br>
                    {{ $dispen->approver->nama_user ?? '-' }}
                </td>
                <td><a href="{{ route('kesiswaan.dispen.show', $dispen) }}">Lihat detail</a></td>
            </tr>
        @empty
            <tr><td colspan="6">Belum ada riwayat dispen.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $dispens->links() }}
@endsection