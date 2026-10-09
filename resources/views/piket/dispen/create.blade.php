@extends('layouts.piket')

@section('title', ($editing ?? false ? 'Edit' : 'Tambah') . ' Dispen - Jurnify')
@section('page-title', ($editing ?? false ? 'Edit' : 'Tambah') . ' Dispen')

@push('styles')
<style>
    .dispen-create-page{--navy:#30366f;--blue:#4169ff;--ink:#1e293b;--muted:#64748b;display:grid;gap:18px;margin:0 auto;max-width:1000px}
    .dispen-create-header h1{margin:0;color:var(--navy);font-size:22px;font-weight:800}.dispen-create-header p{margin:5px 0 0;color:var(--muted);font-size:13px}
    .dispen-create-panel{min-width:0;padding:20px;border:1px solid #e1e7f0;border-radius:14px;background:#fff;box-shadow:0 3px 12px rgba(26,39,73,.04)}
    .dispen-create-panel h2{margin:0 0 8px;color:var(--navy);font-size:16px;font-weight:800}.dispen-create-panel-intro{margin:0 0 17px;color:var(--muted);font-size:12px;line-height:1.5}
    .dispen-create-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.dispen-create-field{display:grid;align-content:start;gap:7px;min-width:0}.dispen-create-field--wide,.dispen-create-actions{grid-column:1/-1}
    .dispen-create-field label,.student-entry-label{color:#475569;font-size:12px;font-weight:700}.dispen-create-control{width:100%;min-height:42px;padding:9px 11px;border:1px solid #cfd7e4;border-radius:8px;background:#fff;color:var(--ink);font:inherit;font-size:13px}.dispen-create-control:focus{border-color:var(--blue);outline:none;box-shadow:0 0 0 3px rgba(65,105,255,.12)}
    .dispen-create-control:disabled{background:#f1f4f8;color:#94a3b8;cursor:not-allowed}.dispen-create-alert{padding:12px 15px;border:1px solid #f2cccc;border-radius:9px;background:#fff2f2;color:#a33232;font-size:12px}.dispen-create-alert ul{margin:0;padding-left:19px}
    .waka-info-card{display:flex;min-height:42px;align-items:center;gap:9px;padding:10px 12px;border:1px solid;border-radius:8px;font-size:12px;line-height:1.45}.waka-info-card--success{border-color:#bbf7d0;background:#f0fdf4;color:#166534}.waka-info-card--warning{border-color:#fde68a;background:#fffbeb;color:#92400e}.waka-info-card--danger{border-color:#fecaca;background:#fef2f2;color:#991b1b}
    .student-entries{display:grid;gap:10px}.student-entry{display:grid;grid-template-columns:30px minmax(0,1fr) minmax(0,1fr) 36px;align-items:end;gap:9px;padding:10px;border:1px solid #e0e8f5;border-radius:13px;background:#fbfcff}.student-entry-number{display:grid;width:28px;height:28px;align-self:center;place-items:center;border-radius:50%;background:#3b82f6;color:#fff;font-size:12px;font-weight:800}.student-entry-field{display:grid;min-width:0;gap:6px}.student-entry-field .dispen-create-control{min-height:40px;background:#fff}.student-search{min-height:40px!important;font-size:12px!important}.student-combobox{position:relative}.student-options{position:absolute;z-index:20;top:calc(100% + 4px);left:0;right:0;max-height:220px;overflow:auto;padding:4px;border:1px solid #d8e1ef;border-radius:9px;background:#fff;box-shadow:0 10px 24px rgba(25,39,73,.14)}.student-option{display:block;width:100%;padding:9px 10px;border:0;border-radius:6px;background:#fff;color:#334155;text-align:left;font:inherit;font-size:12px;cursor:pointer}.student-option:hover,.student-option:focus{outline:none;background:#eff6ff}.student-no-results{padding:9px 10px;color:#94a3b8;font-size:11px}.student-remove{display:grid;width:36px;height:36px;place-items:center;border:1px solid #fecaca;border-radius:9px;background:#fff5f5;color:#ef4444;cursor:pointer}.student-remove:hover{background:#fee2e2}.student-add{display:flex;width:100%;min-height:43px;align-items:center;justify-content:center;gap:8px;margin-top:10px;border:1px solid #3b82f6;border-radius:10px;background:#eff6ff;color:#2563eb;font:inherit;font-size:12px;font-weight:800;cursor:pointer}.student-add:hover{background:#dbeafe}.student-help{margin:7px 0 0;color:var(--muted);font-size:11px}
    textarea.dispen-create-control{min-height:94px;resize:vertical}.dispen-create-actions{display:flex;justify-content:flex-end;gap:9px;padding-top:3px}.dispen-create-button{display:inline-flex;min-height:40px;align-items:center;justify-content:center;gap:7px;padding:8px 15px;border:1px solid transparent;border-radius:8px;font:inherit;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer}.dispen-create-button--secondary{border-color:#d4dbe6;background:#fff;color:#475569}.dispen-create-button--primary{background:var(--navy);color:#fff}.dispen-create-button--primary:hover{background:#252b5d}
    @media(max-width:640px){.dispen-create-panel{padding:16px}.dispen-create-grid{grid-template-columns:1fr}.dispen-create-field--wide,.dispen-create-actions{grid-column:auto}.student-entry{grid-template-columns:28px minmax(0,1fr) minmax(0,1fr) 36px;align-items:end;gap:8px;padding:9px}.student-entry-number{grid-row:1;grid-column:1;align-self:center}.student-entry-field--class{grid-row:1;grid-column:2}.student-entry-field--student{grid-row:1;grid-column:3}.student-remove{grid-row:1;grid-column:4;align-self:end}.student-entry-label{font-size:11px}.student-entry-field .dispen-create-control{min-width:0;padding-inline:8px;font-size:11px}.student-search{font-size:11px!important}.dispen-create-actions{display:grid;grid-template-columns:1fr 1.5fr}.dispen-create-button{padding-inline:9px}}
</style>
@endpush

@section('content')
@php($editing = $editing ?? false)
<main class="dispen-create-page">
    <header class="dispen-create-header"><h1>{{ $editing ? 'Edit' : 'Tambah' }} Dispen</h1><p>Ajukan dispensasi untuk satu atau beberapa siswa sekaligus. Petugas Waka ditentukan berdasarkan jadwal.</p></header>

    @if(session('error'))<div class="dispen-create-alert" role="alert">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="dispen-create-alert" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <section class="dispen-create-panel">
        <h2>Data siswa dispen</h2>
        <p class="dispen-create-panel-intro">Pilih kelas dan siswa untuk setiap baris. Anda dapat menambahkan siswa dari kelas berbeda.</p>
        <form action="{{ $editing ? route('piket.dispen.update', $dispen->id_dispen) : route('piket.dispen.store') }}" method="POST" class="dispen-create-grid">
            @csrf
            @if($editing) @method('PUT') @endif

            <div class="dispen-create-field dispen-create-field--wide">
                <div id="student-entries" class="student-entries"></div>
                <button type="button" id="add-student" class="student-add"><span class="material-symbols-outlined" aria-hidden="true">add</span>Tambah Siswa Dispen</button>
                <p class="student-help">Setiap siswa hanya dapat dicantumkan satu kali dalam pengajuan yang sama.</p>
            </div>

            <div class="dispen-create-field">
                <label for="dispen-date">Tanggal</label>
                <input type="date" id="dispen-date" name="tanggal" value="{{ old('tanggal', $dispen?->tanggal?->format('Y-m-d') ?? date('Y-m-d')) }}" class="dispen-create-control" required>
            </div>
            <div class="dispen-create-field">
                <label>Waka / Kesiswaan Bertugas</label>
                <div id="waka-status-box" class="waka-info-card waka-info-card--warning"><span id="waka-status-text">Memeriksa jadwal...</span></div>
            </div>
            <div class="dispen-create-field">
                <label for="start-period">Jam Mulai</label>
                <select name="id_jam_mulai" id="start-period" class="dispen-create-control" required>
                    <option value="">-- Pilih Jam Mulai --</option>
                    @foreach($jamPels as $jam)<option value="{{ $jam->id_jam }}" @selected(old('id_jam_mulai', $dispen->id_jam_mulai ?? '') == $jam->id_jam)>Jam ke-{{ $jam->jam_ke }} ({{ substr($jam->jam_mulai,0,5) }} - {{ substr($jam->jam_selesai,0,5) }})</option>@endforeach
                </select>
            </div>
            <div class="dispen-create-field">
                <label for="end-period">Jam Selesai</label>
                <select name="id_jam_selesai" id="end-period" class="dispen-create-control" required>
                    <option value="">-- Pilih Jam Selesai --</option>
                    @foreach($jamPels as $jam)<option value="{{ $jam->id_jam }}" @selected(old('id_jam_selesai', $dispen->id_jam_selesai ?? '') == $jam->id_jam)>Jam ke-{{ $jam->jam_ke }} ({{ substr($jam->jam_mulai,0,5) }} - {{ substr($jam->jam_selesai,0,5) }})</option>@endforeach
                </select>
            </div>
            <div class="dispen-create-field dispen-create-field--wide">
                <label for="dispen-reason">Alasan Dispen</label>
                <textarea id="dispen-reason" name="alasan" rows="3" maxlength="255" class="dispen-create-control" placeholder="Contoh: Mengikuti kegiatan lomba sekolah" required>{{ old('alasan', $dispen->alasan ?? '') }}</textarea>
            </div>
            <div class="dispen-create-actions">
                <a href="{{ route('piket.dispen.index') }}" class="dispen-create-button dispen-create-button--secondary">Batal</a>
                <button type="submit" class="dispen-create-button dispen-create-button--primary">{{ $editing ? 'Simpan Perubahan' : 'Simpan Dispen' }}</button>
            </div>
        </form>
    </section>
</main>

<template id="student-entry-template">
    <div class="student-entry" data-student-entry>
        <span class="student-entry-number" data-entry-number></span>
        <div class="student-entry-field student-entry-field--class">
            <label class="student-entry-label" data-class-label>Kelas</label>
            <select class="dispen-create-control" data-class-select required>
                <option value="">– Pilih Kelas –</option>
                @foreach($kelases as $kelas)<option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>@endforeach
            </select>
        </div>
        <div class="student-entry-field student-entry-field--student">
            <label class="student-entry-label" data-student-label>Siswa</label>
            <div class="student-combobox"><input type="search" class="dispen-create-control student-search" data-student-search placeholder="Pilih kelas terlebih dahulu" autocomplete="off" required disabled><input type="hidden" data-student-select><div class="student-options" data-student-options hidden></div></div>
        </div>
        <button type="button" class="student-remove" data-remove-student aria-label="Hapus siswa"><span class="material-symbols-outlined" aria-hidden="true">delete_outline</span></button>
    </div>
</template>

<script>
(() => {
    const studentsByClass = @json($siswaPerKelas);
    const wakaMap = @json($jadwalWakaMap);
    const initialRows = @json(old('students', $initialStudents ?? [['id_kelas' => '', 'id_siswa' => '']]));
    const entries = document.getElementById('student-entries');
    const template = document.getElementById('student-entry-template');
    const dateInput = document.getElementById('dispen-date');
    const wakaBox = document.getElementById('waka-status-box');
    const wakaText = document.getElementById('waka-status-text');
    let nextIndex = 0;

    function refreshNumbers() {
        entries.querySelectorAll('[data-student-entry]').forEach((row, index) => {
            row.querySelector('[data-entry-number]').textContent = index + 1;
            row.querySelector('[data-class-select]').setAttribute('aria-label', `Kelas siswa ${index + 1}`);
            row.querySelector('[data-student-search]').setAttribute('aria-label', `Nama siswa ${index + 1}`);
            row.querySelector('[data-remove-student]').disabled = entries.children.length === 1;
        });
    }

    function fillStudents(row, classId, selectedId = '') {
        const select = row.querySelector('[data-student-select]');
        const search = row.querySelector('[data-student-search]');
        const list = studentsByClass[classId] || [];
        const selected = list.find(student => String(student.id) === String(selectedId));
        select.value = selected ? selected.id : '';
        search.value = selected?.nama || '';
        search.disabled = !classId || !list.length;
        search.placeholder = classId ? (list.length ? 'Ketik nama siswa...' : 'Tidak ada siswa di kelas ini') : 'Pilih kelas terlebih dahulu';
        row.querySelector('[data-student-options]').hidden = true;
    }

    function filterStudents(row, open = true) {
        const query = row.querySelector('[data-student-search]').value.trim().toLocaleLowerCase('id');
        const classId = row.querySelector('[data-class-select]').value;
        const options = row.querySelector('[data-student-options]');
        const list = (studentsByClass[classId] || []).filter(student => {
            const label = `${student.nama} ${student.nis || ''}`.toLocaleLowerCase('id');
            return label.includes(query);
        });
        options.replaceChildren();
        if (!list.length) {
            const empty = document.createElement('div');
            empty.className = 'student-no-results';
            empty.textContent = query ? 'Siswa tidak ditemukan' : 'Tidak ada siswa di kelas ini';
            options.appendChild(empty);
        } else {
            list.forEach(student => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'student-option';
                button.textContent = student.nis ? `${student.nama} (${student.nis})` : student.nama;
                button.addEventListener('mousedown', event => event.preventDefault());
                button.addEventListener('click', () => {
                    row.querySelector('[data-student-select]').value = student.id;
                    row.querySelector('[data-student-search]').value = student.nama;
                    options.hidden = true;
                });
                options.appendChild(button);
            });
        }
        options.hidden = !open || row.querySelector('[data-student-search]').disabled;
    }

    function addRow(initial = {}, copiedClass = '') {
        const row = template.content.firstElementChild.cloneNode(true);
        const index = nextIndex++;
        const classSelect = row.querySelector('[data-class-select]');
        const studentSelect = row.querySelector('[data-student-select]');
        classSelect.name = `students[${index}][id_kelas]`;
        studentSelect.name = `students[${index}][id_siswa]`;
        const classId = String(initial.id_kelas || copiedClass || '');
        classSelect.value = classId;
        fillStudents(row, classId, initial.id_siswa || '');
        classSelect.addEventListener('change', () => { fillStudents(row, classSelect.value); filterStudents(row, false); });
        const search = row.querySelector('[data-student-search]');
        search.addEventListener('input', () => { studentSelect.value = ''; filterStudents(row); });
        search.addEventListener('focus', () => filterStudents(row));
        search.addEventListener('blur', () => { window.setTimeout(() => { row.querySelector('[data-student-options]').hidden = true; }, 120); });
        row.querySelector('[data-remove-student]').addEventListener('click', () => { row.remove(); refreshNumbers(); });
        entries.appendChild(row);
        refreshNumbers();
    }

    document.getElementById('add-student').addEventListener('click', () => {
        const lastClass = entries.querySelector('[data-student-entry]:last-child [data-class-select]')?.value || '';
        addRow({}, lastClass);
    });
    (Array.isArray(initialRows) && initialRows.length ? initialRows : [{}]).forEach(row => addRow(row));

    function updateWakaStatus() {
        const date = dateInput.value;
        wakaBox.className = 'waka-info-card';
        if (!date) {
            wakaBox.classList.add('waka-info-card--warning');
            wakaText.textContent = 'Pilih tanggal terlebih dahulu.';
            return;
        }
        const schedule = wakaMap[date];
        if (schedule?.no_hp) {
            wakaBox.classList.add('waka-info-card--success');
            wakaText.textContent = `Petugas: ${schedule.nama} (WA: ${schedule.no_hp})`;
        } else if (schedule) {
            wakaBox.classList.add('waka-info-card--warning');
            wakaText.textContent = `Petugas: ${schedule.nama} (Nomor WhatsApp belum tersedia)`;
        } else {
            wakaBox.classList.add('waka-info-card--danger');
            wakaText.textContent = 'Waka untuk tanggal ini belum ada di jadwal piket.';
        }
    }
    dateInput.addEventListener('change', updateWakaStatus);
    updateWakaStatus();
})();
</script>
@endsection
