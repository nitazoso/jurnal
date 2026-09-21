<div class="form-grid">
    <div class="form-group">
        <label for="id_user" class="form-label">Petugas Kesiswaan <span class="required">*</span></label>
        <select 
            id="id_user" 
            name="id_user" 
            required 
            class="form-control @error('id_user') is-invalid @enderror"
        >
            <option value="">Pilih petugas</option>
            @foreach($users as $user)
                <option value="{{ $user->id_user }}" {{ old('id_user', $jadwal->id_user ?? '') == $user->id_user ? 'selected' : '' }}>
                    {{ $user->nama_user }} ({{ $user->username }})
                </option>
            @endforeach
        </select>
        @error('id_user')
            <span class="error-msg">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="tanggal" class="form-label">Tanggal <span class="required">*</span></label>
        <input 
            type="date" 
            id="tanggal" 
            name="tanggal" 
            value="{{ old('tanggal', isset($jadwal) && $jadwal->tanggal ? \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d') : '') }}" 
            required 
            class="form-control @error('tanggal') is-invalid @enderror"
        >
        @error('tanggal')
            <span class="error-msg">{{ $message }}</span>
        @enderror
    </div>
</div>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
    }

    .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 14px;
        color: #0f172a;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        border-color: #7886c7;
        box-shadow: 0 0 0 3px rgba(120, 134, 199, 0.15);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .error-msg {
        font-size: 12px;
        color: #dc2626;
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }
</style>