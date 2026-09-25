@extends('layouts.admin')

@section('title', 'Manajemen User - Jurnify')
@section('page-title', 'Manajemen User')

@section('content')

<style>
    .user-page {
        width: 100%;
        max-width: 100%;
    }

    .user-page .stats {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        margin: 0 0 23px;
    }

    .user-page .stat-card {
        min-width: 0;
        min-height: 113px;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 9px;
        padding: 22px 23px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, .025);
        position: relative;
        transition: transform .2s ease, box-shadow .2s ease;
        animation: fadeUp .45s ease both;
    }

    .user-page .stat-card:nth-child(1) { animation-delay: .05s; }
    .user-page .stat-card:nth-child(2) { animation-delay: .1s; }
    .user-page .stat-card:nth-child(3) { animation-delay: .15s; }
    .user-page .stat-card:nth-child(4) { animation-delay: .2s; }

    .user-page .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(0, 0, 0, .055);
    }

    .user-page .stat-title {
        color: #41434c;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .user-page .stat-value {
        display: flex;
        align-items: baseline;
        gap: 8px;
    }

    .user-page .stat-value strong {
        color: #1d2c67;
        font-size: 31px;
        font-weight: 800;
        line-height: 1;
    }

    .user-page .stat-icon {
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
        transition: transform .2s ease, background .2s ease;
    }

    .user-page .stat-card:hover .stat-icon {
        transform: scale(1.05);
        background: #d5defc;
    }

    .user-page .stat-icon .material-symbols-outlined {
        font-size: 22px;
    }

    .user-page .success-message {
        margin-bottom: 20px;
        padding: 12px 16px;
        border-radius: 8px;
        background: #d5f7e8;
        color: #087451;
        font-size: 13px;
        font-weight: 600;
        animation: fadeDown .35s ease both;
    }

    .user-page .error-message {
        margin-bottom: 20px;
        padding: 12px 16px;
        border: 1px solid #f3b4b4;
        border-radius: 8px;
        background: #fff0f0;
        color: #a12626;
        font-size: 13px;
        font-weight: 700;
        animation: fadeDown .35s ease both;
    }

    .user-page .activity-card {
        width: 100%;
        background: #fff;
        border-radius: 9px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .025);
        animation: fadeUp .5s ease .15s both;
    }

    .user-page .activity-header {
        padding: 25px 24px 24px;
    }

    .user-page .activity-title {
        color: #1d2c67;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 3px;
    }

    .user-page .activity-description {
        color: #4b4d56;
        font-size: 14px;
    }

    .user-page .filters {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 24px 16px;
        width: 100%;
    }

    .user-page .search-box {
        width: 330px;
        height: 40px;
        flex-shrink: 1;
        background: #f1f2f5;
        border: 1px solid transparent;
        border-radius: 4px;
        color: #24252b;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        display: flex;
        align-items: center;
        padding: 0 12px;
        gap: 9px;
        transition: background .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .user-page .search-box:focus-within {
        background: #fff;
        border-color: #cfd4e5;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, .1);
    }

    .user-page .search-box .material-symbols-outlined {
        color: #777b86;
        font-size: 21px;
        flex-shrink: 0;
        transition: color .2s ease;
    }

    .user-page .search-box:focus-within .material-symbols-outlined {
        color: #30366f;
    }

    .user-page .search-box input {
        width: 100%;
        min-width: 0;
        border: none;
        outline: none;
        background: transparent;
        font-family: inherit;
        font-size: 13px;
        color: #333;
    }

    .user-page .search-box input::placeholder {
        color: #858891;
    }

    .user-page .role-filters {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
        flex-shrink: 0;
    }

    .user-page .role-filter,
    .user-page .add-user-btn {
        height: 40px;
        border: 1px solid #cfd2dc;
        border-radius: 10px;
        background: #fff;
        color: #30323a;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        font-weight: 700;
        padding: 0 20px;
        cursor: pointer;
        white-space: nowrap;
        transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease, box-shadow .2s ease;
    }

    .user-page .role-filter:hover {
        background: #f7f8fb;
        border-color: #b9becb;
        transform: translateY(-1px);
    }

    .user-page .role-filter.active {
        background: #182864;
        border-color: #182864;
        color: #fff;
    }

    .user-page .role-filter.active:hover {
        background: #202f72;
        border-color: #202f72;
    }

    .user-page .add-user-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        background: #2d336b;
        border-color: #2d336b;
        color: #fff;
        padding: 0 17px;
        text-decoration: none;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(45, 51, 107, .1);
    }

    .user-page .add-user-btn:hover {
        background: #252c61;
        border-color: #252c61;
        transform: translateY(-1px);
        box-shadow: 0 4px 9px rgba(45, 51, 107, .16);
    }

    .user-page .add-user-btn:active {
        transform: translateY(0);
    }

    .user-page .add-user-btn .material-symbols-outlined {
        font-size: 18px;
        transition: transform .2s ease;
    }

    .user-page .add-user-btn:hover .material-symbols-outlined {
        transform: scale(1.08);
    }

    .user-page .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .user-page table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .user-page thead {
        background: #f1f2f5;
    }

    .user-page th {
        height: 60px;
        padding: 0 12px;
        text-align: left;
        color: #484a53;
        font-size: 13px;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: .3px;
    }

    .user-page td {
        height: 94px;
        padding: 9px 12px;
        color: #17181d;
        font-size: 14px;
        vertical-align: middle;
    }

    .user-page tbody tr {
        border-bottom: 1px solid #f4f4f4;
        transition: background .2s ease;
    }

    .user-page tbody tr:hover {
        background: #fafbff;
    }

    .user-page tbody tr:last-child {
        border-bottom: none;
    }

    .user-page th:nth-child(1),
    .user-page td:nth-child(1) {
        width: 33%;
        padding-left: 36px;
    }

    .user-page th:nth-child(2),
    .user-page td:nth-child(2) {
        width: 27%;
    }

    .user-page th:nth-child(3),
    .user-page td:nth-child(3) {
        width: 25%;
    }

    .user-page th:nth-child(4),
    .user-page td:nth-child(4) {
        width: 15%;
        padding-right: 36px;
    }

    .user-page .user-cell strong {
        display: block;
        color: #202126;
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 3px;
        transition: color .2s ease;
    }

    .user-page tbody tr:hover .user-cell strong {
        color: #182864;
    }

    .user-page .user-id {
        color: #4d5059;
        font-family: monospace;
        font-size: 13px;
        word-break: break-word;
    }

    .user-page .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #a9b5df;
        color: #344477;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .user-page tbody tr:hover .role-badge {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(52, 68, 119, .1);
    }

    .user-page .role-badge .material-symbols-outlined {
        font-size: 14px;
    }

    .user-page .user-actions {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .user-page .icon-action {
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
        transition: transform .2s ease, opacity .2s ease;
    }

    .user-page .icon-action .material-symbols-outlined {
        font-size: 19px;
        transition: transform .2s ease;
    }

    .user-page .icon-action.edit {
        color: #182864;
    }

    .user-page .icon-action.delete {
        color: #e00000;
    }

    .user-page .icon-action:hover {
        transform: translateY(-1px);
    }

    .user-page .icon-action:hover .material-symbols-outlined {
        transform: scale(1.08);
    }

    .user-page .icon-action:active {
        transform: translateY(0);
    }

    .user-page .bottom {
        min-height: 70px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
    }

    .user-page .entries {
        color: #3f4148;
        font-size: 13px;
    }

    .user-page .pagination {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .user-page .page {
        min-width: 36px;
        height: 36px;
        border: none;
        border-radius: 4px;
        background: #f0f1f4;
        color: #25272d;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background .2s ease, color .2s ease, transform .2s ease;
    }

    .user-page .page:hover:not(.disabled):not(.active) {
        background: #dce4ff;
        color: #182864;
        transform: translateY(-1px);
    }

    .user-page .page.active {
        background: #182864;
        color: #fff;
        font-weight: 700;
    }

    .user-page .page.disabled {
        opacity: .45;
        pointer-events: none;
    }

    .user-page .page .material-symbols-outlined {
        font-size: 18px;
    }

    .user-page .dots {
        min-width: 24px;
        text-align: center;
        color: #555861;
    }

    .user-page .empty-row {
        text-align: center;
        padding: 40px !important;
        color: #777b86;
    }

    .user-page .delete-modal {
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
        transition: opacity .2s ease, visibility .2s ease;
    }

    .user-page .delete-modal.show {
        visibility: visible;
        opacity: 1;
    }

    .user-page .delete-modal-box {
        width: min(520px, 100%);
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, .22);
        transform: translateY(8px) scale(.98);
        transition: transform .25s ease;
    }

    .user-page .delete-modal.show .delete-modal-box {
        transform: translateY(0) scale(1);
    }

    .user-page .delete-modal-header {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        padding: 28px 30px 20px;
    }

    .user-page .delete-icon {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #ffe1e1;
        color: #e5242a;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-page .delete-icon .material-symbols-outlined {
        font-size: 31px;
    }

    .user-page .delete-modal-title {
        padding-top: 2px;
    }

    .user-page .delete-modal-title h3 {
        margin: 0 0 6px;
        color: #1d2c67;
        font-size: 23px;
        font-weight: 800;
        line-height: 1.2;
    }

    .user-page .delete-modal-title p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.5;
    }

    .user-page .delete-info {
        margin: 4px 30px 20px;
        padding: 17px 20px;
        background: #f8fafc;
        border: 1px solid #dfe6ef;
        border-radius: 12px;
    }

    .user-page .delete-info-name {
        display: block;
        margin-bottom: 7px;
        color: #1d2c67;
        font-size: 17px;
        font-weight: 800;
    }

    .user-page .delete-info-detail {
        display: flex;
        align-items: center;
        gap: 11px;
        color: #64748b;
        font-size: 13px;
        flex-wrap: wrap;
    }

    .user-page .delete-info-detail strong {
        color: #526078;
    }

    .user-page .delete-info-detail .dot {
        color: #94a3b8;
    }

    .user-page .delete-warning {
        display: flex;
        align-items: center;
        margin: 0 30px 24px;
        padding: 13px 16px;
        background: #fff9e8;
        border-left: 4px solid #f59e0b;
        color: #a14c08;
        font-size: 12px;
        line-height: 1.5;
    }

    .user-page .delete-warning .material-symbols-outlined {
        margin-right: 8px;
        color: #d97706;
        font-size: 19px;
        flex-shrink: 0;
    }

    .user-page .delete-modal-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 20px 30px;
        background: #f8fafc;
        border-top: 1px solid #e8ebf0;
    }

    .user-page .delete-modal-footer form {
        margin: 0;
    }

    .user-page .btn-delete-cancel,
    .user-page .btn-delete-confirm {
        height: 42px;
        padding: 0 20px;
        border-radius: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
    }

    .user-page .btn-delete-cancel {
        min-width: 100px;
        background: #fff;
        border: 1px solid #cbd5e1;
        color: #343945;
    }

    .user-page .btn-delete-cancel:hover {
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .user-page .btn-delete-confirm {
        min-width: 120px;
        border: none;
        background: #e5242a;
        color: #fff;
        box-shadow: 0 4px 10px rgba(229, 36, 42, .16);
    }

    .user-page .btn-delete-confirm:hover {
        background: #c91c22;
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(229, 36, 42, .2);
    }

    .user-page .btn-delete-confirm:active,
    .user-page .btn-delete-cancel:active {
        transform: translateY(0);
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(7px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeDown {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 1100px) {
        .user-page .stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .user-page .filters {
            flex-wrap: wrap;
        }

        .user-page .search-box {
            width: 100%;
            flex: 1 1 300px;
        }

        .user-page .role-filters {
            margin-left: 0;
        }
    }

    @media (max-width: 800px) {
        .user-page .stats {
            grid-template-columns: 1fr;
        }

        .user-page .filters {
            flex-direction: column;
            align-items: stretch;
        }

        .user-page .search-box {
            width: 100%;
            flex: none;
        }

        .user-page .role-filters {
            margin-left: 0;
        }

        .user-page .add-user-btn {
            width: fit-content;
        }

        .user-page table {
            min-width: 700px;
        }
    }

    @media (max-width: 600px) {
        .user-page .bottom {
            flex-direction: column;
            gap: 15px;
            padding: 18px;
        }

        .user-page .delete-modal-box {
            width: 100%;
            border-radius: 15px;
        }

        .user-page .delete-modal-header {
            gap: 14px;
            padding: 22px 20px 17px;
        }

        .user-page .delete-icon {
            width: 52px;
            height: 52px;
        }

        .user-page .delete-icon .material-symbols-outlined {
            font-size: 27px;
        }

        .user-page .delete-modal-title h3 {
            font-size: 19px;
        }

        .user-page .delete-modal-title p {
            font-size: 12px;
        }

        .user-page .delete-info {
            margin: 4px 20px 17px;
            padding: 14px 16px;
        }

        .user-page .delete-info-name {
            font-size: 15px;
        }

        .user-page .delete-info-detail {
            font-size: 12px;
        }

        .user-page .delete-warning {
            margin: 0 20px 20px;
            padding: 11px 13px;
            font-size: 11px;
        }

        .user-page .delete-modal-footer {
            padding: 16px 20px;
        }

        .user-page .btn-delete-cancel,
        .user-page .btn-delete-confirm {
            height: 40px;
            font-size: 12px;
        }
    }
</style>

<div class="user-page">

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error-message" role="alert">{{ session('error') }}</div>
    @endif

    <section class="stats">
        <div class="stat-card">
            <div class="stat-title">TOTAL USERS</div>
            <div class="stat-value">
                <strong>{{ $totalUser }}</strong>
            </div>
            <div class="stat-icon">
                <span class="material-symbols-outlined">groups</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">GURU AKTIF</div>
            <div class="stat-value">
                <strong>{{ $totalGuru }}</strong>
            </div>
            <div class="stat-icon">
                <span class="material-symbols-outlined">school</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">TOTAL SEKRE</div>
            <div class="stat-value">
                <strong>{{ $totalSekretaris }}</strong>
            </div>
            <div class="stat-icon">
                <span class="material-symbols-outlined">edit_note</span>
            </div>
        </div>
    </section>

    <section class="activity-card">
        <div class="activity-header">
            <h3 class="activity-title">Daftar User</h3>
            <p class="activity-description">
                Kelola dan pantau seluruh pengguna yang terdaftar dalam sistem Jurnify.
            </p>
        </div>

        <form action="{{ route('admin.user.index') }}" method="GET" class="filters">
            <div class="search-box">
                <span class="material-symbols-outlined">search</span>

                <input
                    type="text"
                    name="search"
                    placeholder="Cari nama atau username..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="role-filters">
                <button
                    type="submit"
                    name="role"
                    value=""
                    class="role-filter {{ !request('role') ? 'active' : '' }}"
                >
                    Semua
                </button>

                <button
                    type="submit"
                    name="role"
                    value="Guru"
                    class="role-filter {{ request('role') === 'Guru' ? 'active' : '' }}"
                >
                    Guru
                </button>

                <button
                    type="submit"
                    name="role"
                    value="Staff Piket"
                    class="role-filter {{ request('role') === 'Staff Piket' ? 'active' : '' }}"
                >
                    Staff Piket
                </button>

                <button
                    type="submit"
                    name="role"
                    value="Sekretaris"
                    class="role-filter {{ request('role') === 'Sekretaris' ? 'active' : '' }}"
                >
                    Sekre
                </button>
            </div>

            <a href="{{ route('admin.user.create') }}" class="add-user-btn">
                <span class="material-symbols-outlined">person_add</span>
                Tambah User
            </a>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>USER</th>
                        <th>USERNAME</th>
                        <th>ROLE</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="user-cell">
                                <strong>{{ $user->nama_user }}</strong>
                            </td>

                            <td class="user-id">
                                {{ $user->username }}
                            </td>

                            <td>
                                <span class="role-badge">
                                    @if($user->role === 'Guru')
                                        <span class="material-symbols-outlined">school</span>
                                    @elseif($user->role === 'Staff Piket')
                                        <span class="material-symbols-outlined">support_agent</span>
                                    @elseif($user->role === 'Sekretaris')
                                        <span class="material-symbols-outlined">edit_note</span>
                                    @elseif($user->role === 'Admin')
                                        <span class="material-symbols-outlined">admin_panel_settings</span>
                                    @else
                                        <span class="material-symbols-outlined">person</span>
                                    @endif

                                    {{ $user->role }}
                                </span>
                            </td>

                            <td class="user-actions">
                                <a
                                    href="{{ route('admin.user.edit', $user->id_user) }}"
                                    class="icon-action edit"
                                    title="Edit user"
                                >
                                    <span class="material-symbols-outlined">edit</span>
                                </a>

                                <button
                                    type="button"
                                    class="icon-action delete user-delete-btn"
                                    title="Hapus user"
                                    data-url="{{ route('admin.user.destroy', $user->id_user) }}"
                                    data-name="{{ $user->nama_user }}"
                                    data-username="{{ $user->username }}"
                                    data-role="{{ $user->role }}"
                                    data-admin-count="{{ $totalAdmin }}"
                                >
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-row">
                                Belum ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bottom">
            <div class="entries">
                Showing {{ $users->firstItem() ?? 0 }}
                to {{ $users->lastItem() ?? 0 }}
                of {{ $users->total() }} entries
            </div>

            @if($users->hasPages())
                <div class="pagination">
                    @if($users->onFirstPage())
                        <span class="page disabled">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="page">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    @endif

                    @php
                        $current = $users->currentPage();
                        $last = $users->lastPage();
                    @endphp

                    @for($page = 1; $page <= $last; $page++)
                        @if($page === 1 || $page === $last || abs($page - $current) <= 1)
                            @if($page === $current)
                                <span class="page active">{{ $page }}</span>
                            @else
                                <a href="{{ $users->url($page) }}" class="page">
                                    {{ $page }}
                                </a>
                            @endif
                        @elseif($page === 2 && $current > 3)
                            <span class="dots">...</span>
                        @elseif($page === $last - 1 && $current < $last - 2)
                            <span class="dots">...</span>
                        @endif
                    @endfor

                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="page">
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
    </section>

    <div id="deleteModal" class="delete-modal">
        <div class="delete-modal-box">

            <div class="delete-modal-header">
                <div class="delete-icon">
                    <span class="material-symbols-outlined">warning</span>
                </div>

                <div class="delete-modal-title">
                    <h3 id="deleteTitle">Hapus User?</h3>
                    <p id="deleteDescription">
                        Apakah Anda yakin ingin menghapus user ini?<br>
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <div class="delete-info">
                <span id="deleteName" class="delete-info-name">
                    Nama User
                </span>

                <div class="delete-info-detail">
                    <span>
                        Username:
                        <strong id="deleteUsername">username</strong>
                    </span>

                    <span class="dot">•</span>

                    <span>
                        Role:
                        <strong id="deleteRole">Guru</strong>
                    </span>
                </div>
            </div>

            <div class="delete-warning">
                <span class="material-symbols-outlined">info</span>

                <span>
                    <span id="deleteWarningText">User yang dihapus tidak dapat dikembalikan dan seluruh akses akun tersebut akan dinonaktifkan.</span>
                </span>
            </div>

            <div class="delete-modal-footer">
                <button
                    type="button"
                    class="btn-delete-cancel"
                    onclick="closeDeleteModal()"
                >
                    Batal
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <button id="deleteConfirmButton" type="submit" class="btn-delete-confirm">
                        Hapus User
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>

<script>
    function openDeleteModal(url, name, username, role, adminCount) {
        const isLastAdmin = role === 'Admin' && Number(adminCount) <= 1;
        const deleteForm = document.getElementById('deleteForm');
        const deleteInfo = document.querySelector('.delete-info');
        const deleteTitle = document.getElementById('deleteTitle');
        const deleteDescription = document.getElementById('deleteDescription');
        const deleteWarningText = document.getElementById('deleteWarningText');
        const deleteConfirmButton = document.getElementById('deleteConfirmButton');

        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteName').textContent = name;
        document.getElementById('deleteUsername').textContent = username;
        document.getElementById('deleteRole').textContent = role;
        deleteInfo.hidden = isLastAdmin;
        deleteConfirmButton.hidden = isLastAdmin;
        deleteTitle.textContent = isLastAdmin ? 'Akun Admin Tidak Bisa Dihapus' : 'Hapus User?';
        deleteDescription.innerHTML = isLastAdmin
            ? 'Admin terakhir harus tetap tersedia agar sistem dapat digunakan.'
            : 'Apakah Anda yakin ingin menghapus user ini?<br>Tindakan ini tidak dapat dibatalkan.';
        deleteWarningText.textContent = isLastAdmin
            ? 'Buat akun Admin lain terlebih dahulu jika ingin menghapus akun ini.'
            : 'User yang dihapus tidak dapat dikembalikan dan seluruh akses akun tersebut akan dinonaktifkan.';
        document.getElementById('deleteModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.user-delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            openDeleteModal(
                this.dataset.url,
                this.dataset.name,
                this.dataset.username,
                this.dataset.role,
                this.dataset.adminCount
            );
        });
    });

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