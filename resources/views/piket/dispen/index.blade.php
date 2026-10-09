@extends('layouts.piket')

@section('title', 'Data Dispen - Jurnify')
@section('page-title', 'Data Dispen')

@push('styles')
<style>
    .dispen-page{width:100%;padding:24px;color:#1e293b}.dispen-page *,.dispen-page *::before,.dispen-page *::after{box-sizing:border-box}
    .dispen-header{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;margin-bottom:22px}.dispen-title{margin:0;color:#1e293b;font-size:24px;font-weight:800}.dispen-description{max-width:760px;margin:7px 0 0;color:#64748b;font-size:13px;line-height:1.6}
    .dispen-add-button{display:inline-flex;min-height:42px;flex:0 0 auto;align-items:center;justify-content:center;gap:8px;padding:0 16px;border-radius:11px;background:#30366f;color:#fff;font-size:13px;font-weight:800;text-decoration:none}.dispen-add-button:hover{background:#252b5d}
    .dispen-alert{display:flex;gap:10px;margin-bottom:14px;padding:13px 15px;border:1px solid;border-radius:12px;font-size:13px}.dispen-alert--success{border-color:#a7f3d0;background:#ecfdf5;color:#047857}.dispen-alert--error{border-color:#fecaca;background:#fef2f2;color:#b91c1c}
    .dispen-tabs{display:flex;gap:5px;margin-bottom:16px;padding:6px;border:1px solid #e2e8f0;border-radius:14px;background:#fff}.dispen-tab{display:inline-flex;min-height:39px;align-items:center;gap:8px;padding:0 14px;border-radius:9px;color:#64748b;font-size:12px;font-weight:700;text-decoration:none}.dispen-tab.is-active{background:#30366f;color:#fff}.dispen-tab-count{min-width:22px;padding:3px 6px;border-radius:20px;background:#f1f5f9;color:#64748b;text-align:center;font-size:10px}.dispen-tab.is-active .dispen-tab-count{background:#ffffff2e;color:#fff}
    .dispen-table-card{overflow:hidden;border:1px solid #e2e8f0;border-radius:15px;background:#fff;box-shadow:0 4px 16px #0f172a0a}.dispen-table{width:100%;border-collapse:collapse;color:#334155;font-size:12px}.dispen-table th{padding:13px 14px;border-bottom:1px solid #e2e8f0;background:#f8fafc;color:#64748b;text-align:left;text-transform:uppercase;letter-spacing:.045em;font-size:10px;font-weight:800;white-space:nowrap}.dispen-table td{padding:14px;border-bottom:1px solid #f1f5f9;vertical-align:middle;line-height:1.5}.dispen-table tr:last-child td{border-bottom:0}.dispen-table tbody tr[data-detail-url]{cursor:pointer}.dispen-table tbody tr[data-detail-url]:hover{background:#fafbff}.dispen-number{width:52px;color:#94a3b8;font-weight:800}.dispen-date{min-width:115px;color:#334155;font-weight:700;white-space:nowrap}.dispen-student-count{color:#1e293b;font-weight:800}.dispen-subline{display:block;margin-top:3px;color:#94a3b8;font-size:10px;font-weight:500}.dispen-time{white-space:nowrap;font-weight:700}.dispen-status{display:inline-flex;align-items:center;gap:5px;padding:5px 9px;border:1px solid;border-radius:20px;font-size:10px;font-weight:800;white-space:nowrap}.dispen-status--menunggu{border-color:#fde68a;background:#fffbeb;color:#a16207}.dispen-status--disetujui{border-color:#a7f3d0;background:#ecfdf5;color:#047857}.dispen-status--ditolak{border-color:#fecaca;background:#fef2f2;color:#b91c1c}.dispen-confirmed{min-width:130px;color:#334155;font-weight:700}.dispen-muted{display:block;margin-top:3px;color:#94a3b8;font-size:10px;font-weight:500}.dispen-actions{display:flex;justify-content:center;gap:6px}.dispen-icon-button{display:inline-grid;width:33px;height:33px;place-items:center;border:1px solid #e2e8f0;border-radius:9px;background:#fff;color:#475569;text-decoration:none;cursor:pointer}.dispen-icon-button .material-symbols-outlined{font-size:18px}.dispen-icon-button--edit{border-color:#fde68a;background:#fffbeb;color:#a16207}.dispen-icon-button--delete{border-color:#fecaca;background:#fff5f5;color:#dc2626}.dispen-icon-button--view{border-color:#c7d2fe;background:#eef2ff;color:#4338ca}.dispen-icon-button:hover{filter:brightness(.97)}.dispen-empty{padding:35px 18px!important;color:#64748b;text-align:center}.dispen-mobile-list{display:none}
    .dispen-modal[hidden]{display:none}.dispen-modal{position:fixed;z-index:1200;inset:0;display:flex;align-items:center;justify-content:center;padding:24px;background:rgba(15,23,42,.58);backdrop-filter:blur(4px)}.dispen-modal-panel{display:flex;width:min(100%,720px);max-height:min(88vh,900px);flex-direction:column;overflow:hidden;border:1px solid #e2e8f0;border-radius:20px;background:#fff;box-shadow:0 24px 80px rgba(15,23,42,.3);animation:dispen-modal-in .18s ease-out}.dispen-modal-header{display:flex;flex:0 0 auto;align-items:center;justify-content:space-between;gap:16px;padding:18px 22px;border-bottom:1px solid #e9edf4}.dispen-modal-title{margin:0;color:#1e293b;font-size:17px;font-weight:800}.dispen-modal-close{display:grid;width:36px;height:36px;place-items:center;border:0;border-radius:10px;background:#f1f5f9;color:#475569;cursor:pointer}.dispen-modal-content{min-height:120px;overflow:auto}.dispen-modal-loading{padding:32px;text-align:center;color:#64748b;font-size:13px}@keyframes dispen-modal-in{from{opacity:0;transform:translateY(8px) scale(.98)}to{opacity:1;transform:translateY(0) scale(1)}}body.dispen-modal-open{overflow:hidden}
    .dispen-pagination{margin-top:15px}.dispen-pagination nav{display:flex;justify-content:space-between;gap:10px;color:#64748b;font-size:12px}.dispen-pagination nav>div{display:flex;align-items:center;gap:4px}.dispen-pagination a,.dispen-pagination span[aria-current],.dispen-pagination nav span[aria-disabled]{display:inline-flex;min-width:34px;min-height:34px;align-items:center;justify-content:center;padding:0 8px;border:1px solid #e2e8f0;border-radius:8px;background:#fff;color:#475569;text-decoration:none}.dispen-pagination span[aria-current]{border-color:#30366f;background:#30366f;color:#fff}.dispen-pagination svg{width:16px;height:16px}
    .dispen-detail-content{display:grid;gap:20px;padding:22px 24px 24px;color:#1e293b}.dispen-detail-overview{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}.dispen-detail-fact{display:grid;align-content:start;gap:6px;min-width:0;padding:13px;border:1px solid #e7ebf3;border-radius:12px;background:#f8faff}.dispen-detail-label{color:#8490a5;font-size:10px;font-weight:800;letter-spacing:.07em;text-transform:uppercase}.dispen-detail-fact strong{color:#26324b;font-size:13px;line-height:1.45}.dispen-detail-fact small,.dispen-detail-info small{display:block;margin-top:2px;color:#8792a7;font-size:11px;font-weight:500}.dispen-detail-status{display:inline-flex;width:max-content;align-items:center;border:1px solid;border-radius:20px;padding:4px 9px;font-size:11px;font-weight:800}.dispen-detail-status--menunggu{border-color:#fde68a;background:#fffbeb;color:#a16207}.dispen-detail-status--disetujui{border-color:#a7f3d0;background:#ecfdf5;color:#047857}.dispen-detail-status--ditolak{border-color:#fecaca;background:#fef2f2;color:#b91c1c}.dispen-detail-section{display:grid;gap:10px}.dispen-detail-section-heading{display:flex;align-items:center;justify-content:space-between;gap:12px}.dispen-detail-section-heading h3{margin:0;color:#26324b;font-size:14px;font-weight:800}.dispen-detail-section-heading>span{padding:5px 9px;border-radius:20px;background:#eff4ff;color:#315bd1;font-size:10px;font-weight:800}.dispen-detail-students{display:grid;gap:7px}.dispen-detail-student{display:flex;align-items:center;gap:11px;padding:10px 12px;border:1px solid #e9edf4;border-radius:11px;background:#fff}.dispen-detail-student-number{display:grid;width:30px;height:30px;flex:0 0 30px;place-items:center;border-radius:50%;background:#edf3ff;color:#3764d8;font-size:11px;font-weight:800}.dispen-detail-student strong{display:block;color:#29354e;font-size:12px;font-weight:750}.dispen-detail-student small{display:block;margin-top:3px;color:#8290a7;font-size:11px}.dispen-detail-info-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.dispen-detail-info{min-width:0;padding:12px;border:1px solid #e9edf4;border-radius:11px;background:#fbfcfe}.dispen-detail-info p{margin:6px 0 0;color:#39465f;font-size:12px;line-height:1.55;white-space:pre-line;overflow-wrap:anywhere}.dispen-detail-footer{padding-top:4px;border-top:1px solid #edf0f5}.dispen-detail-footer a{display:inline-flex;min-height:40px;align-items:center;justify-content:center;gap:7px;padding:0 14px;border-radius:9px;background:#138a54;color:#fff;font-size:12px;font-weight:800;text-decoration:none}.dispen-detail-footer a:hover{background:#0f7145}
    @media(max-width:800px){.dispen-page{padding:18px 14px}.dispen-header{flex-direction:column;gap:13px}.dispen-add-button{width:100%}.dispen-tabs{display:grid;grid-template-columns:repeat(3,minmax(0,1fr))}.dispen-tab{justify-content:center;padding:0 6px;font-size:11px}.dispen-table-card{overflow:visible;border:0;background:transparent;box-shadow:none}.dispen-table{display:none}.dispen-mobile-list{display:grid;gap:10px}.dispen-mobile-card{padding:15px;border:1px solid #e2e8f0;border-radius:14px;background:#fff;box-shadow:0 3px 10px #0f172a08;cursor:pointer}.dispen-mobile-head{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;padding-bottom:11px;border-bottom:1px solid #f1f5f9}.dispen-mobile-number{display:grid;width:28px;height:28px;flex:0 0 28px;place-items:center;border-radius:50%;background:#3b82f6;color:#fff;font-size:11px;font-weight:800}.dispen-mobile-title{display:flex;min-width:0;flex:1;align-items:center;gap:9px}.dispen-mobile-title strong{color:#1e293b;font-size:13px}.dispen-mobile-meta{display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:12px 0}.dispen-mobile-meta>div{min-width:0}.dispen-mobile-label{display:block;margin-bottom:3px;color:#94a3b8;font-size:9px;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.dispen-mobile-value{color:#334155;font-size:11px;font-weight:650;line-height:1.45;overflow-wrap:anywhere}.dispen-mobile-actions{display:flex;justify-content:flex-end;gap:7px;padding-top:10px;border-top:1px solid #f1f5f9}.dispen-pagination nav{flex-direction:column}}
    @media(max-width:640px){.dispen-modal{align-items:flex-end;padding:0}.dispen-modal-panel{width:100%;max-height:90dvh;max-height:90vh;border:0;border-radius:20px 20px 0 0;animation:dispen-drawer-in .22s ease-out}.dispen-modal-header{padding:15px 18px}.dispen-modal-content{padding-bottom:env(safe-area-inset-bottom)}.dispen-detail-content{gap:17px;padding:17px 16px calc(20px + env(safe-area-inset-bottom))}.dispen-detail-overview{grid-template-columns:repeat(2,minmax(0,1fr))}.dispen-detail-fact:last-child{grid-column:1/-1}.dispen-detail-info-grid{grid-template-columns:1fr 1fr}.dispen-detail-info{padding:10px}.dispen-detail-footer a{width:100%}@keyframes dispen-drawer-in{from{transform:translateY(100%)}to{transform:translateY(0)}}}
    @media(max-width:420px){.dispen-page{padding:14px 10px}.dispen-title{font-size:21px}.dispen-mobile-card{padding:13px}.dispen-mobile-meta{gap:9px}}
</style>
@endpush

@section('content')
<div class="dispen-page">
    <header class="dispen-header"><div><h1 class="dispen-title">Data Dispen</h1><p class="dispen-description">Kelola permohonan dispensasi siswa dan teruskan ke Waka/Kesiswaan yang bertugas.</p></div><a href="{{ route('piket.dispen.create') }}" class="dispen-add-button"><span class="material-symbols-outlined" aria-hidden="true">add</span>Tambah Dispen</a></header>
    @if(session('success'))<div class="dispen-alert dispen-alert--success" role="status">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="dispen-alert dispen-alert--error" role="alert">{{ session('error') }}</div>@endif

    <nav class="dispen-tabs" aria-label="Filter data dispen">
        @foreach(['menunggu' => 'Menunggu', 'semua' => 'Semua'] as $key => $label)
            <a href="{{ route('piket.dispen.index', ['status' => $key]) }}" class="dispen-tab {{ $filterStatus === $key ? 'is-active' : '' }}" aria-current="{{ $filterStatus === $key ? 'page' : 'false' }}">{{ $label }}<span class="dispen-tab-count">{{ $counts[$key] }}</span></a>
        @endforeach
    </nav>

    <section class="dispen-table-card" aria-label="Daftar pengajuan dispen">
        <table class="dispen-table">
            <thead><tr><th>#</th><th>Tanggal</th><th>Siswa</th><th>Waktu</th><th>Status</th><th>Dikonfirmasi</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($dispens as $dispen)
                    @php($detailUrl = route('piket.dispen.detail', $dispen->id_dispen))
                    <tr data-detail-url="{{ $detailUrl }}">
                        <td class="dispen-number">{{ str_pad((string) ($dispens->firstItem() + $loop->index), 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="dispen-date">{{ $dispen->tanggal?->translatedFormat('d M Y') ?? '-' }}<span class="dispen-subline">{{ $dispen->jenis_dispen === 'terlambat' ? 'Terlambat' : 'Kegiatan' }}</span></td>
                        <td><span class="dispen-student-count">{{ $dispen->student_count }} Siswa</span><span class="dispen-subline">{{ $dispen->class_count }} Kelas</span></td>
                        <td class="dispen-time">{{ $dispen->jamMulai?->jam_ke ?? '-' }}–{{ $dispen->jamSelesai?->jam_ke ?? '-' }}<span class="dispen-subline">{{ substr($dispen->jamMulai?->jam_mulai ?? '',0,5) }}–{{ substr($dispen->jamSelesai?->jam_selesai ?? '',0,5) }}</span></td>
                        <td><span class="dispen-status dispen-status--{{ $dispen->status }}">{{ $dispen->status === 'menunggu' ? '🟡' : ($dispen->status === 'disetujui' ? '🟢' : '🔴') }} {{ ucfirst($dispen->status) }}</span></td>
                        <td class="dispen-confirmed">{{ $dispen->approver?->nama_user ?? '-' }}@if($dispen->disetujui_pada)<span class="dispen-muted">{{ $dispen->disetujui_pada->translatedFormat('d M, H:i') }}</span>@endif</td>
                        <td><div class="dispen-actions" data-row-action>
                            @if($dispen->status === 'menunggu')
                                <a class="dispen-icon-button dispen-icon-button--edit" href="{{ route('piket.dispen.edit', $dispen->id_dispen) }}" title="Edit" aria-label="Edit"><span class="material-symbols-outlined">edit</span></a>
                                <form method="POST" action="{{ route('piket.dispen.destroy', $dispen->id_dispen) }}" onsubmit="return confirm('Hapus seluruh siswa dalam pengajuan ini?')">@csrf @method('DELETE')<button class="dispen-icon-button dispen-icon-button--delete" type="submit" title="Hapus" aria-label="Hapus"><span class="material-symbols-outlined">delete</span></button></form>
                            @else
                                <button class="dispen-icon-button dispen-icon-button--view" type="button" data-open-detail="{{ $detailUrl }}" title="Lihat detail" aria-label="Lihat detail"><span class="material-symbols-outlined">visibility</span></button>
                            @endif
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="dispen-empty">Belum ada pengajuan dispen pada kategori ini.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="dispen-mobile-list">
            @forelse($dispens as $dispen)
                @php($detailUrl = route('piket.dispen.detail', $dispen->id_dispen))
                <article class="dispen-mobile-card" data-detail-url="{{ $detailUrl }}" tabindex="0" role="link" aria-label="Lihat detail pengajuan dispen">
                    <div class="dispen-mobile-head"><div class="dispen-mobile-title"><span class="dispen-mobile-number">{{ str_pad((string) ($dispens->firstItem() + $loop->index), 2, '0', STR_PAD_LEFT) }}</span><div><strong>{{ $dispen->student_count }} Siswa</strong><span class="dispen-subline">{{ $dispen->jenis_dispen === 'terlambat' ? 'Terlambat' : 'Kegiatan' }} · {{ $dispen->class_count }} Kelas · {{ $dispen->tanggal?->translatedFormat('d M Y') ?? '-' }}</span></div></div><span class="dispen-status dispen-status--{{ $dispen->status }}">{{ ucfirst($dispen->status) }}</span></div>
                    <div class="dispen-mobile-meta"><div><span class="dispen-mobile-label">Waktu</span><div class="dispen-mobile-value">Jam {{ $dispen->jamMulai?->jam_ke ?? '-' }}–{{ $dispen->jamSelesai?->jam_ke ?? '-' }}<br>{{ substr($dispen->jamMulai?->jam_mulai ?? '',0,5) }}–{{ substr($dispen->jamSelesai?->jam_selesai ?? '',0,5) }}</div></div><div><span class="dispen-mobile-label">Dikonfirmasi</span><div class="dispen-mobile-value">{{ $dispen->approver?->nama_user ?? '-' }}<span class="dispen-subline">{{ $dispen->disetujui_pada?->translatedFormat('d M, H:i') ?? 'Belum dikonfirmasi' }}</span></div></div></div>
                    <div class="dispen-mobile-actions" data-row-action>
                        @if($dispen->status === 'menunggu')
                            <a class="dispen-icon-button dispen-icon-button--edit" href="{{ route('piket.dispen.edit', $dispen->id_dispen) }}" title="Edit" aria-label="Edit"><span class="material-symbols-outlined">edit</span></a>
                            <form method="POST" action="{{ route('piket.dispen.destroy', $dispen->id_dispen) }}" onsubmit="return confirm('Hapus seluruh siswa dalam pengajuan ini?')">@csrf @method('DELETE')<button class="dispen-icon-button dispen-icon-button--delete" type="submit" title="Hapus" aria-label="Hapus"><span class="material-symbols-outlined">delete</span></button></form>
                        @else
                            <button class="dispen-icon-button dispen-icon-button--view" type="button" data-open-detail="{{ $detailUrl }}" title="Lihat detail" aria-label="Lihat detail"><span class="material-symbols-outlined">visibility</span></button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="dispen-empty">Belum ada pengajuan dispen pada kategori ini.</div>
            @endforelse
        </div>
    </section>
    <div class="dispen-pagination">{{ $dispens->links() }}</div>
</div>
<div class="dispen-modal" id="dispenDetailModal" hidden aria-hidden="true">
    <section class="dispen-modal-panel" role="dialog" aria-modal="true" aria-labelledby="dispenModalTitle">
        <header class="dispen-modal-header"><h2 class="dispen-modal-title" id="dispenModalTitle">Detail Dispen</h2><button class="dispen-modal-close" id="closeDispenModal" type="button" aria-label="Tutup detail"><span class="material-symbols-outlined">close</span></button></header>
        <div class="dispen-modal-content" id="dispenModalContent"><div class="dispen-modal-loading">Memuat detail...</div></div>
    </section>
</div>
<script>
(() => {
    const modal = document.getElementById('dispenDetailModal');
    const content = document.getElementById('dispenModalContent');
    const closeButton = document.getElementById('closeDispenModal');
    let previousFocus = null;

    function closeModal() {
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('dispen-modal-open');
        content.innerHTML = '<div class="dispen-modal-loading">Memuat detail...</div>';
        previousFocus?.focus();
    }

    async function openModal(url) {
        previousFocus = document.activeElement;
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('dispen-modal-open');
        content.innerHTML = '<div class="dispen-modal-loading">Memuat detail...</div>';
        closeButton.focus();
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
            if (!response.ok) throw new Error('Gagal memuat detail.');
            content.innerHTML = await response.text();
        } catch (error) {
            content.innerHTML = '<div class="dispen-modal-loading">Detail gagal dimuat. Silakan coba lagi.</div>';
        }
    }

    document.querySelectorAll('[data-detail-url]').forEach(row => {
        row.addEventListener('click', event => {
            if (event.target.closest('[data-row-action]')) return;
            openModal(row.dataset.detailUrl);
        });
        row.addEventListener('keydown', event => {
            if ((event.key === 'Enter' || event.key === ' ') && row.matches('article')) {
                event.preventDefault();
                openModal(row.dataset.detailUrl);
            }
        });
    });
    document.querySelectorAll('[data-open-detail]').forEach(button => button.addEventListener('click', () => openModal(button.dataset.openDetail)));
    closeButton.addEventListener('click', closeModal);
    modal.addEventListener('click', event => { if (event.target === modal) closeModal(); });
    document.addEventListener('keydown', event => { if (event.key === 'Escape' && !modal.hidden) closeModal(); });
})();
</script>
@endsection