@extends('layouts.guru')

@section('title', 'Konfirmasi Kehadiran Guru - Jurnify')
@section('page-title', 'Konfirmasi Kehadiran Guru')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <section style="background: #fff; border: 1px solid #dbe4f0; border-radius: 18px; padding: 28px; text-align: center;">
        <div style="align-items: center; background: #dcfce7; border-radius: 999px; color: #166534; display: inline-flex; font-weight: 800; gap: 8px; padding: 9px 14px;">
            <span style="font-size: 18px;">✓</span>
            QR kelas berhasil dibaca
        </div>

        <h1 style="color: #1e293b; font-size: 25px; margin: 22px 0 8px;">Konfirmasi Kehadiran Guru</h1>
        <p style="color: #64748b; margin: 0 0 24px;">Pastikan data kelas sesuai sebelum mengonfirmasi kehadiran.</p>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px; padding: 18px; text-align: left;">
            <p style="color: #64748b; margin: 0 0 10px;"><strong>Kelas:</strong> {{ $jadwal->kelas->nama_kelas ?? '-' }}</p>
            <p style="color: #64748b; margin: 0 0 10px;"><strong>Mata Pelajaran:</strong> {{ $jadwal->mapel->nama_mapel ?? '-' }}</p>
            <p style="color: #64748b; margin: 0 0 10px;"><strong>Jam:</strong> {{ $jadwal->jamMulai->jam_ke ?? '-' }} - {{ $jadwal->jamSelesai->jam_ke ?? '-' }}</p>
            <p style="color: #64748b; margin: 0;"><strong>Tanggal:</strong> {{ now()->format('d-m-Y') }}</p>
        </div>

        <form action="{{ route('guru.jurnal.store-attendance', $jadwal) }}" method="POST">
            @csrf
            <button type="submit" style="background: #16a34a; border: 0; border-radius: 9px; color: #fff; cursor: pointer; font-size: 15px; font-weight: 800; padding: 13px 20px;">
                Konfirmasi Hadir di Kelas Ini
            </button>
        </form>

        <a href="{{ route('guru.jurnal.form', $jadwal) }}" style="color: #64748b; display: inline-block; margin-top: 16px; text-decoration: none;">Kembali ke isi jurnal</a>
    </section>
</div>
@endsection