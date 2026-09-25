@extends('layouts.sekretaris')

@section('title', 'Detail Jurnal Guru')
@section('page-title', 'Detail Jurnal Guru')
@section('page-subtitle', 'Periksa isi jurnal yang dikirim guru sebelum melakukan validasi')

@section('content')

<div class="journal-review-page">

    {{-- Header --}}
    <div class="review-header">
        <div class="header-info">
            <span class="eyebrow">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                Review Jurnal
            </span>

            <h2>{{ $jurnal->kelas->nama_kelas ?? '-' }}</h2>

            <p class="meta-desc">
                <span>{{ $jurnal->guru->nama_guru ?? '-' }}</span>
                <span class="dot">•</span>
                <span>{{ $jurnal->tanggal ? $jurnal->tanggal->translatedFormat('d F Y') : '-' }}</span>
            </p>
        </div>

        <a href="{{ route('sekretaris.validasi-jurnal') }}" class="back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Main Content --}}
    <div class="review-grid">

        {{-- Informasi Umum --}}
        <div class="review-card">
            <div class="card-header">
                <div class="icon-avatar indigo">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <h3>Informasi Umum</h3>
            </div>

            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Guru Pengajar</span>
                    <span class="info-value font-semibold">
                        {{ $jurnal->guru->nama_guru ?? '-' }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Kelas</span>
                    <span class="info-value">
                        {{ $jurnal->kelas->nama_kelas ?? '-' }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Mata Pelajaran</span>
                    <span class="info-value font-semibold text-indigo">
                        {{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Jam Ke-</span>
                    <span class="info-value">
                        Jam {{ $jurnal->jamMulai->jam_ke ?? '-' }} -
                        {{ $jurnal->jamSelesai->jam_ke ?? '-' }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Status Kehadiran Guru</span>
                    <span class="badge badge-guru">
                        {{ $jurnal->status_guru ?? '-' }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Presensi Siswa</span>

                    <div class="attendance-pills">
                        <span class="pill pill-success">
                            {{ $jurnal->jml_hadir ?? 0 }} Hadir
                        </span>

                        <span class="pill pill-danger">
                            {{ $jurnal->jml_tidak_hadir ?? 0 }} Absen
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Isi Jurnal --}}
        <div class="review-card">
            <div class="card-header">
                <div class="icon-avatar emerald">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                </div>
                <h3>Isi Jurnal Guru</h3>
            </div>

            <div class="field-box">
                <label>Materi Pembelajaran</label>

                <div class="content-body">
                    {{ $jurnal->materi ?: 'Belum ada materi yang diisi guru.' }}
                </div>
            </div>

            <div class="field-box">
                <label>Catatan Umum</label>

                <div class="content-body {{ !$jurnal->catatan_umum ? 'text-muted' : '' }}">
                    {{ $jurnal->catatan_umum ?: 'Tidak ada catatan umum.' }}
                </div>
            </div>

            <div class="field-box">
                <label>Deskripsi Tugas</label>

                <div class="content-body {{ !$jurnal->deskripsi_tugas ? 'text-muted' : '' }}">
                    {{ $jurnal->deskripsi_tugas ?: 'Tidak ada tugas yang dicatat.' }}
                </div>
            </div>
        </div>

    </div>

    {{-- Validation Actions --}}
    @if($jurnal->status_validasi_guru === 'Menunggu')

        <div class="review-actions-card">

            <div class="actions-header">
                <h3>Aksi Validasi Jurnal</h3>
                <p>
                    Silakan pilih untuk menyetujui jurnal ini atau meminta perbaikan kepada guru bersangkutan.
                </p>
            </div>

            <div class="action-grid">

                {{-- Approve --}}
                <div class="action-box approve-box">
                    <div>
                        <h4>Setujui Langsung</h4>
                        <p>
                            Jurnal sudah sesuai dan tidak memerlukan revisi.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('sekretaris.validasi-jurnal.update', $jurnal) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status_validasi_guru" value="Disetujui">

                        <button type="submit" class="btn btn-approve">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Setujui Jurnal
                        </button>
                    </form>
                </div>

                {{-- Revision --}}
                <div class="action-box revise-box">
                    <div>
                        <h4>Minta Perbaikan (Revisi)</h4>
                        <p>
                            Berikan catatan perbaikan agar guru dapat memperbaruinya.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('sekretaris.validasi-jurnal.update', $jurnal) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status_validasi_guru" value="Perlu Diperbaiki">

                        <div class="revisi-wrap">
                            <textarea
                                name="catatan_revisi"
                                id="catatan_revisi"
                                rows="3"
                                placeholder="Tuliskan catatan revisi/alasan perbaikan secara jelas..."
                                required
                            ></textarea>
                        </div>

                        <button type="submit" class="btn btn-revise">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M21.5 2v6h-6"/>
                                <path d="M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                            </svg>
                            Kirim Permintaan Perbaikan
                        </button>
                    </form>
                </div>

            </div>
        </div>

    @endif

</div>

<style>
    .journal-review-page {
        width: 100%;
        padding: 4px 0 40px;
        color: #0f172a;
    }

    /* Header */
    .review-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
    }

    .header-info {
        min-width: 0;
    }

    .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 7px;
        color: #4f46e5;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .review-header h2 {
        margin: 0;
        color: #0f172a;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.02em;
    }

    .meta-desc {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .meta-desc .dot {
        color: #cbd5e1;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex-shrink: 0;
        padding: 10px 17px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        color: #334155;
        font-size: 14px;
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
        transition: .2s ease;
    }

    .back-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #0f172a;
    }

    /* Main Grid */
    .review-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    /* Cards */
    .review-card,
    .review-actions-card {
        min-width: 0;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, .06);
    }

    .review-card {
        padding: 22px;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-header h3 {
        margin: 0;
        color: #0f172a;
        font-size: 17px;
        font-weight: 800;
    }

    .icon-avatar {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 36px;
        border-radius: 10px;
    }

    .icon-avatar.indigo {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .icon-avatar.emerald {
        background: #d1fae5;
        color: #059669;
    }

    /* Information */
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        min-height: 48px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }

    .info-item:first-child {
        padding-top: 0;
    }

    .info-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .info-label {
        color: #64748b;
        flex-shrink: 0;
    }

    .info-value {
        max-width: 65%;
        color: #0f172a;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .font-semibold {
        font-weight: 700;
    }

    .text-indigo {
        color: #4f46e5;
    }

    /* Badge */
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 10px;
        border-radius: 20px;
        background: #f1f5f9;
        color: #334155;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .badge-guru {
        background: #e0e7ff;
        color: #4338ca;
    }

    /* Attendance */
    .attendance-pills {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 6px;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .pill-success {
        background: #ecfdf5;
        color: #047857;
    }

    .pill-danger {
        background: #fef2f2;
        color: #b91c1c;
    }

    /* Journal Fields */
    .field-box {
        margin-bottom: 16px;
    }

    .field-box:last-child {
        margin-bottom: 0;
    }

    .field-box label {
        display: block;
        margin-bottom: 7px;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .content-body {
        min-height: 46px;
        padding: 12px 14px;
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        background: #f8fafc;
        color: #0f172a;
        font-size: 13px;
        line-height: 1.6;
        overflow-wrap: anywhere;
        white-space: pre-line;
    }

    .text-muted {
        color: #64748b;
        font-style: italic;
    }

    /* Validation Actions */
    .review-actions-card {
        padding: 22px;
    }

    .actions-header {
        margin-bottom: 18px;
    }

    .actions-header h3 {
        margin: 0 0 5px;
        color: #0f172a;
        font-size: 18px;
        font-weight: 800;
    }

    .actions-header p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .action-grid {
        display: grid;
        grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
        gap: 16px;
    }

    .action-box {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-width: 0;
        padding: 18px;
        border-radius: 12px;
    }

    .action-box h4 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 800;
    }

    .action-box p {
        margin: 0 0 16px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
    }

    .approve-box {
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
    }

    .approve-box h4 {
        color: #166534;
    }

    .revise-box {
        border: 1px solid #fde68a;
        background: #fffbeb;
    }

    .revise-box h4 {
        color: #92400e;
    }

    /* Form */
    .revisi-wrap textarea {
        display: block;
        width: 100%;
        min-height: 82px;
        margin-bottom: 12px;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        outline: none;
        background: #fff;
        color: #0f172a;
        font-family: inherit;
        font-size: 13px;
        line-height: 1.5;
        resize: vertical;
        transition: .2s ease;
    }

    .revisi-wrap textarea::placeholder {
        color: #94a3b8;
    }

    .revisi-wrap textarea:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, .12);
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 11px 16px;
        border: 0;
        border-radius: 9px;
        color: #fff;
        font-family: inherit;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-approve {
        background: #10b981;
    }

    .btn-approve:hover {
        background: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, .22);
    }

    .btn-revise {
        background: #f59e0b;
    }

    .btn-revise:hover {
        background: #d97706;
        box-shadow: 0 4px 12px rgba(245, 158, 11, .22);
    }

    /* Responsive */
    @media (max-width: 1000px) {
        .review-grid {
            grid-template-columns: 1fr;
        }

        .action-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 760px) {
        .review-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .back-btn {
            width: 100%;
        }

        .action-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 520px) {
        .journal-review-page {
            padding-top: 0;
        }

        .review-header h2 {
            font-size: 22px;
        }

        .review-card,
        .review-actions-card {
            padding: 16px;
            border-radius: 14px;
        }

        .info-item {
            align-items: flex-start;
            flex-direction: column;
            gap: 5px;
        }

        .info-value {
            max-width: 100%;
            text-align: left;
        }

        .attendance-pills {
            justify-content: flex-start;
        }

        .meta-desc {
            font-size: 13px;
        }
    }
</style>

@endsection