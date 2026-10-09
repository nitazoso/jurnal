@extends('layouts.piket')

@php($editing = isset($dispen))
@php($storedDuration = $editing && $dispen->tanggal_selesai ? $dispen->tanggal->diffInDays($dispen->tanggal_selesai) + 1 : 1)
@php($selectedJenis = old('jenis', $dispen->jenis ?? $jenisDefault ?? 'izin'))
@php($isSickReport = $selectedJenis === 'sakit')
@section('title', ($editing ? 'Edit' : 'Tambah') . ' Izin & Sakit - Jurnify')
@section('page-title', ($editing ? 'Edit' : 'Tambah') . ' Izin & Sakit')

@push('styles')
<style>
    .report-page{--report-navy:#30366f;--report-ink:#202747;--report-muted:#778097;display:grid;gap:18px;margin:0 auto;max-width:1000px;padding:clamp(16px,3vw,30px)}
    .report-heading{display:flex;align-items:flex-start;gap:14px}.report-back{display:grid;width:42px;height:42px;flex:0 0 42px;place-items:center;border:1px solid #e5e8f1;border-radius:12px;background:#fff;color:#525d8a;text-decoration:none}.report-back:hover{background:#f6f7ff}.report-heading h1{margin:1px 0 5px;color:var(--report-ink);font-size:clamp(23px,3vw,29px);font-weight:800;letter-spacing:-.035em}.report-heading p{margin:0;color:var(--report-muted);font-size:13px;line-height:1.6}
    .report-info{display:flex;align-items:flex-start;gap:11px;padding:13px 15px;border:1px solid #dfe7ff;border-radius:11px;background:#f6f8ff;color:#505d99;font-size:12px;line-height:1.6}.report-info .material-symbols-outlined{font-size:20px}
    .report-card{overflow:hidden;border:1px solid #e7eaf2;border-radius:16px;background:#fff;box-shadow:0 10px 26px rgba(25,35,75,.05)}.report-card-head{padding:19px 22px;border-bottom:1px solid #edf0f5}.report-card-head strong{color:var(--report-ink);font-size:14px}.report-card-head p{margin:4px 0 0;color:var(--report-muted);font-size:11px}.report-form{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:17px;padding:22px}.report-field{display:grid;align-content:start;gap:7px;min-width:0}.report-field[hidden]{display:none}.report-field-wide{grid-column:1/-1}.report-field label{color:#535c74;font-size:12px;font-weight:800}.report-control{width:100%;min-height:43px;border:1px solid #dfe3ec;border-radius:9px;background:#fff;padding:10px 12px;color:#30384f;font:inherit;font-size:13px;outline:none;transition:border-color .18s,box-shadow .18s}.report-control:focus{border-color:#8189cc;box-shadow:0 0 0 3px rgba(79,91,174,.1)}.report-control:disabled{background:#f4f5f8;color:#9aa0ad;cursor:not-allowed}.report-control textarea,textarea.report-control{min-height:106px;resize:vertical}.report-help{color:#8a91a4;font-size:10px;line-height:1.5}.report-error{padding:12px 15px;border:1px solid #f1d0d0;border-radius:11px;background:#fff4f4;color:#a23b3b;font-size:12px}.report-error ul{margin:0;padding-left:18px}.report-actions{display:flex;grid-column:1/-1;justify-content:flex-end;gap:9px;padding-top:3px}.report-button{display:inline-flex;min-height:42px;align-items:center;justify-content:center;gap:7px;padding:10px 15px;border:1px solid #e1e4ed;border-radius:9px;background:#fff;color:#5a6277;font:inherit;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;transition:.18s}.report-button:hover{background:#f7f8fc}.report-button-primary{border-color:var(--report-navy);background:var(--report-navy);color:#fff}.report-button-primary:hover{background:#252b5c}
    @media(max-width:600px){.report-page{gap:14px;padding:14px}.report-heading{gap:10px}.report-back{width:38px;height:38px;flex-basis:38px}.report-heading p{font-size:11px}.report-card{border-radius:13px}.report-card-head{padding:16px}.report-form{grid-template-columns:1fr;gap:15px;padding:16px}.report-field-wide,.report-actions{grid-column:auto}.report-actions{display:grid;grid-template-columns:1fr 1.5fr}.report-button{padding-inline:10px}}
</style>
@endpush

@section('content')
<div class="report-page">
    <header class="report-heading">
        <a href="{{ route('piket.izin-sakit.index') }}" class="report-back" aria-label="Kembali"><span class="material-symbols-outlined">arrow_back</span></a>
        <div><h1>{{ $editing ? 'Edit' : 'Tambah' }} Izin &amp; Sakit</h1><p>Catat surat siswa satu kali untuk seluruh masa berlaku dan sinkronkan ke jurnal guru secara otomatis.</p></div>
    </header>
    <div class="report-info"><span class="material-symbols-outlined">sync</span><span>Status <strong>Izin</strong> atau <strong>Sakit</strong> akan terisi otomatis pada jurnal setiap hari dalam masa berlaku. Surat sakit biasa berlaku 1 hari dan surat dokter 3 hari.</span></div>
    @if($errors->any())<div class="report-error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <section class="report-card">
        <div class="report-card-head"><strong>Data ketidakhadiran</strong><p>Pilih kelas untuk memuat daftar siswa.</p></div>
        <form action="{{ $editing ? route('piket.izin-sakit.update', $dispen->id_dispen) : route('piket.izin-sakit.store') }}" method="POST" enctype="multipart/form-data" class="report-form">
            @csrf
            @if($editing) @method('PUT') @endif
            <div class="report-field"><label for="report-type">Jenis surat</label><select class="report-control" id="report-type" name="jenis" required><option value="izin" @selected($selectedJenis === 'izin')>Izin</option><option value="sakit" @selected($selectedJenis === 'sakit')>Sakit</option></select></div>
            <div class="report-field"><label for="report-date">Tanggal mulai</label><input class="report-control" id="report-date" name="tanggal" type="date" value="{{ old('tanggal', $dispen?->tanggal?->format('Y-m-d') ?? today()->toDateString()) }}" required></div>
            <div class="report-field" id="report-duration-field" @if($isSickReport) hidden @endif><label for="report-duration">Lama izin (hari)</label><input class="report-control" id="report-duration" name="durasi_hari" type="number" min="1" max="365" step="1" value="{{ old('durasi_hari', $storedDuration) }}" @if($isSickReport) disabled @else required @endif><span class="report-help" id="report-duration-help">Pilih jumlah hari izin, maksimal 365 hari.</span><span class="report-help" id="report-end-date"></span></div>
            <div class="report-field" id="report-sick-type-field" @if(!$isSickReport) hidden @endif><label for="report-sick-type">Jenis surat sakit</label><select class="report-control" id="report-sick-type" name="jenis_surat_sakit" @if(!$isSickReport) disabled @else required @endif><option value="biasa" @selected(old('jenis_surat_sakit', $dispen->jenis_surat_sakit ?? 'biasa') === 'biasa')>Surat sakit biasa · 1 hari</option><option value="dokter" @selected(old('jenis_surat_sakit', $dispen->jenis_surat_sakit ?? 'biasa') === 'dokter')>Surat dokter · 3 hari</option></select></div>
            <div class="report-field"><label for="report-class">Kelas</label><select class="report-control" id="report-class" name="id_kelas" required><option value="">Pilih kelas</option>@foreach($kelases as $kelas)<option value="{{ $kelas->id_kelas }}" @selected(old('id_kelas', $dispen?->siswa?->id_kelas) == $kelas->id_kelas)>{{ $kelas->nama_kelas }}</option>@endforeach</select></div>
            <div class="report-field"><label for="report-student">Nama siswa</label><select class="report-control" id="report-student" name="id_siswa" required disabled><option value="">Pilih kelas terlebih dahulu</option></select></div>
            <div class="report-field report-field-wide"><label for="report-reason">Keterangan</label><textarea class="report-control" id="report-reason" name="alasan" maxlength="255" required placeholder="Contoh: izin mengikuti acara keluarga">{{ old('alasan', $dispen->alasan ?? '') }}</textarea><span class="report-help">Maksimal 255 karakter.</span></div>
            <div class="report-field report-field-wide"><label for="report-letter">Foto surat <span style="font-weight:500;color:#969cad">(opsional)</span></label><input class="report-control" id="report-letter" name="surat" type="file" accept="image/*"><span class="report-help">Format gambar, ukuran maksimal 5 MB. Lampiran dapat dilihat guru dari form jurnal.</span>@if($editing && $dispen->surat_path)<a class="report-help" style="color:#2457cb" href="{{ asset('storage/'.$dispen->surat_path) }}" target="_blank" rel="noopener">Lihat lampiran yang tersimpan</a>@endif</div>
            <div class="report-actions"><a href="{{ route('piket.izin-sakit.index') }}" class="report-button">Batal</a><button type="submit" class="report-button report-button-primary"><span class="material-symbols-outlined" style="font-size:17px">{{ $editing ? 'save' : 'sync' }}</span>{{ $editing ? 'Simpan perubahan' : 'Simpan & sinkronkan' }}</button></div>
        </form>
    </section>
</div>
<script>
    (() => {
        const studentsByClass = @json($siswaPerKelas);
        const classSelect = document.getElementById('report-class');
        const studentSelect = document.getElementById('report-student');
        const selectedStudent = @json((string) old('id_siswa', $dispen->id_siswa ?? ''));
        const reportType = document.getElementById('report-type');
        const durationField = document.getElementById('report-duration-field');
        const sickTypeField = document.getElementById('report-sick-type-field');
        const sickType = document.getElementById('report-sick-type');
        const duration = document.getElementById('report-duration');
        const durationHelp = document.getElementById('report-duration-help');
        const endDate = document.getElementById('report-end-date');
        function updateStudents() {
            const students = studentsByClass[classSelect.value] || [];
            studentSelect.replaceChildren(new Option(students.length ? 'Pilih siswa' : 'Tidak ada siswa di kelas ini', ''));
            students.forEach((student) => {
                const option = new Option(student.nis ? `${student.nama} (${student.nis})` : student.nama, student.id);
                if (String(student.id) === String(selectedStudent)) option.selected = true;
                studentSelect.add(option);
            });
            studentSelect.disabled = students.length === 0;
        }
        function updateValidityControls() {
            const isSick = reportType.value === 'sakit';
            durationField.hidden = isSick;
            duration.disabled = isSick;
            duration.required = !isSick;
            sickTypeField.hidden = !isSick;
            sickType.disabled = !isSick;
            sickType.required = isSick;
            const validityDays = isSick ? (sickType.value === 'dokter' ? 3 : 1) : Math.max(1, Number(duration.value) || 1);
            const start = document.getElementById('report-date').value;
            if (start) {
                const end = new Date(`${start}T00:00:00`);
                end.setDate(end.getDate() + validityDays - 1);
                endDate.textContent = `Berlaku sampai ${end.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}.`;
            } else {
                endDate.textContent = '';
            }
            durationHelp.textContent = 'Pilih jumlah hari izin, maksimal 365 hari.';
        }
        classSelect.addEventListener('change', updateStudents);
        reportType.addEventListener('change', updateValidityControls);
        sickType.addEventListener('change', updateValidityControls);
        duration.addEventListener('input', updateValidityControls);
        document.getElementById('report-date').addEventListener('change', updateValidityControls);
        updateStudents();
        updateValidityControls();
    })();
</script>
@endsection
