@extends('layouts.piket')

@section('title', 'Jurnal Harian - Jurnify')
@section('page-title', 'Jurnal Harian')

@push('styles')
<style>
    .daily-page{max-width:1180px;margin:auto;color:#1e293b}
    .daily-head{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;margin-bottom:24px}
    .daily-heading h1{margin:0;font-size:24px;font-weight:800;color:#17265d}
    .daily-heading p{margin:6px 0 0;color:#64748b;font-size:13px}
    .daily-filter{display:flex;align-items:flex-end;gap:10px;padding:12px 14px;border:1px solid #e2e8f0;border-radius:14px;background:#fff}
    .daily-filter label{display:block;margin-bottom:5px;color:#475569;font-size:12px;font-weight:700}
    .daily-filter input{height:40px;padding:0 11px;border:1px solid #cbd5e1;border-radius:9px;font:inherit;box-sizing:border-box}
    .daily-button,.daily-link{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:0 14px;height:40px;border:0;border-radius:9px;background:#30366f;color:#fff;font:inherit;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;box-sizing:border-box}
    .daily-button:hover{background:#242a58}
    .daily-button:disabled{opacity:.45;cursor:not-allowed}
    .daily-link{height:36px;background:#eef2ff;color:#30366f;white-space:nowrap}
    .daily-link:hover{background:#e0e7ff}
    .daily-toolbar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin:22px 0 14px}
    .daily-label{margin:0;color:#334155;font-size:16px;font-weight:800}
    .daily-hint{margin:4px 0 0;color:#64748b;font-size:12px;line-height:1.5}
    .daily-table-wrap{overflow-x:auto;border:1px solid #e2e8f0;border-radius:13px;background:#fff;box-shadow:0 1px 2px #00000008}
    .daily-table{width:100%;border-collapse:collapse}
    .daily-table th,.daily-table td{padding:14px 16px;border-bottom:1px solid #edf0f5;text-align:left;font-size:13px;vertical-align:top}
    .daily-table th{background:#f8fafc;color:#64748b;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap}
    .daily-table th:first-child,.daily-table td:first-child{width:40px;text-align:center}
    .daily-table tr:last-child td{border-bottom:0}
    .daily-class{color:#1e293b;font-weight:800;white-space:nowrap}
    .daily-count{color:#17265d;font-weight:800;white-space:nowrap}
    .daily-muted{color:#64748b;font-weight:500}
    .daily-status{display:inline-flex;align-items:center;padding:4px 8px;border-radius:6px;background:#fff7ed;color:#9a3412;font-size:11px;font-weight:700;white-space:nowrap}
    .daily-status.done{background:#ecfdf5;color:#047857}
    .daily-status.neutral{background:#f1f5f9;color:#475569}
    .daily-check{width:18px;height:18px;accent-color:#30366f;cursor:pointer;vertical-align:middle}
    .select-all-control{display:flex;align-items:center;gap:9px;width:fit-content;margin:0 0 12px;padding:10px 14px;border:1px solid #e2e8f0;border-radius:9px;background:#fff;color:#334155;font-size:13px;font-weight:700}
    .select-all-control label{cursor:pointer}
    .schedule-list{display:grid;gap:8px;min-width:240px}
    .schedule-item{padding:10px 12px;border:1px solid #e2e8f0;border-radius:8px;background:#fff}
    .schedule-subject{color:#17265d;font-size:12px;font-weight:800}
    .schedule-time{margin-top:3px;color:#64748b;font-size:11px}
    .schedule-teacher{margin-top:4px;color:#475569;font-size:11px}
    .schedule-tags{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px}
    .daily-empty{padding:35px 20px;color:#64748b;text-align:center}
    .daily-notice{margin-bottom:14px;padding:12px 15px;border-radius:10px;font-size:13px;font-weight:600}
    @media(max-width:650px){
        .daily-head{align-items:stretch;flex-direction:column}
        .daily-filter{flex-direction:column;align-items:stretch}
        .daily-filter input,.daily-button{width:100%}
        .daily-toolbar{align-items:stretch;flex-direction:column}
        .daily-table th,.daily-table td{padding:12px 10px}
        .schedule-list{min-width:210px}
    }
</style>
@endpush

@section('content')
<div class="daily-page">
    <div class="daily-head">
        <div class="daily-heading">
            <h1>Jurnal Harian</h1>
            <p>Periksa jurnal dan jadwal mata pelajaran pada tanggal terpilih.</p>
        </div>

        <form class="daily-filter" method="GET" action="{{ route('piket.jurnal-harian.index') }}">
            <div>
                <label for="tanggal">Tanggal</label>
                <input id="tanggal" type="date" name="tanggal" value="{{ $tanggal }}" required>
            </div>
            <button class="daily-button" type="submit">
                <span class="material-symbols-outlined">calendar_month</span>
                Pilih tanggal
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="daily-notice" role="status" style="background:#ecfdf5;color:#047857">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="daily-notice" role="status" style="background:#eff6ff;color:#1d4ed8">
            {{ session('info') }}
        </div>
    @endif

    @if(session('error'))
        <div class="daily-notice" role="alert" style="background:#fef2f2;color:#b91c1c">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="daily-notice" role="alert" style="background:#fef2f2;color:#b91c1c">
            {{ $errors->first() }}
        </div>
    @endif

    @if($kelases->isEmpty())
        <div class="daily-table-wrap daily-empty">Belum ada data kelas.</div>
    @else
        <form id="approve-massal-form" method="POST" action="{{ route('piket.jurnal-harian.approve-massal') }}">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <div class="daily-toolbar">
                <div>
                    <h2 class="daily-label">Jadwal dan Jurnal per Kelas</h2>
                    <p class="daily-hint">Pilih kelas untuk approval massal. Periksa status setiap mata pelajaran sebelum menyetujui.</p>
                    <p class="daily-hint"><span id="selected-count">0</span> kelas dipilih.</p>
                </div>

                <button class="daily-button" id="approve-selected" type="submit" disabled>
                    <span class="material-symbols-outlined">task_alt</span>
                    Approve Kelas Terpilih
                </button>
            </div>

            {{-- Pilih Semua terpisah dari kolom kelas --}}
            <div class="select-all-control">
                <input class="daily-check" type="checkbox" id="select-all" aria-label="Pilih semua kelas">
                <label for="select-all">Pilih Semua</label>
            </div>

            <div class="daily-table-wrap">
                <table class="daily-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Kelas</th>
                            <th>Jurnal Terisi</th>
                            <th>Jadwal Mata Pelajaran</th>
                            <th>Status Approval Piket</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelases as $kelas)
                            @php
                                $jadwalHarian = $kelas->jadwal_harian ?? collect();
                                $jumlahSudahApprove = $jadwalHarian->filter(fn($jadwal) => $jadwal->piket_sudah_approve ?? false)->count();
                                $jumlahJadwal = $jadwalHarian->count();
                                $semuaSudahApprove = $jumlahJadwal > 0 && $jumlahSudahApprove === $jumlahJadwal;
                            @endphp

                            <tr>
                                <td>
                                    <input
                                        class="daily-check class-checkbox"
                                        type="checkbox"
                                        name="kelas_ids[]"
                                        value="{{ $kelas->id_kelas }}"
                                        aria-label="Pilih kelas {{ $kelas->nama_kelas }}"
                                    >
                                </td>

                                <td class="daily-class">{{ $kelas->nama_kelas }}</td>

                                <td>
                                    <span class="daily-count">{{ $kelas->jumlah_jurnal_harian }}</span>
                                    <span class="daily-muted">/ {{ $kelas->jumlah_jurnal_wajib_harian }} jurnal</span>
                                </td>

                                <td>
                                    @if($jadwalHarian->isEmpty())
                                        <span class="daily-status neutral">Tidak ada jadwal</span>
                                    @else
                                        <div class="schedule-list">
                                            @foreach($jadwalHarian as $jadwal)
                                                @php
                                                    $jurnal = $jadwal->jurnal_harian;
                                                    $statusJurnal = $jadwal->status_jurnal_harian ?? 'Belum Ada Jurnal';
                                                    $jamMulai = $jadwal->jamMulai?->jam_mulai;
                                                    $jamSelesai = $jadwal->jamSelesai?->jam_selesai;
                                                @endphp

                                                <div class="schedule-item">
                                                    <div class="schedule-subject">
                                                        {{ $jadwal->mapel?->nama_mapel ?? 'Mata pelajaran tidak tersedia' }}
                                                    </div>

                                                    <div class="schedule-time">
                                                        {{ $jamMulai ? \Illuminate\Support\Str::substr($jamMulai, 0, 5) : '--:--' }}
                                                        –
                                                        {{ $jamSelesai ? \Illuminate\Support\Str::substr($jamSelesai, 0, 5) : '--:--' }}
                                                    </div>

                                                    <div class="schedule-teacher">
                                                        Guru: {{ $jurnal?->guru?->nama_guru ?? $jadwal->guru?->nama_guru ?? 'Belum tercatat' }}
                                                    </div>

                                                    <div class="schedule-tags">
                                                        <span class="daily-status {{ $statusJurnal === 'Disetujui' ? 'done' : '' }} {{ $statusJurnal === 'Belum Ada Jurnal' ? 'neutral' : '' }}">
                                                            {{ $statusJurnal }}
                                                        </span>

                                                        @if($jurnal && $jurnal->piket_approved_at)
                                                            <span class="daily-status done">Sudah di-approve piket</span>
                                                        @elseif($jurnal)
                                                            <span class="daily-status neutral">Belum di-approve piket</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if($jumlahJadwal === 0)
                                        <span class="daily-status neutral">Tidak ada jadwal</span>
                                    @elseif($semuaSudahApprove)
                                        <span class="daily-status done">Sudah di-approve</span>
                                    @elseif($jumlahSudahApprove > 0)
                                        <span class="daily-status">{{ $jumlahSudahApprove }}/{{ $jumlahJadwal }} di-approve</span>
                                    @else
                                        <span class="daily-status">Belum di-approve</span>
                                    @endif
                                </td>

                                <td>
                                    <a class="daily-link" href="{{ route('piket.jurnal-harian.kelas', ['kelas' => $kelas->id_kelas, 'tanggal' => $tanggal]) }}">
                                        Lihat Jurnal
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    const checkboxes = Array.from(document.querySelectorAll('.class-checkbox'));
    const count = document.getElementById('selected-count');
    const button = document.getElementById('approve-selected');
    const form = document.getElementById('approve-massal-form');

    function updateSelection() {
        const selected = checkboxes.filter(item => item.checked).length;

        if (count) count.textContent = selected;
        if (button) button.disabled = selected === 0;

        if (selectAll) {
            selectAll.checked = checkboxes.length > 0 && selected === checkboxes.length;
            selectAll.indeterminate = selected > 0 && selected < checkboxes.length;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(item => item.checked = selectAll.checked);
            updateSelection();
        });
    }

    checkboxes.forEach(item => item.addEventListener('change', updateSelection));

    if (form) {
        form.addEventListener('submit', function (event) {
            const selected = checkboxes.filter(item => item.checked).length;

            if (selected === 0) {
                event.preventDefault();
                return;
            }

            if (!confirm('Lanjutkan approval untuk kelas yang dipilih pada tanggal ini?')) {
                event.preventDefault();
            }
        });
    }

    updateSelection();
});
</script>
@endsection