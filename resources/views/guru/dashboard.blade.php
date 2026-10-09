@extends('layouts.guru')
@section('title', 'Dashboard - Jurnify')
@section('content')
{{-- Font Manrope --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="guru-dashboard">
    <div class="dashboard-page">

        {{-- TOP CARDS --}}
        <section class="overview-grid">

            {{-- WELCOME CARD --}}
            <div class="welcome-card dashboard-card">
                <div class="welcome-avatar">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>

                <div class="welcome-content">
                    <h2 class="welcome-title">
                        Selamat datang, {{ auth()->user()->nama_user ?? '-' }}
                    </h2>
                    <p class="welcome-text">
                        Pantau dan kelola aktivitas pembelajaran Anda melalui Jurnify.
                    </p>
                </div>
            </div>

            {{-- TOTAL CARD --}}
            <div class="total-card dashboard-card">
                <div class="total-content">
                    <span class="total-label">Total Jurnal</span>
                    <div class="total-number">{{ $totalJurnal }}</div>
                    <span class="total-text">Jurnal yang telah dibuat</span>
                </div>

                <div class="total-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
            </div>

            {{-- ACTION CARD (TOMBOL ISI JURNAL - IKON DI SAMPING KANAN) --}}
            <a href="{{ route('guru.jurnal.create') }}" class="action-card dashboard-card">
                <div class="action-info">
                    <h3 class="action-title">Isi Jurnal</h3>
                    <p class="action-subtitle">Buat entri baru</p>
                </div>

                <div class="action-btn">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </div>
            </a>

        </section>

        @php
            $chartWidth = 600;
            $chartHeight = 170;
            $chartTop = 16;
            $chartBottom = 22;
            $chartSide = 10;
            $chartMax = max($monthlyTrend->pluck('total')->all() ?: [0]);
            $chartPlotMax = max($chartMax, 1);
            $chartPlotWidth = $chartWidth - ($chartSide * 2);
            $chartPlotHeight = $chartHeight - $chartTop - $chartBottom;
            $chartPoints = $monthlyTrend->map(function ($item, $index) use ($monthlyTrend, $chartWidth, $chartHeight, $chartTop, $chartBottom, $chartSide, $chartPlotWidth, $chartPlotHeight, $chartPlotMax) {
                $count = $monthlyTrend->count();
                $x = $count > 1 ? $chartSide + ($index * ($chartPlotWidth / ($count - 1))) : $chartWidth / 2;
                $y = $chartHeight - $chartBottom - (($item['total'] / $chartPlotMax) * $chartPlotHeight);

                return ['x' => $x, 'y' => $y, 'label' => $item['label'], 'total' => $item['total']];
            });
            $chartLine = $chartPoints->map(fn ($point) => $point['x'] . ',' . $point['y'])->implode(' ');
            $chartBaseline = $chartHeight - $chartBottom;
            $chartArea = $chartPoints->isNotEmpty()
                ? 'M ' . $chartPoints->first()['x'] . ',' . $chartBaseline . ' L ' . $chartLine . ' L ' . $chartPoints->last()['x'] . ',' . $chartBaseline . ' Z'
                : '';
        @endphp

        <section class="teacher-statistics" aria-labelledby="teacher-statistics-title">
            <div class="statistics-heading">
                <div>
                    <span class="statistics-eyebrow">AKTIVITAS MENGAJAR</span>
                    <h3 id="teacher-statistics-title">Statistik Jurnal Saya</h3>
                    <p>Pantau aktivitas dan status validasi jurnal Anda.</p>
                </div>
                <span class="statistics-period">{{ now('Asia/Jakarta')->translatedFormat('F Y') }}</span>
            </div>

            <div class="teacher-stat-grid">
                <article class="teacher-stat-card stat-month">
                    <span class="teacher-stat-label">Total Jurnal</span>
                    <strong>{{ number_format($totalJurnal) }}</strong>
                    <small>Seluruh jurnal yang telah dibuat</small>
                </article>
                <article class="teacher-stat-card stat-approved">
                    <span class="teacher-stat-label">Disetujui</span>
                    <strong>{{ number_format($totalDisetujui) }}</strong>
                    <small>Terverifikasi</small>
                </article>
                <article class="teacher-stat-card stat-pending">
                    <span class="teacher-stat-label">Menunggu</span>
                    <strong>{{ number_format($totalMenunggu) }}</strong>
                    <small>Dalam proses validasi</small>
                </article>
                <article class="teacher-stat-card stat-rejected">
                    <span class="teacher-stat-label">Ditolak</span>
                    <strong>{{ number_format($totalDitolak) }}</strong>
                    <small>Perlu ditinjau kembali</small>
                </article>
            </div>

            <div class="teacher-trend-panel">
                <div class="teacher-trend-heading">
                    <div>
                        <h4>Perkembangan Jurnal</h4>
                        <p>Jumlah jurnal dalam enam bulan terakhir</p>
                    </div>
                    <span class="teacher-trend-total">{{ number_format($monthlyTrend->sum('total')) }} <small>jurnal</small></span>
                </div>
                <svg class="teacher-trend-chart" viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" preserveAspectRatio="none" role="img" aria-label="Grafik jumlah jurnal enam bulan terakhir">
                    <defs>
                        <linearGradient id="teacherTrendFill" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#168b74" stop-opacity=".22"></stop>
                            <stop offset="100%" stop-color="#168b74" stop-opacity=".015"></stop>
                        </linearGradient>
                    </defs>
                    @foreach([0, 1, 2, 3] as $gridIndex)
                        @php $gridY = $chartTop + ($gridIndex * ($chartPlotHeight / 3)); @endphp
                        <line x1="0" y1="{{ $gridY }}" x2="{{ $chartWidth }}" y2="{{ $gridY }}" class="teacher-trend-grid"></line>
                    @endforeach
                    @if($chartPoints->isNotEmpty())
                        <path d="{{ $chartArea }}" fill="url(#teacherTrendFill)"></path>
                    @endif
                    <polyline points="{{ $chartLine }}" fill="none" class="teacher-trend-line"></polyline>
                    @foreach($chartPoints as $point)
                        @if($loop->last)
                            <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="8" class="teacher-trend-halo"></circle>
                        @endif
                        <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="{{ $loop->last ? 5 : 3.5 }}" class="teacher-trend-point {{ $loop->last ? 'is-current' : '' }}"></circle>
                    @endforeach
                </svg>
                <div class="teacher-trend-labels">
                    @foreach($chartPoints as $point)
                        <span><strong>{{ $point['total'] }}</strong>{{ $point['label'] }}</span>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- RINGKASAN JURNAL --}}
        <section class="summary-section">

            <div class="summary-header">
                <div>
                    <h3 class="summary-title">Ringkasan Jurnal</h3>
                    <p class="summary-subtitle">Menampilkan 5 jurnal terbaru yang telah Anda buat</p>
                </div>

                @if($totalJurnal > 5)
                    <a class="see-all" href="{{ route('guru.jurnal.index') }}">
                        Lihat Semua Jurnal
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>

            <div class="journal-list">

                @forelse($jurnals as $index => $jurnal)

                    <article class="journal-card">

                        <div class="journal-top">

                            <div class="journal-heading">
                                <div class="journal-number">
                                    {{ $index + 1 }}
                                </div>

                                <span class="journal-class">
                                    {{ $jurnal->kelas->nama_kelas ?? '-' }}
                                </span>
                            </div>

                            {{-- STATUS --}}
                            @if($jurnal->status_validasi_guru === 'Menunggu')
                                <span class="status status-menunggu">
                                    <span class="status-dot warning"></span>
                                    Menunggu Validasi
                                </span>
                            @elseif(in_array($jurnal->status_validasi_guru, ['Disetujui', 'Terverifikasi'], true))
                                <span class="status status-valid">
                                    <span class="status-dot success"></span>
                                    Terverifikasi
                                </span>
                            @elseif(in_array($jurnal->status_validasi_guru, ['Ditolak', 'Tidak Terverifikasi'], true))
                                <span class="status status-default">
                                    Tidak Terverifikasi
                                </span>
                            @else
                                <span class="status status-default">
                                    {{ $jurnal->status_validasi_guru ?? '-' }}
                                </span>
                            @endif

                        </div>

                        <div class="journal-meta">

                            <span class="meta-item">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                    <path d="M16 2v4M8 2v4M3 10h18" />
                                </svg>
                                {{ $jurnal->tanggal?->format('d M Y') ?? '-' }}
                            </span>

                            <span class="meta-dot">•</span>

                            <span class="meta-item">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 16 14" />
                                </svg>
                                Jam Ke-{{ $jurnal->jamMulai->jam_ke ?? '-' }} - {{ $jurnal->jamSelesai->jam_ke ?? '-' }}
                            </span>

                            @if($jurnal->jadwal?->mapel?->nama_mapel)
                                <span class="meta-dot">•</span>

                                <span class="meta-item">
                                    {{ $jurnal->jadwal->mapel->nama_mapel }}
                                </span>
                            @endif

                        </div>

                        <div class="journal-material">
                            <span class="material-name" title="{{ $jurnal->materi }}">
                                <strong>Materi:</strong> {{ $jurnal->materi ?? '-' }}
                            </span>

                            <a class="detail-link" href="{{ route('guru.jurnal.show', $jurnal->id_jurnal) }}">
                                Detail
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                    </article>

                @empty

                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <strong>Belum Ada Jurnal</strong>
                        <p>Jurnal pembelajaran yang Anda buat akan muncul di sini.</p>
                    </div>

                @endforelse

            </div>

        </section>

    </div>
