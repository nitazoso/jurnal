@extends('layouts.admin')
@section('title', 'Preview Impor Jadwal - Jurnify')
@section('page-title', 'Preview Impor Jadwal')
@section('content')
<div class="p-4 sm:p-8">
    <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-end"><div><a href="{{ route('admin.jadwal.import') }}" class="text-sm font-semibold text-indigo-600">← Unggah file lain</a><h1 class="mt-3 text-2xl font-bold text-slate-800">Periksa hasil pembacaan</h1><p class="mt-1 text-sm text-slate-500">{{ count($rows) }} baris ditemukan. Perbaiki kolom yang belum sesuai, lalu simpan data valid.</p></div></div>
    @if($errors->any())<div class="mb-4 rounded-xl bg-rose-50 p-4 text-sm text-rose-700">{{ $errors->first() }}</div>@endif
    <form id="jadwalImportForm" method="POST" action="{{ route('admin.jadwal.import.store', $token) }}">@csrf<input type="hidden" name="rows_json" id="jadwalImportRows">
        <div class="mb-3 rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-xs text-indigo-800">Format aSc: kelas dibaca dari judul halaman, hari dari baris, dan rentang jam dari lebar blok pada kisi. Ruang yang terbaca ditampilkan di teks sumber; tabel jadwal saat ini tidak menyimpan kolom ruang. Periksa pilihan kosong dan lengkapi data master kelas, guru, atau mapel jika belum tersedia. Hapus centang pada baris yang tidak ingin diimpor.</div>
        <label class="mb-4 flex items-start gap-3 rounded-xl border border-rose-300 bg-rose-50 p-4 text-sm text-rose-900">
            <input type="checkbox" name="confirm_replace" value="1" required class="mt-1 rounded border-rose-400">
            <span><strong>Ganti SEMUA jadwal aktif</strong> di semua kelas, semester, dan tahun ajaran. Ada {{ $existingCount }} jadwal aktif yang akan digantikan. Jadwal lama akan hilang dari daftar jadwal, tetapi riwayat jurnal tetap tersimpan. Jika validasi gagal, jadwal lama tidak akan diubah.</span>
        </label>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm"><table class="min-w-[1200px] w-full text-left text-xs"><thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500"><tr><th class="p-3">Impor</th><th class="p-3"># / Teks terbaca</th><th class="p-3">Kelas</th><th class="p-3">Hari</th><th class="p-3">Guru</th><th class="p-3">Mata pelajaran</th><th class="p-3">Jam mulai</th><th class="p-3">Jam selesai</th><th class="p-3">Semester / tahun</th></tr></thead><tbody class="divide-y divide-slate-100">
        @foreach($rows as $i => $row)
            <tr data-row="{{ $i }}"><td class="p-3"><input type="checkbox" data-field="include" @checked($row['include'] ?? 1) aria-label="Impor baris {{ $i + 1 }}"></td><td class="p-3 align-top"><span class="font-bold text-slate-700">{{ $i + 1 }}</span><div class="mt-1 max-w-44 text-slate-400">{{ $row['kelas_text'] }} · {{ $row['hari'] }} · {{ $row['jam_text'] }}<br>{{ $row['ruang_text'] ?? '' }}<br>{{ $row['guru_text'] }}<br>{{ $row['mapel_text'] }}</div></td>
            <td class="p-3"><select data-field="id_kelas" class="w-36 rounded-lg border-slate-200 text-xs"><option value="">Pilih kelas</option>@foreach($kelases as $item)<option value="{{ $item->id_kelas }}" @selected(($row['id_kelas'] ?? null) == $item->id_kelas)>{{ $item->nama_kelas }}</option>@endforeach</select></td>
            <td class="p-3"><select data-field="hari" class="w-28 rounded-lg border-slate-200 text-xs"><option value="">Pilih hari</option>@foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $day)<option value="{{ $day }}" @selected(($row['hari'] ?? '') === $day)>{{ $day }}</option>@endforeach</select></td>
            <td class="p-3"><select data-field="id_guru" class="w-44 rounded-lg border-slate-200 text-xs"><option value="">Pilih guru</option>@foreach($gurus as $item)<option value="{{ $item->id_guru }}" @selected(($row['id_guru'] ?? null) == $item->id_guru)>{{ $item->nama_guru }}</option>@endforeach</select></td>
            <td class="p-3"><select data-field="id_mapel" class="w-40 rounded-lg border-slate-200 text-xs"><option value="">Pilih mapel</option>@foreach($mapels as $item)<option value="{{ $item->id_mapel }}" @selected(($row['id_mapel'] ?? null) == $item->id_mapel)>{{ $item->nama_mapel }}</option>@endforeach</select></td>
            @foreach(['id_jam_mulai','id_jam_selesai'] as $field)<td class="p-3"><select data-field="{{ $field }}" class="w-36 rounded-lg border-slate-200 text-xs"><option value="">Pilih jam</option>@foreach($jamPels as $item)<option value="{{ $item->id_jam }}" @selected(($row[$field] ?? null) == $item->id_jam)>Jam {{ $item->jam_ke }} · {{ substr($item->jam_mulai,0,5) }}–{{ substr($item->jam_selesai,0,5) }}</option>@endforeach</select></td>@endforeach
            <td class="p-3"><select data-field="semester" class="mb-2 w-24 rounded-lg border-slate-200 text-xs"><option @selected($row['semester']==='Ganjil')>Ganjil</option><option @selected($row['semester']==='Genap')>Genap</option></select><input data-field="tahun_ajaran" value="{{ $row['tahun_ajaran'] }}" class="w-24 rounded-lg border-slate-200 text-xs" required></td></tr>
        @endforeach
        </tbody></table></div>
        <div class="mt-5 flex flex-wrap gap-3"><button class="rounded-xl bg-rose-600 px-5 py-3 text-sm font-bold text-white">Validasi dan ganti semua jadwal</button><a href="{{ route('admin.jadwal.import') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600">Batalkan</a></div>
    </form>
</div>
<script>
document.getElementById('jadwalImportForm').addEventListener('submit', function (event) {
    if (!this.elements.confirm_replace.checked || !window.confirm('SEMUA jadwal lama di semua periode akan diganti. Riwayat jurnal tetap disimpan. Lanjutkan?')) {
        event.preventDefault();
        return;
    }
    const rows = Array.from(this.querySelectorAll('tr[data-row]')).map((tr) => {
        const row = {};
        tr.querySelectorAll('[data-field]').forEach((field) => {
            row[field.dataset.field] = field.type === 'checkbox' ? (field.checked ? '1' : '0') : field.value;
        });
        return row;
    });
    document.getElementById('jadwalImportRows').value = JSON.stringify(rows);
});
</script>
@endsection
