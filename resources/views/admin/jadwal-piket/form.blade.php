@php
    $guruFields = [
        'petugas_kbm_pagi_id' => 'Petugas Piket KBM Pagi',
        'koordinator_kbm_pagi_id' => 'Koordinator Piket KBM Pagi',
        'petugas_kbm_siang_id' => 'Petugas Piket KBM Siang',
        'koordinator_kbm_siang_id' => 'Koordinator Piket KBM Siang',
        'piket_waka_id' => 'Piket Waka',
    ];
    $oldValue = fn ($field) => old($field, isset($jadwal) ? $jadwal->{$field} : '');
    $usernameForGuru = fn ($guruId) => $gurus->firstWhere('id_guru', $guruId)?->username ?? '';
@endphp

<div class="piket-grid">
    <div class="field full-width">
        <label for="tanggal">Hari / Tanggal</label>
        <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', isset($jadwal) ? $jadwal->tanggal?->format('Y-m-d') : '') }}" required>
    </div>

    @foreach ($guruFields as $field => $label)
        <div class="field">
            <label for="{{ $field }}">{{ $label }}</label>
            <select id="{{ $field }}" name="{{ $field }}" required>
                <option value="">Pilih username</option>
                @foreach ($gurus as $guruUser)
                    @php($username = $guruUser->username)
                    <option value="{{ $username }}" @selected(old($field, isset($jadwal) ? $usernameForGuru($jadwal->{$field}) : '') === $username)>
                        {{ $username }} - {{ $guruUser->guru?->nama_guru ?? $guruUser->nama_user }}
                    </option>
                @endforeach
            </select>
        </div>

        @if ($field !== 'piket_waka_id')
            @php
                $prefix = str_replace('_id', '', $field);
                $startField = 'jam_mulai_' . $prefix;
                $endField = 'jam_selesai_' . $prefix;
            @endphp
            <div class="field time-fields full-width">
                <label>Jam {{ $label }}</label>
                <div class="time-row">
                    <input type="text" name="{{ $startField }}" value="{{ $oldValue($startField) }}" placeholder="Contoh: 06:30" required>
                    <span>sampai</span>
                    <input type="text" name="{{ $endField }}" value="{{ $oldValue($endField) }}" placeholder="Contoh: 12:00" required>
                </div>
            </div>
        @endif
    @endforeach
</div>

<style>
    .piket-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
    .field { min-width: 0; }
    .full-width { grid-column: 1 / -1; }
    .field label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #334155; }
    .field input, .field select { width: 100%; min-height: 44px; box-sizing: border-box; padding: 10px 12px; border: 1px solid #d7dbe5; border-radius: 10px; background: #fff; color: #1e293b; font: 13px Manrope, sans-serif; }
    .time-row { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 10px; }
    .time-row span { color: #64748b; font-size: 13px; }
    @media (max-width: 680px) { .piket-grid { grid-template-columns: 1fr; } .full-width { grid-column: auto; } }
</style>
