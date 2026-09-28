@extends('layouts.guru')

@section('title', 'Rekap Jurnal Wali Kelas - Jurnify')
@section('page-title', 'Rekap Jurnal Wali Kelas')
@section('page-subtitle', 'Pantau jurnal yang diisi oleh seluruh guru di kelas wali Anda.')

@section('content')
<div class="homeroom-recap-page">
    <section class="homeroom-recap-heading">
        <div>
            <span class="homeroom-recap-eyebrow">WALI KELAS</span>
            <h1>Rekap Jurnal Kelas {{ $selectedKelas->nama_kelas }}</h1>
            <p>Rekap ini mencakup jurnal dari semua guru yang mengajar di kelas pilihan.</p>
        </div>

        <form method="GET" action="{{ route('guru.jurnal.wali-kelas-rekap') }}" class="homeroom-recap-filters">
            <label>
                <span>Kelas</span>
                <select name="id_kelas">
                    @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id_kelas }}" @selected((int) $selectedKelas->id_kelas === (int) $kelas->id_kelas)>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label>
                <span>Bulan</span>
                <input type="month" name="bulan" value="{{ $validated['bulan'] ?? '' }}">
            </label>

            <label>
                <span>Status jurnal</span>
                <select name="status">
                    <option value="">Semua status</option>
                    @foreach(['Menunggu', 'Disetujui', 'Perlu Diperbaiki', 'Ditolak'] as $status)
                        <option value="{{ $status }}" @selected(($validated['status'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </label>

            <button type="submit">Terapkan</button>
        </form>
    </section>

    <section class="homeroom-recap-summary" aria-label="Ringkasan jurnal kelas">
        <div><span>Total jurnal</span><strong>{{ number_format($summary['total']) }}</strong></div>
        <div><span>Menunggu</span><strong>{{ number_format($summary['menunggu']) }}</strong></div>
        <div><span>Disetujui</span><strong>{{ number_format($summary['disetujui']) }}</strong></div>
        <div><span>Perlu ditinjau</span><strong>{{ number_format($summary['perlu_diperbaiki']) }}</strong></div>
    </section>

    <section class="homeroom-recap-table-section">
        <div class="homeroom-recap-table-wrap">
            <table class="homeroom-recap-table">
                <thead>
                    <tr>
                        <th>Tanggal / Jam</th>
                        <th>Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Materi</th>
                        <th>Kehadiran</th>
                        <th>Status jurnal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnals as $jurnal)
                        @php
                            $statusClass = match ($jurnal->status_validasi_guru) {
                                'Disetujui' => 'is-approved',
                                'Ditolak', 'Perlu Diperbaiki' => 'is-review',
                                default => 'is-pending',
                            };
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $jurnal->tanggal?->translatedFormat('d M Y') ?? '-' }}</strong>
                                <span>Jam {{ $jurnal->jamMulai?->jam_ke ?? '-' }}–{{ $jurnal->jamSelesai?->jam_ke ?? '-' }}</span>
                            </td>
                            <td>{{ $jurnal->guru?->nama_guru ?? 'Guru tidak ditemukan' }}</td>
                            <td>{{ $jurnal->jadwal?->mapel?->nama_mapel ?? '-' }}</td>
                            <td>{{ $jurnal->materi ?: '-' }}</td>
                            <td>{{ $jurnal->jml_hadir ?? 0 }} hadir · {{ $jurnal->jml_tidak_hadir ?? 0 }} tidak hadir</td>
                            <td><span class="homeroom-recap-status {{ $statusClass }}">{{ $jurnal->status_validasi_guru ?? '-' }}</span></td>
                            <td><a class="homeroom-recap-detail" href="{{ route('guru.jurnal.show', ['jurnal' => $jurnal->id_jurnal, 'from' => 'wali-kelas-rekap', 'bulan' => $validated['bulan'] ?? null, 'status' => $validated['status'] ?? null]) }}">Lihat Detail</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td class="homeroom-recap-empty" colspan="7">Belum ada jurnal untuk kelas dan filter yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jurnals->hasPages())
            <div class="homeroom-recap-pagination">{{ $jurnals->links() }}</div>
        @endif
    </section>
