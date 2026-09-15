@extends('layouts.admin')

@section('title', 'Edit Jadwal Piket - Jurnify')
@section('page-title', 'Edit Jadwal Piket')

@section('content')
<div class="card" style="max-width: 900px;">
    <h3 style="margin-bottom: 16px;">Edit Jadwal Piket</h3>

    <form action="{{ route('piket.jadwal.update', $jadwal->id_piket_jadwal) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div>
                <label>Guru</label>
                <select name="id_guru" required>
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id_guru }}" {{ $jadwal->id_guru == $guru->id_guru ? 'selected' : '' }}>{{ $guru->nama_guru }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="{{ $jadwal->tanggal->format('Y-m-d') }}" required>
            </div>

            <div>
                <label>Shift</label>
                <select name="shift" required>
                    <option value="Pagi" {{ $jadwal->shift == 'Pagi' ? 'selected' : '' }}>Pagi</option>
                    <option value="Siang" {{ $jadwal->shift == 'Siang' ? 'selected' : '' }}>Siang</option>
                    <option value="Waka" {{ $jadwal->shift == 'Waka' ? 'selected' : '' }}>Waka</option>
                </select>
            </div>

            <div>
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" value="{{ $jadwal->jam_mulai }}" required>
            </div>

            <div>
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" value="{{ $jadwal->jam_selesai }}" required>
            </div>

            <div>
                <label>Jenis Tugas</label>
                <select name="jenis_tugas" required>
                    <option value="Piket KBM Pagi" {{ $jadwal->jenis_tugas == 'Piket KBM Pagi' ? 'selected' : '' }}>Piket KBM Pagi</option>
                    <option value="Koordinator Piket KBM Pagi" {{ $jadwal->jenis_tugas == 'Koordinator Piket KBM Pagi' ? 'selected' : '' }}>Koordinator Piket KBM Pagi</option>
                    <option value="Piket KBM Siang" {{ $jadwal->jenis_tugas == 'Piket KBM Siang' ? 'selected' : '' }}>Piket KBM Siang</option>
                    <option value="Koordinator Piket KBM Siang" {{ $jadwal->jenis_tugas == 'Koordinator Piket KBM Siang' ? 'selected' : '' }}>Koordinator Piket KBM Siang</option>
                    <option value="Piket Waka" {{ $jadwal->jenis_tugas == 'Piket Waka' ? 'selected' : '' }}>Piket Waka</option>
                </select>
            </div>

            <div>
                <label>Posisi / Tugas</label>
                <input type="text" name="posisi" value="{{ $jadwal->posisi ?? '' }}" placeholder="Contoh: Ruang Guru / Pos Piket">
            </div>

            <div style="grid-column: 1 / -1;">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="4">{{ $jadwal->keterangan }}</textarea>
            </div>
        </div>

        <div style="margin-top: 18px; display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('piket.jadwal.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