</div>

<style>
/* GLOBAL STYLING & MANROPE FONT */
.guru-dashboard,
.guru-dashboard * {
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    box-sizing: border-box;
}

.guru-dashboard {
    --primary: #2D336B;
    --secondary: #7886C7;
    --accent: #A9B5DF;
    --light-bg: #F8FAFC;
    --soft-blue: #EEF2FF;
    --border: #E2E8F0;
    --text: #0F172A;
    --muted: #64748B;
}

.dashboard-page {
    width: 100%;
    margin: 0;
    animation: pageFade .4s cubic-bezier(.16, 1, .3, 1) both;
}

.teacher-statistics {
    margin: 0 0 32px;
}

.statistics-heading,
.teacher-trend-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
}

.statistics-heading {
    margin-bottom: 16px;
}

.statistics-eyebrow {
    color: #168b74;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .08em;
}

.statistics-heading h3 {
    margin: 3px 0 0;
    color: var(--text);
    font-size: 19px;
    font-weight: 800;
}

.statistics-heading p,
.teacher-trend-heading p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 12px;
}

.statistics-period {
    padding: 7px 10px;
    border: 1px solid #dce8e5;
    border-radius: 8px;
    background: #fff;
    color: #47665f;
    font-size: 11px;
    font-weight: 700;
}

