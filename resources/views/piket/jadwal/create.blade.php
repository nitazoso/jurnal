@extends('layouts.piket')

@section('title', 'Tambah Jadwal Piket - Jurnify')
@section('page-title', 'Tambah Jadwal Piket')

@section('content')
<div class="card" style="max-width: 900px;">
    <h3 style="margin-bottom: 16px;">Buat Jadwal Piket</h3>

    <form action="{{ route('piket.jadwal.store') }}" method="POST">
        @csrf

        <div class="form-grid">
            <div>
                <label>Guru</label>
                <select name="id_guru" required>
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Tanggal</label>
                <input type="date" name="tanggal" required>
            </div>

            <div>
                <label>Shift</label>
                <select name="shift" required>
                    <option value="Pagi">Pagi</option>
                    <option value="Siang">Siang</option>
                    <option value="Waka">Waka</option>
                </select>
            </div>

            <div>
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" value="07:00" required>
            </div>

            <div>
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" value="11:00" required>
            </div>

            <div>
                <label>Jenis Tugas</label>
                <select name="jenis_tugas" required>
                    <option value="Piket KBM Pagi">Piket KBM Pagi</option>
                    <option value="Koordinator Piket KBM Pagi">Koordinator Piket KBM Pagi</option>
                    <option value="Piket KBM Siang">Piket KBM Siang</option>
                    <option value="Koordinator Piket KBM Siang">Koordinator Piket KBM Siang</option>
                    <option value="Piket Waka">Piket Waka</option>
                </select>
            </div>

            <div>
                <label>Posisi / Tugas</label>
                <input type="text" name="posisi" placeholder="Contoh: Ruang Guru / Pos Piket">
            </div>

            <div style="grid-column: 1 / -1;">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="4" placeholder="Keterangan tambahan"></textarea>
            </div>
        </div>

        <div style="margin-top: 18px; display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('piket.jadwal.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
