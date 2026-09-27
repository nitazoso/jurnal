@extends('layouts.piket')

@section('title', 'Tambah Dispen - Jurnify')
@section('page-title', 'Tambah Dispen')

@push('styles')
<style>
    .dispen-create-page {
        --dispen-primary: #30366f;
        --dispen-accent: #4169ff;
        --dispen-text: #1f2937;
        --dispen-muted: #64748b;
        animation: dispen-page-fade .3s ease both;
        display: grid;
        gap: 18px;
        margin: 0 auto;
        max-width: 1120px;
    }

    @keyframes dispen-page-fade {
        from { opacity: 0; transform: translateY(7px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dispen-create-header h1 {
        color: var(--dispen-primary);
        font-size: 22px;
        font-weight: 800;
        margin: 0;
    }

    .dispen-create-header p {
        color: var(--dispen-muted);
        font-size: 13px;
        margin: 5px 0 0;
    }

    .dispen-create-panel {
        background: #fff;
        border: 1px solid #e1e7f0;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(26, 39, 73, .035);
        min-width: 0;
        padding: 20px;
    }

    .dispen-create-panel h2 {
        border-bottom: 1px solid #edf0f5;
        color: var(--dispen-primary);
        font-size: 16px;
        font-weight: 800;
        margin: 0 0 17px;
        padding-bottom: 12px;
    }

    .dispen-create-grid {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dispen-create-field {
        display: grid;
        gap: 7px;
        min-width: 0;
    }

    .dispen-create-field--wide,
    .dispen-create-actions {
        grid-column: 1 / -1;
    }

    .dispen-create-field label {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    .dispen-create-control {
        background: #fff;
        border: 1px solid #cfd7e4;
        border-radius: 7px;
        color: var(--dispen-text);
        font: inherit;
        font-size: 14px;
        min-height: 42px;
        padding: 9px 11px;
        width: 100%;
    }

    .dispen-create-control:focus {
        border-color: var(--dispen-accent);
        box-shadow: 0 0 0 3px rgba(65, 105, 255, .12);
        outline: none;
    }

    .dispen-create-control:disabled {
        background: #f1f4f8;
        color: #94a3b8;
        cursor: not-allowed;
    }

    textarea.dispen-create-control {
        min-height: 104px;
        resize: vertical;
    }

    .dispen-create-help {
        color: var(--dispen-muted);
        font-size: 11px;
        margin: 0;
    }

    .dispen-create-alert {
        background: #fff2f2;
        border: 1px solid #f2cccc;
        border-radius: 8px;
        color: #a33232;
        font-size: 13px;
        padding: 12px 15px;
    }

    .dispen-create-alert ul {
        margin: 0;
        padding-left: 19px;
    }

    .dispen-create-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
        justify-content: flex-end;
        padding-top: 3px;
    }

    .dispen-create-button {
        align-items: center;
        border: 1px solid transparent;
        border-radius: 7px;
        cursor: pointer;
        display: inline-flex;
        font-size: 13px;
        font-weight: 700;
        justify-content: center;
        min-height: 39px;
        padding: 8px 14px;
        text-decoration: none;
    }

    .dispen-create-button--secondary {
        background: #fff;
        border-color: #d4dbe6;
        color: #475569;
    }

    .dispen-create-button--primary {
        background: var(--dispen-primary);
        color: #fff;
    }

    .dispen-create-button--primary:hover {
        background: #232956;
    }

    @media (max-width: 640px) {
        .dispen-create-panel { padding: 16px; }
        .dispen-create-grid { grid-template-columns: 1fr; }
        .dispen-create-field--wide,
        .dispen-create-actions { grid-column: auto; }
        .dispen-create-actions { justify-content: stretch; }
        .dispen-create-button { flex: 1; }
    }
</style>
@endpush

@section('content')
<main class="dispen-create-page">
    <header class="dispen-create-header">
        <h1>Tambah Dispen</h1>
        <p>Tambahkan data dispensasi siswa dan teruskan ke petugas Kesiswaan.</p>
    </header>

    @if ($errors->any())
        <div class="dispen-create-alert" role="alert">
            <ul>
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <section class="dispen-create-panel">
        <h2>Informasi Dispen</h2>
        <form action="{{ route('piket.dispen.store') }}" method="POST" class="dispen-create-grid">
            @csrf

            <div class="dispen-create-field">
                <label for="class-select">Kelas</label>
                <select name="id_kelas" id="class-select" class="dispen-create-control" required>
                    <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                    @foreach ($kelases as $kelas)
                        <option value="{{ $kelas->id_kelas }}" @selected(old('id_kelas') == $kelas->id_kelas)>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="dispen-create-field">
                <label for="student-select">Siswa</label>
                <select name="id_siswa" id="student-select" class="dispen-create-control" required disabled>
                    <option value="">-- Pilih kelas terlebih dahulu --</option>
                </select>
                <p id="student-help" class="dispen-create-help" aria-live="polite">Pilih kelas untuk menampilkan seluruh siswa.</p>
            </div>

            <div class="dispen-create-field">
                <label for="kesiswaan-select">Tujuan Kesiswaan</label>
                <select name="id_kesiswaan" id="kesiswaan-select" class="dispen-create-control" required>
                    <option value="">-- Pilih Kesiswaan --</option>
                    @foreach ($petugasKesiswaans as $petugas)
                        <option value="{{ $petugas->id_user }}" @selected(old('id_kesiswaan') == $petugas->id_user)>
                            {{ $petugas->nama_user }} ({{ $petugas->no_wa }})
                        </option>
                    @endforeach
                    @if ($petugasKesiswaans->isEmpty())
                        <option value="" disabled>Belum ada akun Kesiswaan dengan nomor WhatsApp</option>
                    @endif
                </select>
                <p class="dispen-create-help">Hanya petugas Kesiswaan dengan nomor WhatsApp terdaftar.</p>
            </div>

            <div class="dispen-create-field">
                <label for="dispen-date">Tanggal</label>
                <input type="date" id="dispen-date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="dispen-create-control" required>
            </div>

            <div class="dispen-create-field">
                <label for="start-period">Jam Mulai</label>
                <select name="id_jam_mulai" id="start-period" class="dispen-create-control" required>
                    <option value="">-- Pilih Jam Mulai --</option>
                    @foreach ($jamPels as $jam)
                        <option value="{{ $jam->id_jam }}" @selected(old('id_jam_mulai') == $jam->id_jam)>
                            Jam ke-{{ $jam->jam_ke }} ({{ substr($jam->jam_mulai, 0, 5) }} - {{ substr($jam->jam_selesai, 0, 5) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="dispen-create-field">
                <label for="end-period">Jam Selesai</label>
                <select name="id_jam_selesai" id="end-period" class="dispen-create-control" required>
                    <option value="">-- Pilih Jam Selesai --</option>
                    @foreach ($jamPels as $jam)
                        <option value="{{ $jam->id_jam }}" @selected(old('id_jam_selesai') == $jam->id_jam)>
                            Jam ke-{{ $jam->jam_ke }} ({{ substr($jam->jam_mulai, 0, 5) }} - {{ substr($jam->jam_selesai, 0, 5) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="dispen-create-field dispen-create-field--wide">
                <label for="dispen-reason">Alasan Dispen</label>
                <textarea id="dispen-reason" name="alasan" rows="4" class="dispen-create-control" placeholder="Contoh: Mengikuti kegiatan lomba sekolah" required>{{ old('alasan') }}</textarea>
            </div>

            <div class="dispen-create-actions">
                <a href="{{ route('piket.dispen.index') }}" class="dispen-create-button dispen-create-button--secondary">Kembali</a>
                <button type="submit" class="dispen-create-button dispen-create-button--primary">Simpan &amp; Kirim ke Kesiswaan</button>
            </div>
        </form>
    </section>
</main>
</div>

<script>
    (() => {
        const studentsByClass = @json($siswaPerKelas);
        const classSelect = document.getElementById('class-select');
        const studentSelect = document.getElementById('student-select');
        const studentHelp = document.getElementById('student-help');
        const selectedStudent = @json((string) old('id_siswa'));

        const showStudents = (classId, studentId = '') => {
            const students = studentsByClass[classId] || [];
            studentSelect.replaceChildren(new Option(
                students.length ? '-- Pilih Siswa --' : '-- Tidak ada siswa di kelas ini --', ''
            ));
            students.forEach((student) => {
                const label = student.nis ? `${student.nama} (${student.nis})` : student.nama;
                studentSelect.add(new Option(label, student.id, false, String(student.id) === String(studentId)));
            });
            studentSelect.disabled = !classId || !students.length;
            studentHelp.textContent = classId
                ? `${students.length} siswa ditemukan di kelas ini.`
                : 'Pilih kelas untuk menampilkan seluruh siswa.';
        };

        classSelect.addEventListener('change', () => showStudents(classSelect.value));
        if (classSelect.value) showStudents(classSelect.value, selectedStudent);
    })();
</script>

@endsection