.teacher-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 14px;
}

.teacher-stat-card {
    position: relative;
    min-height: 120px;
    padding: 16px;
    overflow: hidden;
    border: 1px solid #e4e9ef;
    border-top: 3px solid #168b74;
    border-radius: 11px;
    background: linear-gradient(145deg, #fff, #f8fbfb);
}

.teacher-stat-card::after {
    position: absolute;
    right: -18px;
    bottom: -25px;
    width: 78px;
    height: 78px;
    border: 12px solid rgba(22, 139, 116, .07);
    border-radius: 50%;
    content: "";
}

.teacher-stat-card.stat-approved { border-top-color: #3c9b70; }
.teacher-stat-card.stat-pending { border-top-color: #d69b32; }
.teacher-stat-card.stat-rejected { border-top-color: #cc6666; }
.teacher-stat-card.stat-approved::after { border-color: rgba(60, 155, 112, .08); }
.teacher-stat-card.stat-pending::after { border-color: rgba(214, 155, 50, .1); }
.teacher-stat-card.stat-rejected::after { border-color: rgba(204, 102, 102, .09); }

.teacher-stat-label,
.teacher-stat-card small {
    display: block;
    color: #687787;
    font-size: 11px;
    font-weight: 700;
}

.teacher-stat-card strong {
    display: block;
    margin: 9px 0 3px;
    color: #172a36;
    font-size: 27px;
    line-height: 1;
    font-weight: 800;
}

.teacher-stat-card small {
    font-size: 10px;
    font-weight: 500;
}

.teacher-trend-panel {
    padding: 17px 18px 12px;
    border: 1px solid #e4e9ef;
    border-radius: 11px;
    background: linear-gradient(145deg, #fff, #f7fbfa);
}

.teacher-trend-heading h4 {
    margin: 0;
    color: var(--text);
    font-size: 15px;
    font-weight: 800;
}

.teacher-trend-total {
    flex: 0 0 auto;
    padding: 6px 10px;
    border: 1px solid #cfe6de;
    border-radius: 8px;
    background: #eff8f4;
    color: #176d5d;
    font-size: 13px;
    font-weight: 800;
}

.teacher-trend-total small {
    color: #587d73;
    font-size: 10px;
    font-weight: 600;
}

.teacher-trend-chart {
    display: block;
    width: 100%;
    height: 160px;
    margin-top: 8px;
    overflow: visible;
}

.teacher-trend-grid {
    stroke: #e3ece9;
    stroke-width: 1;
    stroke-dasharray: 3 5;
}

.teacher-trend-line {
    stroke: #168b74;
    stroke-width: 3.5;
    stroke-linecap: round;
    stroke-linejoin: round;
    filter: drop-shadow(0 3px 3px rgba(22, 139, 116, .18));
}

.teacher-trend-point {
    fill: #168b74;
    stroke: #fff;
    stroke-width: 2;
}

.teacher-trend-point.is-current { fill: #0f6659; }
.teacher-trend-halo { fill: rgba(22, 139, 116, .16); }

.teacher-trend-labels {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 4px;
    text-align: center;
}

.teacher-trend-labels span {
    display: grid;
    gap: 3px;
    color: #738191;
    font-size: 10px;
}

.teacher-trend-labels strong {
    color: #243744;
    font-size: 11px;
}

@keyframes pageFade {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* OVERVIEW GRID (DESKTOP) */
.overview-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 32px;
}

.welcome-card {
    grid-column: span 2; /* Mengambil baris atas secara penuh di Desktop */
}

.dashboard-card {
    transition: transform .25s ease, box-shadow .25s ease;
}

.dashboard-card:hover {
    transform: translateY(-2px);
}

/* WELCOME CARD */
.welcome-card {
    min-height: 140px;
    padding: 24px 28px;
    border-radius: 18px;
    background: linear-gradient(135deg, #2D336B 0%, #47539B 100%);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(45, 51, 107, .12);
}

.welcome-card::before,
.welcome-card::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, .05);
    pointer-events: none;
}

.welcome-card::before {
    width: 180px;
    height: 180px;
    right: -40px;
    bottom: -70px;
}

.welcome-card::after {
    width: 110px;
    height: 110px;
    left: -35px;
    top: -45px;
}

.welcome-avatar {
    width: 58px;
    height: 58px;
    flex-shrink: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, .15);
    border: 2px solid rgba(255, 255, 255, .3);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
    transition: transform .25s ease;
}

.welcome-card:hover .welcome-avatar {
    transform: scale(1.05);
}

.welcome-avatar svg {
    width: 28px;
    height: 28px;
}

.welcome-content {
    position: relative;
    z-index: 1;
    min-width: 0;
}

.welcome-title {
    margin: 0;
    color: #FFFFFF;
    font-size: 22px;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: -.4px;
}

.welcome-text {
    margin: 4px 0 0;
    color: rgba(255, 255, 255, .8);
    font-size: 13.5px;
    font-weight: 500;
}

/* TOTAL CARD */
.total-card {
    min-height: 140px;
    padding: 22px 24px;
    border-radius: 18px;
    background: #E0E7FF;
    border: 1px solid #C7D2FE;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(15, 23, 42, .03);
}

.total-card::after {
    content: "";
    position: absolute;
    width: 140px;
    height: 140px;
    right: -40px;
    top: -40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .3);
}

.total-content {
    position: relative;
    z-index: 1;
}

.total-label {
    display: block;
    color: #3730A3;
    font-size: 13.5px;
    font-weight: 800;
}

.total-number {
    color: #1E1B4B;
    font-size: 36px;
    line-height: 1.1;
    font-weight: 800;
    letter-spacing: -.8px;
    margin-top: 4px;
}

.total-text {
    display: block;
    margin-top: 4px;
    color: #4338CA;
    font-size: 12px;
    font-weight: 600;
    opacity: .9;
}

.total-icon {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    border-radius: 14px;
    background: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 12px rgba(45, 51, 107, .08);
}

.total-icon svg {
    width: 24px;
    height: 24px;
    color: var(--primary);
}

/* ACTION CARD - WARM CREAM (ISI JURNAL) */
.action-card {
    min-height: 140px;
    padding: 22px 24px;
    border-radius: 18px;
    background: #FFFDF9;
    border: 1.5px solid #E2DFD8;
    color: #1E293B;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0, 0, 0, .04);
    transition: all .3s cubic-bezier(.16, 1, .3, 1);
}

.action-card:hover {
    transform: translateY(-4px);
    border-color: #2D336B;
    box-shadow: 0 10px 24px rgba(45, 51, 107, .12);
}

.action-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    z-index: 1;
}

.action-badge {
    padding: 3px 9px;
    border-radius: 20px;
    background: #F2EFE7;
    color: #475569;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.action-title {
    margin: 0;
    font-size: 19px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.2;
    letter-spacing: -.3px;
}

.action-subtitle {
    margin: 4px 0 0;
    font-size: 12px;
    color: #64748B;
    font-weight: 600;
}

.action-btn {
    width: 46px;
    height: 46px;
    flex-shrink: 0;
    border-radius: 14px;
    background: #2D336B;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    transition: transform .3s ease, background-color .3s ease;
}

.action-card:hover .action-btn {
    transform: scale(1.08) rotate(90deg);
    background: #1E234A;
}

.action-btn svg {
    width: 22px;
    height: 22px;
}

/* SUMMARY SECTION */
.summary-section {
    width: 100%;
}

.summary-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
}

.summary-title {
    margin: 0;
    color: var(--text);
    font-size: 19px;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: -.3px;
}

.summary-subtitle {
    margin: 3px 0 0;
    color: var(--muted);
    font-size: 13px;
    font-weight: 500;
}

.see-all {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border: 1px solid #CBD5E1;
    border-radius: 10px;
    background: #FFFFFF;
    color: #475569;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    transition: all .2s ease;
}

.see-all svg {
    width: 14px;
    height: 14px;
    transition: transform .2s ease;
}

.see-all:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: #F8FAFC;
}

