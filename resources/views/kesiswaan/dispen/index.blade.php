@extends('layouts.kesiswaan')

@section('title', 'Persetujuan Dispen - Jurnify')
@section('page-title', 'Persetujuan Dispen')

@section('content')
<h2>Pengajuan Dispen</h2>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<p><a href="{{ route('kesiswaan.dashboard') }}">Kembali ke dashboard</a></p>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Siswa</th>
            <th>Tanggal</th>
            <th>Alasan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dispens as $dispen)
            <tr>
                <td>{{ $dispen->siswa->nama_siswa ?? '-' }}</td>
                <td>{{ $dispen->tanggal->format('d-m-Y') }}</td>
                <td>{{ $dispen->alasan }}</td>
                <td>{{ ucfirst($dispen->status) }}</td>
                <td><a href="{{ route('kesiswaan.dispen.show', $dispen) }}">Buka pengajuan</a></td>
            </tr>
        @empty
            <tr><td colspan="5">Belum ada pengajuan dispen.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $dispens->links() }}
@endsection
