@extends('layouts.admin')

@section('title', 'Statistik Jurnal - Jurnify')
@section('page-title', 'Statistik Jurnal')

@section('content')

<div class="stats-page">
    <div class="stats-shell">
        <div class="stats-header">
            <div>
                <h3 class="stats-title">Statistik Jurnal</h3>
                <p class="stats-subtitle">Ringkasan aktivitas jurnal berdasarkan periode, kelas, dan guru.</p>
            </div>

            <a href="{{ route('admin.jurnal.index') }}" class="secondary-btn">Kembali ke Daftar</a>
        </div>

        <form action="{{ route('admin.jurnal.statistik') }}" method="GET" class="stats-filter">
            <div class="field-group">
                <label for="from_date">Dari Tanggal</label>
                <input id="from_date" type="date" name="from_date" value="{{ request('from_date') }}">
            </div>

            <div class="field-group">
                <label for="to_date">Sampai Tanggal</label>
                <input id="to_date" type="date" name="to_date" value="{{ request('to_date') }}">
            </div>

            <div class="field-group">
                <label for="id_kelas">Kelas</label>
                <select id="id_kelas" name="id_kelas">
                    <option value="">Semua Kelas</option>
                    @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id_kelas }}" {{ request('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group">
                <label for="id_guru">Guru</label>
                <select id="id_guru" name="id_guru">
                    <option value="">Semua Guru</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id_guru }}" {{ request('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                            {{ $guru->nama_guru }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group">
                <label for="status_validasi">Status Validasi</label>
                <select id="status_validasi" name="status_validasi">
                    <option value="">Semua Status</option>
                    @foreach(['Disetujui', 'Menunggu', 'Ditolak', 'Perlu Diperbaiki'] as $status)
                        <option value="{{ $status }}" {{ request('status_validasi') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="primary-btn">Terapkan</button>
                <a href="{{ route('admin.jurnal.statistik') }}" class="ghost-btn">Reset</a>
            </div>
        </form>

        <div class="quick-range">
            <a href="{{ route('admin.jurnal.statistik', ['from_date' => now('Asia/Jakarta')->copy()->subDays(6)->toDateString(), 'to_date' => now('Asia/Jakarta')->toDateString()] + request()->except(['from_date', 'to_date'])) }}" class="range-chip {{ request('from_date') && request('to_date') && request('from_date') == now('Asia/Jakarta')->copy()->subDays(6)->toDateString() && request('to_date') == now('Asia/Jakarta')->toDateString() ? 'active' : '' }}">7 Hari</a>
            <a href="{{ route('admin.jurnal.statistik', ['from_date' => now('Asia/Jakarta')->copy()->subDays(29)->toDateString(), 'to_date' => now('Asia/Jakarta')->toDateString()] + request()->except(['from_date', 'to_date'])) }}" class="range-chip {{ request('from_date') && request('to_date') && request('from_date') == now('Asia/Jakarta')->copy()->subDays(29)->toDateString() && request('to_date') == now('Asia/Jakarta')->toDateString() ? 'active' : '' }}">30 Hari</a>
            <a href="{{ route('admin.jurnal.statistik', ['from_date' => now('Asia/Jakarta')->copy()->subDays(89)->toDateString(), 'to_date' => now('Asia/Jakarta')->toDateString()] + request()->except(['from_date', 'to_date'])) }}" class="range-chip {{ request('from_date') && request('to_date') && request('from_date') == now('Asia/Jakarta')->copy()->subDays(89)->toDateString() && request('to_date') == now('Asia/Jakarta')->toDateString() ? 'active' : '' }}">90 Hari</a>
            <a href="{{ route('admin.jurnal.statistik', request()->except(['from_date','to_date'])) }}" class="range-chip {{ !request('from_date') && !request('to_date') ? 'active' : '' }}">Semua</a>
        </div>

        <section class="metric-grid">
            <div class="metric-card primary">
                <div class="metric-label">Total Jurnal</div>
                <div class="metric-value">{{ number_format($totalJurnal) }}</div>
                <div class="metric-meta">Semua data yang sesuai filter</div>
            </div>

            <div class="metric-card success">
                <div class="metric-label">Jurnal Hari Ini</div>
                <div class="metric-value">{{ number_format($jurnalHariIni) }}</div>
                <div class="metric-meta">{{ now('Asia/Jakarta')->translatedFormat('d M Y') }}</div>
            </div>

            <div class="metric-card warning">
                <div class="metric-label">Rata-rata / Hari</div>
                <div class="metric-value">{{ number_format($averagePerDay, 2) }}</div>
                <div class="metric-meta">{{ $rangeStart->translatedFormat('d M Y') }} - {{ $rangeEnd->translatedFormat('d M Y') }}</div>
            </div>

            <div class="metric-card danger">
                <div class="metric-label">Validasi Sukses</div>
                <div class="metric-value">{{ $validPercentage }}%</div>
                <div class="metric-meta">{{ number_format($totalDisetujui) }} disetujui</div>
            </div>
        </section>

        <section class="content-grid">
            <div class="panel">
                <div class="panel-header">
                    <h4>Distribusi Validasi</h4>
                </div>

                <div class="status-list">
                    @foreach(['Disetujui', 'Menunggu', 'Ditolak', 'Perlu Diperbaiki'] as $status)
                        @php
                            $count = $statusSummary[$status] ?? 0;
                            $max = max($statusSummary->values()->all() ?: [1]);
                            $width = $max > 0 ? ($count / $max) * 100 : 0;
                        @endphp

                        <div class="status-row">
                            <div class="status-meta">
                                <span class="status-name">{{ $status }}</span>
                                <span class="status-count">{{ number_format($count) }}</span>
                            </div>

                            <div class="progress-bar">
                                <span style="width: {{ $width }}%" class="progress-fill {{ strtolower(str_replace(' ', '-', $status)) }}"></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h4>Trend 6 Bulan Terakhir</h4>
                </div>

                <div class="trend-chart">
                    @php
                        $chartMax = max($monthlyTrend->pluck('total')->all() ?: [1]);
                    @endphp

                    @foreach($monthlyTrend as $item)
                        <div class="bar-group">
                            <div class="bar-wrap">
                                <span class="bar" style="height: {{ $chartMax > 0 ? ($item['total'] / $chartMax) * 100 : 0 }}%"></span>
                            </div>
                            <small>{{ $item['label'] }}</small>
                            <strong>{{ $item['total'] }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bottom-grid">
            <div class="panel">
                <div class="panel-header">
                    <h4>Jurnal Terbaru</h4>
                </div>

                @if($recentJournals->isNotEmpty())
                    <div class="recent-list">
                        @foreach($recentJournals as $entry)
                            <div class="recent-item">
                                <div class="recent-main">
                                    <strong>{{ $entry->guru?->nama_guru ?? '-' }}</strong>
                                    <span>{{ $entry->kelas?->nama_kelas ?? '-' }} • {{ $entry->jadwal?->mapel?->nama_mapel ?? '-' }}</span>
                                </div>
                                <div class="recent-side">
                                    <span class="recent-date">{{ \Carbon\Carbon::parse($entry->tanggal)->translatedFormat('d M Y') }}</span>
                                    <span class="pill {{ strtolower(str_replace(' ', '-', $entry->status_validasi_guru ?? 'Menunggu')) }}">{{ $entry->status_validasi_guru ?? 'Menunggu' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="empty-text">Belum ada data jurnal terbaru.</p>
                @endif
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h4>Kelas dengan Jurnal Terbanyak</h4>
                </div>

                @if($kelasTop->isNotEmpty())
                    <ul class="leaderboard">
                        @foreach($kelasTop as $item)
                            <li>
                                <span>{{ $item['nama'] }}</span>
                                <strong>{{ $item['total'] }}</strong>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="empty-text">Belum ada data jurnal untuk filter saat ini.</p>
                @endif
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h4>Guru dengan Jurnal Terbanyak</h4>
                </div>

                @if($guruTop->isNotEmpty())
                    <ul class="leaderboard">
                        @foreach($guruTop as $item)
                            <li>
                                <span>{{ $item['nama'] }}</span>
                                <strong>{{ $item['total'] }}</strong>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="empty-text">Belum ada data guru untuk filter saat ini.</p>
                @endif
            </div>
        </section>
    </div>
</div>

<style>
    .stats-page {
        width: 100%;
        color: #1e293b;
    }

    .stats-shell {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        padding: 24px;
    }

    .stats-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }

    .stats-title {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
    }

    .stats-subtitle {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .secondary-btn,
    .primary-btn,
    .ghost-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .primary-btn {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff;
        border-color: transparent;
    }

    .secondary-btn {
        background: #eef2ff;
        color: #3730a3;
        border-color: #c7d2fe;
    }

    .ghost-btn {
        background: #f8fafc;
        color: #334155;
        border-color: #e2e8f0;
    }

    .stats-filter {
        display: grid;
        grid-template-columns: repeat(5, minmax(140px, 1fr));
        gap: 14px;
        margin-bottom: 12px;
        padding: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
    }

    .quick-range {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    .range-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 12px;
        border-radius: 999px;
        background: #eef2ff;
        color: #3730a3;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #c7d2fe;
    }

    .range-chip.active {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border-color: transparent;
        color: #fff;
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .field-group label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .field-group input,
    .field-group select {
        width: 100%;
        height: 42px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #fff;
        padding: 0 12px;
        color: #0f172a;
    }

    .filter-actions {
        display: flex;
        align-items: end;
        gap: 10px;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .metric-card {
        border-radius: 16px;
        padding: 18px 18px 16px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        background: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .metric-card.primary { background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); }
    .metric-card.success { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); }
    .metric-card.warning { background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); }
    .metric-card.danger { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); }

    .recent-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .recent-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
    }

    .recent-main {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .recent-main strong {
        color: #0f172a;
        font-size: 14px;
    }

    .recent-main span,
    .recent-date {
        color: #64748b;
        font-size: 12px;
    }

    .recent-side {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
    }

    .pill.disetujui { background: #dcfce7; color: #166534; }
    .pill.menunggu { background: #fef3c7; color: #92400e; }
    .pill.ditolak { background: #fee2e2; color: #991b1b; }
    .pill.perlu-diperbaiki { background: #ede9fe; color: #6d28d9; }

    .metric-label {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #475569;
    }

    .metric-value {
        margin-top: 14px;
        font-size: 34px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
    }

    .metric-meta {
        margin-top: 8px;
        font-size: 12px;
        color: #475569;
    }

    .content-grid,
    .bottom-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 16px;
        margin-bottom: 22px;
    }

    .panel {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        padding: 18px;
    }

    .panel-header {
        margin-bottom: 18px;
    }

    .panel-header h4 {
        margin: 0;
        color: #1e293b;
        font-size: 18px;
        font-weight: 800;
    }

    .status-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .status-row {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .status-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        font-weight: 700;
        color: #334155;
    }

    .progress-bar {
        position: relative;
        width: 100%;
        height: 10px;
        overflow: hidden;
        border-radius: 999px;
        background: #e2e8f0;
    }

    .progress-fill {
        display: inline-block;
        height: 100%;
        border-radius: inherit;
    }

    .progress-fill.disetujui { background: linear-gradient(90deg, #16a34a, #4ade80); }
    .progress-fill.menunggu { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .progress-fill.ditolak { background: linear-gradient(90deg, #ef4444, #f87171); }
    .progress-fill.perlu-diperbaiki { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }

    .trend-chart {
        height: 220px;
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 12px;
        padding-top: 18px;
    }

    .bar-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }

    .bar-wrap {
        width: 100%;
        height: 160px;
        display: flex;
        align-items: end;
        justify-content: center;
        background: linear-gradient(180deg, #f8fafc, #f1f5f9);
        border-radius: 12px 12px 0 0;
        border: 1px solid #e2e8f0;
        padding: 6px;
    }

    .bar {
        display: block;
        width: 100%;
        border-radius: 10px 10px 0 0;
        background: linear-gradient(180deg, #818cf8, #4f46e5);
        min-height: 4px;
    }

    .bar-group small {
        font-size: 11px;
        color: #64748b;
        text-align: center;
    }

    .bar-group strong {
        font-size: 12px;
        color: #0f172a;
    }

    .leaderboard {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .leaderboard li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 12px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
    }

    .leaderboard span {
        color: #334155;
        font-weight: 600;
    }

    .leaderboard strong {
        color: #1d4ed8;
        font-size: 14px;
    }

    .empty-text {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    @media (max-width: 980px) {
        .stats-filter,
        .metric-grid,
        .content-grid,
        .bottom-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .stats-shell {
            padding: 16px;
        }

        .stats-header,
        .stats-filter,
        .metric-grid,
        .content-grid,
        .bottom-grid {
            grid-template-columns: 1fr;
            display: grid;
        }

        .stats-header {
            justify-content: stretch;
        }

        .secondary-btn,
        .primary-btn,
        .ghost-btn {
            width: 100%;
        }
    }
</style>

@endsection
