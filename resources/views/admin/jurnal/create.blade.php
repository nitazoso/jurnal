@extends('layouts.admin')

@section('title', 'Tambah Jurnal - Jurnify')

@section('page-title', 'Tambah Jurnal')

@section('content')

<div class="activity-header">
    <h3 class="activity-title">Tambah Jurnal</h3>

    <p class="activity-description">
        Isi data jurnal pembelajaran.
    </p>
</div>

<form action="{{ route('admin.jurnal.store') }}" method="POST">
    @csrf

    <div>
        <label>Tanggal</label>
        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required>
    </div>

    <div>
        <label>Jadwal</label>
        <select name="id_jadwal" required>
            <option value="">-- Pilih Jadwal --</option>

            @foreach($jadwals as $jadwal)
                <option value="{{ $jadwal->id_jadwal }}">
                    {{ $jadwal->hari }} - {{ $jadwal->tahun_ajaran }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Guru</label>
        <select name="id_guru" required>
            <option value="">-- Pilih Guru --</option>

            @foreach($guru as $item)
                <option value="{{ $item->id_guru }}">
                    {{ $item->nama_guru }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Kelas</label>
        <select name="id_kelas" required>
            <option value="">-- Pilih Kelas --</option>

            @foreach($kelas as $item)
                <option value="{{ $item->id_kelas }}">
                    {{ $item->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Jam Mulai</label>
        <select name="id_jam_mulai" required>
            <option value="">-- Pilih Jam --</option>

            @foreach($jamPels as $jam)
                <option value="{{ $jam->id_jam }}">
                    Jam {{ $jam->jam_ke }} ({{ $jam->jam_mulai }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Jam Selesai</label>
        <select name="id_jam_selesai" required>
            <option value="">-- Pilih Jam --</option>

            @foreach($jamPels as $jam)
                <option value="{{ $jam->id_jam }}">
                    Jam {{ $jam->jam_ke }} ({{ $jam->jam_selesai }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Materi</label>
        <input type="text" name="materi" required>
    </div>

    <div>
        <label>Status Guru</label>
        <select name="status_guru" required>
            <option value="Hadir">Hadir</option>
            <option value="Izin">Izin</option>
            <option value="Sakit">Sakit</option>
            <option value="Tanpa Keterangan">Tanpa Keterangan</option>
        </select>
    </div>

    <div>
        <label>Jumlah Hadir</label>
        <input type="number" name="jml_hadir" min="0" required>
    </div>

    <div>
        <label>Jumlah Tidak Hadir</label>
        <input type="number" name="jml_tidak_hadir" min="0" required>
    </div>

    <div>
        <label>Ada Tugas?</label>
        <select name="ada_tugas" required>
            <option value="Tidak">Tidak</option>
            <option value="Ya">Ya</option>
        </select>
    </div>

    <div>
        <label>Deskripsi Tugas</label>
        <textarea name="deskripsi_tugas"></textarea>
    </div>

    <div>
        <label>Catatan Umum</label>
        <input type="text" name="catatan_umum">
    </div>

    <button type="submit">
        Simpan Jurnal
    </button>
</form>
