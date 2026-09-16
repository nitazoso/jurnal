<div class="form-grid">
    <div>
        <label>Petugas Kesiswaan</label>
        <select name="id_user" required>
            <option value="">Pilih petugas</option>
            @foreach($users as $user)
                <option value="{{ $user->id_user }}" {{ old('id_user', $jadwal->id_user ?? '') == $user->id_user ? 'selected' : '' }}>
                    {{ $user->nama_user }} ({{ $user->username }})
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Tanggal</label>
        <input type="date" name="tanggal" value="{{ old('tanggal', isset($jadwal) ? $jadwal->tanggal?->format('Y-m-d') : '') }}" required>
    </div>
</div>
