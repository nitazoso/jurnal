@extends('layouts.admin')

@section('title', 'Daftar Jurnal - Jurnify')
@section('page-title', 'Daftar Jurnal')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="jurnal-page">

    <div class="jurnal-card">

        {{-- HEADER --}}
        <div class="jurnal-header">
            <h3 class="jurnal-title">Daftar Jurnal</h3>
            <p class="jurnal-description">
                Daftar jurnal pembelajaran yang telah dibuat oleh guru.
            </p>
        </div>

        {{-- FILTER & SEARCH --}}
        <form action="{{ route('admin.jurnal.index') }}" method="GET" class="jurnal-filter">

            <div class="search-box">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                    <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari guru, mapel, kelas, atau materi..."
                >
            </div>

            <button type="submit" class="search-btn">
                <svg class="search-btn-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                    <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Cari</span>
            </button>

        </form>

        {{-- TABLE DATA --}}
        <div class="table-wrapper">
            <table class="jurnal-table">

                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL</th>
                        <th>GURU</th>
                        <th>MAPEL</th>
                        <th>KELAS</th>
                        <th>MATERI</th>
                        <th>KEHADIRAN</th>
                        <th>STATUS GURU</th>
                        <th>VALIDASI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jurnals as $jurnal)
                        <tr>

                            <td>
                                <span class="number-text">
                                    {{ $jurnals->firstItem() + $loop->index }}
                                </span>
                            </td>

                            <td>
                                <span class="date-text">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y') }}
                                </span>
                            </td>

                            <td>
                                <span class="guru-name">
                                    {{ $jurnal->guru->nama_guru ?? $jurnal->user->nama_user ?? '-' }}
                                </span>
                            </td>

                            <td>
                                {{ $jurnal->jadwal->mapel->nama_mapel ?? $jurnal->mapel->nama_mapel ?? '-' }}
                            </td>

                            <td>
                                {{ $jurnal->kelas->nama_kelas ?? '-' }}
                            </td>

                            <td>
                                <span class="materi-text" title="{{ $jurnal->materi }}">
                                    {{ $jurnal->materi }}
                                </span>
                            </td>

                            <td>
                                <div class="attendance-box">
                                    <span class="hadir-text">
                                        {{ $jurnal->jml_hadir ?? 0 }} hadir
                                    </span>
                                    <span class="absen-text">
                                        {{ $jurnal->jml_tidak_hadir ?? 0 }} tidak hadir
                                    </span>
                                </div>
                            </td>

                            <td>
                                <span class="status-badge">
                                    {{ $jurnal->status_guru ?? 'Hadir' }}
                                </span>
                            </td>

                            <td>
                                <span class="validation-badge
                                    {{ strtolower($jurnal->status_validasi_guru ?? 'Disetujui') === 'disetujui' ? 'approved' : '' }}
                                    {{ strtolower($jurnal->status_validasi_guru ?? '') === 'menunggu' ? 'pending' : '' }}
                                    {{ strtolower($jurnal->status_validasi_guru ?? '') === 'ditolak' ? 'rejected' : '' }}">
                                    {{ $jurnal->status_validasi_guru ?? 'Disetujui' }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty">
                                Belum ada data jurnal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINASI --}}
        @if($jurnals->hasPages())
            <div class="pagination-wrapper">

                <div class="pagination-info">
                    Menampilkan {{ $jurnals->firstItem() }}–{{ $jurnals->lastItem() }}
                    dari {{ $jurnals->total() }} jurnal
                </div>

                <div class="pagination">

                    {{-- PREVIOUS --}}
                    @if($jurnals->onFirstPage())
                        <span class="disabled">
                            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path
                                    d="M15 18L9 12L15 6"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $jurnals->previousPageUrl() }}">
                            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path
                                    d="M15 18L9 12L15 6"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>
                    @endif

                    {{-- PAGE NUMBERS --}}
                    @foreach($jurnals->getUrlRange(1, $jurnals->lastPage()) as $page => $url)
                        @if($page == $jurnals->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- NEXT --}}
                    @if($jurnals->hasMorePages())
                        <a href="{{ $jurnals->nextPageUrl() }}">
                            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path
                                    d="M9 18L15 12L9 6"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>
                    @else
                        <span class="disabled">
                            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path
                                    d="M9 18L15 12L9 6"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>
                    @endif

                </div>
            </div>
        @endif

    </div>

</div>

