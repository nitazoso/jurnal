<div class="form-grid">

    <div class="form-group">
        <label for="id_guru">Guru</label>
        <select id="id_guru" name="id_guru" required>
            <option value="">Pilih Guru</option>
            @foreach($gurus as $guru)
                <option value="{{ $guru->id_guru }}"
                    {{ old('id_guru', $jadwal->id_guru ?? '') == $guru->id_guru ? 'selected' : '' }}>
                    {{ $guru->nama_guru }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="tanggal">Tanggal</label>
        <input
            type="date"
            id="tanggal"
            name="tanggal"
            value="{{ old('tanggal', isset($jadwal) ? $jadwal->tanggal?->format('Y-m-d') : '') }}"
            required
        >
    </div>

    <div class="form-group">
        <label for="shift">Shift</label>
        <select id="shift" name="shift" required>
            @foreach(['Pagi', 'Siang', 'Waka'] as $shift)
                <option value="{{ $shift }}"
                    {{ old('shift', $jadwal->shift ?? 'Pagi') == $shift ? 'selected' : '' }}>
                    {{ $shift }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="jenis_tugas">Jenis Tugas</label>
        <select id="jenis_tugas" name="jenis_tugas" required>
            @foreach([
                'Piket KBM Pagi',
                'Koordinator Piket KBM Pagi',
                'Piket KBM Siang',
                'Koordinator Piket KBM Siang',
                'Piket Waka'
            ] as $jenisTugas)
                <option value="{{ $jenisTugas }}"
                    {{ old('jenis_tugas', $jadwal->jenis_tugas ?? '') == $jenisTugas ? 'selected' : '' }}>
                    {{ $jenisTugas }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="jam_mulai">Jam Mulai</label>
        <input
            type="time"
            id="jam_mulai"
            name="jam_mulai"
            value="{{ old('jam_mulai', $jadwal->jam_mulai ?? '07:00') }}"
            required
        >
    </div>

    <div class="form-group">
        <label for="jam_selesai">Jam Selesai</label>
        <input
            type="time"
            id="jam_selesai"
            name="jam_selesai"
            value="{{ old('jam_selesai', $jadwal->jam_selesai ?? '11:00') }}"
            required
        >
    </div>

    <div class="form-group full-width">
        <label for="posisi">Posisi / Tugas</label>
        <input
            type="text"
            id="posisi"
            name="posisi"
            value="{{ old('posisi', $jadwal->posisi ?? '') }}"
            placeholder="Contoh: Ruang Guru / Pos Piket"
        >
    </div>

    <div class="form-group full-width">
        <label for="keterangan">Keterangan</label>
        <textarea
            id="keterangan"
            name="keterangan"
            rows="4"
            placeholder="Keterangan tambahan"
        >{{ old('keterangan', $jadwal->keterangan ?? '') }}</textarea>
    </div>

</div>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 24px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #D9DDE8;
        border-radius: 10px;
        background: #FFFFFF;
        color: #1E293B;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        outline: none;
        transition: all 0.2s ease;
    }

    .form-group input,
    .form-group select {
        height: 44px;
        padding: 0 13px;
    }

    .form-group textarea {
        min-height: 105px;
        padding: 12px 13px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #7886C7;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, 0.12);
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #A8B0BE;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }
    }
</style>