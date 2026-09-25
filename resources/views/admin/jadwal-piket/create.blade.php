@extends('layouts.admin')

@section('title', 'Tambah Jadwal Piket - Jurnify')
@section('page-title', 'Tambah Jadwal Piket')

@push('styles')
<style>
    .piket-form-page {
        max-width: 1000px;
        margin: 0 auto;
    }

    .form-header {
        margin-bottom: 24px;
    }

    .form-title {
        font-size: 25px;
        font-weight: 800;
        color: #1b234a;
        margin: 0;
    }

    .form-description {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .form-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-header {
        margin-bottom: 16px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 16px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .section-description {
        margin: 5px 0 0;
        font-size: 12px;
        color: #64748b;
    }

    .section-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f1f5f9;
    }

    .form-group {
        margin-bottom: 17px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 7px;
    }

    .form-control {
        width: 100%;
        height: 44px;
        border: 1px solid #dbe1e8;
        border-radius: 11px;
        padding: 0 13px;
        font-size: 13px;
        color: #334155;
        outline: none;
        background: white;
    }

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
    }

    .time-info {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #f8fafc;
        padding: 8px 11px;
        border-radius: 9px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 14px;
    }

    .multi-select {
        min-height: 125px;
        border: 1px solid #dbe1e8;
        border-radius: 11px;
        padding: 10px;
        background: white;
    }

    .multi-select select {
        width: 100%;
        height: 100px;
        border: none;
        outline: none;
        font-size: 13px;
        color: #334155;
    }

    .multi-help {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }

    .error-message {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
    }

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 13px 16px;
        border-radius: 11px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 10px;
    }

    .btn {
        height: 43px;
        padding: 0 17px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        cursor: pointer;
    }

    .btn-secondary {
        background: white;
        border: 1px solid #dbe1e8;
        color: #64748b;
    }

    .btn-submit {
        background: #1b234a;
        border: 1px solid #1b234a;
        color: white;
    }

    .btn-submit:hover {
        background: #30366f;
    }

    .two-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media(max-width: 700px) {
        .two-column {
            grid-template-columns: 1fr;
        }

        .form-card {
            padding: 18px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="piket-form-page">

    <div class="form-header">
        <h1 class="form-title">Tambah Jadwal Piket</h1>
        <p class="form-description">
            Atur seluruh petugas piket dalam satu tanggal.
        </p>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <strong>Jadwal belum dapat disimpan.</strong>

            <ul style="margin:7px 0 0 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.jadwal-piket.store') }}"
        method="POST"
    >
        @csrf

        <div class="form-card">

            <div class="form-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="section-icon">📅</span>
                        Tanggal Jadwal
                    </h2>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal</label>

                    <input
                        type="date"
                        name="tanggal"
                        value="{{ old('tanggal', $tanggal) }}"
                        class="form-control"
                        required
                    >
                </div>
            </div>

            <div class="form-section">

                <div class="section-header">
                    <h2 class="section-title">
                        <span class="section-icon">☀️</span>
                        Piket KBM Pagi
                    </h2>

                    <p class="section-description">
                        Atur jam pelaksanaan KBM pagi.
                    </p>
                </div>

                <div class="two-column">
                    <div class="form-group">
                        <label class="form-label" for="jam_mulai_pagi">Jam Mulai KBM Pagi</label>
                        <input
                            id="jam_mulai_pagi"
                            type="time"
                            name="jam_mulai_pagi"
                            value="{{ old('jam_mulai_pagi', $piketHours['jam_mulai_pagi']) }}"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="jam_selesai_pagi">Jam Selesai KBM Pagi</label>
                        <input
                            id="jam_selesai_pagi"
                            type="time"
                            name="jam_selesai_pagi"
                            value="{{ old('jam_selesai_pagi', $piketHours['jam_selesai_pagi']) }}"
                            class="form-control"
                            required
                        >
                    </div>
                </div>

                <div class="two-column">

                    <div class="form-group">
                        <label class="form-label">
                            Petugas Pagi
                        </label>

                        <div class="multi-select">
                            <select
                                name="pagi_petugas[]"
                                multiple
                            >
                                @foreach($gurus as $guru)
                                    <option
                                        value="{{ $guru->id_guru }}"
                                        @selected(
                                            in_array(
                                                $guru->id_guru,
                                                old('pagi_petugas', [])
                                            )
                                        )
                                    >
                                        {{ $guru->nama_guru }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="multi-help">
                            Tekan Ctrl / Command untuk memilih beberapa guru.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Koordinator Pagi
                        </label>

                        <select
                            name="pagi_koordinator"
                            class="form-control"
                        >
                            <option value="">-- Pilih Koordinator --</option>

                            @foreach($gurus as $guru)
                                <option
                                    value="{{ $guru->id_guru }}"
                                    @selected(
                                        old('pagi_koordinator') == $guru->id_guru
                                    )
                                >
                                    {{ $guru->nama_guru }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="form-section">

                <div class="section-header">
                    <h2 class="section-title">
                        <span class="section-icon">🌤️</span>
                        Piket KBM Siang
                    </h2>

                    <p class="section-description">
                        Atur jam pelaksanaan KBM siang.
                    </p>
                </div>

                <div class="two-column">
                    <div class="form-group">
                        <label class="form-label" for="jam_mulai_siang">Jam Mulai KBM Siang</label>
                        <input
                            id="jam_mulai_siang"
                            type="time"
                            name="jam_mulai_siang"
                            value="{{ old('jam_mulai_siang', $piketHours['jam_mulai_siang']) }}"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="jam_selesai_siang">Jam Selesai KBM Siang</label>
                        <input
                            id="jam_selesai_siang"
                            type="time"
                            name="jam_selesai_siang"
                            value="{{ old('jam_selesai_siang', $piketHours['jam_selesai_siang']) }}"
                            class="form-control"
                            required
                        >
                    </div>
                </div>

                <div class="two-column">

                    <div class="form-group">
                        <label class="form-label">
                            Petugas Siang
                        </label>

                        <div class="multi-select">
                            <select
                                name="siang_petugas[]"
                                multiple
                            >
                                @foreach($gurus as $guru)
                                    <option
                                        value="{{ $guru->id_guru }}"
                                        @selected(
                                            in_array(
                                                $guru->id_guru,
                                                old('siang_petugas', [])
                                            )
                                        )
                                    >
                                        {{ $guru->nama_guru }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="multi-help">
                            Tekan Ctrl / Command untuk memilih beberapa guru.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Koordinator Siang
                        </label>

                        <select
                            name="siang_koordinator"
                            class="form-control"
                        >
                            <option value="">-- Pilih Koordinator --</option>

                            @foreach($gurus as $guru)
                                <option
                                    value="{{ $guru->id_guru }}"
                                    @selected(
                                        old('siang_koordinator') == $guru->id_guru
                                    )
                                >
                                    {{ $guru->nama_guru }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="form-section">

                <div class="section-header">
                    <h2 class="section-title">
                        <span class="section-icon">🏫</span>
                        Piket Waka
                    </h2>

                    <p class="section-description">
                        Pilih satu petugas Waka untuk tanggal ini.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Petugas Waka
                    </label>

                    <select
                        name="waka"
                        class="form-control"
                    >
                        <option value="">-- Tidak ada / Pilih Waka --</option>

                        @foreach($gurus as $guru)
                            <option
                                value="{{ $guru->id_guru }}"
                                @selected(
                                    old('waka') == $guru->id_guru
                                )
                            >
                                {{ $guru->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="form-section">

                <div class="section-header">
                    <h2 class="section-title">
                        <span class="section-icon">📝</span>
                        Keterangan
                    </h2>
                </div>

                <textarea
                    name="keterangan"
                    class="form-control"
                    style="height:100px;padding-top:12px;resize:vertical;"
                    placeholder="Keterangan tambahan jika diperlukan..."
                >{{ old('keterangan') }}</textarea>

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('admin.jadwal-piket.index') }}"
                    class="btn btn-secondary"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-submit"
                >
                    <i class="fas fa-save"></i>
                    Simpan Jadwal
                </button>

            </div>

        </div>

    </form>

</div>
@endsection