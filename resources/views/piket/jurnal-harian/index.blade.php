@extends('layouts.piket')

@section('title', 'Jurnal Harian - Jurnify')
@section('page-title', 'Jurnal Harian')

@push('styles')
<style>
    .daily-page { max-width: 1180px; margin: 0 auto; color: #1e293b; }
    .daily-head { display:flex; justify-content:space-between; align-items:flex-end; gap:20px; margin-bottom:26px; }
    .daily-heading h1 { margin:0; font-size:24px; font-weight:800; color:#17265d; }
    .daily-heading p { margin:6px 0 0; color:#64748b; font-size:13px; }
    .daily-filter { display:flex; align-items:flex-end; gap:10px; padding:14px; border:1px solid #e2e8f0; border-radius:14px; background:#fff; }
    .daily-filter label { display:block; margin-bottom:5px; color:#475569; font-size:12px; font-weight:700; }
    .daily-filter input { height:40px; padding:0 11px; border:1px solid #cbd5e1; border-radius:9px; color:#334155; font:inherit; }
    .daily-filter button,.daily-link { display:inline-flex; min-height:40px; align-items:center; justify-content:center; gap:7px; padding:0 14px; border:0; border-radius:9px; background:#30366f; color:white; font:inherit; font-size:12px; font-weight:800; text-decoration:none; cursor:pointer; }
    .daily-filter button:hover,.daily-link:hover { background:#252b5d; }
    .daily-label { margin:0 0 13px; color:#334155; font-size:15px; font-weight:800; }
    .daily-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(245px,1fr)); gap:16px; }
    .daily-card { display:flex; min-height:190px; flex-direction:column; padding:19px; border:1px solid #e2e8f0; border-radius:15px; background:#fff; box-shadow:0 3px 12px rgba(15,23,42,.04); }
    .daily-card-top { display:flex; align-items:center; gap:11px; }
    .daily-icon { display:grid; width:42px; height:42px; place-items:center; border-radius:12px; background:#eef2ff; color:#30366f; }
    .daily-card h2 { margin:0; color:#1e293b; font-size:16px; font-weight:800; }
    .daily-count { margin:19px 0 7px; color:#17265d; font-size:27px; font-weight:850; }
    .daily-count small { color:#64748b; font-size:13px; font-weight:600; }
    .daily-status { display:flex; align-items:center; gap:7px; margin:0 0 17px; color:#b45309; font-size:12px; font-weight:750; }
    .daily-status.done { color:#047857; }
    .daily-status .material-symbols-outlined { font-size:17px; }
    .daily-link { align-self:flex-start; margin-top:auto; min-height:36px; }
    .daily-empty { padding:35px 20px; border:1px dashed #cbd5e1; border-radius:14px; background:#fff; color:#64748b; text-align:center; }
    @media(max-width:650px) { .daily-head { align-items:stretch; flex-direction:column; } .daily-filter { justify-content:space-between; } .daily-filter input { width:100%; min-width:0; } }
</style>
@endpush

@section('content')
<div class="daily-page">
    <div class="daily-head">
        <div class="daily-heading">
            <h1>Jurnal Harian</h1>
            <p>Ringkasan jurnal yang diisi setiap kelas pada tanggal terpilih.</p>
        </div>
        <form class="daily-filter" method="GET" action="{{ route('piket.jurnal-harian.index') }}">
            <div>
                <label for="tanggal">Tanggal</label>
                <input id="tanggal" type="date" name="tanggal" value="{{ $tanggal }}" required>
            </div>
            <button type="submit"><span class="material-symbols-outlined">calendar_month</span>Pilih tanggal</button>
        </form>
    </div>

    <h2 class="daily-label">Kelas</h2>
    @if($kelases->isEmpty())
        <div class="daily-empty">Belum ada data kelas.</div>
    @else
        <div class="daily-grid">
            @foreach($kelases as $kelas)
                @php
                    $sudahDiapprove = $kelas->jumlah_layak_approve_harian > 0
                        && $kelas->jumlah_sudah_diapprove_harian >= $kelas->jumlah_layak_approve_harian;
                @endphp
                <article class="daily-card">
                    <div class="daily-card-top">
                        <span class="daily-icon material-symbols-outlined">school</span>
                        <h2>{{ $kelas->nama_kelas }}</h2>
                    </div>
                    <p class="daily-count">{{ $kelas->jumlah_jurnal_harian }} / {{ $kelas->jumlah_jurnal_wajib_harian }} <small>jurnal</small></p>
                    <p class="daily-status {{ $sudahDiapprove ? 'done' : '' }}">
                        <span class="material-symbols-outlined">{{ $sudahDiapprove ? 'task_alt' : 'pending' }}</span>
                        {{ $sudahDiapprove ? 'Sudah di-approve' : 'Belum di-approve' }}
                    </p>
                    <a class="daily-link" href="{{ route('piket.jurnal-harian.kelas', ['kelas' => $kelas->id_kelas, 'tanggal' => $tanggal]) }}">Lihat Jurnal</a>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
