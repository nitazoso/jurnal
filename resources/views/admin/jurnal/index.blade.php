@extends('layouts.admin')

@section('title', 'Daftar Jurnal - Jurnify')

@section('page-title', 'Daftar Jurnal')

<style>
    .jurnal-page {
        width: 100%;
    }

    .jurnal-card {
        width: 100%;
        background: #fff;
        border-radius: 9px;
        overflow: hidden;
        box-shadow: 0 2px 7px rgba(0, 0, 0, .025);
        animation: jurnalFadeIn .45s ease both;
    }

    .jurnal-header {
        padding: 25px 24px 23px;
    }

    .jurnal-title {
        margin: 0 0 3px;
        color: #1d2c67;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.3;
    }

    .jurnal-description {
        margin: 0;
        color: #4b4d56;
        font-size: 14px;
        line-height: 1.5;
    }

    .jurnal-filter {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 24px 22px;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 9px;
        width: 312px;
        height: 38px;
        padding: 0 12px;
        background: #f1f2f5;
        border: 1px solid transparent;
        border-radius: 5px;
        transition: background .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .search-box:focus-within {
        background: #fff;
        border-color: #cfd4e5;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, .12);
    }

    .search-box .material-symbols-outlined {
        color: #777b86;
        font-size: 20px;
        transition: color .2s ease;
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
        font-size: 12px;
    }

    .search-box input::placeholder {
        color: #8a8d96;
    }

    .search-btn {
        width: 82px;
        height: 38px;
        margin-left: 229px;
        border: none;
        border-radius: 8px;
        background: #30366f;
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(48, 54, 111, .12);
        transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
    }

    .search-btn:hover {
        background: #252c61;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(48, 54, 111, .18);
    }

    .search-btn:active {
        transform: translateY(0);
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .jurnal-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .jurnal-table thead {
        background: #f1f2f5;
    }

    .jurnal-table th {
        height: 56px;
        padding: 0 12px;
        color: #3f4350;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .2px;
        text-align: left;
        white-space: nowrap;
    }

    .jurnal-table td {
        height: 82px;
        padding: 10px 12px;
        border-bottom: 1px solid #f2f3f5;
        color: #30323a;
        font-size: 12px;
        vertical-align: middle;
        transition: background .2s ease;
    }

    .jurnal-table tbody tr {
        transition: background .2s ease;
    }

    .jurnal-table tbody tr:hover {
        background: #fafbff;
    }

    .jurnal-table tbody tr:hover td {
        border-bottom-color: #e9ebf3;
    }

    .jurnal-table th:first-child,
    .jurnal-table td:first-child {
        width: 45px;
        padding-left: 24px;
    }

    .jurnal-table th:nth-child(2),
    .jurnal-table td:nth-child(2) {
        width: 105px;
    }

    .jurnal-table th:nth-child(3),
    .jurnal-table td:nth-child(3) {
        width: 145px;
    }

    .jurnal-table th:nth-child(4),
    .jurnal-table td:nth-child(4) {
        width: 125px;
    }

    .jurnal-table th:nth-child(5),
    .jurnal-table td:nth-child(5) {
        width: 95px;
    }

    .jurnal-table th:nth-child(6),
    .jurnal-table td:nth-child(6) {
        width: 220px;
    }

    .jurnal-table th:nth-child(7),
    .jurnal-table td:nth-child(7) {
        width: 145px;
    }

    .jurnal-table th:nth-child(8),
    .jurnal-table td:nth-child(8) {
        width: 145px;
    }

    .jurnal-table th:last-child,
    .jurnal-table td:last-child {
        padding-right: 24px;
    }

    .number-text {
        color: #555965;
        font-weight: 600;
    }

    .date-text {
        color: #4d5059;
        white-space: nowrap;
    }

    .guru-name {
        color: #202126;
        font-size: 12px;
        font-weight: 700;
    }

    .materi-text {
        display: -webkit-box;
        overflow: hidden;
        color: #30323a;
        line-height: 1.45;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .attendance {
        color: #202126;
        font-weight: 700;
        line-height: 1.5;
    }

    .attendance small {
        color: #777b86;
        font-size: 10px;
        font-weight: 600;
    }

    .status-badge,
    .validation-badge {
        display: inline-flex;
        align-items: center;
        min-height: 25px;
        padding: 4px 9px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .status-badge {
        background: #dce4ff;
        color: #344477;
    }

    .validation-badge {
        background: #d5f7e8;
        color: #087451;
    }

    .jurnal-table tbody tr:hover .status-badge,
    .jurnal-table tbody tr:hover .validation-badge {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, .06);
    }

    .empty {
        height: 94px !important;
        padding: 0 !important;
        color: #777b86 !important;
        font-size: 12px !important;
        text-align: center;
    }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 70px;
        padding: 0 24px;
    }

    .pagination-info {
        color: #3f4148;
        font-size: 12px;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 8px;
        border-radius: 4px;
        background: #f0f1f4;
        color: #343945;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s ease, color .2s ease, transform .2s ease;
    }

    .pagination a:hover {
        background: #dce4ff;
        color: #182864;
        transform: translateY(-1px);
    }

    .pagination .active {
        background: #182864;
        color: #fff;
    }

    .pagination .disabled {
        opacity: .45;
    }

    .pagination .material-symbols-outlined {
        font-size: 18px;
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

    @media (max-width: 1200px) {
        .jurnal-table {
            min-width: 1100px;
        }

        .search-btn {
            margin-left: 80px;
        }
    }

    @media (max-width: 768px) {
        .jurnal-header {
            padding: 20px;
        }

        .jurnal-filter {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            padding: 0 20px 20px;
        }

        .search-box,
        .search-btn {
            width: 100%;
        }

        .search-btn {
            margin-left: 0;
        }

        .pagination-wrapper {
            flex-direction: column;
            justify-content: center;
            gap: 12px;
            padding: 16px 20px;
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
                                <div class="attendance">
                                    {{ $jurnal->jml_hadir }} hadir
                                </div>

                                <small>
                                    {{ $jurnal->jml_tidak_hadir }} tidak hadir
                                </small>
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