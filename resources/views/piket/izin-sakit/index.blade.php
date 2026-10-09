@extends('layouts.piket')

@section('title', 'Izin & Sakit - Jurnify')
@section('page-title', 'Izin & Sakit')

@push('styles')
<style>
    .is-page{--is-navy:#30366f;--is-ink:#202747;--is-muted:#778097;--is-line:#e8ebf3;display:grid;gap:20px;margin:0 auto;max-width:1440px;padding:clamp(16px,3vw,32px)}
    .is-hero{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:24px;padding:clamp(22px,4vw,36px);border:1px solid #e5e9f5;border-radius:20px;background:linear-gradient(115deg,#fff 12%,#f5f6ff 100%);box-shadow:0 12px 30px rgba(39,48,102,.06)}
    .is-hero:after{position:absolute;right:8%;top:-95px;width:250px;height:250px;border-radius:50%;background:rgba(91,108,214,.06);content:"";pointer-events:none}
    .is-eyebrow{display:inline-flex;align-items:center;gap:7px;color:#5965b1;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}
    .is-title{margin:8px 0 5px;color:var(--is-ink);font-size:clamp(24px,3vw,32px);font-weight:800;letter-spacing:-.04em;line-height:1.15}
    .is-subtitle{margin:0;color:var(--is-muted);font-size:14px;line-height:1.65}
    .is-hero-actions{z-index:1;display:flex;flex-wrap:wrap;gap:10px}
    .is-button{display:inline-flex;min-height:43px;align-items:center;justify-content:center;gap:8px;padding:10px 15px;border:1px solid transparent;border-radius:10px;font:inherit;font-size:13px;font-weight:800;text-decoration:none;transition:transform .18s,box-shadow .18s,background .18s}
    .is-button:hover{transform:translateY(-1px);box-shadow:0 7px 16px rgba(48,54,111,.14)}
    .is-button-primary{background:var(--is-navy);color:#fff}.is-button-primary:hover{background:#242a5d}
    .is-button-secondary{border-color:#dfe3f2;background:#fff;color:var(--is-navy)}.is-button-secondary:hover{background:#f7f8ff}
    .is-alert{display:flex;align-items:center;gap:10px;padding:13px 16px;border:1px solid;border-radius:12px;font-size:13px;font-weight:600}
    .is-alert-success{border-color:#cdebd9;background:#effaf3;color:#19734a}.is-alert-error{border-color:#f1d0d0;background:#fff4f4;color:#a23b3b}
    .is-alert .material-symbols-outlined{font-size:20px}
    .is-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
    .is-stat{display:flex;align-items:center;gap:13px;min-width:0;padding:17px 19px;border:1px solid var(--is-line);border-radius:15px;background:#fff;box-shadow:0 4px 14px rgba(25,35,75,.035)}
    .is-stat-icon{display:grid;width:42px;height:42px;flex:0 0 42px;place-items:center;border-radius:12px;background:#eef0ff;color:#555fb0}.is-stat-icon.sick{background:#fff0f1;color:#c64c63}.is-stat-icon.wait{background:#fff6e8;color:#ba7a20}
    .is-stat-label{display:block;color:var(--is-muted);font-size:11px;font-weight:700}.is-stat-value{display:block;margin-top:2px;color:var(--is-ink);font-size:20px;font-weight:800}
    .is-panel{overflow:hidden;border:1px solid var(--is-line);border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(25,35,75,.045)}
    .is-panel-head{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:20px 22px;border-bottom:1px solid #edf0f5}
    .is-panel-head h2{margin:0;color:var(--is-ink);font-size:16px;font-weight:800}.is-panel-head p{margin:4px 0 0;color:var(--is-muted);font-size:12px}
    .is-count{white-space:nowrap;border-radius:999px;background:#f1f3fb;padding:6px 10px;color:#5c648b;font-size:11px;font-weight:800}
    .is-filters{display:flex;gap:10px;padding:15px 22px;border-bottom:1px solid #f0f2f7}
    .is-search{position:relative;flex:1;min-width:180px}.is-search .material-symbols-outlined{position:absolute;top:50%;left:12px;transform:translateY(-50%);color:#969db0;font-size:19px}.is-control{width:100%;height:41px;border:1px solid #e0e4ed;border-radius:9px;background:#fff;color:#39415b;font:inherit;font-size:12px;outline:none;transition:border-color .18s,box-shadow .18s}.is-search .is-control{padding:0 12px 0 39px}.is-select{width:155px;padding:0 11px}.is-control:focus{border-color:#8991d4;box-shadow:0 0 0 3px rgba(86,98,183,.1)}
    .is-table-wrap{overflow-x:auto}.is-table{width:100%;border-collapse:collapse;white-space:nowrap}.is-table th{padding:12px 16px;background:#f8f9fc;color:#8990a3;font-size:10px;font-weight:800;letter-spacing:.08em;text-align:left;text-transform:uppercase}.is-table td{padding:14px 16px;border-top:1px solid #f0f2f7;color:#545d74;font-size:12px}.is-table tbody tr{transition:background .15s}.is-table tbody tr:hover{background:#fafbff}.is-number{color:#a0a6b6!important;font-size:11px!important}.is-student{display:flex;align-items:center;gap:10px;color:var(--is-ink)!important;font-weight:800!important}.is-avatar{display:grid;width:34px;height:34px;flex:0 0 34px;place-items:center;border-radius:10px;background:#eef0ff;color:#5964ae;font-size:12px;font-weight:800}.is-type,.is-status{display:inline-flex;align-items:center;gap:5px;padding:5px 9px;border-radius:999px;font-size:10px;font-weight:800}.is-type-sakit{background:#fff0f1;color:#c64c63}.is-type-izin{background:#eef0ff;color:#5964ae}.is-status-approved{background:#edf8f1;color:#34815b}.is-status-pending{background:#fff6e8;color:#a66d17}.is-status-rejected{background:#fff0f0;color:#bd5252}.is-reason{display:block;max-width:220px;overflow:hidden;text-overflow:ellipsis}.is-actions{display:flex;align-items:center;justify-content:flex-end;gap:6px}.is-action{display:inline-flex;min-height:31px;align-items:center;justify-content:center;gap:5px;padding:6px 10px;border:1px solid #e5e8ef;border-radius:8px;background:white;color:#525c79;font-size:11px;font-weight:800;text-decoration:none;transition:.16s}.is-action:hover{border-color:#bbc2e6;background:#f6f7ff;color:var(--is-navy)}.is-action-edit{border-color:#f2dfbb;background:#fffaf0;color:#a76b13}.is-action-edit:hover{border-color:#e8c77e;background:#fff4dc;color:#87540b}.is-processed{color:#a1a6b4;font-size:11px}
    .is-mobile-list{display:none}.is-empty{padding:42px 18px;text-align:center}.is-empty-icon{display:grid;width:54px;height:54px;margin:0 auto 12px;place-items:center;border-radius:16px;background:#f2f3fb;color:#8189bd}.is-empty strong{display:block;color:var(--is-ink);font-size:14px}.is-empty p{margin:5px 0 0;color:var(--is-muted);font-size:12px}
    .is-pagination{padding:15px 20px;border-top:1px solid #f0f2f7}.is-pagination nav{display:flex;justify-content:space-between;align-items:center}.is-pagination svg{width:16px}
    @media(max-width:800px){.is-hero{align-items:flex-start;flex-direction:column}.is-hero-actions{width:100%}.is-hero-actions .is-button{flex:1}.is-stats{gap:9px}.is-stat{gap:9px;padding:13px}.is-stat-icon{width:36px;height:36px;flex-basis:36px}.is-stat-value{font-size:17px}}
    @media(max-width:600px){.is-page{gap:14px;padding:14px}.is-hero{gap:17px;padding:20px;border-radius:16px}.is-title{font-size:25px}.is-subtitle{font-size:12px}.is-hero-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px}.is-button{min-height:42px;padding:9px 10px;font-size:11px}.is-stats{grid-template-columns:1fr 1fr}.is-stat:last-child{grid-column:1/-1}.is-stat{padding:12px}.is-stat-value{font-size:16px}.is-panel{border-radius:13px}.is-panel-head{padding:16px}.is-filters{padding:12px;flex-direction:column}.is-search{min-width:0}.is-select{width:100%}.is-table-wrap{display:none}.is-mobile-list{display:grid;gap:10px;padding:12px}.is-record{padding:14px;border:1px solid #edf0f5;border-radius:12px;background:#fff}.is-record-top{display:flex;align-items:center;justify-content:space-between;gap:8px}.is-record-person{display:flex;min-width:0;align-items:center;gap:9px}.is-record-name{overflow:hidden;color:var(--is-ink);font-size:12px;font-weight:800;text-overflow:ellipsis;white-space:nowrap}.is-record-class{margin-top:3px;color:var(--is-muted);font-size:10px}.is-record-meta{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;color:#687189;font-size:10px}.is-record-reason{margin-top:9px;color:#535c74;font-size:11px;line-height:1.5}.is-record-actions{display:flex;gap:7px;margin-top:12px}.is-record-actions .is-action{flex:1}.is-pagination{overflow-x:auto;padding:12px}.is-count{font-size:10px}}
    @media(prefers-reduced-motion:reduce){.is-page *{scroll-behavior:auto!important;transition:none!important}}
</style>
@endpush

@section('content')
@php
    $currentRows = $dispens->getCollection();
    $sickCount = $currentRows->where('jenis', 'sakit')->count();
    $izinCount = $currentRows->where('jenis', '!=', 'sakit')->count();
    $pendingCount = $currentRows->count();
@endphp
<div class="is-page">
    <header class="is-hero">
        <div>
            <span class="is-eyebrow"><span class="material-symbols-outlined" style="font-size:16px">description</span> Administrasi Piket</span>
            <h1 class="is-title">Izin &amp; Sakit</h1>
            <p class="is-subtitle">Pantau dan kelola surat izin serta surat sakit siswa dalam satu tempat.</p>
        </div>
        <div class="is-hero-actions">
            <a class="is-button is-button-primary" href="{{ route('piket.izin-sakit.create', ['jenis' => 'izin']) }}"><span class="material-symbols-outlined" style="font-size:18px">add</span>Tambah Izin</a>
            <a class="is-button is-button-secondary" href="{{ route('piket.izin-sakit.create', ['jenis' => 'sakit']) }}"><span class="material-symbols-outlined" style="font-size:18px">add</span>Tambah Sakit</a>
        </div>
    </header>

    @if(session('success'))<div class="is-alert is-alert-success" role="status"><span class="material-symbols-outlined">check_circle</span>{{ session('success') }}</div>@endif
    @if(session('error'))<div class="is-alert is-alert-error" role="alert"><span class="material-symbols-outlined">error</span>{{ session('error') }}</div>@endif

    <section class="is-stats" aria-label="Ringkasan surat pada halaman ini">
        <div class="is-stat"><span class="is-stat-icon"><span class="material-symbols-outlined">event_available</span></span><span><span class="is-stat-label">Surat izin</span><strong class="is-stat-value">{{ $izinCount }}</strong></span></div>
        <div class="is-stat"><span class="is-stat-icon sick"><span class="material-symbols-outlined">medical_services</span></span><span><span class="is-stat-label">Surat sakit</span><strong class="is-stat-value">{{ $sickCount }}</strong></span></div>
        <div class="is-stat"><span class="is-stat-icon wait"><span class="material-symbols-outlined">summarize</span></span><span><span class="is-stat-label">Total data</span><strong class="is-stat-value">{{ $pendingCount }}</strong></span></div>
    </section>

    <section class="is-panel">
        <div class="is-panel-head"><div><h2>Daftar surat siswa</h2><p>Gunakan pencarian atau filter untuk menemukan data.</p></div><span class="is-count">{{ $dispens->total() }} data</span></div>
        <div class="is-filters">
            <label class="is-search"><span class="material-symbols-outlined">search</span><input id="record-search" class="is-control" type="search" placeholder="Cari nama, kelas, atau alasan..." aria-label="Cari nama siswa, kelas, atau alasan"></label>
            <select id="type-filter" class="is-control is-select" aria-label="Filter jenis surat"><option value="all">Semua jenis</option><option value="izin">Surat izin</option><option value="sakit">Surat sakit</option></select>
        </div>
        @if($dispens->isEmpty())
            <div class="is-empty"><span class="is-empty-icon"><span class="material-symbols-outlined">folder_open</span></span><strong>Belum ada surat</strong><p>Data surat izin atau sakit yang ditambahkan akan muncul di sini.</p></div>
        @else
            <div class="is-table-wrap"><table class="is-table"><thead><tr><th>No.</th><th>Siswa</th><th>Jenis</th><th>Tanggal</th><th>Alasan</th><th style="text-align:right">Aksi</th></tr></thead><tbody>
                @foreach($dispens as $dispen)
                    @php $isSick = $dispen->jenis === 'sakit'; $searchText = strtolower(($dispen->siswa->nama_siswa ?? '').' '.($dispen->siswa->kelas->nama_kelas ?? '').' '.$dispen->alasan); @endphp
                    <tr data-record data-type="{{ $isSick ? 'sakit' : 'izin' }}" data-search="{{ $searchText }}">
                        <td class="is-number">{{ $dispens->firstItem() + $loop->index }}</td>
                        <td><span class="is-student"><span class="is-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($dispen->siswa->nama_siswa ?? 'S', 0, 1)) }}</span>{{ $dispen->siswa->nama_siswa ?? '-' }}<small style="display:block;color:#9298a9;font-size:10px;font-weight:500">{{ $dispen->siswa->kelas->nama_kelas ?? 'Kelas tidak tersedia' }}</small></span></td>
                        <td><span class="is-type {{ $isSick ? 'is-type-sakit' : 'is-type-izin' }}"><span class="material-symbols-outlined" style="font-size:14px">{{ $isSick ? 'medical_services' : 'event_available' }}</span>{{ $isSick ? 'Sakit' : 'Izin' }}</span></td>
                        <td>{{ $dispen->tanggal?->format('d M Y') ?? '-' }}</td><td><span class="is-reason" title="{{ $dispen->alasan }}">{{ $dispen->alasan }}</span></td>
                        <td><div class="is-actions">
                            <a class="is-action is-action-edit" href="{{ route('piket.izin-sakit.edit', $dispen->id_dispen) }}"><span class="material-symbols-outlined" style="font-size:15px">edit</span>Edit</a>
                            @if($dispen->surat_path)<a class="is-action" href="{{ asset('storage/'.$dispen->surat_path) }}" target="_blank" rel="noopener"><span class="material-symbols-outlined" style="font-size:15px">open_in_new</span>Surat</a>@endif
                        </div></td>
                    </tr>
                @endforeach
            </tbody></table></div>
            <div class="is-mobile-list">
                @foreach($dispens as $dispen)
                    @php $isSick = $dispen->jenis === 'sakit'; $searchText = strtolower(($dispen->siswa->nama_siswa ?? '').' '.($dispen->siswa->kelas->nama_kelas ?? '').' '.$dispen->alasan); @endphp
                    <article class="is-record" data-record data-type="{{ $isSick ? 'sakit' : 'izin' }}" data-search="{{ $searchText }}">
                        <div class="is-record-top"><div class="is-record-person"><span class="is-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($dispen->siswa->nama_siswa ?? 'S', 0, 1)) }}</span><div style="min-width:0"><div class="is-record-name">{{ $dispen->siswa->nama_siswa ?? '-' }}</div><div class="is-record-class">{{ $dispen->siswa->kelas->nama_kelas ?? 'Kelas tidak tersedia' }}</div></div></div><span class="is-type {{ $isSick ? 'is-type-sakit' : 'is-type-izin' }}">{{ $isSick ? 'Sakit' : 'Izin' }}</span></div>
                        <div class="is-record-meta"><span><span class="material-symbols-outlined" style="font-size:13px;vertical-align:middle">calendar_today</span> {{ $dispen->tanggal?->format('d M Y') ?? '-' }}</span></div>
                        <div class="is-record-reason">{{ $dispen->alasan }}</div>
                        <div class="is-record-actions">
                            <a class="is-action is-action-edit" href="{{ route('piket.izin-sakit.edit', $dispen->id_dispen) }}">Edit</a>
                            @if($dispen->surat_path)<a class="is-action" href="{{ asset('storage/'.$dispen->surat_path) }}" target="_blank" rel="noopener">Lihat surat</a>@endif
                        </div>
                    </article>
                @endforeach
            </div>
            <div id="filter-empty" class="is-empty" hidden><span class="is-empty-icon"><span class="material-symbols-outlined">search_off</span></span><strong>Data tidak ditemukan</strong><p>Coba kata kunci atau jenis surat yang berbeda.</p></div>
            <div class="is-pagination">{{ $dispens->links() }}</div>
        @endif
    </section>
</div>
<script>
    (() => {
        const search = document.getElementById('record-search');
        const filter = document.getElementById('type-filter');
        if (!search || !filter) return;
        const records = [...document.querySelectorAll('[data-record]')];
        const empty = document.getElementById('filter-empty');
        const applyFilters = () => {
            const query = search.value.trim().toLocaleLowerCase('id');
            const type = filter.value;
            let visible = 0;
            records.forEach((record) => {
                const match = (!query || record.dataset.search.includes(query)) && (type === 'all' || record.dataset.type === type);
                record.hidden = !match;
                if (match) visible++;
            });
            empty.hidden = visible > 0;
        };
        search.addEventListener('input', applyFilters);
        filter.addEventListener('change', applyFilters);
    })();
</script>
@endsection
