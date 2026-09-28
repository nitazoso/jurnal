@extends('layouts.sekretaris')

@section('title', 'Riwayat Jurnal Tervalidasi')
@section('page-title', 'Riwayat Jurnal')
@section('page-subtitle', 'Arsip jurnal yang telah divalidasi sekretaris')

@section('content')
@php
    $totalRiwayat = $riwayatPerHari->sum(fn ($jurnalsHari) => $jurnalsHari->count());
@endphp

<div class="history-page">
    <div class="history-heading">
        <div>
            <h2>Jurnal Tervalidasi</h2>
            <p>Jurnal tersimpan sebagai arsip dan dikelompokkan berdasarkan tanggal.</p>
        </div>
        <span class="history-count">{{ $totalRiwayat }} jurnal</span>
    </div>

    @if($riwayatPerHari->isEmpty())
        <div class="history-empty">
            <span class="material-symbols-outlined" aria-hidden="true">event_note</span>
            <h3>Belum ada riwayat validasi</h3>
            <p>Jurnal yang telah divalidasi sekretaris akan tersimpan di sini.</p>
        </div>
    @else
        @foreach($riwayatPerHari as $tanggal => $jurnalsHari)
            <section class="history-day">
                <div class="history-day-heading">
                    <div>
                        <span class="history-day-label">Riwayat Tanggal</span>
                        <h3>{{ \Illuminate\Support\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}</h3>
                    </div>
                    <span class="history-count">{{ $jurnalsHari->count() }} jurnal</span>
                </div>

                <div class="history-table-wrap">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Guru</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Jam</th>
                                <th>Materi</th>
                                <th>Validator</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jurnalsHari as $jurnal)
                                <tr>
                                    <td data-label="Guru">{{ $jurnal->guru?->nama_guru ?? '-' }}</td>
                                    <td data-label="Kelas">{{ $jurnal->kelas?->nama_kelas ?? '-' }}</td>
                                    <td data-label="Mata Pelajaran">{{ $jurnal->jadwal?->mapel?->nama_mapel ?? '-' }}</td>
                                    <td data-label="Jam">{{ $jurnal->jamMulai?->jam_ke ?? '-' }} - {{ $jurnal->jamSelesai?->jam_ke ?? '-' }}</td>
                                    <td data-label="Materi">{{ $jurnal->materi ?: '-' }}</td>
                                    <td data-label="Validator">{{ $jurnal->validator?->nama_user ?? '-' }}</td>
                                    <td class="history-action">
                                        <a href="{{ route('sekretaris.validasi-jurnal.show', $jurnal) }}" aria-label="Lihat jurnal {{ $jurnal->guru?->nama_guru ?? '' }}">
                                            Detail
                                            <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach
    @endif
</div>

<style>
    .history-page {
        color: #1e293b;
    }

    .history-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .history-heading h2 {
        font-size: 20px;
        font-weight: 800;
    }

    .history-heading p {
        margin-top: 4px;
        color: #73809a;
        font-size: 13px;
    }

    .history-count {
        padding: 7px 11px;
        border: 1px solid #dbe3f1;
        border-radius: 8px;
        background: #fff;
        color: #2d336b;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .history-day {
        margin-top: 24px;
    }

    .history-day-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 0 0 10px;
        border-bottom: 2px solid #dfe5f3;
        margin-bottom: 12px;
    }

    .history-day-label {
        color: #73809a;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .history-day-heading h3 {
        margin-top: 3px;
        color: #26316c;
        font-size: 15px;
        font-weight: 800;
    }

    .history-table-wrap {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .history-table th,
    .history-table td {
        padding: 13px 15px;
        border-bottom: 1px solid #edf0f5;
        font-size: 12px;
        vertical-align: middle;
    }

    .history-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .history-table td:first-child {
        color: #172554;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pill {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 999px;
        background: #fff4d6;
        color: #8a4b08;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-pill.is-approved {
        background: #dcfce7;
        color: #166534;
    }

    .history-action a {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        color: #3543a5;
        font-weight: 700;
        white-space: nowrap;
    }

    .history-action .material-symbols-outlined {
        font-size: 18px;
    }

    .history-empty {
        display: grid;
        justify-items: center;
        padding: 56px 20px;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: #fff;
        text-align: center;
    }

    .history-empty .material-symbols-outlined {
        margin-bottom: 10px;
        color: #7886c7;
        font-size: 34px;
    }

    .history-empty h3 {
        color: #1e293b;
        font-size: 15px;
    }

    .history-empty p {
        margin-top: 5px;
        color: #73809a;
        font-size: 12px;
    }

    @media (max-width: 760px) {
        .history-table thead {
            display: none;
        }

        .history-table,
        .history-table tbody,
        .history-table tr,
        .history-table td {
            display: block;
            width: 100%;
        }

        .history-table tr {
            padding: 8px 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .history-table td {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding: 7px 0;
            border: 0;
            text-align: right;
            white-space: normal;
        }

        .history-table td::before {
            content: attr(data-label);
            color: #73809a;
            font-size: 10px;
            font-weight: 700;
            text-align: left;
        }

        .history-table .history-action {
            justify-content: flex-end;
        }

        .history-table .history-action::before {
            content: none;
        }
    }
</style>
@endsection