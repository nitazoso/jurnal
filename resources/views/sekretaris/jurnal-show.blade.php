@extends('layouts.sekretaris')

@section('title', 'Detail Jurnal Guru')
@section('page-title', 'Detail Jurnal Guru')
@section('page-subtitle', 'Periksa isi jurnal yang dikirim guru sebelum validasi')

@section('content')
<div class="journal-review-page">
    <div class="review-header">
        <div>
            <span class="eyebrow">Review jurnal</span>
            <h2>{{ $jurnal->kelas->nama_kelas ?? '-' }}</h2>
            <p>{{ $jurnal->guru->nama_guru ?? '-' }} • {{ $jurnal->tanggal ? $jurnal->tanggal->translatedFormat('d F Y') : '-' }}</p>
        </div>
        <a href="{{ route('sekretaris.validasi-jurnal') }}" class="back-btn">Kembali</a>
    </div>

    <div class="review-grid">
        <div class="review-card">
            <h3>Informasi umum</h3>
            <ul>
                <li><strong>Guru:</strong> {{ $jurnal->guru->nama_guru ?? '-' }}</li>
                <li><strong>Kelas:</strong> {{ $jurnal->kelas->nama_kelas ?? '-' }}</li>
                <li><strong>Mata Pelajaran:</strong> {{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}</li>
                <li><strong>Jam:</strong> {{ $jurnal->jamMulai->jam_ke ?? '-' }} - {{ $jurnal->jamSelesai->jam_ke ?? '-' }}</li>
                <li><strong>Status guru:</strong> {{ $jurnal->status_guru ?? '-' }}</li>
                <li><strong>Hadir:</strong> {{ $jurnal->jml_hadir ?? 0 }} • <strong>Absen:</strong> {{ $jurnal->jml_tidak_hadir ?? 0 }}</li>
            </ul>
        </div>

        <div class="review-card">
            <h3>Isi jurnal guru</h3>
            <div class="field-box">
                <label>Materi</label>
                <p>{{ $jurnal->materi ?: 'Belum ada materi yang diisi guru.' }}</p>
            </div>

            <div class="field-box">
                <label>Catatan umum</label>
                <p>{{ $jurnal->catatan_umum ?: 'Tidak ada catatan umum.' }}</p>
            </div>

            <div class="field-box">
                <label>Deskripsi tugas</label>
                <p>{{ $jurnal->deskripsi_tugas ?: 'Tidak ada tugas yang dicatat.' }}</p>
            </div>
        </div>
    </div>

    @if($jurnal->status_validasi_guru === 'Menunggu')
        <div class="review-actions">
            <form method="POST" action="{{ route('sekretaris.validasi-jurnal.update', $jurnal) }}" class="inline-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_validasi_guru" value="Disetujui">
                <button type="submit" class="approve-btn">Setujui jurnal</button>
            </form>

            <form method="POST" action="{{ route('sekretaris.validasi-jurnal.update', $jurnal) }}" class="inline-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_validasi_guru" value="Perlu Diperbaiki">
                <div class="revisi-wrap">
                    <label for="catatan_revisi">Catatan revisi</label>
                    <textarea name="catatan_revisi" id="catatan_revisi" rows="3" placeholder="Tuliskan alasan revisi..."></textarea>
                </div>
                <button type="submit" class="revise-btn">Minta perbaikan</button>
            </form>
        </div>
    @endif
</div>

<style>
    .journal-review-page {
        padding: 16px 4px 30px;
        color: #0f172a;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        margin-bottom: 20px;
    }
    .eyebrow {
        display: inline-block;
        font-size: 11px;
        letter-spacing: 0.12em;
        color: #4f46e5;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .review-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
    }
    .review-header p {
        margin: 6px 0 0;
        color: #475569;
    }
    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #0f172a;
        text-decoration: none;
        font-weight: 700;
    }
    .review-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }
    .review-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.03);
    }
    .review-card h3 {
        margin-top: 0;
        font-size: 18px;
        margin-bottom: 14px;
    }
    .review-card ul {
        margin: 0;
        padding-left: 18px;
        line-height: 2;
    }
    .field-box {
        padding: 12px 0;
        border-top: 1px solid #f1f5f9;
    }
    .field-box:first-of-type {
        border-top: 0;
        padding-top: 0;
    }
    .field-box label {
        display: block;
        font-size: 12px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .field-box p {
        margin: 0;
        color: #0f172a;
        line-height: 1.7;
    }
    .review-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        align-items: flex-start;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
    }
    .inline-form {
        flex: 1;
        min-width: 240px;
    }
    .approve-btn,
    .revise-btn {
        border: 0;
        border-radius: 10px;
        padding: 12px 18px;
        font-weight: 800;
        cursor: pointer;
    }
    .approve-btn {
        background: #16a34a;
        color: white;
    }
    .revise-btn {
        margin-top: 10px;
        background: #f59e0b;
        color: white;
    }
    .revisi-wrap {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 10px;
    }
    .revisi-wrap label {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
    }
    .revisi-wrap textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 12px 14px;
        resize: vertical;
    }
    @media (max-width: 768px) {
        .review-grid {
            grid-template-columns: 1fr;
        }
        .review-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection
