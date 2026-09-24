@extends('layouts.admin')

@section('title', 'Edit Jadwal Piket - Jurnify')
@section('page-title', 'Edit Jadwal Piket')

@push('styles')
<style>
    .edit-page {
        max-width: 1000px;
        margin: auto;
    }

    .header {
        margin-bottom: 22px;
    }

    .header h1 {
        margin: 0;
        color: #1b234a;
        font-size: 25px;
        font-weight: 800;
    }

    .header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 18px rgba(15,23,42,.04);
    }

    .section {
        padding-bottom: 25px;
        margin-bottom: 25px;
        border-bottom: 1px solid #eef2f7;
    }

    .section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .section h2 {
        margin: 0 0 5px;
        color: #1e293b;
        font-size: 16px;
        font-weight: 800;
    }

    .section p {
        margin: 0 0 15px;
        color: #64748b;
        font-size: 12px;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    input,
    select,
    textarea {
        width: 100%;
        border: 1px solid #dbe1e8;
        border-radius: 11px;
        outline: none;
        font-size: 13px;
        color: #334155;
        background: white;
    }

    input,
    select {
        height: 44px;
        padding: 0 12px;
    }

    textarea {
        padding: 12px;
        min-height: 100px;
        resize: vertical;
    }

    select[multiple] {
        height: 130px;
        padding: 8px;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.08);
    }

    .time {
        display: inline-flex;
        padding: 8px 11px;
        background: #f8fafc;
        border-radius: 9px;
        color: #64748b;
        font-size: 12px;
        margin-bottom: 15px;
    }

    .help {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 5px;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn {
        height: 43px;
        padding: 0 17px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-back {
        border: 1px solid #dbe1e8;
        color: #64748b;
        background: white;
    }

    .btn-save {
        border: 1px solid #1b234a;
        background: #1b234a;
        color: white;
    }

    .btn-delete {
        margin-right: auto;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #dc2626;
    }

    .error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        border-radius: 11px;
        padding: 13px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    @media(max-width:700px) {
        .grid {
            grid-template-columns: 1fr;
        }

        .actions {
            flex-wrap: wrap;
        }

        .btn-delete {
            margin-right: 0;
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="edit-page">

    <div class="header">
        <h1>Edit Jadwal Piket</h1>
        <p>
            {{ $tanggal->translatedFormat('l, d F Y') }}
        </p>
    </div>

    @if($errors->any())
        <div class="error">
            <strong>Periksa kembali data:</strong>

            <ul style="margin:7px 0 0 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.jadwal-piket.update', $tanggal->format('Y-m-d')) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <div class="card">

            <div class="section">

                <h2>📅 Tanggal</h2>
                <p>Tanggal pelaksanaan jadwal piket.</p>

                <input
                    type="date"
                    name="tanggal"
                    value="{{ old('tanggal', $tanggal->format('Y-m-d')) }}"
                    required
                >

            </div>

            <div class="section">

                <h2>☀️ Piket KBM Pagi</h2>
                <p>Shift pagi, 07:00 - 11:00.</p>

                <div class="time">
                    <i class="fas fa-clock" style="margin-right:6px;"></i>
                    07:00 - 11:00
                </div>

                <div class="grid">

                    <div>
                        <label>Petugas Pagi</label>

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
                                            old('pagi_petugas', $pagiPetugas)
                                        )
                                    )
                                >
                                    {{ $guru->nama_guru }}
                                </option>
                            @endforeach
                        </select>

                        <div class="help">
                            Gunakan Ctrl / Command untuk memilih beberapa guru.
                        </div>
                    </div>

                    <div>
                        <label>Koordinator Pagi</label>

                        <select name="pagi_koordinator">
                            <option value="">-- Pilih Koordinator --</option>

                            @foreach($gurus as $guru)
                                <option
                                    value="{{ $guru->id_guru }}"
                                    @selected(
                                        old(
                                            'pagi_koordinator',
                                            $pagiKoordinator
                                        ) == $guru->id_guru
                                    )
                                >
                                    {{ $guru->nama_guru }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="section">

                <h2>🌤️ Piket KBM Siang</h2>
                <p>Shift siang, 11:00 - 15:00.</p>

                <div class="time">
                    <i class="fas fa-clock" style="margin-right:6px;"></i>
                    11:00 - 15:00
                </div>

                <div class="grid">

                    <div>
                        <label>Petugas Siang</label>

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
                                            old('siang_petugas', $siangPetugas)
                                        )
                                    )
                                >
                                    {{ $guru->nama_guru }}
                                </option>
                            @endforeach
                        </select>

                        <div class="help">
                            Gunakan Ctrl / Command untuk memilih beberapa guru.
                        </div>
                    </div>

                    <div>
                        <label>Koordinator Siang</label>

                        <select name="siang_koordinator">
                            <option value="">-- Pilih Koordinator --</option>

                            @foreach($gurus as $guru)
                                <option
                                    value="{{ $guru->id_guru }}"
                                    @selected(
                                        old(
                                            'siang_koordinator',
                                            $siangKoordinator
                                        ) == $guru->id_guru
                                    )
                                >
                                    {{ $guru->nama_guru }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="section">

                <h2>🏫 Piket Waka</h2>
                <p>Satu petugas Waka untuk tanggal tersebut.</p>

                <label>Petugas Waka</label>

                <select name="waka">
                    <option value="">-- Tidak ada / Pilih Waka --</option>

                    @foreach($gurus as $guru)
                        <option
                            value="{{ $guru->id_guru }}"
                            @selected(
                                old('waka', $wakaGuru) == $guru->id_guru
                            )
                        >
                            {{ $guru->nama_guru }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="section">

                <h2>📝 Keterangan</h2>

                <textarea
                    name="keterangan"
                    placeholder="Keterangan tambahan..."
                >{{ old('keterangan', $keterangan) }}</textarea>

            </div>

            <div class="actions">

                <button
                    type="button"
                    class="btn btn-delete"
                    onclick="document.getElementById('delete-form').submit();"
                >
                    <i class="fas fa-trash"></i>
                    Hapus Jadwal
                </button>

                <a
                    href="{{ route('admin.jadwal-piket.index', ['month' => $tanggal->format('Y-m')]) }}"
                    class="btn btn-back"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-save"
                >
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>

            </div>

        </div>

    </form>

    <form
        id="delete-form"
        action="{{ route('admin.jadwal-piket.destroy', $tanggal->format('Y-m-d')) }}"
        method="POST"
        style="display:none;"
        onsubmit="return confirm('Hapus seluruh jadwal piket pada tanggal ini?');"
    >
        @csrf
        @method('DELETE')
    </form>

</div>
@endsection