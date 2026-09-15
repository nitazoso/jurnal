@extends('layouts.admin')

@section('title', 'Detail Dispen - Jurnify')
@section('page-title', 'Detail Pengajuan Dispen')

@section('content')
<h2>Detail Pengajuan Dispen</h2>
<p><a href="{{ route('kesiswaan.dispen.index') }}">Kembali ke daftar</a></p>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<dl>
    <dt>Siswa</dt>
    <dd>{{ $dispen->siswa->nama_siswa ?? '-' }}</dd>
    <dt>Kelas</dt>
    <dd>{{ $dispen->siswa->kelas->nama_kelas ?? '-' }}</dd>
    <dt>Tanggal</dt>
    <dd>{{ $dispen->tanggal->format('d-m-Y') }}</dd>
    <dt>Jam</dt>
    <dd>{{ $dispen->jamMulai->jam_mulai ?? '-' }} - {{ $dispen->jamSelesai->jam_selesai ?? '-' }}</dd>
    <dt>Alasan</dt>
    <dd>{{ $dispen->alasan }}</dd>
    <dt>Status</dt>
    <dd>{{ ucfirst($dispen->status) }}</dd>
</dl>

@if ($dispen->status === 'menunggu')
    <form action="{{ route('kesiswaan.dispen.approve', $dispen) }}" method="POST">
        @csrf
        <label for="approve-note">Catatan persetujuan (opsional)</label><br>
        <textarea id="approve-note" name="catatan_persetujuan" rows="3"></textarea><br>
        <button type="submit">Setujui Dispen</button>
    </form>

    <form action="{{ route('kesiswaan.dispen.reject', $dispen) }}" method="POST">
        @csrf
        <label for="reject-note">Alasan penolakan</label><br>
        <textarea id="reject-note" name="catatan_persetujuan" rows="3" required></textarea><br>
        <button type="submit">Tolak Dispen</button>
    </form>
@else
    <p>Pengajuan ini sudah {{ $dispen->status }}.</p>
    @if ($dispen->catatan_persetujuan)
        <p>Catatan: {{ $dispen->catatan_persetujuan }}</p>
    @endif
@endif
@endsection
