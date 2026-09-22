@extends('layouts.admin')

@section('title', 'Jadwal Piket - Jurnify')
@section('page-title', 'Jadwal Piket')
@section('page-subtitle', 'Kelola penugasan jadwal piket guru')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="piket-page">

    {{-- HEADER --}}
    <div class="piket-header">
        <div class="header-text">
            <h1 class="header-title">Jadwal Piket</h1>
            <p class="header-subtitle">
                Kelola penugasan jadwal piket guru.
            </p>
        </div>

        <a href="{{ route('admin.jadwal-piket.create') }}" class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Tambah Jadwal</span>
        </a>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"/>
                <polyline points="22 4 12 14.01 9 11.01" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="table-card">

        <div class="table-header">
            <div>
                <h2>Daftar Jadwal Piket</h2>
                <p>Data penugasan guru yang telah dijadwalkan.</p>
            </div>

            <div class="total-badge">
                {{ $jadwals->count() }} Jadwal
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-tanggal">Tanggal</th>
                        <th class="col-guru">Guru</th>
                        <th class="col-shift">Shift</th>
                        <th class="col-jam">Jam</th>
                        <th class="col-tugas">Tugas</th>
                        <th class="col-posisi">Posisi</th>
                        <th class="col-keterangan">Keterangan</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jadwals as $jadwal)
                        <tr>

                            {{-- NO --}}
                            <td class="td-no">
                                {{ $loop->iteration }}
                            </td>

                            {{-- TANGGAL --}}
                            <td class="td-tanggal">
                                <div class="date-wrapper">
                                    <div class="date-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </div>
                                    <span>{{ $jadwal->tanggal?->format('d M Y') ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- GURU --}}
                            <td class="td-guru">
                                <div class="guru-wrapper">
                                    <div class="user-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21a8 8 0 0 0-16 0"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                    <span>{{ $jadwal->guru->nama_guru ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- SHIFT --}}
                            <td class="td-shift">
                                <span class="shift-badge">
                                    {{ $jadwal->shift }}
                                </span>
                            </td>

                            {{-- JAM --}}
                            <td class="td-jam">
                                <span class="time-text">
                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                </span>
                            </td>

                            {{-- TUGAS --}}
                            <td class="td-tugas">
                                <span class="task-text">
                                    {{ $jadwal->jenis_tugas }}
                                </span>
                            </td>

                            {{-- POSISI --}}
                            <td class="td-posisi">
                                <span class="position-badge">
                                    {{ $jadwal->posisi ?? '-' }}
                                </span>
                            </td>

                            {{-- KETERANGAN --}}
                            <td class="td-keterangan">
                                <span class="description-text">
                                    {{ $jadwal->keterangan ?? '-' }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="td-aksi">
                                <div class="action-buttons">

                                    <a href="{{ route('admin.jadwal-piket.edit', $jadwal) }}"
                                       class="btn-action edit"
                                       title="Edit Jadwal">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <form action="{{ route('admin.jadwal-piket.destroy', $jadwal) }}"
                                          method="POST"
                                          class="delete-form">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="btn-action delete"
                                                title="Hapus Jadwal"
                                                onclick='openDeleteModal(
                                                    @json(route("admin.jadwal-piket.destroy", $jadwal)),
                                                    @json($jadwal->tanggal?->format("d M Y") ?? "-")
                                                )'>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                <line x1="10" y1="11" x2="10" y2="17"/>
                                                <line x1="14" y1="11" x2="14" y2="17"/>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="9" class="empty-state">
                                <div class="empty-content">
                                    <div class="empty-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </div>

                                    <h3>Belum Ada Jadwal</h3>
                                    <p>Belum ada jadwal piket guru yang ditambahkan.</p>

                                    <a href="{{ route('admin.jadwal-piket.create') }}" class="empty-button">
                                        + Tambah Jadwal
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

