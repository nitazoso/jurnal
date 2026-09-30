@extends('layouts.piket')

@section('title', 'Data Dispen - Jurnify')
@section('page-title', 'Data Dispen')

@push('styles')
<style>
    .dispen-page { width: 100%; padding: 24px; color: #1e293b; }
    .dispen-page *, .dispen-page *::before, .dispen-page *::after { box-sizing: border-box; }
    .dispen-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 22px; }
    .dispen-title { margin: 0; color: #1e293b; font-size: 24px; font-weight: 800; line-height: 1.25; }
    .dispen-description { max-width: 760px; margin: 7px 0 0; color: #64748b; font-size: 13px; line-height: 1.65; }
    .dispen-add-button { display: inline-flex; min-height: 42px; flex: 0 0 auto; align-items: center; justify-content: center; gap: 8px; padding: 0 16px; border-radius: 11px; background: #30366f; color: #fff; font-size: 13px; font-weight: 800; text-decoration: none; transition: background .2s, transform .2s; }
    .dispen-add-button:hover { transform: translateY(-1px); background: #252b5d; }
    .dispen-alert { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 14px; padding: 13px 15px; border: 1px solid; border-radius: 12px; font-size: 13px; line-height: 1.5; }
    .dispen-alert svg { width: 19px; height: 19px; flex: 0 0 auto; margin-top: 1px; }
    .dispen-alert--success { border-color: #a7f3d0; background: #ecfdf5; color: #047857; }
    .dispen-alert--error { border-color: #fecaca; background: #fef2f2; color: #b91c1c; }
    .dispen-tabs { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 16px; padding: 6px; border: 1px solid #e2e8f0; border-radius: 15px; background: #fff; box-shadow: 0 3px 12px rgba(15, 23, 42, .04); }
    .dispen-tab { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; gap: 9px; padding: 0 14px; border-radius: 10px; color: #64748b; font-size: 13px; font-weight: 700; text-decoration: none; transition: background .2s, color .2s; }
    .dispen-tab:hover { background: #f1f5f9; color: #30366f; }
    .dispen-tab.is-active { background: #30366f; color: #fff; box-shadow: 0 2px 7px rgba(48, 54, 111, .18); }
    .dispen-tab-count { min-width: 23px; padding: 3px 7px; border-radius: 20px; background: #f1f5f9; color: #64748b; text-align: center; font-size: 11px; line-height: 1.2; }
    .dispen-tab.is-active .dispen-tab-count { background: rgba(255,255,255,.17); color: #fff; }
    .dispen-table-card { overflow: hidden; border: 1px solid #e2e8f0; border-radius: 16px; background: #fff; box-shadow: 0 4px 18px rgba(15, 23, 42, .04); }
    .dispen-table-scroll { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .dispen-table { width: 100%; min-width: 1390px; border-collapse: separate; border-spacing: 0; color: #334155; font-size: 12px; }
    .dispen-table thead th { padding: 13px 14px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; text-align: left; text-transform: uppercase; letter-spacing: .045em; font-size: 10px; font-weight: 800; white-space: nowrap; }
    .dispen-table thead th:first-child { padding-left: 18px; }
    .dispen-table tbody td { padding: 13px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: top; line-height: 1.55; }
    .dispen-table tbody tr:last-child td { border-bottom: 0; }
    .dispen-table tbody tr:hover { background: #fafbff; }
    .dispen-table .cell-number { width: 54px; padding-left: 18px; color: #94a3b8; }
    .dispen-table .cell-student { min-width: 145px; color: #1e293b; font-weight: 800; }
    .dispen-table .cell-time { min-width: 130px; white-space: nowrap; }
    .dispen-table .cell-reason, .dispen-table .cell-notes { max-width: 190px; }
    .dispen-table .cell-actions { min-width: 230px; text-align: center; }
    .dispen-muted { color: #94a3b8; }
    .dispen-phone { display: block; margin-top: 3px; color: #059669; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 11px; }
    .dispen-no-phone { display: block; margin-top: 3px; color: #d97706; font-size: 11px; font-style: italic; }
    .dispen-status { display: inline-flex; align-items: center; padding: 4px 10px; border: 1px solid; border-radius: 20px; font-size: 11px; font-weight: 800; white-space: nowrap; }
    .dispen-status--menunggu { border-color: #fde68a; background: #fffbeb; color: #a16207; }
    .dispen-status--disetujui { border-color: #a7f3d0; background: #ecfdf5; color: #047857; }
    .dispen-status--ditolak { border-color: #fecaca; background: #fef2f2; color: #b91c1c; }
    .dispen-actions { display: flex; align-items: center; justify-content: center; gap: 6px; }
    .dispen-action { display: inline-flex; min-height: 31px; align-items: center; justify-content: center; padding: 0 10px; border: 0; border-radius: 8px; color: #fff; font-family: inherit; font-size: 11px; font-weight: 700; text-decoration: none; white-space: nowrap; cursor: pointer; transition: background .2s; }
    .dispen-action--whatsapp { gap: 5px; background: #16a34a; }
    .dispen-action--whatsapp:hover { background: #15803d; }
    .dispen-action--edit { background: #eab308; }
    .dispen-action--edit:hover { background: #ca8a04; }
    .dispen-action--delete { background: #dc2626; }
    .dispen-action--delete:hover { background: #b91c1c; }
    .dispen-action-form { display: inline; margin: 0; }
    .dispen-empty { padding: 38px 20px !important; color: #64748b; text-align: center; font-size: 13px; }
    .dispen-pagination { margin-top: 16px; }
    .dispen-pagination nav { display: flex; justify-content: space-between; gap: 12px; color: #64748b; font-size: 12px; }
    .dispen-pagination nav > div { display: flex; align-items: center; gap: 4px; }
    .dispen-pagination a, .dispen-pagination span[aria-current], .dispen-pagination nav span[aria-disabled] { display: inline-flex; min-width: 34px; min-height: 34px; align-items: center; justify-content: center; padding: 0 9px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; color: #475569; text-decoration: none; }
    .dispen-pagination span[aria-current] { border-color: #30366f; background: #30366f; color: #fff; }
    .dispen-pagination a:hover { border-color: #c7d2fe; background: #eef2ff; color: #30366f; }
    .dispen-pagination svg { width: 16px; height: 16px; }
    @media (max-width: 760px) {
        .dispen-page { padding: 18px 14px; }
        .dispen-header { flex-direction: column; gap: 14px; margin-bottom: 18px; }
        .dispen-add-button { width: 100%; }
        .dispen-tabs { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .dispen-tab { gap: 5px; padding: 0 7px; font-size: 12px; }
        .dispen-pagination nav { flex-direction: column; }
    }
    @media (max-width: 420px) {
        .dispen-page { padding: 14px 10px; }
        .dispen-title { font-size: 21px; }
        .dispen-description { font-size: 12px; }
        .dispen-tabs { gap: 3px; padding: 4px; }
        .dispen-tab { min-height: 38px; font-size: 11px; }
    }
</style>
@endpush

@section('content')
<div class="dispen-page">
    <header class="dispen-header">
        <div>
            <h1 class="dispen-title">Data Dispen</h1>
            <p class="dispen-description">Kelola data dispensasi siswa dan teruskan permohonan ke Waka/Kesiswaan bertugas via WhatsApp.</p>
        </div>
        <a href="{{ route('piket.dispen.create') }}" class="dispen-add-button"><span aria-hidden="true">+</span><span>Tambah Dispen</span></a>
    </header>

    @if(session('success'))
        <div class="dispen-alert dispen-alert--success" role="status">
            <svg class="dispen-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="dispen-alert dispen-alert--error" role="alert">
            <svg class="dispen-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <nav class="dispen-tabs" role="tablist" aria-label="Filter data dispen">
        @foreach(['menunggu' => 'Menunggu', 'riwayat' => 'Riwayat', 'semua' => 'Semua'] as $key => $label)
            <a href="{{ route('piket.dispen.index', ['status' => $key]) }}" class="dispen-tab {{ $filterStatus === $key ? 'is-active' : '' }}" aria-current="{{ $filterStatus === $key ? 'page' : 'false' }}">
                {{ $label }}<span class="dispen-tab-count">{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </nav>

    <section class="dispen-table-card" aria-label="Daftar dispensasi">
        <div class="dispen-table-scroll">
            <table class="dispen-table">
                <thead>
                    <tr>
                        <th>No</th><th>Siswa</th><th>Kelas</th><th>Tanggal</th><th>Jam</th><th>Alasan</th><th>Waka Bertugas</th><th>Status</th><th>Catatan</th><th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispens as $dispen)
                        @php
                            $wakaTerjadwal = $dispen->tanggal
                                ? $jadwalWakaByTanggal->get($dispen->tanggal->toDateString())?->guru
                                : null;
                            $wakaGuru = $wakaTerjadwal ?? $dispen->petugasKesiswaan?->guru;
                            $wakaNama = $wakaGuru?->nama_guru ?? $dispen->petugasKesiswaan?->nama_user;
                        @endphp
                        <tr>
                            <td class="cell-number">{{ $dispens->firstItem() + $loop->index }}</td>
                            <td class="cell-student">{{ $dispen->siswa->nama_siswa ?? '-' }}</td>
                            <td>{{ $dispen->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td>{{ $dispen->tanggal ? $dispen->tanggal->format('d-m-Y') : '-' }}</td>
                            <td class="cell-time">Jam ke-{{ $dispen->jamMulai->jam_ke ?? '?' }} - ke-{{ $dispen->jamSelesai->jam_ke ?? '?' }}<br><span class="dispen-muted">({{ substr($dispen->jamMulai->jam_mulai ?? '', 0, 5) }} - {{ substr($dispen->jamSelesai->jam_selesai ?? '', 0, 5) }})</span></td>
                            <td class="cell-reason" title="{{ $dispen->alasan }}">{{ $dispen->alasan }}</td>
                            <td>
                                @if($wakaNama)
                                    <div>{{ $wakaNama }}</div>
                                    @if($wakaGuru?->no_hp)
                                        <span class="dispen-phone">{{ $wakaGuru->no_hp }}</span>
                                    @else
                                        <span class="dispen-no-phone">No. HP belum ada</span>
                                    @endif
                                @else
                                    <span class="dispen-muted">Jadwal belum tersedia</span>
                                @endif
                            </td>
                            <td>
                                <span class="dispen-status dispen-status--{{ $dispen->status }}">{{ ucfirst($dispen->status) }}</span>
                                @if($dispen->disetujui_pada)
                                    <span class="dispen-muted" style="display:block;margin-top:4px">{{ $dispen->disetujui_pada->format('d-m-Y H:i') }}</span>
                                @endif
                            </td>
                            <td class="cell-notes" title="{{ $dispen->catatan_persetujuan }}">{{ $dispen->catatan_persetujuan ?: '-' }}</td>
                            <td class="cell-actions">
                                @if($dispen->status === 'menunggu')
                                    <div class="dispen-actions">
                                        <a href="{{ route('piket.dispen.whatsapp', $dispen->id_dispen) }}" class="dispen-action dispen-action--whatsapp" target="_blank" rel="noopener" title="Kirim ke WhatsApp Waka/Kesiswaan bertugas">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.529 1.771.814 2.791.814 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.768-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zM12 2C6.477 2 2 6.477 2 12c0 1.891.524 3.66 1.434 5.176L2 22l4.957-1.396A9.957 9.957 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
                                            <span>Kirim WhatsApp</span>
                                        </a>
                                        <a href="{{ route('piket.dispen.edit', $dispen->id_dispen) }}" class="dispen-action dispen-action--edit">Edit</a>
                                        <form class="dispen-action-form" action="{{ route('piket.dispen.destroy', $dispen->id_dispen) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data dispen ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dispen-action dispen-action--delete">Hapus</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="dispen-muted">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="dispen-empty">
                            @if($filterStatus === 'menunggu')
                                Belum ada dispen yang menunggu persetujuan.
                            @elseif($filterStatus === 'riwayat')
                                Belum ada riwayat dispen.
                            @else
                                Belum ada data dispen.
                            @endif
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="dispen-pagination">{{ $dispens->links() }}</div>
</div>
@endsection
