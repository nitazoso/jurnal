@extends('layouts.piket')

@section('title', 'Edit Surat Sakit - Jurnify')
@section('page-title', 'Edit Surat Sakit')

@push('styles')
<style>
    .sick-edit-page{--edit-navy:#30366f;--edit-ink:#202747;--edit-muted:#778097;display:grid;gap:18px;margin:0 auto;max-width:980px;padding:clamp(16px,3vw,30px)}
    .sick-edit-heading{display:flex;align-items:flex-start;gap:14px}.sick-edit-back{display:grid;width:42px;height:42px;flex:0 0 42px;place-items:center;border:1px solid #e5e8f1;border-radius:12px;background:#fff;color:#525d8a;text-decoration:none;transition:.18s}.sick-edit-back:hover{border-color:#c9cdeb;background:#f6f7ff}.sick-edit-heading h1{margin:1px 0 5px;color:var(--edit-ink);font-size:clamp(22px,3vw,28px);font-weight:800;letter-spacing:-.035em}.sick-edit-heading p{margin:0;color:var(--edit-muted);font-size:13px;line-height:1.6}
    .sick-edit-card{overflow:hidden;border:1px solid #e7eaf2;border-radius:17px;background:#fff;box-shadow:0 10px 26px rgba(25,35,75,.05)}.sick-edit-card-head{padding:19px 22px;border-bottom:1px solid #edf0f5}.sick-edit-card-head strong{color:var(--edit-ink);font-size:14px}.sick-edit-card-head p{margin:4px 0 0;color:var(--edit-muted);font-size:11px}.sick-edit-form{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;padding:22px}.sick-edit-field{display:grid;align-content:start;gap:7px;min-width:0}.sick-edit-field-wide{grid-column:1/-1}.sick-edit-field label{color:#535c74;font-size:12px;font-weight:800}.sick-edit-control{width:100%;min-height:43px;border:1px solid #dfe3ec;border-radius:9px;background:#fff;padding:10px 12px;color:#30384f;font:inherit;font-size:13px;outline:none;transition:border-color .18s,box-shadow .18s}.sick-edit-control:focus{border-color:#8189cc;box-shadow:0 0 0 3px rgba(79,91,174,.1)}textarea.sick-edit-control{min-height:112px;resize:vertical}.sick-edit-help{color:#8a91a4;font-size:10px;line-height:1.5}.sick-edit-file{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}.sick-edit-current{display:inline-flex;align-items:center;gap:5px;color:#5964ae;font-size:11px;font-weight:800;text-decoration:none}.sick-edit-current:hover{text-decoration:underline}.sick-edit-error{padding:13px 16px;border:1px solid #f1d0d0;border-radius:11px;background:#fff4f4;color:#a23b3b;font-size:12px}.sick-edit-error ul{margin:0;padding-left:18px}.sick-edit-actions{display:flex;grid-column:1/-1;justify-content:flex-end;gap:9px;padding-top:4px}.sick-edit-button{display:inline-flex;min-height:42px;align-items:center;justify-content:center;gap:7px;padding:10px 15px;border:1px solid #e1e4ed;border-radius:9px;background:#fff;color:#5a6277;font:inherit;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;transition:.18s}.sick-edit-button:hover{background:#f7f8fc}.sick-edit-button-primary{border-color:var(--edit-navy);background:var(--edit-navy);color:#fff}.sick-edit-button-primary:hover{background:#252b5c}
    @media(max-width:600px){.sick-edit-page{gap:14px;padding:14px}.sick-edit-heading{gap:10px}.sick-edit-back{width:38px;height:38px;flex-basis:38px}.sick-edit-heading p{font-size:11px}.sick-edit-card{border-radius:13px}.sick-edit-card-head{padding:16px}.sick-edit-form{grid-template-columns:1fr;gap:15px;padding:16px}.sick-edit-field-wide,.sick-edit-actions{grid-column:auto}.sick-edit-actions{display:grid;grid-template-columns:1fr 1.4fr}.sick-edit-button{padding-inline:10px}}
</style>
@endpush

@section('content')
<div class="sick-edit-page">
    <header class="sick-edit-heading">
        <a href="{{ route('piket.izin-sakit.index') }}" class="sick-edit-back" aria-label="Kembali ke daftar"><span class="material-symbols-outlined">arrow_back</span></a>
        <div><h1>Edit surat sakit</h1><p>Perbarui identitas siswa, tanggal, alasan, atau lampiran surat.</p></div>
    </header>
    @if($errors->any())<div class="sick-edit-error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <section class="sick-edit-card">
        <div class="sick-edit-card-head"><strong>Informasi surat</strong><p>Pastikan data siswa dan tanggal surat sudah sesuai.</p></div>
        <form action="{{ route('piket.izin-sakit.sakit.update', $dispen->id_dispen) }}" method="POST" enctype="multipart/form-data" class="sick-edit-form">
            @csrf @method('PUT')
            <div class="sick-edit-field"><label for="class-select">Kelas</label><select id="class-select" name="id_kelas" required class="sick-edit-control"><option value="">Pilih kelas</option>@foreach($kelases as $kelas)<option value="{{ $kelas->id_kelas }}" @selected(old('id_kelas', $dispen->siswa?->id_kelas) == $kelas->id_kelas)>{{ $kelas->nama_kelas }}</option>@endforeach</select></div>
            <div class="sick-edit-field"><label for="student-select">Nama siswa</label><select id="student-select" name="id_siswa" required class="sick-edit-control"><option value="">Pilih siswa</option></select></div>
            <div class="sick-edit-field"><label for="tanggal">Tanggal surat</label><input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', $dispen->tanggal?->format('Y-m-d')) }}" required class="sick-edit-control"></div>
            <div class="sick-edit-field"><label for="surat">Lampiran surat <span style="font-weight:500;color:#969cad">(opsional)</span></label><input id="surat" name="surat" type="file" accept="image/*" class="sick-edit-control"><div class="sick-edit-file">@if($dispen->surat_path)<a class="sick-edit-current" href="{{ asset('storage/'.$dispen->surat_path) }}" target="_blank" rel="noopener"><span class="material-symbols-outlined" style="font-size:16px">open_in_new</span>Lihat lampiran saat ini</a>@else<span class="sick-edit-help">Belum ada lampiran.</span>@endif</div><span class="sick-edit-help">Format gambar, ukuran maksimal 5 MB. Kosongkan jika tidak mengganti.</span></div>
            <div class="sick-edit-field sick-edit-field-wide"><label for="alasan">Alasan / keterangan</label><textarea id="alasan" name="alasan" maxlength="255" required class="sick-edit-control">{{ old('alasan', $dispen->alasan) }}</textarea><span class="sick-edit-help">Maksimal 255 karakter.</span></div>
            <div class="sick-edit-actions"><a href="{{ route('piket.izin-sakit.index') }}" class="sick-edit-button">Batal</a><button type="submit" class="sick-edit-button sick-edit-button-primary"><span class="material-symbols-outlined" style="font-size:17px">save</span>Simpan perubahan</button></div>
        </form>
    </section>
</div>
<script>
    (() => {
        const studentsByClass = @json($siswaPerKelas);
        const classSelect = document.getElementById('class-select');
        const studentSelect = document.getElementById('student-select');
        const selectedStudent = @json((string) old('id_siswa', $dispen->id_siswa));
        function updateStudents() {
            const students = studentsByClass[classSelect.value] || [];
            studentSelect.innerHTML = '<option value="">Pilih siswa</option>';
            students.forEach((student) => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = student.nama + (student.nis ? ' (' + student.nis + ')' : '');
                if (String(student.id) === String(selectedStudent)) option.selected = true;
                studentSelect.appendChild(option);
            });
        }
        classSelect.addEventListener('change', updateStudents);
        updateStudents();
    })();
</script>
@endsection
