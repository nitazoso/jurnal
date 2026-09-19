@extends('layouts.admin')
@section('title', 'Data Guru - Jurnify')
@section('page-title', 'Data Guru')

<style>
.guru-page {
    width: 100%;
    max-width: 100%;
    animation: pageFadeIn .45s ease both;
}

.guru-page .stats {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
    margin: 0 0 23px;
}

.guru-page .stat-card {
    min-width: 0;
    min-height: 113px;
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 9px;
    padding: 22px 23px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, .025);
    position: relative;
    animation: cardUp .45s ease both;
    transition: transform .25s, box-shadow .25s;
}

.guru-page .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(29, 44, 103, .08);
}

.stat-title {
    color: #41434c;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 7px;
}

.stat-value strong {
    color: #1d2c67;
    font-size: 31px;
    font-weight: 800;
    line-height: 1;
}

.stat-icon {
    position: absolute;
    top: 24px;
    right: 23px;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #dce4ff;
    color: #172b67;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform .25s, box-shadow .25s;
}

.stat-card:hover .stat-icon {
    transform: scale(1.08) rotate(-2deg);
    box-shadow: 0 4px 10px rgba(45, 51, 107, .12);
}

.stat-icon .material-symbols-outlined {
    font-size: 22px;
}

.success-message {
    margin-bottom: 20px;
    padding: 12px 16px;
    border-radius: 8px;
    background: #d5f7e8;
    color: #087451;
    font-size: 13px;
    font-weight: 600;
    animation: fadeDown .4s ease both;
}

.activity-card {
    width: 100%;
    background: #fff;
    border-radius: 9px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .025);
    animation: cardUp .5s ease .08s both;
    transition: box-shadow .25s;
}

.activity-card:hover {
    box-shadow: 0 5px 16px rgba(29, 44, 103, .06);
}

.activity-header {
    padding: 25px 24px 24px;
}

.activity-title {
    color: #1d2c67;
    font-size: 20px;
    font-weight: 800;
    margin: 0 0 3px;
}

.activity-description {
    color: #4b4d56;
    font-size: 14px;
    margin: 0;
}

.filters {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 24px 16px;
    width: 100%;
}

.search-box {
    width: 330px;
    height: 40px;
    background: #f1f2f5;
    border: 1px solid transparent;
    border-radius: 4px;
    display: flex;
    align-items: center;
    padding: 0 12px;
    gap: 9px;
    margin: 0;
    transition: border-color .2s, box-shadow .2s, background .2s;
}

.search-box:focus-within {
    background: #fff;
    border-color: #cfd2dc;
    box-shadow: 0 0 0 3px rgba(65, 105, 255, .08);
}

.search-box .material-symbols-outlined {
    color: #777b86;
    font-size: 21px;
    transition: color .2s, transform .2s;
}

.search-box:focus-within .material-symbols-outlined {
    color: #4169ff;
    transform: scale(1.05);
}

.search-box input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    font: 13px Manrope, sans-serif;
    color: #30323a;
}

.search-box input::placeholder {
    color: #777b86;
}

.role-filter,
.add-user-btn {
    height: 40px;
    border: 1px solid #cfd2dc;
    border-radius: 10px;
    background: #fff;
    color: #30323a;
    font: 700 13px Manrope, sans-serif;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
    transition: background .2s, border-color .2s, transform .2s, box-shadow .2s;
}

.role-filter:hover {
    background: #f1f2f5;
    border-color: #b9bdc8;
    transform: translateY(-1px);
}

.add-user-btn {
    margin-left: auto;
    background: #2d336b;
    border-color: #2d336b;
    color: #fff;
    padding: 0 17px;
    gap: 7px;
}

.add-user-btn:hover {
    background: #1e2450;
    border-color: #1e2450;
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(45, 51, 107, .18);
}

.add-user-btn:active,
.role-filter:active {
    transform: translateY(0);
}

.add-user-btn .material-symbols-outlined {
    font-size: 19px;
    transition: transform .2s;
}

