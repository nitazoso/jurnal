@extends('layouts.admin')

@section('title', 'Daftar Jurnal - Jurnify')
@section('page-title', 'Daftar Jurnal')

<style>
    .jurnal-page {
        width: 100%;
        font-family: 'Manrope', sans-serif;
    }

    .jurnal-card {
        width: 100%;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        animation: jurnalFadeIn .45s ease both;
    }

    .jurnal-header {
        padding: 28px 28px 20px;
    }

    .jurnal-title {
        margin: 0 0 6px;
        color: #1d2c67;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.3;
    }

    .jurnal-description {
        margin: 0;
        color: #4b4d56;
        font-size: 14px;
        line-height: 1.5;
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
        width: 360px;
        height: 42px;
        padding: 0 14px;
        background: #f1f2f5;
        border: 1px solid transparent;
        border-radius: 8px;
        transition: all .2s ease;
    }

    .search-box:focus-within {
        background: #fff;
        border-color: #cfd4e5;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, .12);
    }

    .search-box .material-symbols-outlined {
        color: #777b86;
        font-size: 22px;
    }

    .search-box:focus-within .material-symbols-outlined {
        color: #30366f;
    }

    .search-box input {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        color: #30323a;
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
    }

    .search-btn {
        height: 42px;
        padding: 0 20px;
        margin-left: 0;
        border: none;
        border-radius: 8px;
        background: #30366f;
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(48, 54, 111, .12);
        transition: all .2s ease;
    }

    .search-btn:hover {
        background: #252c61;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(48, 54, 111, .18);
    }

    /* TABLE */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .jurnal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .jurnal-table thead {
        background: #f1f2f5;
    }

    .jurnal-table th {
        padding: 16px;
        color: #3f4350;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-align: left;
        white-space: nowrap;
    }

    .jurnal-table td {
        padding: 16px;
        border-bottom: 1px solid #f2f3f5;
        color: #30323a;
        font-size: 13px;
        vertical-align: middle;
    }

    .jurnal-table tbody tr:hover {
        background: #fafbff;
    }

    .jurnal-table th:first-child,
    .jurnal-table td:first-child {
        padding-left: 28px;
        text-align: center;
        width: 50px;
    }

    .jurnal-table th:last-child,
    .jurnal-table td:last-child {
        padding-right: 28px;
    }

    .number-text {
        color: #555965;
        font-weight: 600;
    }

    .date-text {
        color: #4d5059;
        font-weight: 500;
        white-space: nowrap;
    }

    .guru-name {
        color: #202126;
        font-size: 13px;
        font-weight: 700;
    }

    .materi-text {
        display: -webkit-box;
        overflow: hidden;
        color: #30323a;
        line-height: 1.5;
        font-size: 13px;
        max-width: 260px;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* FIX TUKAR / TUMPANG TINDIH KEHADIRAN */
    .attendance-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 2px;
    }

    .attendance-box .hadir-text {
        color: #202126;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
    }

    .attendance-box .absen-text {
        color: #777b86;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.2;
    }

    .status-badge,
    .validation-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge {
        background: #dce4ff;
        color: #344477;
    }

    .validation-badge {
        background: #d5f7e8;
        color: #087451;
    }

    .empty {
        padding: 40px !important;
        color: #777b86 !important;
        font-size: 14px !important;
        text-align: center;
    }

    /* PAGINATION */
    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 28px;
    }

    .pagination-info {
        color: #3f4148;
        font-size: 13px;
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
        border-radius: 6px;
        background: #f0f1f4;
        color: #343945;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all .2s ease;
    }

    .pagination a:hover {
        background: #dce4ff;
        color: #182864;
    }

    .pagination .active {
        background: #182864;
        color: #fff;
    }

    .pagination .disabled {
        opacity: .45;
    }

    @keyframes jurnalFadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

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

        .pagination-wrapper {
            flex-direction: column;
            gap: 16px;
            padding: 20px;
        }
    }
</style>

@section('content')

<div class="jurnal-page">
    <div class="jurnal-card">

        <div class="jurnal-header">
            <h3 class="jurnal-title">Daftar Jurnal</h3>
            <p class="jurnal-description">
                Daftar jurnal pembelajaran yang telah dibuat oleh guru.
            </p>
        </div>

        <form action="{{ route('admin.jurnal.index') }}" method="GET" class="jurnal-filter">
            <div class="search-box">
                <span class="material-symbols-outlined">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari guru, mapel, kelas, atau materi...">
            </div>

            <button type="submit" class="search-btn">
                Cari
            </button>
        </form>

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
                                    {{ $jurnal->tanggal->format('d M Y') }}
                                </span>
                            </td>

                            <td>
                                <span class="guru-name">
                                    {{ $jurnal->guru->nama_guru ?? '-' }}
                                </span>
                            </td>

                            <td>
                                {{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}
                            </td>

                            <td>
                                {{ $jurnal->kelas->nama_kelas ?? '-' }}
                            </td>

                            <td>
                                <span class="materi-text">
                                    {{ $jurnal->materi }}
                                </span>
                            </td>

                            <td>
                                <div class="attendance-box">
                                    <span class="hadir-text">{{ $jurnal->jml_hadir }} hadir</span>
                                    <span class="absen-text">{{ $jurnal->jml_tidak_hadir }} tidak hadir</span>
                                </div>
                            </td>

                            <td>
                                <span class="status-badge">
                                    {{ $jurnal->status_guru }}
                                </span>
                            </td>

                            <td>
                                <span class="validation-badge">
                                    {{ $jurnal->status_validasi_guru }}
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

        @if($jurnals->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Menampilkan {{ $jurnals->firstItem() }}–{{ $jurnals->lastItem() }}
                    dari {{ $jurnals->total() }} jurnal
                </div>

                <div class="pagination">
                    @if($jurnals->onFirstPage())
                        <span class="disabled">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $jurnals->previousPageUrl() }}">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    @endif

                    @foreach($jurnals->getUrlRange(1, $jurnals->lastPage()) as $page => $url)
                        @if($page == $jurnals->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($jurnals->hasMorePages())
                        <a href="{{ $jurnals->nextPageUrl() }}">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    @else
                        <span class="disabled">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </span>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>

@endsection