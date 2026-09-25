@extends('layouts.admin')

@section('title', 'Mata Pelajaran - Jurnify')
@section('page-title', 'Mata Pelajaran')
@section('page-subtitle', 'Kelola data mata pelajaran sekolah')

@section('content')

{{-- Font Manrope --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="mapel-page">

    {{-- HEADER --}}
    <div class="mapel-header">
        <div class="header-text">
            <h1 class="header-title">Data Mata Pelajaran</h1>
            <p class="header-subtitle">Kelola dan atur daftar mata pelajaran di sistem</p>
        </div>

        <a href="{{ route('admin.mapel.create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Tambah Mapel</span>
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke-linecap="round" stroke-linejoin="round" />
                <polyline points="22 4 12 14.01 9 11.01" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STATISTIK --}}
    <div class="stats-card">
        <div class="stats-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <div class="stats-info">
            <span class="stats-label">Total Mata Pelajaran</span>
            <h2 class="stats-value">{{ $totalMapel }}</h2>
        </div>
    </div>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('admin.mapel.index') }}" class="search-form">
        <div class="search-box">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mata pelajaran..." class="search-input">

            @if(request('search'))
                <a href="{{ route('admin.mapel.index') }}" class="search-reset" title="Reset Pencarian">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </a>
            @endif
        </div>

        <button type="submit" class="btn-search">
            Cari
        </button>
    </form>

    {{-- TABLE --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-nama">Nama Mata Pelajaran</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($mapels as $index => $mapel)
                        <tr>
                            <td class="td-no">
                                {{ $mapels->firstItem() + $index }}
                            </td>

                            <td class="td-nama">
                                <span class="mapel-name">
                                    {{ $mapel->nama_mapel }}
                                </span>
                            </td>

                            <td class="td-aksi">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.mapel.edit', $mapel->id_mapel) }}" class="btn-action edit" title="Edit Mapel">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <button type="button" class="btn-action delete" title="Hapus Mapel" onclick="openDeleteModal('{{ $mapel->id_mapel }}', '{{ addslashes($mapel->nama_mapel) }}')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            <line x1="10" y1="11" x2="10" y2="17" />
                                            <line x1="14" y1="11" x2="14" y2="17" />
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">
                                <div class="empty-content">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                        <line x1="9" y1="9" x2="15" y2="15" />
                                        <line x1="15" y1="9" x2="9" y2="15" />
                                    </svg>
                                    <p>Belum ada data mata pelajaran.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mapels instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="table-bottom">
                <div class="entries">
                    Showing {{ $mapels->firstItem() ?? 0 }}
                    to {{ $mapels->lastItem() ?? 0 }}
                    of {{ $mapels->total() }} results
                </div>

                @if($mapels->hasPages())
                    <div class="pagination">
                        @if($mapels->onFirstPage())
                            <span class="page disabled">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </span>
                        @else
                            <a href="{{ $mapels->previousPageUrl() }}" class="page">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </a>
                        @endif

                        @php
                            $current = $mapels->currentPage();
                            $last = $mapels->lastPage();
                        @endphp

                        @for($page = 1; $page <= $last; $page++)
                            @if($page === 1 || $page === $last || abs($page - $current) <= 1)
                                @if($page === $current)
                                    <span class="page active">{{ $page }}</span>
                                @else
                                    <a href="{{ $mapels->url($page) }}" class="page">
                                        {{ $page }}
                                    </a>
                                @endif
                            @elseif($page === 2 && $current > 3)
                                <span class="dots">...</span>
                            @elseif($page === $last - 1 && $current < $last - 2)
                                <span class="dots">...</span>
                            @endif
                        @endfor

                        @if($mapels->hasMorePages())
                            <a href="{{ $mapels->nextPageUrl() }}" class="page">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </a>
                        @else
                            <span class="page disabled">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- DELETE MODAL --}}