{{-- DELETE MODAL --}}
<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">

        <div class="delete-modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                <line x1="10" y1="11" x2="10" y2="17"/>
                <line x1="14" y1="11" x2="14" y2="17"/>
            </svg>
        </div>

        <div class="delete-modal-text">
            <h3>Hapus Jadwal Piket?</h3>

            <p>
                Apakah Anda yakin ingin menghapus jadwal piket tanggal
                <strong id="deleteScheduleDate"></strong>?
            </p>

            <span>Data jadwal yang sudah dihapus tidak dapat dikembalikan.</span>
        </div>

        <div class="delete-modal-actions">
            <button type="button"
                    class="delete-modal-cancel"
                    onclick="closeDeleteModal()">
                Batal
            </button>

            <form id="deleteModalForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="delete-modal-confirm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        <line x1="10" y1="11" x2="10" y2="17"/>
                        <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>

    </div>
</div>

<style>
    .piket-page,
    .piket-page * {
        font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .piket-page {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 24px;
        animation: piketFadeUp 0.45s cubic-bezier(.16, 1, .3, 1) both;
    }

    /* HEADER */
    .piket-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .header-title {
        margin: 0;
        font-size: 24px;
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
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, #7886C7 0%, #2D336B 100%);
        color: #FFFFFF;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(45, 51, 107, 0.15);
        transition: all 0.25s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45, 51, 107, 0.25);
        color: #FFFFFF;
    }

    .btn-primary svg {
        width: 18px;
        height: 18px;
    }

    /* ALERT */
    .alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
    }

    .alert-success {
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        color: #166534;
    }

    .alert-success svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    /* TABLE CARD */
    .table-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        box-shadow: 0 4px 14px rgba(45, 51, 107, 0.03);
        overflow: hidden;
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 22px 24px;
        border-bottom: 1px solid #E2E8F0;
    }

    .table-header h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: #1E293B;
    }

    .table-header p {
        margin: 4px 0 0;
        font-size: 12.5px;
        color: #94A3B8;
        font-weight: 500;
    }

    .total-badge {
        padding: 7px 12px;
        border-radius: 10px;
        background: #F0F3FF;
        color: #2D336B;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* TABLE */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: auto;
        min-width: 1250px;
        border-collapse: collapse;
        text-align: left;
    }

    .data-table th {
        padding: 15px 18px;
        background: #F8FAFC;
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        border-bottom: 1px solid #E2E8F0;
        white-space: nowrap;
    }

    .data-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 13.5px;
        color: #1E293B;
        vertical-align: middle;
        white-space: nowrap;
    }

    .data-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .data-table tbody tr:hover {
        background: #F8FAFC;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* COLUMNS */
    .col-no,
    .td-no {
        width: 55px;
        min-width: 55px;
        text-align: center;
        color: #64748B;
        font-weight: 600;
    }

    .col-tanggal,
    .td-tanggal {
        min-width: 155px;
    }

    .col-guru,
    .td-guru {
        min-width: 210px;
    }

    .col-shift,
    .td-shift {
        min-width: 100px;
    }

    .col-jam,
    .td-jam {
        min-width: 155px;
    }

    .col-tugas,
    .td-tugas {
        min-width: 150px;
    }

    .col-posisi,
    .td-posisi {
        min-width: 130px;
    }

    .col-keterangan,
    .td-keterangan {
        min-width: 200px;
        max-width: 260px;
    }

    .col-aksi,
    .td-aksi {
        width: 1%;
        min-width: max-content;
        white-space: nowrap;
        text-align: left;
        padding-left: 12px !important;
    }

    /* DATE */
    .date-wrapper,
    .guru-wrapper {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .date-icon,
    .user-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #F0F3FF;
        color: #7886C7;
        flex-shrink: 0;
    }

    .date-icon svg,
    .user-icon svg {
        width: 17px;
        height: 17px;
    }

    .date-wrapper > span,
    .guru-wrapper > span {
        font-weight: 700;
        color: #1E293B;
    }

    /* BADGES */
    .shift-badge,
    .position-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 11px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
    }

    .shift-badge {
        background: #EEF2FF;
        color: #4F46E5;
    }

    .position-badge {
        background: #F0FDF4;
        color: #15803D;
    }

    /* TEXT */
    .time-text {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .task-text {
        font-weight: 700;
        color: #334155;
    }

    .description-text {
        display: block;
        max-width: 240px;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #64748B;
        font-weight: 500;
    }

    /* ACTION */
    .action-buttons {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        border: none;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-action svg {
        width: 15px;
        height: 15px;
    }

    .btn-action.edit {
        background: #EEF2FF;
        color: #4F46E5;
    }

    .btn-action.edit:hover {
        background: #E0E7FF;
        color: #3730A3;
        transform: translateY(-1px);
    }

    .btn-action.delete {
        background: #FEE2E2;
        color: #DC2626;
    }

    .btn-action.delete:hover {
        background: #FCA5A5;
        color: #991B1B;
        transform: translateY(-1px);
    }

    /* EMPTY */
    .empty-state {
        padding: 56px 24px !important;
        text-align: center;
    }

    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: #94A3B8;
    }

    .empty-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 4px;
        border-radius: 16px;
        background: #F0F3FF;
        color: #7886C7;
    }

    .empty-icon svg {
        width: 28px;
        height: 28px;
    }

    .empty-content h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: #334155;
    }

    .empty-content p {
        margin: 0;
        font-size: 13px;
        color: #94A3B8;
        font-weight: 500;
    }

    .empty-button {
        margin-top: 8px;
        padding: 9px 15px;
        border-radius: 10px;
        background: #2D336B;
        color: #FFFFFF;
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .empty-button:hover {
        background: #1E234D;
        color: #FFFFFF;
        transform: translateY(-1px);
    }

    /* DELETE MODAL */
    .delete-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(3px);
    }

    .delete-modal.show {
        display: flex;
        animation: modalFadeIn 0.2s ease both;
    }

    .delete-modal-content {
        width: 100%;
        max-width: 430px;
        padding: 28px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        text-align: center;
        animation: modalScaleIn 0.25s cubic-bezier(.16, 1, .3, 1) both;
    }

    .delete-modal-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FEF2F2;
        color: #DC2626;
        border-radius: 16px;
    }

    .delete-modal-icon svg {
        width: 27px;
        height: 27px;
    }

    .delete-modal-text h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #1E293B;
    }

    .delete-modal-text p {
        margin: 10px 0 4px;
        font-size: 14px;
        line-height: 1.6;
        color: #64748B;
    }

    .delete-modal-text p strong {
        color: #334155;
        font-weight: 700;
    }

    .delete-modal-text span {
        display: block;
        font-size: 12.5px;
        color: #94A3B8;
    }

    .delete-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 26px;
    }

    .delete-modal-cancel,
    .delete-modal-confirm {
        height: 42px;
        padding: 0 18px;
        border-radius: 10px;
        font-family: inherit;
        font-size: 13.5px;
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
        gap: 7px;
        background: #DC2626;
        color: #FFFFFF;
        border: none;
    }

    .delete-modal-confirm:hover {
        background: #B91C1C;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    .delete-modal-confirm svg {
        width: 15px;
        height: 15px;
    }

    /* ANIMATION */
    @keyframes piketFadeUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes modalScaleIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(5px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* RESPONSIVE */
    @media (max-width: 767px) {
        .piket-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-primary {
            width: 100%;
        }

        .table-header {
            align-items: flex-start;
        }

        .data-table {
            min-width: 1250px;
        }
    }

    @media (max-width: 480px) {
        .delete-modal {
            padding: 16px;
        }

        .delete-modal-content {
            padding: 24px 20px;
        }

        .delete-modal-actions {
            flex-direction: column-reverse;
        }

        .delete-modal-cancel,
        .delete-modal-confirm {
            width: 100%;
        }
    }
</style>

<script>
    function openDeleteModal(url, date) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteModalForm');
        const dateElement = document.getElementById('deleteScheduleDate');

        form.action = url;
        dateElement.textContent = date;

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