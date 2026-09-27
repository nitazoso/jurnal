@extends('layouts.piket')

@section('title', 'Detail Jurnal - Jurnify')
@section('page-title', 'Detail Jurnal')

@push('styles')
<style>
    .journal-detail-page {
        --detail-primary: #30366f;
        --detail-accent: #4169ff;
        --detail-text: #1f2937;
        --detail-muted: #64748b;
        display: grid;
        gap: 18px;
        padding: 24px;
    }

    .journal-detail-header {
        align-items: flex-start;
        background: #eef2ff;
        border: 1px solid #dce4ff;
        border-left: 4px solid var(--detail-accent);
        border-radius: 10px;
        display: flex;
        gap: 20px;
        justify-content: space-between;
        padding: 20px 22px;
    }

    .journal-detail-eyebrow {
        color: var(--detail-primary);
        font-size: 11px;
        font-weight: 800;
        margin: 0 0 5px;
        text-transform: uppercase;
    }

    .journal-detail-title {
        color: var(--detail-text);
        font-size: 23px;
        font-weight: 800;
        line-height: 1.3;
        margin: 0;
        overflow-wrap: anywhere;
    }

    .journal-detail-subtitle {
        color: var(--detail-muted);
        font-size: 13px;
        margin: 5px 0 0;
    }

    .journal-detail-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .journal-detail-status {
        background: #fff;
        border: 1px solid #dbe3f0;
        border-radius: 999px;
        color: var(--detail-primary);
        font-size: 12px;
        font-weight: 800;
        padding: 7px 12px;
        white-space: nowrap;
    }

    .journal-detail-status.approved {
        background: #eaf8ef;
        border-color: #c7ead2;
        color: #187548;
    }

    .journal-detail-status.rejected {
        background: #fff0f0;
        border-color: #f4cccc;
        color: #a33232;
    }

    .journal-detail-status.revision {
        background: #fff7e8;
        border-color: #f1dfb5;
        color: #8b5a12;
    }

    .journal-detail-back {
        align-items: center;
        background: var(--detail-primary);
        border-radius: 7px;
        color: #fff;
        display: inline-flex;
        font-size: 13px;
        font-weight: 700;
        gap: 7px;
        min-height: 38px;
        padding: 8px 13px;
        text-decoration: none;
        white-space: nowrap;
    }

    .journal-detail-back:hover {
        background: #222956;
    }

    .journal-detail-panel {
        background: #fff;
        border: 1px solid #e1e7f0;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(26, 39, 73, .035);
        min-width: 0;
        padding: 20px;
    }

    .journal-detail-panel h2 {
        border-bottom: 1px solid #edf0f5;
        color: var(--detail-primary);
        font-size: 16px;
        font-weight: 800;
        margin: 0;
        padding: 0 0 12px;
    }

    .journal-detail-info {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin: 16px 0 0;
    }

    .journal-detail-info-item {
        background: #f8f9fc;
        border: 1px solid #edf0f5;
        border-radius: 7px;
        min-width: 0;
        padding: 11px 13px;
    }

    .journal-detail-info-item dt {
        color: var(--detail-muted);
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .journal-detail-info-item dd {
        color: var(--detail-text);
        font-size: 14px;
        font-weight: 700;
        margin: 0;
        overflow-wrap: anywhere;
    }

    .journal-detail-copy {
        color: #374151;
        font-size: 14px;
        line-height: 1.65;
        margin: 14px 0 0;
        overflow-wrap: anywhere;
        white-space: pre-line;
    }

    .journal-detail-attendance {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 15px;
    }

    .journal-detail-attendance-item {
        background: #eaf8ef;
        border: 1px solid #d3efdc;
        border-radius: 8px;
        color: #187548;
        min-width: 150px;
        padding: 12px 15px;
    }

    .journal-detail-attendance-item.absent {
        background: #fff5ed;
        border-color: #f5dfcc;
        color: #9a5723;
    }

    .journal-detail-attendance-item span {
        display: block;
        font-size: 11px;
        font-weight: 700;
    }

    .journal-detail-attendance-item strong {
        display: block;
        font-size: 22px;
        line-height: 1.2;
        margin-top: 3px;
    }

    .journal-detail-table-wrap {
        margin-top: 16px;
        overflow-x: auto;
    }

    .journal-detail-table {
        border-collapse: collapse;
        min-width: 560px;
        width: 100%;
    }

    .journal-detail-table th,
    .journal-detail-table td {
        border-bottom: 1px solid #edf0f5;
        font-size: 13px;
        padding: 11px 12px;
        text-align: left;
    }

    .journal-detail-table th {
        background: #f7f8fb;
        color: var(--detail-muted);
        font-size: 11px;
        text-transform: uppercase;
    }

    @media (max-width: 800px) {
        .journal-detail-info {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .journal-detail-page {
            gap: 13px;
            padding: 15px 12px;
        }

        .journal-detail-header {
            flex-direction: column;
            padding: 17px;
        }

        .journal-detail-title {
            font-size: 20px;
        }

        .journal-detail-actions {
            justify-content: flex-start;
        }

        .journal-detail-panel {
            padding: 16px;
        }

        .journal-detail-info {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<main class="journal-detail-page">
    @php
        $status = $jurnal->status_validasi_guru ?? '-';
        $statusClass = match ($status) {
            'Disetujui' => 'approved',
            'Ditolak' => 'rejected',
            'Perlu Diperbaiki' => 'revision',
            default => 'pending',
        };
    @endphp

    <header class="journal-detail-header">
        <div>
            <p class="journal-detail-eyebrow">Detail Aktivitas Jurnal</p>
            <h1 class="journal-detail-title">{{ $jurnal->jadwal?->mapel?->nama_mapel ?? 'Detail Jurnal' }}</h1>
            <p class="journal-detail-subtitle">
                {{ $jurnal->kelas?->nama_kelas ?? '-' }} · {{ $jurnal->tanggal?->translatedFormat('l, d F Y') ?? '-' }}
            </p>
        </div>
        <div class="journal-detail-actions">
            <span class="journal-detail-status {{ $statusClass }}">{{ $status }}</span>
            <a href="{{ route('piket.jurnal.rekap') }}" class="journal-detail-back">&larr; Kembali ke Rekap</a>
        </div>
    </header>

    <section class="journal-detail-panel">
        <h2>Informasi Pembelajaran</h2>
        <dl class="journal-detail-info">
            <div class="journal-detail-info-item"><dt>Tanggal</dt><dd>{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}</dd></div>
            <div class="journal-detail-info-item"><dt>Guru</dt><dd>{{ $jurnal->guru?->nama_guru ?? '-' }}</dd></div>
            <div class="journal-detail-info-item"><dt>Kelas</dt><dd>{{ $jurnal->kelas?->nama_kelas ?? '-' }}</dd></div>
            <div class="journal-detail-info-item"><dt>Mata Pelajaran</dt><dd>{{ $jurnal->jadwal?->mapel?->nama_mapel ?? '-' }}</dd></div>
            <div class="journal-detail-info-item"><dt>Jam Pelajaran</dt><dd>{{ $jurnal->jamMulai?->jam_ke ?? '-' }} - {{ $jurnal->jamSelesai?->jam_ke ?? '-' }}</dd></div>
            <div class="journal-detail-info-item"><dt>Status Kehadiran Guru</dt><dd>{{ $jurnal->status_kehadiran_validasi ?? $jurnal->status_guru ?? '-' }}</dd></div>
            @if (($jurnal->status_kehadiran_validasi ?? null) === 'Tidak Hadir')
                <div class="journal-detail-info-item"><dt>Alasan Tidak Hadir</dt><dd>{{ $jurnal->status_guru }}</dd></div>
            @endif
        </dl>
    </section>

    <section class="journal-detail-panel">
        <h2>Materi Pembelajaran</h2>
        <p class="journal-detail-copy">{{ $jurnal->materi ?: '-' }}</p>
    </section>

    <section class="journal-detail-panel">
        <h2>Kehadiran Siswa</h2>
        <div class="journal-detail-attendance">
            <div class="journal-detail-attendance-item">
                <span>Siswa Hadir</span>
                <strong>{{ $jurnal->jml_hadir ?? 0 }}</strong>
            </div>
            <div class="journal-detail-attendance-item absent">
                <span>Siswa Tidak Hadir</span>
                <strong>{{ $jurnal->jml_tidak_hadir ?? 0 }}</strong>
            </div>
        </div>
        @if ($jurnal->detailAbsensis->isNotEmpty())
            <div class="journal-detail-table-wrap">
                <table class="journal-detail-table">
                    <thead>
                        <tr><th>Nama Siswa</th><th>Status</th><th>Keterangan</th><th>Surat</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($jurnal->detailAbsensis as $absensi)
                            <tr>
                                <td>{{ $absensi->siswa?->nama_siswa ?? '-' }}</td>
                                <td>{{ $absensi->status ?? '-' }}</td>
                                <td>{{ $absensi->keterangan ?? '-' }}</td>
                                <td>
                                    @if ($absensi->dispen?->surat_path)
                                        <a href="{{ asset('storage/' . $absensi->dispen->surat_path) }}" target="_blank" rel="noopener">Lihat foto surat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="journal-detail-panel">
        <h2>Tugas / Penugasan</h2>
        <p class="journal-detail-copy">{{ $jurnal->ada_tugas ?? 'Tidak ada' }}</p>
        @if ($jurnal->deskripsi_tugas)
            <p class="journal-detail-copy">{{ $jurnal->deskripsi_tugas }}</p>
        @endif
    </section>

    @if ($jurnal->catatan_umum || $jurnal->catatan_revisi)
        <section class="journal-detail-panel">
            <h2>Catatan</h2>
            @if ($jurnal->catatan_umum)
                <p class="journal-detail-copy">{{ $jurnal->catatan_umum }}</p>
            @endif
            @if ($jurnal->catatan_revisi)
                <p class="journal-detail-copy"><strong>Revisi:</strong> {{ $jurnal->catatan_revisi }}</p>
            @endif
        </section>
    @endif

    @if ($jurnal->validated_at)
        <section class="journal-detail-panel">
            <h2>VALIDASI OLEH</h2>
            <dl class="journal-detail-info">
                <div class="journal-detail-info-item"><dt>Validator</dt><dd>Sekretaris<br>{{ $jurnal->validator?->nama_user ?? '-' }}</dd></div>
                <div class="journal-detail-info-item"><dt>Tanggal Validasi</dt><dd>{{ $jurnal->validated_at->copy()->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}</dd></div>
                <div class="journal-detail-info-item"><dt>Waktu Validasi</dt><dd>{{ $jurnal->validated_at->copy()->timezone('Asia/Jakarta')->format('H:i') }}</dd></div>
                <div class="journal-detail-info-item"><dt>Status kehadiran guru</dt><dd>{{ $jurnal->status_kehadiran_validasi }}</dd></div>
            </dl>
        </section>
    @endif

    <section class="journal-detail-panel">
        <h2>Diisi Oleh</h2>
        <dl class="journal-detail-info">
            <div class="journal-detail-info-item"><dt>Nama</dt><dd>{{ $jurnal->user?->nama_user ?? '-' }}</dd></div>
            <div class="journal-detail-info-item"><dt>Username</dt><dd>{{ $jurnal->user?->username ?? '-' }}</dd></div>
            <div class="journal-detail-info-item"><dt>Peran</dt><dd>{{ $jurnal->user?->role ?? '-' }}</dd></div>
            <div class="journal-detail-info-item"><dt>Nomor WhatsApp</dt><dd>{{ $jurnal->user?->no_wa ?: '-' }}</dd></div>
            <div class="journal-detail-info-item"><dt>ID Pengisi</dt><dd>{{ $jurnal->id_user }}</dd></div>
            <div class="journal-detail-info-item"><dt>Waktu Input</dt><dd>{{ $jurnal->created_at?->copy()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') ?? '-' }}</dd></div>
        </dl>
    </section>
</main>
@endsection