<style>
    .jurnal-page,
    .jurnal-page * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .jurnal-page {
        width: 100%;
    }

    .jurnal-card {
        width: 100%;
        background: #FFFFFF;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03);
        animation: jurnalFadeIn 0.45s ease both;
    }

    /* HEADER */
    .jurnal-header {
        padding: 28px 28px 20px;
    }

    .jurnal-title {
        margin: 0 0 6px;
        color: #2D336B;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: -0.4px;
    }

    .jurnal-description {
        margin: 0;
        color: #64748B;
        font-size: 14px;
        line-height: 1.5;
        font-weight: 500;
    }

    /* FILTER & SEARCH */
    .jurnal-filter {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 28px 24px;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 380px;
        height: 44px;
        padding: 0 14px;
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .search-box:focus-within {
        border-color: #7886C7;
        box-shadow: 0 0 0 4px rgba(120, 134, 199, 0.15);
    }

    .search-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        color: #94A3B8;
        transition: color 0.2s ease;
    }

    .search-box:focus-within .search-icon {
        color: #7886C7;
    }

    .search-box input {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        color: #0F172A;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
    }

    .search-box input::placeholder {
        color: #94A3B8;
    }

    .search-btn {
        height: 44px;
        padding: 0 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #7886C7 0%, #2D336B 100%);
        color: #FFFFFF;
        font-family: inherit;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(45, 51, 107, 0.15);
        transition: all 0.2s ease;
    }

    .search-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(45, 51, 107, 0.25);
    }

    .search-btn-icon {
        width: 17px;
        height: 17px;
        flex-shrink: 0;
    }

    /* TABLE */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .jurnal-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .jurnal-table thead {
        background: #F8FAFC;
    }

    .jurnal-table th {
        padding: 16px 20px;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #E2E8F0;
        white-space: nowrap;
    }

    .jurnal-table td {
        padding: 18px 20px;
        border-bottom: 1px solid #F1F5F9;
        color: #1E293B;
        font-size: 14px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .jurnal-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .jurnal-table tbody tr:hover {
        background: #F8FAFC;
    }

    .jurnal-table th:first-child,
    .jurnal-table td:first-child {
        padding-left: 28px;
        text-align: center;
        width: 60px;
    }

    .jurnal-table th:last-child,
    .jurnal-table td:last-child {
        padding-right: 28px;
    }

    .number-text {
        color: #64748B;
        font-weight: 600;
    }

    .date-text {
        color: #334155;
        font-weight: 600;
    }

    .guru-name {
        color: #0F172A;
        font-weight: 700;
    }

    .materi-text {
        display: -webkit-box;
        overflow: hidden;
        color: #334155;
        line-height: 1.5;
        font-size: 13.5px;
        max-width: 280px;
        white-space: normal;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* ATTENDANCE */
    .attendance-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 2px;
    }

    .attendance-box .hadir-text {
        color: #166534;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
    }

    .attendance-box .absen-text {
        color: #94A3B8;
        font-size: 11.5px;
        font-weight: 600;
        line-height: 1.2;
    }

    /* BADGES */
    .status-badge,
    .validation-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* Disetujui */
    .validation-badge.approved {
        background: #DCFCE7;
        color: #15803D;
    }

    /* Menunggu */
    .validation-badge.pending {
        background: #FEF3C7;
        color: #B45309;
    }

    /* Ditolak */
    .validation-badge.rejected {
        background: #FEE2E2;
        color: #DC2626;
    }

    .validation-badge {
        background: #DCFCE7;
        color: #15803D;
    }

    /* EMPTY STATE */
    .empty {
        padding: 56px 24px !important;
        color: #94A3B8 !important;
        font-size: 14.5px !important;
        font-weight: 600;
        text-align: center;
    }

    /* PAGINATION */
    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 28px;
        border-top: 1px solid #E2E8F0;
        background: #FFFFFF;
    }

    .pagination-info {
        color: #64748B;
        font-size: 13.5px;
        font-weight: 500;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 8px;
        background: #F1F5F9;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .pagination a:hover {
        background: #E2E8F0;
        color: #0F172A;
    }

    .pagination .active {
        background: #2D336B;
        color: #FFFFFF;
    }

    .pagination .disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    .chevron-icon {
        width: 18px;
        height: 18px;
        display: block;
    }

    /* ANIMATION */
    @keyframes jurnalFadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .jurnal-header {
            padding: 20px;
        }

        .jurnal-filter {
            flex-direction: column;
            align-items: stretch;
            padding: 0 20px 20px;
        }

        .search-box {
            width: 100%;
        }

        .search-btn {
            width: 100%;
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 16px;
            padding: 20px;
            align-items: center;
        }
    }
</style>

@endsection