.see-all:hover svg {
    transform: translateX(3px);
}

/* JOURNAL LIST */
.journal-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.journal-card {
    padding: 18px 20px;
    background: #FFFFFF;
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, .02);
    transition: all .2s ease;
}

.journal-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 6px 16px rgba(15, 23, 42, .05);
    transform: translateY(-1px);
}

.journal-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 8px;
}

.journal-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.journal-number {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    border-radius: 10px;
    background: var(--soft-blue);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 800;
}

.journal-class {
    color: var(--text);
    font-size: 15px;
    font-weight: 800;
    white-space: nowrap;
}

/* STATUS BADGES */
.status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
}

.status-menunggu {
    background: #FEF3C7;
    color: #92400E;
    border: 1px solid #FDE68A;
}

.status-valid {
    background: #DCFCE7;
    color: #14532D;
    border: 1px solid #BBF7D0;
}

.status-default {
    background: #F1F5F9;
    color: #475569;
    border: 1px solid #E2E8F0;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status-dot.warning {
    background: #D97706;
}

.status-dot.success {
    background: #16A34A;
}

/* META INFO */
.journal-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-left: 48px;
    margin-bottom: 12px;
    color: var(--muted);
    font-size: 12px;
    font-weight: 500;
}

.meta-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.meta-item svg {
    width: 14px;
    height: 14px;
    color: #94A3B8;
    flex-shrink: 0;
}

