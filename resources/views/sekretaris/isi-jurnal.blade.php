@extends('layouts.sekretaris')

@section('title', 'Isi Jurnal Guru')
@section('page-title', 'Isi Jurnal Guru')
@section('page-subtitle', 'Isi jurnal pembelajaran atas nama guru')

@section('content')

{{-- Import Font Manrope jika belum dimuat di layout utama --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="journal-page">

    {{-- PAGE HEADER --}}
    <div class="page-heading">
        <div>
            <h2>Isi Jurnal Atas Nama Guru</h2>
            <p>Jurnal disimpan dengan guru pengampu dari jadwal yang dipilih dan langsung tersedia bagi staf piket.</p>
        </div>
    </div>

    {{-- FORM CARD --}}
    <div class="journal-card">

        {{-- NOTICE / INSTRUCTION --}}
        <div class="notice">
            <div class="notice-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <strong>Perhatikan Sebelum Mengisi</strong>
                <p>Pastikan pengisian sudah mendapatkan konfirmasi langsung dari guru pengampu mata pelajaran.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('sekretaris.isi-jurnal.store') }}">
            @csrf

            {{-- SECTION 1: INFORMASI PEMBELAJARAN --}}
            <div class="form-section">
                <div class="section-title">
                    <span class="section-number">1</span>
                    <div>
                        <h3>Informasi Pembelajaran</h3>
                        <p>Pilih jadwal guru dan tentukan informasi dasar jurnal.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field full">
                        <label for="id_jadwal">Jadwal Guru</label>
                        <select id="id_jadwal" name="id_jadwal" required>
                            <option value="">Pilih jadwal guru</option>
                            @foreach($jadwals as $jadwal)
                                <option value="{{ $jadwal->id_jadwal }}" @selected(old('id_jadwal') == $jadwal->id_jadwal)>
                                    {{ $jadwal->guru?->nama_guru ?? '-' }} — {{ $jadwal->mapel?->nama_mapel ?? '-' }} — {{ $jadwal->kelas?->nama_kelas ?? '-' }}
                                    (Jam {{ $jadwal->jamMulai?->jam_ke ?? '-' }}-{{ $jadwal->jamSelesai?->jam_ke ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="tanggal">Tanggal Pembelajaran</label>
                        <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', now()->toDateString()) }}" required>
                    </div>

                    <div class="field">
                        <label for="status_guru">Status Guru</label>
                        <select id="status_guru" name="status_guru" required>
                            <option value="Izin" @selected(old('status_guru') === 'Izin')>Izin</option>
                            <option value="Sakit" @selected(old('status_guru') === 'Sakit')>Sakit</option>
                        </select>
                    </div>

                    <div class="field full">
                        <label for="catatan_umum">Catatan Ketidakhadiran Guru</label>
                        <textarea id="catatan_umum" name="catatan_umum" rows="3" required>{{ old('catatan_umum') }}</textarea>
                    </div>

                    <div class="field full">
                        <label for="materi">Materi Pembelajaran</label>
                        <input id="materi" name="materi" value="{{ old('materi') }}" placeholder="Contoh: Persamaan kuadrat dan penerapannya" required>
                    </div>
                </div>
            </div>

            <div class="form-divider"></div>

            {{-- SECTION 2: KEHADIRAN SISWA --}}
            <div class="form-section">
                <div class="section-title">
                    <span class="section-number">2</span>
                    <div>
                        <h3>Kehadiran Siswa</h3>
                        <p>Masukkan jumlah siswa yang hadir dan tidak hadir pada jam pelajaran ini.</p>
                    </div>
                </div>

                <div class="attendance-grid">
                    <div class="attendance-box hadir">
                        <div class="attendance-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="field">
                            <label for="jml_hadir">Jumlah Hadir</label>
                            <input id="jml_hadir" name="jml_hadir" type="number" min="0" placeholder="0" value="{{ old('jml_hadir') }}" required>
                        </div>
                    </div>

                    <div class="attendance-box tidak-hadir">
                        <div class="attendance-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div class="field">
                            <label for="jml_tidak_hadir">Jumlah Tidak Hadir</label>
                            <input id="jml_tidak_hadir" name="jml_tidak_hadir" type="number" min="0" placeholder="0" value="{{ old('jml_tidak_hadir') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-divider"></div>

            {{-- SECTION 3: TUGAS & CATATAN --}}
            <div class="form-section">
                <div class="section-title">
                    <span class="section-number">3</span>
                    <div>
                        <h3>Tugas & Catatan</h3>
                        <p>Tambahkan instruksi tugas atau catatan kegiatan pembelajaran jika ada.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="ada_tugas">Ada Tugas?</label>
                        <select id="ada_tugas" name="ada_tugas" required>
                            <option value="Tidak" @selected(old('ada_tugas') === 'Tidak')>Tidak</option>
                            <option value="Ya" @selected(old('ada_tugas') === 'Ya')>Ya</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="deskripsi_tugas">Deskripsi Tugas <span>(opsional)</span></label>
                        <input id="deskripsi_tugas" name="deskripsi_tugas" value="{{ old('deskripsi_tugas') }}" placeholder="Tugas LKS hal 45 atau PR kelompok">
                    </div>

                    <div class="field full">
                        <label for="catatan_umum">Catatan Pembelajaran <span>(opsional)</span></label>
                        <textarea id="catatan_umum" name="catatan_umum" placeholder="Ringkasan suasana kelas, kendala, atau pesan tambahan dari guru.">{{ old('catatan_umum') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="form-actions">
                <button class="button outline" type="reset">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Bersihkan Form
                </button>

                <button class="button submit-button" type="submit">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l5 5L20 7"/>
                    </svg>
                    Simpan Jurnal
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* GLOBAL STYLING & FONT MANROPE */
.journal-page,
.journal-page * {
    font-family: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    box-sizing: border-box;
}

.journal-page {
    width: 100%;
    animation: fadeInUp .4s cubic-bezier(.16,1,.3,1) both;
}

/* PAGE HEADER */
.page-heading {
    margin-bottom: 24px;
}

.page-heading h2 {
    margin: 0;
    color: #0F172A;
    font-size: 26px;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: -.5px;
}

.page-heading p {
    margin: 6px 0 0;
    color: #64748B;
    font-size: 14.5px;
    font-weight: 500;
}

/* MAIN CONTAINER CARD */
.journal-card {
    width: 100%;
    padding: 32px;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    background: #FFFFFF;
    box-shadow: 0 4px 16px rgba(15, 23, 42, .04);
}

/* NOTICE BOX */
.notice {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 32px;
    padding: 18px 20px;
    border: 1px solid #E0E7FF;
    border-radius: 16px;
    background: #EEF2FF;
}

.notice-icon {
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 2px;
    border-radius: 12px;
    background: #DCE4FF;
    color: #2D336B;
}

.notice-icon svg {
    width: 20px;
    height: 20px;
}

.notice strong {
    display: block;
    color: #2D336B;
    font-size: 14.5px;
    font-weight: 800;
}

.notice p {
    margin: 4px 0 0;
    color: #475569;
    font-size: 13.5px;
    line-height: 1.5;
}

/* SECTION TITLES */
.form-section {
    padding: 4px 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
}

.section-number {
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #EEF2FF;
    color: #2D336B;
    font-size: 14px;
    font-weight: 800;
}

.section-title h3 {
    margin: 0;
    color: #0F172A;
    font-size: 17px;
    font-weight: 800;
}

.section-title p {
    margin: 3px 0 0;
    color: #64748B;
    font-size: 13px;
    font-weight: 500;
}

/* FORM LAYOUT & INPUTS */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.field {
    min-width: 0;
}

.field.full {
    grid-column: 1 / -1;
}

.field label {
    display: block;
    margin-bottom: 8px;
    color: #334155;
    font-size: 13.5px;
    font-weight: 700;
}

.field label span {
    color: #94A3B8;
    font-size: 12.5px;
    font-weight: 500;
}

.field input,
.field select,
.field textarea {
    width: 100%;
    border: 1px solid #CBD5E1;
    border-radius: 12px;
    outline: none;
    background: #FFFFFF;
    color: #0F172A;
    font-size: 13.5px;
    font-weight: 500;
    transition: all .2s ease;
}

.field input,
.field select {
    height: 46px;
    padding: 0 16px;
}

.field select {
    cursor: pointer;
}

.field textarea {
    min-height: 110px;
    padding: 14px 16px;
    resize: vertical;
    line-height: 1.6;
}

.field input::placeholder,
.field textarea::placeholder {
    color: #94A3B8;
}

.field input:focus,
.field select:focus,
.field textarea:focus {
    border-color: #2D336B;
    box-shadow: 0 0 0 3px rgba(45, 51, 107, .12);
}

/* ATTENDANCE BOXES */
.attendance-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.attendance-box {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    background: #F8FAFC;
}

.attendance-icon {
    width: 44px;
    height: 44px;
    flex: 0 0 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}

.attendance-icon svg {
    width: 22px;
    height: 22px;
}

.attendance-box.hadir .attendance-icon {
    background: #DCFCE7;
    color: #15803D;
}

.attendance-box.tidak-hadir .attendance-icon {
    background: #FFE4E6;
    color: #BE123C;
}

.attendance-box .field {
    flex: 1;
}

.attendance-box .field label {
    margin-bottom: 6px;
}

/* DIVIDER */
.form-divider {
    height: 1px;
    margin: 32px 0;
    background: #F1F5F9;
}

/* FORM ACTIONS */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #F1F5F9;
}

.form-actions .button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 46px;
    padding: 0 22px;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s ease;
}

.form-actions .button svg {
    width: 18px;
    height: 18px;
}

.form-actions .button.outline {
    border: 1px solid #CBD5E1;
    background: #FFFFFF;
    color: #475569;
}

.form-actions .button.outline:hover {
    border-color: #94A3B8;
    background: #F8FAFC;
    color: #1E293B;
}

.form-actions .submit-button {
    border: 0;
    background: #2D336B;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(45, 51, 107, .15);
}

.form-actions .submit-button:hover {
    background: #1E234A;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(45, 51, 107, .25);
}

/* ANIMATIONS & RESPONSIVE */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 900px) {
    .form-grid,
    .attendance-grid {
        grid-template-columns: 1fr;
    }

    .field.full {
        grid-column: auto;
    }
}

@media (max-width: 767px) {
    .journal-card {
        padding: 22px;
        border-radius: 16px;
    }

    .page-heading h2 {
        font-size: 22px;
    }

    .page-heading p {
        font-size: 13.5px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions .button {
        width: 100%;
    }
}

@media (max-width: 420px) {
    .journal-card {
        padding: 18px;
    }

    .notice {
        padding: 14px;
    }

    .section-title {
        gap: 12px;
    }

    .attendance-box {
        padding: 14px;
    }
}
</style>
@endsection