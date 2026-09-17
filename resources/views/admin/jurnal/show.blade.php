@extends('layouts.admin')

@section('title', 'Detail Jurnal - Jurnify')
@section('page-title', 'Detail Jurnal')

@section('content')
<div class="activity-card" style="max-width: 900px;">
    <div class="activity-header" style="display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; flex-wrap: wrap;">
        <div>
            <h3 class="activity-title">Detail Jurnal Pembelajaran</h3>
            <p class="activity-description">Informasi lengkap jurnal yang dibuat oleh guru.</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn-reset" style="padding: 8px 14px; text-decoration: none;">Kembali</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-top: 24px;">
        <div>
            <small>Tanggal</small>
            <p>{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}</p>
        </div>
        <div>
            <small>Guru</small>
            <p>{{ $jurnal->guru->nama_guru ?? '-' }}</p>
        </div>
        <div>
            <small>Mata Pelajaran</small>
            <p>{{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}</p>
        </div>
        <div>
            <small>Kelas</small>
            <p>{{ $jurnal->kelas->nama_kelas ?? '-' }}</p>
        </div>
        <div>
            <small>Jam Pelajaran</small>
            <p>{{ $jurnal->jamMulai->jam_ke ?? '-' }} - {{ $jurnal->jamSelesai->jam_ke ?? '-' }}</p>
        </div>
        <div>
            <small>Status Guru</small>
            <p>{{ $jurnal->status_guru ?? '-' }}</p>
        </div>
        <div>
            <small>Status Validasi</small>
            <p>{{ $jurnal->status_validasi_guru ?? '-' }}</p>
        </div>
        <div>
            <small>Kehadiran</small>
            <p>{{ $jurnal->jml_hadir ?? 0 }} hadir, {{ $jurnal->jml_tidak_hadir ?? 0 }} tidak hadir</p>
        </div>
    </div>

    <div style="margin-top: 24px;">
        <small>Materi</small>
        <p style="margin-top: 6px; white-space: pre-line;">{{ $jurnal->materi ?? '-' }}</p>
    </div>

    @if($jurnal->ada_tugas || $jurnal->deskripsi_tugas)
        <div style="margin-top: 20px;">
            <small>Tugas</small>
            <p style="margin-top: 6px; white-space: pre-line;">{{ $jurnal->deskripsi_tugas ?? 'Ada tugas' }}</p>
        </div>
    @endif

    @if($jurnal->catatan_revisi || $jurnal->catatan_umum)
        <div style="margin-top: 20px;">
            <small>Catatan</small>
            <p style="margin-top: 6px; white-space: pre-line;">{{ $jurnal->catatan_revisi ?: $jurnal->catatan_umum }}</p>
        </div>
    @endif
</div>
@endsection