.meta-dot {
    color: #CBD5E1;
}

/* MATERIAL CONTAINER */
.journal-material {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 10px 14px;
    border-radius: 10px;
    background: #F8FAFC;
}

.material-name {
    min-width: 0;
    color: #334155;
    font-size: 13px;
    line-height: 1.4;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.material-name strong {
    color: var(--text);
    font-weight: 700;
}

.detail-link {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    flex-shrink: 0;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: color .2s ease;
}

.detail-link:hover {
    color: var(--primary);
}

.detail-link svg {
    width: 14px;
    height: 14px;
}

/* EMPTY STATE */
.empty-state {
    padding: 40px 20px;
    background: #FFFFFF;
    border: 1px solid var(--border);
    border-radius: 16px;
    text-align: center;
}

.empty-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    border-radius: 50%;
    background: #EEF2FF;
    color: var(--primary);
}

.empty-icon svg {
    width: 22px;
    height: 22px;
}

.empty-state strong {
    display: block;
    color: var(--text);
    font-size: 14px;
    font-weight: 800;
}

.empty-state p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 13px;
}

/* RESPONSIVE DESIGN (MOBILE & TABLET) */
@media (max-width: 767px) {
    .teacher-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
    }

    .teacher-stat-card {
        min-height: 106px;
        padding: 13px;
    }

    .teacher-trend-panel {
        padding: 14px 12px 10px;
    }

    .teacher-trend-chart {
        height: 135px;
    }

    .overview-grid {
        grid-template-columns: 1fr 1fr; /* 2 kolom untuk Total Jurnal & Isi Jurnal di bawahnya */
        gap: 12px;
        margin-bottom: 24px;
    }

    .welcome-card {
        grid-column: span 2; /* Paling atas mengambil baris penuh */
        padding: 18px 20px;
        gap: 14px;
    }

    .welcome-avatar {
        width: 44px;
        height: 44px;
    }

    .welcome-title {
        font-size: 17px;
    }

    .welcome-text {
        font-size: 12.5px;
    }

    /* Total Jurnal & Isi Jurnal Berdampingan di Mobile */
    .total-card,
    .action-card {
        min-height: 110px;
        padding: 14px 16px;
        border-radius: 14px;
        flex-direction: column;
        align-items: flex-start;
        justify-content: space-between;
    }

    .total-number {
        font-size: 26px;
    }

    .total-label {
        font-size: 12px;
    }

    .total-text {
        display: none; /* Sembunyikan deskripsi panjang di layar HP agar hemat ruang */
    }

    .total-icon {
        display: none; /* Sembunyikan ikon besar di total card pada layar HP */
    }

    .action-badge {
        display: none; /* Sembunyikan badge kecil di layar HP */
    }

    .action-title {
        font-size: 16px;
    }

    .action-subtitle {
        font-size: 11px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        align-self: flex-end; /* Posisikan tombol ikon di sudut kanan bawah kartu pada layar HP */
    }

    .action-btn svg {
        width: 18px;
        height: 18px;
    }

    .summary-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
    }

    .see-all {
        width: 100%;
        justify-content: center;
    }

    .journal-meta {
        margin-left: 0;
    }
}

@media (max-width: 420px) {
    .journal-card {
        padding: 14px;
    }

    .journal-material {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .detail-link {
        align-self: flex-end;
    }
}

@media (prefers-reduced-motion: reduce) {
    .dashboard-page,
    .dashboard-card,
    .journal-card,
    .see-all,
    .detail-link,
    .action-btn {
        animation: none;
        transition: none;
    }
}
</style>

@endsection