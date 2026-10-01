@extends('layouts.piket')

@section('title', 'Jurnal Harian ' . $kelas->nama_kelas . ' - Jurnify')
@section('page-title', 'Jurnal Harian')

@push('styles')
<style>
    .daily-detail { max-width:1000px; margin:0 auto; padding-bottom:125px; color:#1e293b; }
    .daily-detail-head { display:flex; align-items:flex-start; justify-content:space-between; gap:18px; margin-bottom:28px; }
    .daily-detail-head h1 { margin:0; color:#17265d; font-size:24px; font-weight:850; }
    .daily-detail-head p { margin:7px 0 0; color:#64748b; font-size:13px; }
    .daily-back { display:inline-flex; align-items:center; gap:7px; color:#30366f; font-size:12px; font-weight:800; text-decoration:none; white-space:nowrap; }
    .daily-back:hover { color:#1e2945; }
    .daily-timeline { position:relative; }
    .daily-timeline::before { position:absolute; top:17px; bottom:18px; left:83px; width:2px; background:#dbe3f0; content:""; }
    .daily-entry { position:relative; display:grid; grid-template-columns:83px minmax(0,1fr); gap:22px; padding-bottom:23px; }
    .daily-time { position:relative; padding-top:18px; color:#334155; font-size:12px; font-weight:800; line-height:1.45; text-align:right; }
    .daily-time::after { position:absolute; z-index:1; top:21px; right:-28px; width:12px; height:12px; border:3px solid #fff; border-radius:50%; background:#4964ce; box-shadow:0 0 0 2px #c7d2fe; content:""; }
    .daily-entry-card { min-width:0; padding:20px 22px; border:1px solid #e2e8f0; border-radius:15px; background:#fff; box-shadow:0 3px 13px rgba(15,23,42,.045); }
    .daily-entry-title { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; }
    .daily-entry-title h2 { margin:0; color:#202b55; font-size:15px; font-weight:850; }
    .daily-teacher { margin:5px 0 0; color:#64748b; font-size:12px; }
    .daily-review-status { display:inline-flex; align-items:center; gap:5px; padding:6px 9px; border-radius:999px; font-size:10px; font-weight:800; white-space:nowrap; }
    .daily-review-status.approved { background:#ecfdf5; color:#047857; }
    .daily-review-status.rejected { background:#fef2f2; color:#b91c1c; }
    .daily-review-status .material-symbols-outlined { font-size:16px; }
    .daily-rejection-note { margin-top:15px; padding:12px 14px; border:1px solid #fecaca; border-radius:10px; background:#fff7f7; color:#991b1b; }
    .daily-rejection-note strong { display:block; margin-bottom:4px; font-size:11px; }
    .daily-rejection-note p { margin:0; font-size:12px; line-height:1.5; overflow-wrap:anywhere; }
    .daily-field { margin-top:17px; }
    .daily-field-label { margin:0 0 5px; color:#64748b; font-size:10px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; }
    .daily-field-value { margin:0; color:#334155; font-size:13px; line-height:1.55; overflow-wrap:anywhere; }
    .daily-attendance { display:flex; flex-wrap:wrap; gap:8px 16px; margin-top:17px; }
    .daily-attendance-item { display:inline-flex; align-items:center; gap:6px; color:#334155; font-size:12px; font-weight:750; }
    .daily-attendance-item .material-symbols-outlined { color:#059669; font-size:18px; }
    .daily-attendance-item.absent .material-symbols-outlined { color:#d97706; }
    .daily-attendance-item.dispen .material-symbols-outlined { color:#7c3aed; }
    .daily-attendance-item.dispen { color:#6d28d9; }
    .daily-attendance-item.empty .material-symbols-outlined { color:#94a3b8; }
    .daily-attendance-action { margin-top:17px; }
    .daily-attendance-action a { display:inline-flex; min-height:36px; align-items:center; justify-content:center; gap:6px; padding:0 12px; border:1px solid #dbe3f0; border-radius:9px; color:#30366f; font-size:11px; font-weight:800; text-decoration:none; transition:.18s ease; }
    .daily-attendance-action a:hover { border-color:#a5b4fc; background:#f5f7ff; }
    .daily-approval-dock { position:fixed; z-index:95; right:0; bottom:0; left:260px; padding:12px 24px calc(12px + env(safe-area-inset-bottom)); border-top:1px solid #dbe3f0; background:rgba(255,255,255,.97); box-shadow:0 -8px 24px rgba(15,23,42,.08); backdrop-filter:blur(12px); }
    .daily-approval-dock-inner { display:flex; max-width:1000px; align-items:center; justify-content:space-between; gap:18px; margin:0 auto; }
    .daily-approval-dock-copy { min-width:0; }
    .daily-approval-dock-copy strong { display:block; color:#1e293b; font-size:13px; font-weight:850; }
    .daily-approval-dock-copy p { margin:4px 0 0; color:#64748b; font-size:11px; line-height:1.45; }
    .daily-approval-dock-copy.is-approved strong { color:#047857; }
    .daily-approve-button { display:inline-flex; min-height:42px; flex:0 0 auto; align-items:center; justify-content:center; gap:8px; padding:8px 18px; border:0; border-radius:10px; background:#30366f; color:#fff; font:inherit; font-size:12px; font-weight:850; cursor:pointer; transition:background .18s ease; }
    .daily-approve-button:hover { background:#252b5d; }
    .daily-approve-button .material-symbols-outlined { font-size:19px; }
    .daily-empty { padding:32px 20px; border:1px dashed #cbd5e1; border-radius:14px; background:#fff; color:#64748b; text-align:center; }
    @media(max-width:1100px) { .daily-approval-dock { left:230px; } }
    @media(max-width:800px) { .daily-approval-dock { left:200px; } }
    @media(max-width:620px) {
        .daily-approval-dock { padding:11px 14px calc(11px + env(safe-area-inset-bottom)); }
        .daily-approval-dock-inner { align-items:stretch; flex-direction:column; gap:10px; }
        .daily-approve-button { width:100%; }
        .daily-detail-head { flex-direction:column; }
        .daily-timeline::before { left:8px; }
        .daily-entry { grid-template-columns:1fr; gap:8px; padding-left:28px; }
        .daily-time { padding-top:0; text-align:left; }
        .daily-time::after { top:2px; right:auto; left:-25px; }
        .daily-entry-card { padding:16px; }
    }
    @media(max-width:600px) { .daily-approval-dock { left:0; } }
</style>
@endpush

@section('content')
@php
    $tanggalLabel = \Illuminate\Support\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y');
    $jurnalsUntukDiapprove = $jurnals->where('status_validasi_guru', 'Disetujui');
    $jurnalsBelumDiapprove = $jurnalsUntukDiapprove->filter(fn ($jurnal) => ! $jurnal->piket_approved_at);
    $approvalTerbaru = $jurnalsUntukDiapprove
        ->filter(fn ($jurnal) => $jurnal->piket_approved_at)
        ->sortByDesc('piket_approved_at')
        ->first();
@endphp
<div class="daily-detail">
    <header class="daily-detail-head">
        <div>
            <h1>{{ $kelas->nama_kelas }}</h1>
            <p>Jurnal pembelajaran · {{ $tanggalLabel }}</p>
        </div>
        <a class="daily-back" href="{{ route('piket.jurnal-harian.index', ['tanggal' => $tanggal]) }}">
            <span class="material-symbols-outlined">arrow_back</span>Kembali ke Jurnal Harian
        </a>
    </header>

    @if($jurnals->isEmpty())
        <div class="daily-empty">
            @if($adaMenungguVerifikasi)
                Jurnal untuk {{ $kelas->nama_kelas }} pada tanggal ini masih menunggu verifikasi sekretaris.
            @else
                Belum ada jurnal yang selesai diverifikasi untuk {{ $kelas->nama_kelas }} pada tanggal ini.
            @endif
        </div>
    @else
        <div class="daily-timeline">
            @foreach($jurnals as $jurnal)
                @php
                    $jamMulai = $jurnal->jamMulai?->jam_mulai ? substr($jurnal->jamMulai->jam_mulai, 0, 5) : '--:--';
                    $jamSelesai = $jurnal->jamSelesai?->jam_selesai ? substr($jurnal->jamSelesai->jam_selesai, 0, 5) : '--:--';
                    $jumlahDispen = $jurnal->detailAbsensis->filter(fn ($absensi) => mb_strtolower((string) $absensi->status) === 'dispen')->count();
                    $jumlahTidakHadirLain = max(0, (int) ($jurnal->jml_tidak_hadir ?? 0) - $jumlahDispen);
                    $jurnalDitolak = in_array($jurnal->status_validasi_guru, ['Ditolak', 'Perlu Diperbaiki'], true);
                    $statusSekretaris = match ($jurnal->status_validasi_guru) {
                        'Disetujui' => 'Disetujui Sekretaris',
                        'Ditolak' => 'Ditolak Sekretaris',
                        'Perlu Diperbaiki' => 'Perlu Diperbaiki',
                        default => 'Belum Diverifikasi',
                    };
                @endphp
                <article class="daily-entry">
                    <div class="daily-time">{{ $jamMulai }} – {{ $jamSelesai }}</div>
                    <div class="daily-entry-card">
                        <div class="daily-entry-title">
                            <div>
                                <h2>{{ mb_strtoupper($jurnal->jadwal?->mapel?->nama_mapel ?? 'Mata Pelajaran') }}</h2>
                                <p class="daily-teacher">{{ $jurnal->guru?->nama_guru ?? 'Guru belum tercatat' }}</p>
                            </div>
                            <span class="daily-review-status {{ $jurnalDitolak ? 'rejected' : 'approved' }}">
                                <span class="material-symbols-outlined">{{ $jurnalDitolak ? 'cancel' : 'verified' }}</span>{{ $statusSekretaris }}
                            </span>
                        </div>

                        <div class="daily-field">
                            <p class="daily-field-label">Materi</p>
                            <p class="daily-field-value">{{ $jurnal->materi ?: 'Materi belum dicatat.' }}</p>
                        </div>

                        @if($jurnal->keterangan)
                            <div class="daily-field">
                                <p class="daily-field-label">Keterangan</p>
                                <p class="daily-field-value">{{ $jurnal->keterangan }}</p>
                            </div>
                        @endif

                        @if($jurnalDitolak)
                            <div class="daily-rejection-note">
                                <strong>Keterangan Sekretaris</strong>
                                <p>{{ $jurnal->catatan_revisi ?: 'Jurnal ditolak oleh sekretaris tanpa catatan tambahan.' }}</p>
                            </div>
                        @endif

                        <div class="daily-attendance">
                            <span class="daily-attendance-item"><span class="material-symbols-outlined">groups</span>{{ $jurnal->jml_hadir ?? 0 }} Hadir</span>
                            @if($jumlahTidakHadirLain > 0)
                                <span class="daily-attendance-item absent"><span class="material-symbols-outlined">warning</span>{{ $jumlahTidakHadirLain }} Tidak Hadir</span>
                            @endif
                            @if($jumlahDispen > 0)
                                <span class="daily-attendance-item dispen"><span class="material-symbols-outlined">assignment_turned_in</span>{{ $jumlahDispen }} Dispen</span>
                            @endif
                        </div>

                        <div class="daily-attendance-action">
                            <a href="{{ route('piket.jurnal.show', $jurnal) }}"><span class="material-symbols-outlined">fact_check</span>Lihat Kehadiran</a>
                        </div>

                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

@if($jurnalsUntukDiapprove->isNotEmpty())
    <aside class="daily-approval-dock" aria-label="Persetujuan jurnal piket">
        <div class="daily-approval-dock-inner">
            @if($jurnalsBelumDiapprove->isNotEmpty())
                <div class="daily-approval-dock-copy">
                    <strong>{{ $jurnalsBelumDiapprove->count() }} jurnal menunggu approve piket</strong>
                    <p>Hanya jurnal yang sudah disetujui sekretaris yang akan di-approve.</p>
                </div>
                <form method="POST" action="{{ route('piket.jurnal-harian.approve', ['kelas' => $kelas->id_kelas]) }}">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <button class="daily-approve-button" type="submit">
                        <span class="material-symbols-outlined">check</span>Approve Jurnal
                    </button>
                </form>
            @else
                <div class="daily-approval-dock-copy is-approved">
                    <strong>✓ Semua jurnal sudah di-approve</strong>
                    @if($approvalTerbaru)
                        <p>Oleh: {{ $approvalTerbaru->piketApprover?->nama_user ?? 'Pengguna tidak tersedia' }} ·
                            {{ $approvalTerbaru->piket_approved_at->copy()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </aside>
@endif
@endsection
