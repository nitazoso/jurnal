@extends('layouts.piket')

@section('title', 'Rekap Aktivitas Jurnal - Jurnify')
@section('page-title', 'Rekap Aktivitas Jurnal')

@push('styles')
<style>
    .rekap-page { --rekap-navy:#30366f; --rekap-blue:#4169ff; --rekap-ink:#1e293b; --rekap-muted:#64748b; --rekap-line:#e2e8f0; --rekap-soft:#f8fafc; width:100%; max-width:1500px; margin:0 auto; padding:24px; color:var(--rekap-ink); }
    .rekap-page, .rekap-page * { box-sizing:border-box; }
    .rekap-page a { text-decoration:none; }
    .rekap-header { display:flex; align-items:flex-start; justify-content:space-between; gap:24px; margin-bottom:22px; }
    .rekap-heading { display:flex; align-items:flex-start; gap:12px; min-width:0; }
    .rekap-heading-icon { display:flex; width:42px; height:42px; flex:0 0 42px; align-items:center; justify-content:center; border-radius:13px; background:#eef2ff; color:var(--rekap-navy); }
    .rekap-heading h1 { margin:0; color:var(--rekap-ink); font-size:24px; font-weight:800; line-height:1.25; }
    .rekap-heading p { margin:6px 0 0; color:var(--rekap-muted); font-size:13px; line-height:1.5; }
    .rekap-tabs { display:flex; flex:0 0 auto; gap:4px; padding:5px; border:1px solid var(--rekap-line); border-radius:14px; background:#f8fafc; }
    .rekap-tab { display:inline-flex; min-height:40px; align-items:center; justify-content:center; gap:7px; padding:0 13px; border-radius:10px; color:#64748b; font-size:12px; font-weight:750; white-space:nowrap; transition:.18s ease; }
    .rekap-tab:hover { background:#eef2ff; color:var(--rekap-navy); }
    .rekap-tab.is-active { background:#fff; color:var(--rekap-navy); box-shadow:0 2px 7px rgba(15,23,42,.1); }
    .rekap-tab .material-symbols-outlined { font-size:18px; }
    .rekap-stats { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; margin-bottom:18px; }
    .rekap-stat:first-child { grid-column:1/-1; }
    .rekap-stat { display:flex; min-height:100px; align-items:center; justify-content:space-between; gap:14px; padding:18px 20px; border:1px solid var(--rekap-line); border-radius:18px; background:#fff; box-shadow:0 3px 14px rgba(15,23,42,.035); }
    .rekap-stat-label { color:#64748b; font-size:12px; font-weight:650; }
    .rekap-stat-value { margin-top:5px; color:var(--rekap-ink); font-size:25px; font-weight:850; line-height:1; }
    .rekap-stat-note { margin-top:6px; color:#94a3b8; font-size:10px; }
    .rekap-stat-icon { display:flex; width:42px; height:42px; flex:0 0 42px; align-items:center; justify-content:center; border-radius:14px; background:#eff6ff; color:#2563eb; }
    .rekap-stat:nth-child(2) .rekap-stat-icon { background:#faf5ff; color:#9333ea; }
    .rekap-stat:nth-child(3) .rekap-stat-icon { background:#ecfdf5; color:#059669; }
    .rekap-card { margin-bottom:16px; overflow:hidden; border:1px solid var(--rekap-line); border-radius:18px; background:#fff; box-shadow:0 3px 16px rgba(15,23,42,.035); }
    .rekap-card-pad { padding:20px; }
    .rekap-card.rekap-picker-card { position:relative; z-index:1; overflow:visible; }
    .rekap-card.rekap-picker-card.picker-open { z-index:30; }
    .rekap-card-head { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; margin-bottom:16px; }
    .rekap-card-title { margin:0; color:var(--rekap-ink); font-size:16px; font-weight:800; }
    .rekap-card-description { margin:5px 0 0; color:var(--rekap-muted); font-size:12px; line-height:1.5; }
    .rekap-search { display:block; width:100%; height:42px; padding:0 13px; border:1px solid var(--rekap-line); border-radius:11px; background:#fff; color:#334155; font:inherit; font-size:13px; outline:none; }
    .rekap-search::placeholder { color:#94a3b8; }
    .rekap-search:focus, .rekap-select:focus, .rekap-date:focus { border-color:#818cf8; box-shadow:0 0 0 3px rgba(99,102,241,.12); outline:none; }
    .rekap-quick-picks { display:flex; flex-wrap:wrap; align-items:center; gap:8px; }
    .rekap-quick-link { display:inline-flex; min-height:39px; align-items:center; padding:0 14px; border:1px solid var(--rekap-line); border-radius:11px; background:#f8fafc; color:#475569; font-size:12px; font-weight:750; transition:.18s ease; }
    .rekap-quick-link:hover, .rekap-quick-link.is-selected { border-color:#a5b4fc; background:#eef2ff; color:var(--rekap-navy); }
    .rekap-combo { position:relative; min-width:170px; }
    .rekap-combo-input { display:block; width:100%; min-height:39px; padding:0 12px 0 36px; border:1px dashed #cbd5e1; border-radius:11px; background:#fff; color:#334155; font:inherit; font-size:12px; font-weight:700; outline:none; }
    .rekap-combo-input::placeholder { color:#64748b; opacity:1; }
    .rekap-combo-input:focus { border-style:solid; border-color:#a5b4fc; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
    .rekap-combo-icon { position:absolute; z-index:1; top:50%; left:11px; color:var(--rekap-navy); font-size:18px; pointer-events:none; transform:translateY(-50%); }
    .rekap-picker-menu { position:absolute; z-index:60; top:calc(100% + 7px); left:0; display:none; width:min(360px,calc(100vw - 48px)); max-height:280px; padding:7px; overflow:auto; border:1px solid var(--rekap-line); border-radius:13px; background:#fff; box-shadow:0 14px 35px rgba(15,23,42,.18); }
    .rekap-combo.is-open .rekap-picker-menu { display:block; }
    .rekap-picker-option { display:block; padding:9px 10px; border-radius:8px; color:#334155; font-size:12px; font-weight:650; }
    .rekap-picker-option:hover { background:#eef2ff; color:var(--rekap-navy); }
    .rekap-picker-empty { padding:10px; color:#94a3b8; font-size:11px; }
    .rekap-selected { display:flex; align-items:center; gap:13px; padding:16px 18px; border:1px solid #dbeafe; border-radius:16px; background:#f5f7ff; }
    .rekap-selected-icon { display:flex; width:44px; height:44px; flex:0 0 44px; align-items:center; justify-content:center; border-radius:13px; background:var(--rekap-navy); color:#fff; }
    .rekap-selected-info { min-width:0; flex:1; }
    .rekap-selected-title { margin:0; color:#1e293b; font-size:16px; font-weight:850; }
    .rekap-selected-subtitle { margin:5px 0 0; color:#64748b; font-size:11px; line-height:1.5; }
    .rekap-pill { display:inline-flex; align-items:center; padding:4px 8px; border:1px solid #dbeafe; border-radius:20px; background:#fff; color:var(--rekap-navy); font-size:9px; font-weight:850; text-transform:uppercase; }
    .rekap-download { display:grid; grid-template-columns:minmax(190px,.8fr) minmax(0,2fr); align-items:end; gap:18px; padding:18px; border:1px solid var(--rekap-line); border-radius:16px; background:#fff; }
    .rekap-download-title { margin:0; color:#1e293b; font-size:13px; font-weight:850; }
    .rekap-download-note { margin:5px 0 0; color:#64748b; font-size:11px; line-height:1.55; }
    .rekap-download-form { display:grid; grid-template-columns:minmax(125px,1fr) minmax(125px,1fr) auto auto; align-items:end; gap:9px; }
    .rekap-field { display:block; min-width:0; color:#475569; font-size:10px; font-weight:800; }
    .rekap-date, .rekap-select { display:block; width:100%; height:40px; margin-top:5px; padding:0 10px; border:1px solid var(--rekap-line); border-radius:10px; background:#fff; color:#334155; font:inherit; font-size:12px; }
    .rekap-button { display:inline-flex; min-height:40px; align-items:center; justify-content:center; gap:6px; padding:0 13px; border:1px solid transparent; border-radius:10px; background:var(--rekap-navy); color:#fff; font:inherit; font-size:11px; font-weight:800; white-space:nowrap; cursor:pointer; transition:.18s ease; }
    .rekap-button:hover { background:#252b5d; }
    .rekap-button--light { border-color:var(--rekap-line); background:#fff; color:#334155; }
    .rekap-button--light:hover { border-color:#c7d2fe; background:#f8faff; color:var(--rekap-navy); }
    .rekap-filter-form { display:grid; grid-template-columns:minmax(220px,1fr) minmax(180px,.55fr) auto; align-items:end; gap:11px; }
    .rekap-filter-actions { display:flex; gap:7px; }
    .rekap-filter-actions .rekap-button { min-width:40px; }
    .rekap-button-label { display:inline; }
    .rekap-click-row { cursor:pointer; }
    .rekap-click-row:focus { outline:2px solid #818cf8; outline-offset:-2px; }
    .rekap-journal-mobile { display:none; }
    .rekap-journal-card { display:block; padding:14px; border:1px solid var(--rekap-line); border-radius:15px; background:#fff; color:inherit; text-decoration:none; box-shadow:0 2px 8px rgba(15,23,42,.035); transition:border-color .18s,box-shadow .18s,transform .18s; }
    .rekap-journal-card:hover { transform:translateY(-1px); border-color:#c7d2fe; box-shadow:0 6px 15px rgba(48,54,111,.08); }
    .rekap-journal-mobile-list { display:grid; gap:9px; }
    .rekap-journal-top { display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }
    .rekap-journal-time { color:#334155; font-size:11px; font-weight:800; line-height:1.5; }
    .rekap-journal-time span { display:block; margin-top:2px; color:#94a3b8; font-size:10px; font-weight:600; }
    .rekap-journal-context { min-width:0; margin-top:11px; }
    .rekap-journal-subject { color:var(--rekap-navy); font-size:14px; font-weight:850; line-height:1.4; overflow-wrap:anywhere; }
    .rekap-journal-who { margin-top:4px; color:#64748b; font-size:11px; line-height:1.45; overflow-wrap:anywhere; }
    .rekap-journal-material { margin-top:11px; padding:9px 10px; border-radius:10px; background:#f8fafc; }
    .rekap-journal-material-label { color:#94a3b8; font-size:9px; font-weight:850; letter-spacing:.06em; text-transform:uppercase; }
    .rekap-journal-material-text { margin-top:3px; color:#475569; font-size:11px; line-height:1.5; overflow-wrap:anywhere; }
    .rekap-journal-bottom { display:flex; align-items:center; gap:8px; margin-top:11px; }
    .rekap-journal-count { display:inline-flex; align-items:center; gap:5px; padding:5px 8px; border-radius:8px; background:#effaf3; color:#187548; font-size:10px; font-weight:750; }
    .rekap-journal-count--absent { background:#fff7f0; color:#9a5723; }
    .rekap-journal-arrow { display:flex; width:28px; height:28px; align-items:center; justify-content:center; margin-left:auto; border-radius:9px; background:#f1f5f9; color:#64748b; }
    .rekap-journal-empty { padding:28px 16px; border:1px dashed #cbd5e1; border-radius:14px; color:#64748b; text-align:center; }
    .rekap-table-scroll { width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .rekap-table { width:100%; min-width:950px; border-collapse:separate; border-spacing:0; color:#334155; font-size:12px; }
    .rekap-table th { padding:12px 13px; border-bottom:1px solid var(--rekap-line); background:#f8fafc; color:#64748b; text-align:left; text-transform:uppercase; letter-spacing:.04em; font-size:10px; font-weight:850; white-space:nowrap; }
    .rekap-table td { padding:12px 13px; border-bottom:1px solid #f1f5f9; vertical-align:top; line-height:1.5; }
    .rekap-table tbody tr:last-child td { border-bottom:0; }
    .rekap-table tbody tr:hover { background:#fafbff; }
    .rekap-date-cell { min-width:105px; white-space:nowrap; }
    .rekap-subtext { display:block; margin-top:3px; color:#94a3b8; font-size:10px; }
    .rekap-subject { display:inline-flex; padding:4px 8px; border-radius:7px; background:#eef2ff; color:var(--rekap-navy); font-size:10px; font-weight:750; }
    .rekap-status { display:inline-flex; padding:4px 8px; border:1px solid #e2e8f0; border-radius:20px; background:#f8fafc; color:#475569; font-size:10px; font-weight:750; white-space:nowrap; }
    .rekap-detail-link { color:#4f46e5; font-size:11px; font-weight:800; white-space:nowrap; }
    .rekap-detail-link:hover { color:#312e81; text-decoration:underline; }
    .rekap-empty { padding:38px 20px !important; color:#64748b; text-align:center; }
    .rekap-empty-icon { display:flex; width:48px; height:48px; align-items:center; justify-content:center; margin:0 auto 11px; border-radius:15px; background:#f1f5f9; color:#94a3b8; }
    .rekap-empty-title { color:#334155; font-size:14px; font-weight:800; }
    .rekap-empty-note { max-width:460px; margin:5px auto 0; color:#94a3b8; font-size:11px; line-height:1.6; }
    @media (max-width:1100px) {
        .rekap-header { flex-direction:column; }
        .rekap-tabs { max-width:100%; overflow-x:auto; }
        .rekap-download { grid-template-columns:minmax(0,1fr); gap:13px; }
        .rekap-filter-form { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .rekap-filter-form .rekap-search-wrap { grid-column:1/-1; }
    }
    @media (max-width:700px) {
        .rekap-heading { display:none; }
        .rekap-page { padding:16px 12px; }
        .rekap-heading h1 { font-size:21px; }
        .rekap-tabs { display:grid; width:100%; grid-template-columns:repeat(3,minmax(0,1fr)); }
        .rekap-tab { gap:5px; padding:0 5px; font-size:10px; }
        .rekap-tab .material-symbols-outlined { font-size:16px; }
        .rekap-stats { grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; }
        .rekap-stat:first-child { grid-column:1/-1; }
        .rekap-stat { min-height:78px; padding:14px 16px; }
        .rekap-stat-value { font-size:22px; }
        .rekap-card-pad { padding:15px; }
        .rekap-quick-picks { gap:6px; }
        .rekap-quick-link, .rekap-combo-input { min-height:36px; font-size:11px; }
        .rekap-combo { min-width:150px; }
        .rekap-download-form { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .rekap-download-form .rekap-button { width:100%; }
        .rekap-filter-form { grid-template-columns:minmax(0,1fr) minmax(102px,.8fr) auto; gap:5px; }
        .rekap-filter-form .rekap-search-wrap { grid-column:auto; }
        .rekap-filter-actions { gap:4px; }
        .rekap-filter-actions .rekap-button { width:35px; min-width:35px; min-height:38px; padding:0; }
        .rekap-button-label { display:none; }
        .rekap-filter-form .rekap-search, .rekap-filter-form .rekap-select { height:38px; margin-top:4px; padding:0 7px; font-size:11px; }
        .rekap-journal-desktop { display:none; }
        .rekap-journal-mobile { display:block; }

    }
    @media (max-width:440px) {
        .rekap-page { padding:13px 9px; }
        .rekap-heading-icon { width:36px; height:36px; flex-basis:36px; }
        .rekap-heading h1 { font-size:19px; }
        .rekap-heading p { font-size:11px; }
        .rekap-tab .material-symbols-outlined { display:none; }
        .rekap-quick-link { max-width:100%; }
        .rekap-download-form { grid-template-columns:1fr; }
        .rekap-download-form .rekap-button { width:100%; }
    }
</style>
@endpush

@section('content')
@php($currentView = request('view', 'kelas'))
<div class="rekap-page">
    <header class="rekap-header">
        <div class="rekap-heading">
            <span class="rekap-heading-icon"><span class="material-symbols-outlined">menu_book</span></span>
            <div><h1>Rekap Aktivitas Jurnal</h1><p>Pilih kelas atau guru untuk melihat dan mengunduh laporan aktivitas jurnal.</p></div>
        </div>
        <nav class="rekap-tabs" aria-label="Jenis rekap">
            <a class="rekap-tab {{ $currentView === 'kelas' ? 'is-active' : '' }}" href="{{ route('piket.jurnal.rekap', ['view' => 'kelas']) }}"><span class="material-symbols-outlined">school</span>Rekap Kelas</a>
            <a class="rekap-tab {{ $currentView === 'guru' ? 'is-active' : '' }}" href="{{ route('piket.jurnal.rekap', ['view' => 'guru']) }}"><span class="material-symbols-outlined">person</span>Rekap Guru</a>
            <a class="rekap-tab {{ $currentView === 'semua' ? 'is-active' : '' }}" href="{{ route('piket.jurnal.rekap', ['view' => 'semua']) }}"><span class="material-symbols-outlined">summarize</span>Semua Aktivitas</a>
        </nav>
    </header>

    <section class="rekap-stats" aria-label="Ringkasan jurnal">
        <article class="rekap-stat"><div><div class="rekap-stat-label">Total Jurnal</div><div class="rekap-stat-value">{{ $totalJurnal ?? 0 }}</div><div class="rekap-stat-note">Seluruh status jurnal</div></div><span class="rekap-stat-icon"><span class="material-symbols-outlined">description</span></span></article>
        <article class="rekap-stat"><div><div class="rekap-stat-label">Total Kelas</div><div class="rekap-stat-value">{{ $totalKelas ?? 0 }}</div><div class="rekap-stat-note">Kelas terdaftar</div></div><span class="rekap-stat-icon"><span class="material-symbols-outlined">groups</span></span></article>
        <article class="rekap-stat"><div><div class="rekap-stat-label">Jurnal Hari Ini</div><div class="rekap-stat-value">{{ $jurnalHariIni ?? 0 }}</div><div class="rekap-stat-note">Seluruh status jurnal</div></div><span class="rekap-stat-icon"><span class="material-symbols-outlined">today</span></span></article>
    </section>

    @if($currentView === 'semua')
        <section class="rekap-card">
            <div class="rekap-card-pad rekap-card-head"><div><h2 class="rekap-card-title">Semua Aktivitas Jurnal</h2><p class="rekap-card-description">Daftar jurnal dari seluruh kelas dan guru.</p></div><span class="rekap-pill">{{ $jurnals->count() }} aktivitas</span></div>
            <div class="rekap-table-scroll rekap-journal-desktop"><table class="rekap-table"><thead><tr><th>No</th><th>Tanggal / Jam</th><th>Guru</th><th>Kelas</th><th>Mapel</th><th>Materi</th><th>Hadir</th><th>Status</th><th></th></tr></thead><tbody>
                @forelse($jurnals as $jurnal)
                    <tr class="rekap-click-row" role="link" tabindex="0" onclick="if (!event.target.closest('a')) window.location.href='{{ route('piket.jurnal.show', $jurnal) }}'" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href='{{ route('piket.jurnal.show', $jurnal) }}'; }">
                        <td data-label="No">{{ $loop->iteration }}</td><td data-label="Tanggal / Jam" class="rekap-date-cell">{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}<span class="rekap-subtext">{{ $jurnal->jamMulai?->jam_ke ?? '-' }}{{ $jurnal->jamSelesai?->jam_ke ? ' – '.$jurnal->jamSelesai->jam_ke : '' }}</span></td><td data-label="Guru">{{ $jurnal->guru?->nama_guru ?? '-' }}</td><td data-label="Kelas">{{ $jurnal->kelas?->nama_kelas ?? '-' }}</td><td data-label="Mapel"><span class="rekap-subject">{{ $jurnal->jadwal?->mapel?->nama_mapel ?? '-' }}</span></td><td data-label="Materi">{{ $jurnal->materi ?: '-' }}</td><td data-label="Hadir">{{ $jurnal->jml_hadir ?? 0 }}</td><td data-label="Status"><span class="rekap-status">{{ $jurnal->status_validasi_guru ?? '-' }}</span></td><td data-label="Detail"><a class="rekap-detail-link" href="{{ route('piket.jurnal.show', $jurnal) }}">Buka detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="9"><div class="rekap-empty"><span class="rekap-empty-icon"><span class="material-symbols-outlined">event_busy</span></span><div class="rekap-empty-title">Belum ada aktivitas jurnal</div><p class="rekap-empty-note">Jurnal akan tampil di sini setelah guru mengisi aktivitas mengajar.</p></div></td></tr>
                @endforelse
            </tbody></table></div>
            <div class="rekap-journal-mobile">
                <div class="rekap-journal-mobile-list">
                    @forelse($jurnals as $jurnal)
                        <a class="rekap-journal-card" href="{{ route('piket.jurnal.show', $jurnal) }}">
                            <div class="rekap-journal-top"><div class="rekap-journal-time">{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}<span>Jam {{ $jurnal->jamMulai?->jam_ke ?? '-' }}{{ $jurnal->jamSelesai?->jam_ke ? '–'.$jurnal->jamSelesai->jam_ke : '' }}</span></div><span class="rekap-status">{{ $jurnal->status_validasi_guru ?? '-' }}</span></div>
                            <div class="rekap-journal-context"><div class="rekap-journal-subject">{{ $jurnal->jadwal?->mapel?->nama_mapel ?? 'Mata pelajaran belum diisi' }}</div><div class="rekap-journal-who">{{ $jurnal->guru?->nama_guru ?? '-' }} · {{ $jurnal->kelas?->nama_kelas ?? '-' }}</div></div>
                            <div class="rekap-journal-material"><div class="rekap-journal-material-label">Materi</div><div class="rekap-journal-material-text">{{ $jurnal->materi ?: 'Materi belum dicatat.' }}</div></div>
                            <div class="rekap-journal-bottom"><span class="rekap-journal-count">Hadir {{ $jurnal->jml_hadir ?? 0 }}</span><span class="rekap-journal-count rekap-journal-count--absent">Tidak hadir {{ $jurnal->jml_tidak_hadir ?? 0 }}</span><span class="rekap-journal-arrow"><span class="material-symbols-outlined">arrow_forward</span></span></div>
                        </a>
                    @empty
                        <div class="rekap-journal-empty">Belum ada aktivitas jurnal.</div>
                    @endforelse
                </div>
            </div>
        </section>
    @elseif($currentView === 'kelas')
        <section class="rekap-card rekap-card-pad rekap-picker-card">
            <div class="rekap-card-head"><div><h2 class="rekap-card-title">Pilih Kelas</h2><p class="rekap-card-description">Pilih satu kelas untuk melihat jurnal dan mengunduh laporannya.</p></div></div>
            <div class="rekap-quick-picks">
                @foreach($kelases->take(5) as $kelas)
                    <a class="rekap-quick-link {{ $selectedKelas && $selectedKelas->id_kelas == $kelas->id_kelas ? 'is-selected' : '' }}" href="{{ route('piket.jurnal.rekap', ['view' => 'kelas', 'id_kelas' => $kelas->id_kelas]) }}">{{ $kelas->nama_kelas }}</a>
                @endforeach
                @if($kelases->count() > 5)
                    <div class="rekap-combo">
                        <span class="material-symbols-outlined rekap-combo-icon">search</span>
                        <input class="rekap-combo-input" type="search" data-picker-search="kelas-options" placeholder="Ketik nama kelas…" aria-label="Cari dan pilih kelas" aria-controls="kelas-options" aria-expanded="false" aria-autocomplete="list" role="combobox" autocomplete="off">
                        <div class="rekap-picker-menu" id="kelas-options" role="listbox">
                            @foreach($kelases->slice(5) as $kelas)
                                <a class="rekap-picker-option" data-picker-option data-search-name="{{ mb_strtolower($kelas->nama_kelas) }}" role="option" href="{{ route('piket.jurnal.rekap', ['view' => 'kelas', 'id_kelas' => $kelas->id_kelas]) }}">{{ $kelas->nama_kelas }}</a>
                            @endforeach
                            <span class="rekap-picker-empty" hidden>Tidak ada kelas yang cocok.</span>
                        </div>
                    </div>
                @endif
                @if($kelases->isEmpty())<span class="rekap-empty-title">Belum ada kelas terdaftar.</span>@endif
            </div>
        </section>
        @if($selectedKelas)
            <section class="rekap-card rekap-card-pad">
                <div class="rekap-selected"><span class="rekap-selected-icon"><span class="material-symbols-outlined">school</span></span><div class="rekap-selected-info"><h2 class="rekap-selected-title">Jurnal Kelas {{ $selectedKelas->nama_kelas }}</h2><p class="rekap-selected-subtitle">{{ $selectedKelas->waliKelas ? 'Wali kelas: '.$selectedKelas->waliKelas->nama_guru.' · ' : '' }}{{ $jurnals->count() }} jurnal pada filter saat ini</p></div><span class="rekap-pill">Kelas</span></div>
            </section>
            @include('piket.jurnal.partials.docx-controls', ['jenis' => 'kelas', 'objek' => $selectedKelas, 'judulObjek' => 'kelas '.$selectedKelas->nama_kelas])
            <section class="rekap-card rekap-card-pad">
                <div class="rekap-card-head"><div><h2 class="rekap-card-title">Jurnal Mengajar</h2><p class="rekap-card-description">Daftar aktivitas untuk kelas {{ $selectedKelas->nama_kelas }}.</p></div><span class="rekap-pill">{{ $jurnals->count() }} jurnal</span></div>
                @include('piket.jurnal.partials.filters', ['idKelas' => $selectedKelas->id_kelas, 'idGuru' => null])
                @include('piket.jurnal.partials.table', ['jurnals' => $jurnals, 'jenis' => 'kelas'])
            </section>
        @else
            <section class="rekap-card"><div class="rekap-empty"><span class="rekap-empty-icon"><span class="material-symbols-outlined">touch_app</span></span><div class="rekap-empty-title">Pilih satu kelas untuk mulai</div><p class="rekap-empty-note">Gunakan kartu kelas di atas. Setelah kelas dipilih, ringkasan jurnal dan tombol unduh khusus kelas itu akan muncul.</p></div></section>
        @endif
    @elseif($currentView === 'guru')
        <section class="rekap-card rekap-card-pad rekap-picker-card">
            <div class="rekap-card-head"><div><h2 class="rekap-card-title">Pilih Guru</h2><p class="rekap-card-description">Pilih satu guru untuk melihat jurnal yang dibuat dan mengunduh laporannya.</p></div></div>
            <div class="rekap-quick-picks">
                @foreach($gurus->take(5) as $guru)
                    <a class="rekap-quick-link {{ $selectedGuru && $selectedGuru->id_guru == $guru->id_guru ? 'is-selected' : '' }}" href="{{ route('piket.jurnal.rekap', ['view' => 'guru', 'id_guru' => $guru->id_guru]) }}">{{ $guru->nama_guru }}</a>
                @endforeach
                @if($gurus->count() > 5)
                    <div class="rekap-combo">
                        <span class="material-symbols-outlined rekap-combo-icon">search</span>
                        <input class="rekap-combo-input" type="search" data-picker-search="guru-options" placeholder="Ketik nama guru…" aria-label="Cari dan pilih guru" aria-controls="guru-options" aria-expanded="false" aria-autocomplete="list" role="combobox" autocomplete="off">
                        <div class="rekap-picker-menu" id="guru-options" role="listbox">
                            @foreach($gurus->slice(5) as $guru)
                                <a class="rekap-picker-option" data-picker-option data-search-name="{{ mb_strtolower($guru->nama_guru) }}" role="option" href="{{ route('piket.jurnal.rekap', ['view' => 'guru', 'id_guru' => $guru->id_guru]) }}">{{ $guru->nama_guru }}</a>
                            @endforeach
                            <span class="rekap-picker-empty" hidden>Tidak ada guru yang cocok.</span>
                        </div>
                    </div>
                @endif
                @if($gurus->isEmpty())<span class="rekap-empty-title">Belum ada data guru.</span>@endif
            </div>
        </section>
        @if($selectedGuru)
            <section class="rekap-card rekap-card-pad">
                <div class="rekap-selected"><span class="rekap-selected-icon"><span class="material-symbols-outlined">person</span></span><div class="rekap-selected-info"><h2 class="rekap-selected-title">{{ $selectedGuru->nama_guru }}</h2><p class="rekap-selected-subtitle">{{ $jurnals->count() }} jurnal pada filter saat ini</p></div><span class="rekap-pill">Guru</span></div>
            </section>
            @include('piket.jurnal.partials.docx-controls', ['jenis' => 'guru', 'objek' => $selectedGuru, 'judulObjek' => $selectedGuru->nama_guru])
            <section class="rekap-card rekap-card-pad">
                <div class="rekap-card-head"><div><h2 class="rekap-card-title">Jurnal Mengajar</h2><p class="rekap-card-description">Daftar aktivitas yang dibuat {{ $selectedGuru->nama_guru }}.</p></div><span class="rekap-pill">{{ $jurnals->count() }} jurnal</span></div>
                @include('piket.jurnal.partials.filters', ['idKelas' => null, 'idGuru' => $selectedGuru->id_guru])
                @include('piket.jurnal.partials.table', ['jurnals' => $jurnals, 'jenis' => 'guru'])
            </section>
        @else
            <section class="rekap-card"><div class="rekap-empty"><span class="rekap-empty-icon"><span class="material-symbols-outlined">touch_app</span></span><div class="rekap-empty-title">Pilih satu guru untuk mulai</div><p class="rekap-empty-note">Gunakan kartu guru di atas. Setelah guru dipilih, jurnal dan unduhan khusus guru itu akan muncul.</p></div></section>
        @endif
    @endif
</div>
<script>
    document.querySelectorAll('[data-picker-search]').forEach(input => {
        const combo = input.closest('.rekap-combo');
        const menu = document.getElementById(input.dataset.pickerSearch);
        const updateResults = () => {
            const query = input.value.trim().toLocaleLowerCase('id');
            let visible = 0;
            menu.querySelectorAll('[data-picker-option]').forEach(option => {
                const matches = option.dataset.searchName.includes(query);
                option.hidden = !matches;
                if (matches) visible++;
            });
            const empty = menu.querySelector('.rekap-picker-empty');
            if (empty) empty.hidden = visible > 0;
        };
        const pickerCard = combo.closest('.rekap-picker-card');
        const open = () => { combo.classList.add('is-open'); pickerCard?.classList.add('picker-open'); input.setAttribute('aria-expanded', 'true'); };
        const close = () => { combo.classList.remove('is-open'); pickerCard?.classList.remove('picker-open'); input.setAttribute('aria-expanded', 'false'); };
        input.addEventListener('focus', () => { updateResults(); open(); });
        input.addEventListener('input', () => { updateResults(); open(); });
        input.addEventListener('keydown', event => {
            if (event.key === 'ArrowDown') { event.preventDefault(); menu.querySelector('[data-picker-option]:not([hidden])')?.focus(); }
            if (event.key === 'Escape') close();
        });
        combo.addEventListener('focusout', event => { if (!combo.contains(event.relatedTarget)) setTimeout(close, 100); });
        document.addEventListener('pointerdown', event => { if (!combo.contains(event.target)) close(); });
    });
</script>
@endsection