.add-user-btn:hover .material-symbols-outlined {
    transform: scale(1.08);
}

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

thead {
    background: #f1f2f5;
}

th {
    height: 60px;
    padding: 0 12px;
    text-align: left;
    color: #484a53;
    font-size: 13px;
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: .3px;
}

td {
    height: 94px;
    padding: 9px 12px;
    color: #17181d;
    font-size: 14px;
    vertical-align: middle;
}

tbody tr {
    border-bottom: 1px solid #f4f4f4;
    transition: background .2s;
}

tbody tr:hover {
    background: #fafbff;
}

.no-column {
    text-align: center;
}

.guru-nip {
    font-family: monospace;
    color: #4d5059;
    font-size: 13px;
    word-break: break-word;
}

.guru-name {
    color: #202126;
    font-size: 15px;
    font-weight: 800;
    transition: color .2s;
}

tbody tr:hover .guru-name {
    color: #1d2c67;
}

.guru-actions {
    display: flex;
    align-items: center;
    gap: 18px;
}

.icon-action {
    width: 22px;
    height: 30px;
    border: none;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    padding: 0;
    transition: transform .2s;
}

.icon-action:hover {
    transform: translateY(-2px) scale(1.08);
}

.icon-action .material-symbols-outlined {
    font-size: 19px;
}

.icon-action.edit {
    color: #182864;
}

.icon-action.delete {
    color: #e00000;
}

.table-bottom {
    min-height: 70px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
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
    font-size: 18px;
}

.empty-row {
    text-align: center;
    padding: 40px;
    color: #777b86 !important;
}

.delete-modal {
    display: flex;
    visibility: hidden;
    opacity: 0;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0, 0, 0, .45);
    align-items: center;
    justify-content: center;
    padding: 20px;
    transition: opacity .25s, visibility .25s;
}

.delete-modal.show {
    visibility: visible;
    opacity: 1;
}

.delete-modal-box {
    width: min(520px, 100%);
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0, 0, 0, .22);
    transform: translateY(10px) scale(.98);
    transition: transform .25s ease;
}

.delete-modal.show .delete-modal-box {
    transform: translateY(0) scale(1);
}

.delete-modal-header {
    display: flex;
    gap: 18px;
    padding: 28px 30px 20px;
}

.delete-icon {
    width: 60px;
    height: 60px;
    flex: 0 0 60px;
    border-radius: 50%;
    background: #ffe1e1;
    color: #e5242a;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform .25s;
}

.delete-modal.show .delete-icon {
    animation: iconPop .35s ease .1s both;
}

.delete-icon .material-symbols-outlined {
    font-size: 31px;
}

.delete-modal-header h3 {
    color: #1d2c67;
    font-size: 23px;
    font-weight: 800;
    margin: 2px 0 4px;
}

.delete-modal-header p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
}

.delete-info {
    margin: 4px 30px 20px;
    padding: 17px 20px;
    background: #f8fafc;
    border: 1px solid #dfe6ef;
    border-radius: 12px;
}

.delete-name {
    color: #1d2c67;
    font-size: 17px;
    font-weight: 800;
    margin-bottom: 6px;
}

.delete-detail {
    display: flex;
    gap: 11px;
    color: #64748b;
    font-size: 13px;
    flex-wrap: wrap;
}

.delete-detail strong {
    color: #526078;
}

.delete-detail .dot {
    color: #94a3b8;
}

.delete-warning {
    display: flex;
    gap: 10px;
    margin: 0 30px 24px;
    padding: 13px 16px;
    background: #fff9e8;
    border-left: 4px solid #f59e0b;
    color: #a14c08;
    font-size: 12px;
    line-height: 1.5;
}

.delete-warning .material-symbols-outlined {
    color: #d97706;
    font-size: 19px;
    flex: 0 0 19px;
}

.delete-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 30px;
    background: #f8fafc;
    border-top: 1px solid #e8ebf0;
}

