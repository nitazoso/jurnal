@extends('layouts.piket')

@section('title', 'Detail Jurnal - Jurnify')
@section('page-title', 'Detail Jurnal')

@push('styles')
<style>
    .jurnal-show { --show-navy:#30366f; --show-ink:#1e293b; --show-muted:#64748b; --show-line:#e2e8f0; display:grid; gap:16px; width:100%; max-width:1200px; margin:0 auto; padding:24px; color:var(--show-ink); }
    .jurnal-show, .jurnal-show * { box-sizing:border-box; }
    .show-header { display:flex; align-items:flex-start; justify-content:space-between; gap:20px; padding:22px; border:1px solid #dce4ff; border-left:4px solid #4169ff; border-radius:18px; background:linear-gradient(120deg,#f0f3ff,#fff); }
    .show-heading { min-width:0; }
    .show-eyebrow { margin:0 0 6px; color:var(--show-navy); font-size:10px; font-weight:850; letter-spacing:.09em; text-transform:uppercase; }
    .show-title { margin:0; color:var(--show-ink); font-size:23px; font-weight:850; line-height:1.3; overflow-wrap:anywhere; }
    .show-subtitle { margin:6px 0 0; color:var(--show-muted); font-size:12px; line-height:1.5; }
    .show-header-actions { display:flex; flex:0 0 auto; align-items:center; gap:9px; }
    .show-status { display:inline-flex; min-height:34px; align-items:center; padding:0 11px; border:1px solid #dbe3f0; border-radius:30px; background:#fff; color:var(--show-navy); font-size:11px; font-weight:800; white-space:nowrap; }
    .show-status--approved { border-color:#bbebcd; background:#effaf3; color:#187548; }
    .show-status--rejected { border-color:#f4cccc; background:#fff1f1; color:#a33232; }
    .show-status--revision { border-color:#f1dfb5; background:#fff8e9; color:#8b5a12; }
    .show-back { display:inline-flex; min-height:38px; align-items:center; gap:6px; padding:0 12px; border-radius:10px; background:var(--show-navy); color:#fff; font-size:12px; font-weight:750; text-decoration:none; white-space:nowrap; }
    .show-back:hover { background:#252b5d; }
    .show-panel { min-width:0; padding:20px; border:1px solid var(--show-line); border-radius:17px; background:#fff; box-shadow:0 3px 14px rgba(15,23,42,.035); }
    .show-panel-heading { display:flex; align-items:center; gap:9px; margin:0 0 15px; padding-bottom:12px; border-bottom:1px solid #edf0f5; color:var(--show-navy); font-size:15px; font-weight:850; }
    .show-panel-heading .material-symbols-outlined { font-size:20px; }
    .show-info-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; }
    .show-info-item { min-width:0; padding:12px 13px; border:1px solid #edf0f5; border-radius:12px; background:#f8fafc; }
    .show-info-item dt { margin:0 0 5px; color:#8490a4; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.04em; }
    .show-info-item dd { margin:0; color:#334155; font-size:13px; font-weight:750; line-height:1.5; overflow-wrap:anywhere; }
    .show-copy { margin:0; color:#475569; font-size:13px; line-height:1.75; overflow-wrap:anywhere; white-space:pre-line; }
    .show-copy + .show-copy { margin-top:10px; }
    .show-attendance-summary { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; margin-bottom:16px; }
    .show-attendance-count { padding:13px 15px; border:1px solid #d3efdc; border-radius:13px; background:#effaf3; color:#187548; }
    .show-attendance-count--absent { border-color:#f5dfcc; background:#fff7f0; color:#9a5723; }
    .show-attendance-label { font-size:11px; font-weight:750; }
    .show-attendance-number { margin-top:3px; font-size:23px; font-weight:850; line-height:1.15; }
    .show-absence-title { margin:0 0 10px; color:#334155; font-size:12px; font-weight:850; }
    .show-absence-list { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; }
    .show-absence-item { display:flex; min-width:0; align-items:flex-start; gap:11px; padding:12px; border:1px solid #edf0f5; border-radius:13px; background:#fff; }
    .show-student-avatar { display:flex; width:36px; height:36px; flex:0 0 36px; align-items:center; justify-content:center; border-radius:11px; background:#f1f5f9; color:#475569; font-size:13px; font-weight:850; }
    .show-absence-info { min-width:0; flex:1; }
    .show-student-name { color:#263247; font-size:12px; font-weight:800; line-height:1.45; overflow-wrap:anywhere; }
    .show-student-reason { margin-top:4px; color:#64748b; font-size:11px; line-height:1.5; overflow-wrap:anywhere; }
    .show-absence-badge { display:inline-flex; align-items:center; padding:4px 8px; border-radius:20px; background:#f1f5f9; color:#475569; font-size:9px; font-weight:850; white-space:nowrap; }
    .show-absence-badge--sakit { background:#fff1f2; color:#be435b; }
    .show-absence-badge--izin { background:#eef2ff; color:#4f46a5; }
    .show-absence-badge--alpha { background:#fff1f1; color:#b93838; }
    .show-absence-badge--dispen { background:#fff7e8; color:#a66d17; }
    .show-letter-link { display:inline-flex; margin-top:6px; color:#4f46e5; font-size:10px; font-weight:800; text-decoration:none; }
    .show-letter-link:hover { text-decoration:underline; }
    .show-no-absence { padding:15px; border:1px solid #bbebcd; border-radius:12px; background:#effaf3; color:#187548; font-size:12px; font-weight:700; }
    .show-no-detail { margin-top:10px; color:#9a5723; font-size:11px; line-height:1.6; }
    .show-validation-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; }
    @media(max-width:850px) { .show-info-grid,.show-validation-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:650px) {
        .jurnal-show { gap:12px; padding:14px 11px; }
        .show-header { flex-direction:column; gap:13px; padding:17px; }
        .show-header-actions { width:100%; flex-wrap:wrap; }
        .show-title { font-size:20px; }
        .show-panel { padding:15px; }
        .show-info-grid,.show-validation-grid { grid-template-columns:repeat(2,minmax(0,1fr)); gap:8px; }
        .show-absence-list { grid-template-columns:1fr; }
    }
    @media(max-width:400px) {
        .show-info-grid,.show-validation-grid { grid-template-columns:1fr; }
        .show-attendance-summary { gap:7px; }
        .show-attendance-count { padding:11px; }
        .show-status { font-size:10px; }
        .show-back { font-size:11px; }
    }
</style>
@endpush

@section('content')
@php
    $status = $jurnal->status_validasi_guru ?? '-';
    $statusClass = match ($status) {
        'Disetujui' => 'show-status--approved',
        'Ditolak' => 'show-status--rejected',
        'Perlu Diperbaiki' => 'show-status--revision',
        default => '',
    };
    $absensiTidakHadir = $jurnal->detailAbsensis
        ->filter(fn ($absensi) => mb_strtolower(trim((string) $absensi->status)) !== 'hadir')
        ->values();
@endphp
<main class="jurnal-show">
    <header class="show-header">
        <div class="show-heading">
            <p class="show-eyebrow">Detail Aktivitas Jurnal</p>
            <h1 class="show-title">{{ $jurnal->jadwal?->mapel?->nama_mapel ?? 'Detail Jurnal' }}</h1>
            <p class="show-subtitle">{{ $jurnal->kelas?->nama_kelas ?? '-' }} · {{ $jurnal->tanggal?->translatedFormat('l, d F Y') ?? '-' }}</p>
        </div>
        <div class="show-header-actions"><span class="show-status {{ $statusClass }}">{{ $status }}</span><a href="{{ route('piket.jurnal.rekap') }}" class="show-back"><span aria-hidden="true">←</span>Kembali ke Rekap</a></div>
    </header>

    <section class="show-panel">
        <h2 class="show-panel-heading"><span class="material-symbols-outlined">school</span>Informasi Pembelajaran</h2>
        <dl class="show-info-grid">
            <div class="show-info-item"><dt>Tanggal</dt><dd>{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}</dd></div>
            <div class="show-info-item"><dt>Guru</dt><dd>{{ $jurnal->guru?->nama_guru ?? '-' }}</dd></div>
            <div class="show-info-item"><dt>Kelas</dt><dd>{{ $jurnal->kelas?->nama_kelas ?? '-' }}</dd></div>
            <div class="show-info-item"><dt>Mata Pelajaran</dt><dd>{{ $jurnal->jadwal?->mapel?->nama_mapel ?? '-' }}</dd></div>
            <div class="show-info-item"><dt>Jam Pelajaran</dt><dd>{{ $jurnal->jamMulai?->jam_ke ?? '-' }} – {{ $jurnal->jamSelesai?->jam_ke ?? '-' }}</dd></div>
            <div class="show-info-item"><dt>Status Kehadiran Guru</dt><dd>{{ $jurnal->status_kehadiran_validasi ?? $jurnal->status_guru ?? '-' }}</dd></div>
            @if(($jurnal->status_kehadiran_validasi ?? null) === 'Tidak Hadir')
                <div class="show-info-item"><dt>Alasan Guru Tidak Hadir</dt><dd>{{ $jurnal->status_guru ?? '-' }}</dd></div>
            @endif
        </dl>
    </section>

    <section class="show-panel">
        <h2 class="show-panel-heading"><span class="material-symbols-outlined">menu_book</span>Materi Pembelajaran</h2>
        <p class="show-copy">{{ $jurnal->materi ?: 'Materi belum dicatat.' }}</p>
    </section>

    <section class="show-panel">
        <h2 class="show-panel-heading"><span class="material-symbols-outlined">fact_check</span>Kehadiran Siswa</h2>
        <div class="show-attendance-summary">
            <div class="show-attendance-count"><div class="show-attendance-label">Siswa Hadir</div><div class="show-attendance-number">{{ $jurnal->jml_hadir ?? 0 }}</div></div>
            <div class="show-attendance-count show-attendance-count--absent"><div class="show-attendance-label">Siswa Tidak Hadir</div><div class="show-attendance-number">{{ $jurnal->jml_tidak_hadir ?? 0 }}</div></div>
        </div>
        <h3 class="show-absence-title">Daftar siswa tidak hadir</h3>
        @if($absensiTidakHadir->isNotEmpty())
            <div class="show-absence-list">
                @foreach($absensiTidakHadir as $absensi)
                    @php
                        $attendanceStatus = mb_strtolower(trim((string) $absensi->status));
                        $statusLabel = match ($attendanceStatus) { 'alpha', 'alpa' => 'Alpa', 'izin' => 'Izin', 'sakit' => 'Sakit', 'dispen' => 'Dispen', default => ucfirst($attendanceStatus ?: 'Tidak hadir') };
                        $statusClass = in_array($attendanceStatus, ['alpha', 'alpa', 'izin', 'sakit', 'dispen'], true) ? 'show-absence-badge--' . ($attendanceStatus === 'alpa' ? 'alpha' : $attendanceStatus) : '';
                        $namaSiswa = $absensi->siswa?->nama_siswa ?? 'Nama siswa tidak tersedia';
                    @endphp
                    <article class="show-absence-item">
                        <span class="show-student-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($namaSiswa, 0, 1)) }}</span>
                        <div class="show-absence-info">
                            <div class="show-student-name">{{ $namaSiswa }}</div>
                            @if($absensi->keterangan)<div class="show-student-reason">{{ $absensi->keterangan }}</div>@endif
                            @if($absensi->dispen?->surat_path)<a class="show-letter-link" href="{{ asset('storage/' . $absensi->dispen->surat_path) }}" target="_blank" rel="noopener">Lihat foto surat</a>@endif
                        </div>
                        <span class="show-absence-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </article>
                @endforeach
            </div>
        @else
            <div class="show-no-absence">Tidak ada siswa yang tercatat izin, sakit, atau alpa.</div>
            @if(($jurnal->jml_tidak_hadir ?? 0) > 0)
                <p class="show-no-detail">Jumlah siswa tidak hadir tercatat {{ $jurnal->jml_tidak_hadir }}, tetapi rincian nama/status siswa belum tersedia di data absensi.</p>
            @endif
        @endif
    </section>

    <section class="show-panel">
        <h2 class="show-panel-heading"><span class="material-symbols-outlined">assignment</span>Tugas / Penugasan</h2>
        <p class="show-copy">{{ $jurnal->ada_tugas ?: 'Tidak ada tugas.' }}</p>
        @if($jurnal->deskripsi_tugas)<p class="show-copy">{{ $jurnal->deskripsi_tugas }}</p>@endif
    </section>

    @if($jurnal->catatan_umum || $jurnal->catatan_revisi)
        <section class="show-panel">
            <h2 class="show-panel-heading"><span class="material-symbols-outlined">sticky_note_2</span>Catatan</h2>
            @if($jurnal->catatan_umum)<p class="show-copy">{{ $jurnal->catatan_umum }}</p>@endif
            @if($jurnal->catatan_revisi)<p class="show-copy"><strong>Revisi:</strong> {{ $jurnal->catatan_revisi }}</p>@endif
        </section>
    @endif

    @if($jurnal->validated_at)
        <section class="show-panel">
            <h2 class="show-panel-heading"><span class="material-symbols-outlined">verified</span>Validasi</h2>
            <dl class="show-validation-grid">
                <div class="show-info-item"><dt>Validator</dt><dd>{{ $jurnal->validator?->nama_user ?? '-' }}</dd></div>
                <div class="show-info-item"><dt>Tanggal Validasi</dt><dd>{{ $jurnal->validated_at->copy()->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}</dd></div>
                <div class="show-info-item"><dt>Waktu Validasi</dt><dd>{{ $jurnal->validated_at->copy()->timezone('Asia/Jakarta')->format('H:i') }}</dd></div>
            </dl>
        </section>
    @endif
</main>
@endsection
