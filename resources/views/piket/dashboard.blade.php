@extends('layouts.piket')

@section('title', 'Dashboard Staff Piket')
@section('page-title', 'Dashboard')

@section('content')

<style>
    /* Reset & Base Styles */
    :root {
        --primary-dark: #1e254b;
        --accent-blue: #4f46e5;
        --accent-blue-hover: #4338ca;
        --bg-light: #f8fafc;
        --card-bg: #ffffff;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --green-bg: #e6f4ea;
        --green-text: #137333;
        --red-bg: #fce8e6;
        --red-text: #c5221f;
    }

    .dashboard-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: var(--text-dark);
    }

    /* Welcome Banner */
    .welcome-card {
        background: linear-gradient(135deg, #1e254b 0%, #2a3467 100%);
        border-radius: 16px;
        padding: 24px 32px;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(30, 37, 75, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .welcome-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px rgba(30, 37, 75, 0.3);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .avatar-circle {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .welcome-text h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .badge-status {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .welcome-text p {
        margin: 6px 0 0 0;
        color: #94a3b8;
        font-size: 14px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 16px;
    }

    .stat-card-main {
        background: linear-gradient(135deg, #232b5d 0%, #1a2044 100%);
        border-radius: 16px;
        padding: 24px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card-main:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .stat-card-light {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card-light:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
    }

    .stat-title-sm {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 8px;
    }

    .stat-value-lg {
        font-size: 38px;
        font-weight: 800;
        line-height: 1.2;
    }

    .stat-value-lg span {
        font-size: 14px;
        font-weight: 500;
        color: #94a3b8;
    }

    .trend-panel {
        margin-bottom: 32px;
        padding: 20px 22px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: linear-gradient(145deg, #fff, #f7fbfa);
    }

    .trend-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }

    .trend-heading h3 {
        margin: 0;
        color: var(--text-dark);
        font-size: 16px;
        font-weight: 750;
    }

    .trend-heading p {
        margin: 4px 0 0;
        color: var(--text-muted);
        font-size: 12px;
    }

    .trend-total {
        padding: 6px 10px;
        border: 1px solid #cce8df;
        border-radius: 8px;
        background: #eff8f4;
        color: #176d5d;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .trend-chart {
        display: block;
        width: 100%;
        height: 145px;
        margin-top: 8px;
        overflow: visible;
    }

    .trend-gridline {
        stroke: #e3ece9;
        stroke-width: 1;
        stroke-dasharray: 3 5;
    }

    .trend-line {
        stroke: #168b74;
        stroke-width: 3.5;
        stroke-linecap: round;
        stroke-linejoin: round;
        filter: drop-shadow(0 3px 3px rgba(22, 139, 116, .16));
    }

    .trend-point { fill: #168b74; stroke: #fff; stroke-width: 2; }
    .trend-point.current { fill: #0f6659; }
    .trend-halo { fill: rgba(22, 139, 116, .16); }

    .trend-labels {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 4px;
        text-align: center;
    }

    .trend-labels span {
        display: grid;
        gap: 3px;
        color: #738191;
        font-size: 10px;
    }

    .trend-labels strong { color: #243744; font-size: 11px; }

    /* Section Header */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        gap: 12px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        color: var(--text-dark);
    }

    .section-subtitle {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .view-all-link {
        font-size: 13px;
        color: var(--accent-blue);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
        white-space: nowrap;
    }

    .view-all-link:hover {
        color: var(--accent-blue-hover);
        text-decoration: underline;
    }

    /* List Card & Item Hover */
    .jurnal-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .jurnal-item {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        padding: 18px 24px;
        display: grid;
        grid-template-columns: 2.5fr 1fr 1fr 1fr 20px;
        align-items: center;
        text-decoration: none;
        color: inherit;
        transition: all 0.25s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
    }

    .jurnal-item:hover {
        transform: translateX(6px) translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
        background-color: #fdfdfd;
    }

    .teacher-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: background-color 0.2s;
        flex-shrink: 0;
    }

    .icon-box .material-symbols-outlined { font-size: 22px; }
    .avatar-circle .material-symbols-outlined { font-size: 26px; }

    .jurnal-item:hover .icon-box {
        background: #4f46e5;
        color: #ffffff;
    }

    .teacher-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .subject-name {
        font-size: 13px;
        color: var(--text-muted);
        margin: 2px 0 0 0;
    }

    .info-col {
        display: flex;
        flex-direction: column;
    }

    .col-label {
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .col-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        width: fit-content;
    }

    .status-badge.hadir {
        background-color: #e0e7ff;
        color: #4338ca;
    }

    .chevron-icon {
        color: #cbd5e1;
        transition: transform 0.2s, color 0.2s;
    }

    .jurnal-item:hover .chevron-icon {
        color: var(--accent-blue);
        transform: translateX(4px);
    }

    .empty-card {
        background: #ffffff;
        padding: 32px;
        text-align: center;
        border-radius: 14px;
        color: var(--text-muted);
        border: 1px dashed #cbd5e1;
    }

    /* =========================
       RESPONSIVE BREAKPOINTS
    ========================= */

    /* Tablet (1024px ke bawah) */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .stat-card-main {
            grid-column: span 2;
        }

        .jurnal-item {
            grid-template-columns: 2fr 1fr 1fr 20px;
            gap: 12px;
        }

        /* Sembunyikan status badge di tablet kecil agar tidak terlalu padat */
        .jurnal-item .info-col:nth-child(4) {
            display: none;
        }
    }

    /* Mobile (768px ke bawah) */
@media (max-width: 768px) {
    .welcome-card {
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
    }

    .user-info {
        gap: 14px;
    }

    .welcome-text h2 {
        font-size: 18px;
    }

    .welcome-text p {
        font-size: 13px;
    }

    /* --- PERUBAHAN DI SINI --- */
    .stats-grid {
        grid-template-columns: 1fr 1fr; /* Membagi menjadi 2 kolom (kiri & kanan) */
        gap: 12px;                      /* Jarak antar kartu sedikit dirapatkan agar pas */
    }

    .stat-card-main {
        grid-column: span 2; /* Kartu Jurnal Utama tetap memanjang di paling atas */
    }

    .stat-card-light {
        padding: 16px; /* Padding sedikit diperkecil agar muat di layar HP */
    }

        .trend-panel {
            padding: 16px 12px 12px;
        }

        .trend-chart {
            height: 125px;
        }

    .stat-title-sm {
        font-size: 10px; /* Ukuran judul diperkecil sedikit */
    }

    .stat-value-lg {
        font-size: 28px; /* Ukuran angka disesuaikan */
    }
    /* ------------------------- */

    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .jurnal-item {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        position: relative;
    }

    .jurnal-item .teacher-info {
        width: 100%;
        padding-right: 24px;
    }

    .chevron-icon {
        position: absolute;
        right: 16px;
        top: 20px;
    }

    .jurnal-item:hover {
        transform: translateY(-2px);
    }
}
</style>

<div class="dashboard-container">
    <!-- Header Banner -->
    <div class="welcome-card">
        <div class="user-info">
            <div class="avatar-circle">
                <span class="material-symbols-outlined" aria-hidden="true">person</span>
            </div>
            <div class="welcome-text">
                <h2>Selamat datang, {{ auth()->user()->nama_user ?? 'Staff Piket' }} </h2>
                <p>Semua sistem pencatatan aktivitas belajar mengajar hari ini berjalan normal.</p>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="stats-grid">
        <!-- Card Jurnal -->
        <div class="stat-card-main">
            <div class="stat-title-sm">JURNAL HARI INI</div>
            <div class="stat-value-lg">{{ number_format($totalJurnalHariIni) }} <span>Jurnal Terisi</span></div>
            <div style="margin-top: 20px; font-size: 12px; color: #34d399; display: flex; align-items: center; gap: 4px;">
             {{ number_format($totalHadir) }} siswa hadir hari ini
            </div>
        </div>

        <div class="stat-card-light">
            <div class="stat-title-sm">SISWA HADIR</div>
            <div class="stat-value-lg" style="color: #137333;">{{ number_format($totalHadir) }}</div>
            <div style="margin-top: 10px; font-size: 12px; color: #64748b;">Dari jurnal hari ini</div>
        </div>

        <div class="stat-card-light">
            <div class="stat-title-sm">SISWA TIDAK HADIR</div>
            <div class="stat-value-lg" style="color: #c5221f;">{{ number_format($totalAbsen) }}</div>
            <div style="margin-top: 10px; font-size: 12px; color: #64748b;">Dari jurnal hari ini</div>
        </div>

        <!-- Card Dispen -->
        <div class="stat-card-light" style="background: #eefbe8; border-color: #d2f4c2;">
            <div class="stat-title-sm" style="color: #2e7d32;">DISPEN HARI INI</div>
            <div class="stat-value-lg" style="color: #1b5e20;">{{ number_format($totalDispenHariIni) }}</div>
            <div style="margin-top: 10px; font-size: 12px; color: #388e3c; font-weight: 500;">
                Siswa Dispensasi
            </div>
        </div>

        <!-- Card Piket -->
        <div class="stat-card-light" style="background: #fef2f2; border-color: #fecaca;">
            <div class="stat-title-sm" style="color: #991b1b;">PIKET HARI INI</div>
            <div class="stat-value-lg" style="color: #991b1b;">{{ $piketHariIni->count() }}</div>
            <div style="margin-top: 10px; font-size: 12px; color: #dc2626; font-weight: 500;">
                Petugas Bertugas
            </div>
        </div>
    </div>

    @php
        $chartWidth = 600;
        $chartHeight = 150;
        $chartTop = 14;
        $chartBottom = 20;
        $chartSide = 10;
        $chartMax = max($monthlyTrend->pluck('total')->all() ?: [0]);
        $chartPlotMax = max($chartMax, 1);
        $chartPlotWidth = $chartWidth - ($chartSide * 2);
        $chartPlotHeight = $chartHeight - $chartTop - $chartBottom;
        $chartPoints = $monthlyTrend->map(function ($item, $index) use ($monthlyTrend, $chartWidth, $chartHeight, $chartBottom, $chartTop, $chartSide, $chartPlotWidth, $chartPlotHeight, $chartPlotMax) {
            $count = $monthlyTrend->count();
            $x = $count > 1 ? $chartSide + ($index * ($chartPlotWidth / ($count - 1))) : $chartWidth / 2;
            $y = $chartHeight - $chartBottom - (($item['total'] / $chartPlotMax) * $chartPlotHeight);

            return ['x' => $x, 'y' => $y, 'label' => $item['label'], 'total' => $item['total']];
        });
        $chartLine = $chartPoints->map(fn ($point) => $point['x'] . ',' . $point['y'])->implode(' ');
        $chartArea = $chartPoints->isNotEmpty()
            ? 'M ' . $chartPoints->first()['x'] . ',' . ($chartHeight - $chartBottom) . ' L ' . $chartLine . ' L ' . $chartPoints->last()['x'] . ',' . ($chartHeight - $chartBottom) . ' Z'
            : '';
    @endphp

    <section class="trend-panel" aria-labelledby="piket-trend-title">
        <div class="trend-heading">
            <div>
                <h3 id="piket-trend-title">Perkembangan Jurnal</h3>
                <p>Jurnal masuk dalam enam bulan terakhir</p>
            </div>
            <span class="trend-total">{{ number_format($monthlyTrend->sum('total')) }} jurnal</span>
        </div>
        <svg class="trend-chart" viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" preserveAspectRatio="none" role="img" aria-label="Grafik jurnal masuk enam bulan terakhir">
            <defs>
                <linearGradient id="piketTrendFill" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0%" stop-color="#168b74" stop-opacity=".22"></stop>
                    <stop offset="100%" stop-color="#168b74" stop-opacity=".015"></stop>
                </linearGradient>
            </defs>
            @foreach([0, 1, 2, 3] as $gridIndex)
                @php $gridY = $chartTop + ($gridIndex * ($chartPlotHeight / 3)); @endphp
                <line x1="0" y1="{{ $gridY }}" x2="{{ $chartWidth }}" y2="{{ $gridY }}" class="trend-gridline"></line>
            @endforeach
            @if($chartPoints->isNotEmpty())
                <path d="{{ $chartArea }}" fill="url(#piketTrendFill)"></path>
            @endif
            <polyline points="{{ $chartLine }}" fill="none" class="trend-line"></polyline>
            @foreach($chartPoints as $point)
                @if($loop->last)
                    <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="8" class="trend-halo"></circle>
                @endif
                <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="{{ $loop->last ? 5 : 3.5 }}" class="trend-point {{ $loop->last ? 'current' : '' }}"></circle>
            @endforeach
        </svg>
        <div class="trend-labels">
            @foreach($chartPoints as $point)
                <span><strong>{{ $point['total'] }}</strong>{{ $point['label'] }}</span>
            @endforeach
        </div>
    </section>

    <!-- Jurnal Terbaru Section -->
    <div class="section-header">
        <div>
            <h3 class="section-title">Jurnal Masuk (Terbaru)</h3>
            <p class="section-subtitle">Daftar presensi dan catatan kelas yang baru diserahkan oleh guru.</p>
        </div>
        <a href="{{ route('piket.jurnal.rekap') }}" class="view-all-link">Lihat Semua &rarr;</a>
    </div>

    <!-- List Dynamic Data -->
    <div class="jurnal-list">
        @forelse($jurnals as $jurnal)
            <a href="{{ route('piket.jurnal.show', $jurnal) }}" class="jurnal-item" aria-label="Lihat detail jurnal {{ $jurnal->materi ?? '' }}">
                <div class="teacher-info">
                    <div class="icon-box">
                        <span class="material-symbols-outlined" aria-hidden="true">menu_book</span>
                    </div>
                    <div>
                        <h4 class="teacher-name">{{ $jurnal->guru->nama_guru ?? '-' }}</h4>
                        <p class="subject-name">{{ $jurnal->materi ?? 'Materi Belum Diisi' }}</p>
                    </div>
                </div>

                <div class="info-col">
                    <span class="col-label">KELAS</span>
                    <span class="col-value">{{ $jurnal->kelas->nama_kelas ?? '-' }}</span>
                </div>

                <div class="info-col">
                    <span class="col-label">TANGGAL</span>
                    <span class="col-value">{{ $jurnal->tanggal?->format('d M Y') ?? '-' }}</span>
                </div>

                <div class="info-col">
                    <span class="col-label">STATUS</span>
                    <span class="status-badge hadir">{{ $jurnal->status_validasi_guru ?? '-' }}</span>
                </div>

                <div style="text-align: right;">
                    <span class="material-symbols-outlined chevron-icon" aria-hidden="true">chevron_right</span>
                </div>
            </a>
        @empty
            <div class="empty-card">
                <span class="material-symbols-outlined" style="font-size: 32px; display: block; margin-bottom: 8px;" aria-hidden="true">inbox</span>
                Belum ada jurnal yang tercatat hari ini.
            </div>
        @endforelse

</div>
@endsection