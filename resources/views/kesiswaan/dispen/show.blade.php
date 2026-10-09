@extends('layouts.kesiswaan')

@section('title', 'Detail Dispen - Jurnify')
@section('page-title', 'Detail Pengajuan Dispen')

@section('content')
<style>
    .dispen-detail {
        max-width: 900px;
    }

    .dispen-detail__header,
    .dispen-detail__panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .dispen-detail__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }

    .dispen-detail__header h2,
    .dispen-detail__panel h3 {
        margin: 0 0 8px;
        color: #172033;
    }

    .dispen-detail__header p,
    .dispen-detail__panel p {
        margin: 0;
        color: #64748b;
    }

    .dispen-detail__back {
        display: inline-block;
        margin-bottom: 16px;
        color: #2563eb;
        text-decoration: none;
    }

    .dispen-detail__status {
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        padding: 7px 12px;
        white-space: nowrap;
    }

    .dispen-detail__status--menunggu { background: #fef3c7; color: #92400e; }
    .dispen-detail__status--disetujui { background: #dcfce7; color: #166534; }
    .dispen-detail__status--ditolak { background: #fee2e2; color: #991b1b; }

    .dispen-detail__data {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 28px;
        margin: 0;
    }

    .dispen-detail__data div { border-bottom: 1px solid #eef2f7; padding-bottom: 12px; }
    .dispen-detail__data dt { color: #64748b; font-size: 13px; margin-bottom: 5px; }
    .dispen-detail__data dd { color: #172033; font-weight: 600; margin: 0; }

    .dispen-detail__actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .dispen-detail__form {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 18px;
    }

    .dispen-detail__form label { display: block; font-weight: 600; margin-bottom: 8px; }
    .dispen-detail__form textarea { box-sizing: border-box; min-height: 90px; padding: 10px; resize: vertical; width: 100%; }
    .dispen-detail__button { border: 0; border-radius: 6px; color: #fff; cursor: pointer; font-weight: 700; margin-top: 12px; padding: 10px 14px; }
    .dispen-detail__button--approve { background: #15803d; }
    .dispen-detail__button--reject { background: #b91c1c; }
    .dispen-detail__error { color: #b91c1c; margin: 0 0 16px; }

    @media (max-width: 640px) {
        .dispen-detail__header,
        .dispen-detail__actions { display: block; }
        .dispen-detail__status { display: inline-block; margin-top: 14px; }
        .dispen-detail__form + .dispen-detail__form { margin-top: 16px; }
        .dispen-detail__data { grid-template-columns: 1fr; }
    }
</style>

<div class="dispen-detail">
    <a class="dispen-detail__back" href="{{ route('kesiswaan.dispen.index') }}">&larr; Kembali ke daftar pengajuan</a>

    <div class="dispen-detail__header">
        <div>
            <h2>Detail Pengajuan Dispen</h2>
            <p>Periksa data pengajuan sebelum memberikan keputusan.</p>
        </div>
        <span class="dispen-detail__status dispen-detail__status--{{ $dispen->status }}">
            {{ ucfirst($dispen->status) }}
        </span>
    </div>

@if ($errors->any())
    <ul class="dispen-detail__error">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

    <section class="dispen-detail__panel">
        <h3>Informasi Pengajuan</h3>
        <dl class="dispen-detail__data">
            <div><dt>Nama siswa</dt><dd>{{ $dispen->siswa->nama_siswa ?? '-' }}</dd></div>
            <div><dt>Kelas</dt><dd>{{ $dispen->siswa->kelas->nama_kelas ?? '-' }}</dd></div>
            <div><dt>Tanggal</dt><dd>{{ $dispen->tanggal?->format('d-m-Y') ?? '-' }}</dd></div>
            <div><dt>Jam dispen</dt><dd>{{ $dispen->jamMulai->jam_mulai ?? '-' }} - {{ $dispen->jamSelesai->jam_selesai ?? '-' }}</dd></div>
            <div style="grid-column: 1 / -1"><dt>Alasan</dt><dd>{{ $dispen->alasan ?: '-' }}</dd></div>
        </dl>
    </section>

@if ($dispen->status === 'menunggu')
    <div class="dispen-detail__actions">
        <form class="dispen-detail__form" action="{{ route('kesiswaan.dispen.approve', $dispen) }}" method="POST">
            @csrf
            <label for="approve-note">Catatan persetujuan (opsional)</label>
            <textarea id="approve-note" name="catatan_persetujuan" placeholder="Tambahkan catatan jika diperlukan"></textarea>
            <button class="dispen-detail__button dispen-detail__button--approve" type="submit">Setujui Dispen</button>
        </form>

        <form class="dispen-detail__form" action="{{ route('kesiswaan.dispen.reject', $dispen) }}" method="POST">
            @csrf
            <label for="reject-note">Alasan penolakan <span aria-hidden="true">*</span></label>
            <textarea id="reject-note" name="catatan_persetujuan" placeholder="Tuliskan alasan penolakan" required></textarea>
            <button class="dispen-detail__button dispen-detail__button--reject" type="submit">Tolak Dispen</button>
        </form>
    </div>
@else
    <section class="dispen-detail__panel">
        <h3>Catatan Keputusan</h3>
        <p>Pengajuan ini sudah {{ $dispen->status }}{{ $dispen->disetujui_pada ? ' pada '.$dispen->disetujui_pada->format('d-m-Y H:i') : '' }}.</p>
        @if ($dispen->catatan_persetujuan)
            <p style="margin-top: 10px"><strong>Catatan:</strong> {{ $dispen->catatan_persetujuan }}</p>
        @endif
    </section>
@endif
</div>
@endsection