.cancel-btn,
.confirm-btn {
    height: 42px;
    padding: 0 20px;
    border-radius: 8px;
    font: 800 13px Manrope, sans-serif;
    cursor: pointer;
    transition: background .2s, border-color .2s, transform .2s, box-shadow .2s;
}

.cancel-btn {
    min-width: 100px;
    background: #fff;
    border: 1px solid #cbd5e1;
    color: #343945;
}

.cancel-btn:hover {
    background: #f1f2f5;
    border-color: #b9bdc8;
    transform: translateY(-1px);
}

.confirm-btn {
    min-width: 120px;
    background: #e5242a;
    border: 1px solid #e5242a;
    color: #fff;
    box-shadow: 0 2px 5px rgba(229, 36, 42, .2);
}

.confirm-btn:hover {
    background: #c91c22;
    border-color: #c91c22;
    transform: translateY(-1px);
    box-shadow: 0 5px 12px rgba(229, 36, 42, .22);
}

.cancel-btn:active,
.confirm-btn:active {
    transform: translateY(0);
}

@keyframes pageFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes cardUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeDown {
    from {
        opacity: 0;
        transform: translateY(-7px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes iconPop {
    from {
        opacity: 0;
        transform: scale(.75);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@media (prefers-reduced-motion: reduce) {
    .guru-page,
    .stat-card,
    .activity-card,
    .success-message,
    .delete-modal,
    .delete-modal-box,
    .delete-icon {
        animation: none !important;
        transition: none !important;
    }
}

@media (max-width: 900px) {
    .guru-page .stats {
        grid-template-columns: 1fr 1fr;
    }

    .filters {
        flex-wrap: wrap;
    }

    .search-box {
        width: 100%;
    }

    .add-user-btn {
        margin-left: 0;
    }
}

@media (max-width: 600px) {
    .guru-page .stats {
        grid-template-columns: 1fr;
    }

    .activity-header {
        padding: 20px;
    }

    .filters {
        padding: 0 20px 16px;
    }

    .table-bottom {
        padding: 0 15px;
        gap: 10px;
        flex-direction: column;
        justify-content: center;
    }

    .delete-modal-header {
        padding: 22px 20px 16px;
    }

    .delete-info {
        margin-left: 20px;
        margin-right: 20px;
    }

    .delete-warning {
        margin-left: 20px;
        margin-right: 20px;
    }

    .delete-modal-footer {
        padding: 16px 20px;
    }
}
</style>

@section('content')
<div class="guru-page">
    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <div class="stats">
        <div class="stat-card">
            <div class="stat-title">Total Guru</div>
            <div class="stat-value">
                <strong>{{ $totalGuru ?? $gurus->count() }}</strong>
            </div>
            <div class="stat-icon">
                <span class="material-symbols-outlined">school</span>
            </div>
        </div>
    </div>

    <div class="activity-card">
        <div class="activity-header">
            <h2 class="activity-title">Daftar Guru</h2>
            <p class="activity-description">
                Kelola data guru yang terdaftar di sistem Jurnify.
            </p>
        </div>

        <div class="filters">
            <form action="{{ route('admin.guru.index') }}" method="GET" class="search-box">
                <span class="material-symbols-outlined">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP guru...">
            </form>

            @if(request('search'))
                <a href="{{ route('admin.guru.index') }}" class="role-filter">Reset</a>
            @endif

            <a href="{{ route('admin.guru.create') }}" class="add-user-btn">
                <span class="material-symbols-outlined">person_add</span>
                Tambah Guru
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <colgroup>
                    <col style="width: 50px;">
                    <col style="width: 22%;">
                    <col style="width: 33%;">
                    <col style="width: 25%;">
                    <col style="width: 15%;">
                </colgroup>

                <thead>
                    <tr>
                        <th class="no-column">NO</th>
                        <th>NIP</th>
                        <th>NAMA GURU</th>
                        <th>NO. HP</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($gurus as $guru)
                        <tr>
                            <td class="no-column">{{ $loop->iteration }}</td>
                            <td>
                                <span class="guru-nip">{{ $guru->nip ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="guru-name">{{ $guru->nama_guru }}</div>
                            </td>
                            <td>{{ $guru->no_hp ?? '-' }}</td>
                            <td>
                                <div class="guru-actions">
                                    <a href="{{ route('admin.guru.edit', $guru->id_guru) }}"
                                        class="icon-action edit"
                                        title="Edit guru">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>

                                    <button type="button"
                                        class="icon-action delete guru-delete-btn"
                                        title="Hapus guru"
                                        data-url="{{ route('admin.guru.destroy', $guru->id_guru) }}"
                                        data-name="{{ $guru->nama_guru }}"
                                        data-nip="{{ $guru->nip ?? '-' }}"
                                        data-hp="{{ $guru->no_hp ?? '-' }}">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">
                                @if(request('search'))
                                    Guru dengan pencarian "{{ request('search') }}" tidak ditemukan.
                                @else
                                    Belum ada data guru.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gurus instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="table-bottom">
                <div class="entries">
                    Menampilkan {{ $gurus->firstItem() ?? 0 }}–{{ $gurus->lastItem() ?? 0 }}
                    dari {{ $gurus->total() }} guru
                </div>

                <div class="pagination">
                    @if($gurus->onFirstPage())
                        <span class="page disabled">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $gurus->previousPageUrl() }}" class="page">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    @endif

                    @foreach($gurus->getUrlRange(1, $gurus->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                            class="page {{ $page == $gurus->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if($gurus->hasMorePages())
                        <a href="{{ $gurus->nextPageUrl() }}" class="page">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    @else
                        <span class="page disabled">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<div id="guruDeleteModal" class="delete-modal">
    <div class="delete-modal-box">
        <div class="delete-modal-header">
            <div class="delete-icon">
                <span class="material-symbols-outlined">delete</span>
            </div>

            <div>
                <h3>Hapus Guru?</h3>
                <p>Apakah kamu yakin ingin menghapus data guru ini?</p>
            </div>
        </div>

        <div class="delete-info">
            <div class="delete-name" id="guruDeleteName">-</div>

            <div class="delete-detail">
                <span>
                    <strong>NIP:</strong>
                    <span id="guruDeleteNip">-</span>
                </span>

                <span class="dot">•</span>

                <span>
                    <strong>No. HP:</strong>
                    <span id="guruDeleteHp">-</span>
                </span>
            </div>
        </div>

        <div class="delete-warning">
            <span class="material-symbols-outlined">warning</span>
            <span>
                Data guru yang dihapus tidak dapat dikembalikan.
                Pastikan kamu sudah yakin sebelum melanjutkan.
            </span>
        </div>

        <div class="delete-modal-footer">
            <button type="button" class="cancel-btn" id="cancelGuruDelete">
                Batal
            </button>

            <form id="guruDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="confirm-btn">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openGuruDeleteModal(url, name, nip, hp) {
        document.getElementById('guruDeleteForm').action = url;
        document.getElementById('guruDeleteName').textContent = name;
        document.getElementById('guruDeleteNip').textContent = nip;
        document.getElementById('guruDeleteHp').textContent = hp;
        document.getElementById('guruDeleteModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeGuruDeleteModal() {
        document.getElementById('guruDeleteModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Event listener tombol hapus
        document.querySelectorAll('.guru-delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                openGuruDeleteModal(
                    this.dataset.url,
                    this.dataset.name,
                    this.dataset.nip,
                    this.dataset.hp
                );
            });
        });

        // Event listener tombol batal
        document.getElementById('cancelGuruDelete').addEventListener('click', closeGuruDeleteModal);

        // Event listener klik di luar modal
        document.getElementById('guruDeleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeGuruDeleteModal();
        });

        // Event listener tekan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeGuruDeleteModal();
        });
    });
</script>
@endpush