<div class="form-grid">
    <div>
        <label>Guru</label>
        <select name="id_guru" required>
            <option value="">Pilih Guru</option>
            @foreach($gurus as $guru)
                <option value="{{ $guru->id_guru }}" {{ old('id_guru', $jadwal->id_guru ?? '') == $guru->id_guru ? 'selected' : '' }}>{{ $guru->nama_guru }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Tanggal</label>
        <input type="date" name="tanggal" value="{{ old('tanggal', isset($jadwal) ? $jadwal->tanggal?->format('Y-m-d') : '') }}" required>
    </div>
    <div>
        <label>Shift</label>
        <select name="shift" required>
            @foreach(['Pagi', 'Siang', 'Waka'] as $shift)
                <option value="{{ $shift }}" {{ old('shift', $jadwal->shift ?? 'Pagi') == $shift ? 'selected' : '' }}>{{ $shift }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai ?? '07:00') }}" required>
    </div>
    <div>
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai ?? '11:00') }}" required>
    </div>
    <div>
        <label>Jenis Tugas</label>
        <select name="jenis_tugas" required>
            @foreach(['Piket KBM Pagi', 'Koordinator Piket KBM Pagi', 'Piket KBM Siang', 'Koordinator Piket KBM Siang', 'Piket Waka'] as $jenisTugas)
                <option value="{{ $jenisTugas }}" {{ old('jenis_tugas', $jadwal->jenis_tugas ?? '') == $jenisTugas ? 'selected' : '' }}>{{ $jenisTugas }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Posisi / Tugas</label>
        <input type="text" name="posisi" value="{{ old('posisi', $jadwal->posisi ?? '') }}" placeholder="Contoh: Ruang Guru / Pos Piket">
    </div>
    <div style="grid-column: 1 / -1;">
        <label>Keterangan</label>
        <textarea name="keterangan" rows="4" placeholder="Keterangan tambahan">{{ old('keterangan', $jadwal->keterangan ?? '') }}</textarea>
    </div>
</div>