</div>

<style>
    .homeroom-recap-page { display: grid; gap: 18px; color: #172554; }
    .homeroom-recap-heading { display: flex; align-items: end; justify-content: space-between; gap: 20px; padding: 20px 22px; border: 1px solid #dce5f2; border-radius: 12px; background: linear-gradient(120deg, #f4f8ff, #fff 70%); }
    .homeroom-recap-eyebrow { color: #4757b2; font-size: 10px; font-weight: 800; letter-spacing: .12em; }
    .homeroom-recap-heading h1 { margin: 5px 0 4px; color: #202b61; font-size: 20px; font-weight: 800; }
    .homeroom-recap-heading p { margin: 0; color: #64748b; font-size: 12px; }
    .homeroom-recap-filters { display: flex; flex-wrap: wrap; align-items: end; gap: 9px; }
    .homeroom-recap-filters label { display: grid; gap: 5px; color: #64748b; font-size: 10px; font-weight: 700; }
    .homeroom-recap-filters select, .homeroom-recap-filters input { min-height: 38px; padding: 7px 9px; border: 1px solid #cbd5e1; border-radius: 6px; background: #fff; color: #1e293b; font: inherit; font-size: 12px; }
    .homeroom-recap-filters button { min-height: 38px; padding: 0 14px; border: 0; border-radius: 6px; background: #30366f; color: #fff; font: inherit; font-size: 12px; font-weight: 700; cursor: pointer; }
    .homeroom-recap-summary { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; }
    .homeroom-recap-summary div { display: grid; gap: 5px; padding: 13px 15px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; }
    .homeroom-recap-summary span { color: #64748b; font-size: 11px; font-weight: 600; }
    .homeroom-recap-summary strong { color: #202b61; font-size: 20px; }
    .homeroom-recap-table-section { overflow: hidden; border: 1px solid #e2e8f0; border-radius: 10px; background: #fff; }
    .homeroom-recap-table-wrap { overflow-x: auto; }
    .homeroom-recap-table { width: 100%; border-collapse: collapse; text-align: left; }
    .homeroom-recap-table th { padding: 12px 14px; background: #f8fafc; color: #64748b; font-size: 10px; font-weight: 800; text-transform: uppercase; white-space: nowrap; }
    .homeroom-recap-table td { padding: 13px 14px; border-top: 1px solid #eef2f7; color: #334155; font-size: 12px; vertical-align: top; }
    .homeroom-recap-table td:first-child strong, .homeroom-recap-table td:first-child span { display: block; }
    .homeroom-recap-table td:first-child span { margin-top: 3px; color: #64748b; font-size: 10px; }
    .homeroom-recap-status { display: inline-flex; padding: 4px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; white-space: nowrap; }
    .homeroom-recap-status.is-approved { background: #dcfce7; color: #166534; }
    .homeroom-recap-status.is-review { background: #fee2e2; color: #991b1b; }
    .homeroom-recap-status.is-pending { background: #fef3c7; color: #92400e; }
    .homeroom-recap-detail { display: inline-flex; min-height: 32px; align-items: center; padding: 0 10px; border: 1px solid #c7d2fe; border-radius: 6px; color: #3730a3; font-size: 11px; font-weight: 700; text-decoration: none; white-space: nowrap; }
    .homeroom-recap-detail:hover { background: #eef2ff; }
    .homeroom-recap-empty { padding: 34px !important; color: #64748b !important; text-align: center; }
    .homeroom-recap-pagination { padding: 12px 16px; border-top: 1px solid #eef2f7; }
    @media (max-width: 900px) { .homeroom-recap-heading { align-items: stretch; flex-direction: column; } }
    @media (max-width: 620px) { .homeroom-recap-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); } .homeroom-recap-filters { display: grid; grid-template-columns: 1fr 1fr; } .homeroom-recap-filters label:first-child { grid-column: 1 / -1; } }
</style>
@endsection