<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">
        <div class="delete-modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                <path d="M10 11v6"></path>
                <path d="M14 11v6"></path>
                <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path>
            </svg>
        </div>

        <div class="delete-modal-text">
            <h3>Hapus Mata Pelajaran?</h3>
            <p>
                Apakah Anda yakin ingin menghapus
                <strong id="deleteMapelName"></strong>?
            </p>
            <span>Data yang sudah dihapus tidak dapat dikembalikan.</span>
        </div>

        <div class="delete-modal-actions">
            <button type="button" class="delete-modal-cancel" onclick="closeDeleteModal()">
                Batal
            </button>

            <form id="deleteModalForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="delete-modal-confirm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .mapel-page,
    .mapel-page * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .mapel-page {
        width: 100%;
        max-width: 100%; /* MEMBATASI LEBAR MAX KESAMPING */
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
        animation: mapelFadeUp 0.45s cubic-bezier(.16, 1, .3, 1) both;
    }

    /* HEADER */
    .mapel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .header-title {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: #2D336B;
        letter-spacing: -0.4px;
    }

    .header-subtitle {
        margin: 4px 0 0;
        font-size: 13.5px;
        color: #64748B;
        font-weight: 500;
    }

    /* PRIMARY BUTTON */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: linear-gradient(135deg, #7886C7 0%, #2D336B 100%);
        color: #FFFFFF;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(45, 51, 107, 0.12);
        transition: all 0.25s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1.5px);
        box-shadow: 0 6px 16px rgba(45, 51, 107, 0.2);
        color: #FFFFFF;
    }

    .btn-primary svg {
        width: 16px;
        height: 16px;
    }

    /* ALERT */
    .alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 600;
    }

    .alert svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .alert-success {
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        color: #166534;
    }

    .alert-danger {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #991B1B;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 18px;
    }

    /* STATS */
    .stats-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 24px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(45, 51, 107, 0.02);
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #F0F3FF;
        color: #7886C7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stats-icon svg {
        width: 24px;
        height: 24px;
    }

    .stats-label {
        display: block;
        font-size: 12.5px;
        color: #64748B;
        font-weight: 600;
    }

    .stats-value {
        margin: 2px 0 0;
        font-size: 24px;
        font-weight: 800;
        color: #2D336B;
        line-height: 1.2;
    }

    /* SEARCH */
    .search-form {
        display: flex;
        gap: 10px;
    }

    .search-box {
        position: relative;
        flex: 1;
        display: flex;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: 14px;
        width: 16px;
        height: 16px;
        color: #94A3B8;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 11px 40px 11px 40px;
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 600;
        color: #0F172A;
        outline: none;
        transition: all 0.2s ease;
    }

    .search-input::placeholder {
        color: #94A3B8;
        font-weight: 500;
    }

    .search-input:focus {
        border-color: #7886C7;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, 0.15);
    }

    .search-reset {
        position: absolute;
        right: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        color: #94A3B8;
        border-radius: 50%;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .search-reset:hover {
        background: #F1F5F9;
        color: #0F172A;
    }

    .search-reset svg {
        width: 15px;
        height: 15px;
    }

    .btn-search {
        padding: 0 20px;
        background: #2D336B;
        color: #FFFFFF;
        border: none;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-search:hover {
        background: #1E234D;
    }

    /* TABLE CARD Ringkas */
    .table-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(45, 51, 107, 0.02);
        overflow: hidden;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .data-table th {
        padding: 14px 18px;
        background: #F8FAFC;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #E2E8F0;
        white-space: nowrap;
    }

    .data-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 14px;
        color: #1E293B;
        vertical-align: middle;
        white-space: nowrap;
    }

    .data-table tbody tr:hover {
        background-color: #F8FAFC;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* KOLOM LEBAR YANG DIPAS-KAN */
    .col-no,
    .td-no {
        width: 60px;
        text-align: center;
        font-weight: 600;
        color: #64748B;
    }

    .col-nama,
    .td-nama {
        width: 300px;
    }

    .col-aksi,
    .td-aksi {
        width: 180px;
        text-align: center;
    }

    .mapel-name {
        font-weight: 700;
        color: #0F172A;
    }

    /* ACTION BUTTONS */
    .action-buttons {
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-action svg {
        width: 14px;
        height: 14px;
    }

    .btn-action.edit {
        background: #EEF2FF;
        color: #4F46E5;
    }

    .btn-action.edit:hover {
        background: #E0E7FF;
        color: #3730A3;
    }

    .btn-action.delete {
        background: #FEE2E2;
        color: #DC2626;
    }

    .btn-action.delete:hover {
        background: #FCA5A5;
        color: #991B1B;
    }

    /* EMPTY STATE */
    .empty-state {
        padding: 40px 20px !important;
        text-align: center;
    }

    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: #94A3B8;
    }

    .empty-content svg {
        width: 40px;
        height: 40px;
    }

    .empty-content p {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
    }

    /* PAGINATION */
    .table-bottom {
        min-height: 70px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        border-top: 1px solid #E2E8F0;
        background: #FFFFFF;
    }

    .entries {
        color: #3f4148;
        font-size: 13px;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .page {
        min-width: 36px;
        height: 36px;
        border-radius: 4px;
        background: #f0f1f4;
        color: #3f4148;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: background .2s, color .2s, transform .2s;
    }

    .page:not(.active):not(.disabled):hover {
        background: #dce4ff;
        color: #182864;
        transform: translateY(-1px);
    }

    .page.active {
        background: #182864;
        color: #fff;
    }

    .page.disabled {
        opacity: .45;
        pointer-events: none;
    }

    .page .material-symbols-outlined {
        font-family: 'Material Symbols Outlined';
        font-size: 18px;
        font-weight: normal;
        line-height: 1;
    }

    .dots {
        min-width: 24px;
        text-align: center;
        color: #555861;
    }

    /* DELETE MODAL */
    .delete-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(3px);
    }

    .delete-modal.show {
        display: flex;
        animation: modalFadeIn 0.2s ease both;
    }

    .delete-modal-content {
        width: 100%;
        max-width: 400px;
        padding: 24px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
        text-align: center;
        animation: modalScaleIn 0.25s cubic-bezier(.16, 1, .3, 1) both;
    }

    .delete-modal-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FEF2F2;
        color: #DC2626;
        border-radius: 14px;
    }

    .delete-modal-icon svg {
        width: 24px;
        height: 24px;
    }

    .delete-modal-text h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        color: #1E293B;
    }

    .delete-modal-text p {
        margin: 8px 0 4px;
        font-size: 13.5px;
        line-height: 1.5;
        color: #64748B;
    }

    .delete-modal-text p strong {
        color: #334155;
        font-weight: 700;
    }

    .delete-modal-text span {
        display: block;
        font-size: 12px;
        color: #94A3B8;
    }

    .delete-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
    }

    .delete-modal-cancel,
    .delete-modal-confirm {
        height: 40px;
        padding: 0 16px;
        border-radius: 10px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .delete-modal-cancel {
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #E2E8F0;
    }

    .delete-modal-cancel:hover {
        background: #E2E8F0;
        color: #334155;
    }

    .delete-modal-confirm {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #DC2626;
        color: #FFFFFF;
        border: none;
    }

    .delete-modal-confirm:hover {
        background: #B91C1C;
    }

    .delete-modal-confirm svg {
        width: 14px;
        height: 14px;
    }

    /* ANIMATION */
    @keyframes mapelFadeUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes modalFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes modalScaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    /* RESPONSIVE */
    @media (max-width: 767px) {
        .mapel-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .search-form {
            flex-direction: column;
        }

        .btn-search {
            width: 100%;
            padding: 10px;
        }
    }
</style>

<script>
    function openDeleteModal(id, name) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteModalForm');
        const nameElement = document.getElementById('deleteMapelName');

        form.action = "{{ url('admin/mapel') }}/" + id;
        nameElement.textContent = name;

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');

        modal.classList.remove('show');
        document.body.style.overflow = '';
    }

    document.getElementById('deleteModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>

@endsection