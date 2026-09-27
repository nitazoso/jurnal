@extends('layouts.piket')

@section('title', 'Kirim Surat Sakit - Jurnify')
@section('page-title', 'Kirim Surat Sakit')

@push('styles')
<style>
    .sick-page {
        --sick-primary: #30366f;
        --sick-accent: #4169ff;
        --sick-text: #1f2937;
        --sick-muted: #64748b;
        display: grid;
        gap: 18px;
        margin: 0 auto;
        max-width: 1120px;
    }

    .sick-page-header h1 {
        color: var(--sick-primary);
        font-size: 22px;
        font-weight: 800;
        margin: 0;
    }

    .sick-page-header p {
        color: var(--sick-muted);
        font-size: 13px;
        margin: 5px 0 0;
    }

    .sick-panel {
        background: #fff;
        border: 1px solid #e1e7f0;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(26, 39, 73, .035);
        min-width: 0;
        padding: 20px;
    }

    .sick-panel-title {
        border-bottom: 1px solid #edf0f5;
        color: var(--sick-primary);
        font-size: 16px;
        font-weight: 800;
        margin: 0 0 17px;
        padding-bottom: 12px;
    }

    .sick-form-grid {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .sick-field {
        display: grid;
        gap: 7px;
        min-width: 0;
    }

    .sick-field--wide {
        grid-column: 1 / -1;
    }

    .sick-field label {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    .sick-control {
        background: #fff;
        border: 1px solid #cfd7e4;
        border-radius: 7px;
        color: var(--sick-text);
        font: inherit;
        font-size: 14px;
        min-height: 42px;
        padding: 9px 11px;
        width: 100%;
    }

    .sick-control:focus {
        border-color: var(--sick-accent);
        box-shadow: 0 0 0 3px rgba(65, 105, 255, .12);
        outline: none;
    }

    .sick-control:disabled {
        background: #f1f4f8;
        color: #94a3b8;
        cursor: not-allowed;
    }

    textarea.sick-control {
        min-height: 104px;
        resize: vertical;
    }

    input[type="file"].sick-control {
        padding: 5px;
    }

    input[type="file"].sick-control::file-selector-button {
        background: #eef2ff;
        border: 0;
        border-radius: 5px;
        color: var(--sick-primary);
        cursor: pointer;
        font-weight: 700;
        margin-right: 10px;
        padding: 7px 10px;
    }

    .sick-help {
        color: var(--sick-muted);
        font-size: 11px;
        margin: 0;
    }

    .sick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
        grid-column: 1 / -1;
        justify-content: flex-end;
        padding-top: 3px;
    }

    .sick-button {
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

    .sick-button--secondary {
        background: #fff;
        border-color: #d4dbe6;
        color: #475569;
    }

    .sick-button--primary {
        background: var(--sick-primary);
        color: #fff;
    }

    .sick-button--primary:hover {
        background: #232956;
    }

    .sick-alert {
        border: 1px solid;
        border-radius: 8px;
        font-size: 13px;
        padding: 12px 15px;
    }

    .sick-alert--success {
        background: #eef9f1;
        border-color: #cdebd5;
        color: #187548;
    }

    .sick-alert--error {
        background: #fff2f2;
        border-color: #f2cccc;
        color: #a33232;
    }

    .sick-alert ul {
        margin: 0;
        padding-left: 19px;
    }

    .sick-table-wrap {
        overflow-x: auto;
    }

    .sick-table {
        border-collapse: collapse;
        min-width: 580px;
        width: 100%;
    }

    .sick-table th,
    .sick-table td {
        border-bottom: 1px solid #edf0f5;
        font-size: 13px;
        padding: 12px 13px;
        text-align: left;
    }

    .sick-table th {
        background: #f7f8fb;
        color: var(--sick-muted);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .sick-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .sick-status {
        background: #eaf8ef;
        border-radius: 999px;
        color: #187548;
        display: inline-block;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 9px;
    }

    .sick-photo-link {
        color: var(--sick-accent);
        font-weight: 700;
        text-decoration: none;
    }

    .sick-photo-link:hover {
        text-decoration: underline;
    }

    .sick-empty {
        color: var(--sick-muted);
        padding: 24px !important;
        text-align: center !important;
    }

    @media (max-width: 640px) {
        .sick-panel {
            padding: 16px;
        }

        .sick-form-grid {
            grid-template-columns: 1fr;
        }

        .sick-field--wide,
        .sick-actions {
            grid-column: auto;
        }

        .sick-actions {
            justify-content: stretch;
        }

        .sick-button {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')
<main class="sick-page">
    <header class="sick-page-header">
        <h1>Kirim Surat Sakit</h1>
        <p>Data akan otomatis muncul sebagai Sakit pada jurnal guru.</p>
    </header>

    @if(session('success'))
        <div class="sick-alert sick-alert--success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="sick-alert sick-alert--error">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="sick-panel">
        <h2 class="sick-panel-title">Data Laporan</h2>
        <form action="{{ route('piket.dispen.sakit.store') }}" method="POST" enctype="multipart/form-data" class="sick-form-grid">
            @csrf
            <div class="sick-field">
                <label for="sick-class">Kelas</label>
                <select name="id_kelas" id="sick-class" class="sick-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id_kelas }}" @selected(old('id_kelas') == $kelas->id_kelas)>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sick-field">
                <label for="sick-student">Siswa</label>
                <select name="id_siswa" id="sick-student" class="sick-control" required disabled>
                    <option value="">-- Pilih kelas terlebih dahulu --</option>
                </select>
            </div>
            <div class="sick-field">
                <label for="sick-date">Tanggal</label>
                <input type="date" id="sick-date" name="tanggal" value="{{ old('tanggal', today()->toDateString()) }}" class="sick-control" required>
            </div>
            <div class="sick-field sick-field--wide">
                <label for="sick-reason">Keterangan Sakit</label>
                <textarea id="sick-reason" name="alasan" rows="3" class="sick-control" required>{{ old('alasan') }}</textarea>
            </div>
            <div class="sick-field sick-field--wide">
                <label for="sick-letter">Foto Surat <span style="font-weight: 400; color: #94a3b8;">(opsional)</span></label>
                <input type="file" id="sick-letter" name="surat" accept="image/*" class="sick-control">
                <p class="sick-help">Format gambar, maksimal 5 MB.</p>
            </div>
            <div class="sick-actions">
                <a href="{{ route('piket.dispen.index') }}" class="sick-button sick-button--secondary">Kembali</a>
                <button type="submit" class="sick-button sick-button--primary">Kirim Surat Sakit</button>
            </div>
        </form>
    </section>

    <section class="sick-panel">
        <h2 class="sick-panel-title">Status Surat Sakit Hari Ini</h2>
        <div class="sick-table-wrap">
            <table class="sick-table">
                <thead><tr><th>Siswa</th><th>Kelas</th><th>Status</th><th>Surat</th></tr></thead>
                <tbody>
                    @forelse($sickReports as $report)
                        <tr>
                            <td>{{ $report->siswa?->nama_siswa ?? '-' }}</td>
                            <td>{{ $report->siswa?->kelas?->nama_kelas ?? '-' }}</td>
                            <td><span class="sick-status">Sakit</span></td>
                            <td>@if($report->surat_path)<a href="{{ asset('storage/' . $report->surat_path) }}" target="_blank" rel="noopener" class="sick-photo-link">Lihat foto</a>@else-@endif</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="sick-empty">Belum ada laporan sakit hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>
<script>
(() => {
    const data = @json($siswaPerKelas);
    const classInput = document.getElementById('sick-class');
    const studentInput = document.getElementById('sick-student');
    classInput.addEventListener('change', () => {
        const students = data[classInput.value] || [];
        studentInput.replaceChildren(new Option(students.length ? '-- Pilih Siswa --' : '-- Tidak ada siswa --', ''));
        students.forEach(student => studentInput.add(new Option(student.nis ? `${student.nama} (${student.nis})` : student.nama, student.id)));
        studentInput.disabled = !students.length;
    });
})();
</script>
@endsection
