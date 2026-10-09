@extends('layouts.admin')
@section('title', 'Impor Jadwal - Jurnify')
@section('page-title', 'Impor Jadwal')
@section('content')
<div class="mx-auto max-w-4xl p-4 sm:p-8">
    <a href="{{ route('admin.jadwal.index') }}" class="text-sm font-semibold text-indigo-600">← Kembali ke jadwal</a>
    <div class="mt-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-6"><p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Jurnify · Admin</p><h1 class="mt-2 text-2xl font-bold text-slate-800">Impor jadwal pelajaran</h1><p class="mt-2 text-sm text-slate-500">Unggah file Excel, CSV, atau PDF aSc. Jadwal kisi aSc akan dibaca per kelas, hari, dan rentang jam, lalu ditampilkan untuk diperiksa sebelum disimpan.</p></div>
        @if($errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{{ $errors->first() }}</div>@endif
        <form action="{{ route('admin.jadwal.import.preview-upload') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <label class="block rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/40 p-8 text-center"><span class="text-sm font-semibold text-slate-700">Pilih file jadwal (.xlsx, .xls, .csv, .pdf)</span><input class="mt-4 block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:font-semibold file:text-white" type="file" name="file" accept=".xlsx,.xls,.csv,.pdf" required><span class="mt-2 block text-xs text-slate-500">Maksimal 10 MB. PDF harus berisi teks yang bisa disalin, bukan hasil scan. Untuk PDF aSc, sistem membaca blok kisi satu kelas per halaman.</span></label>
            <div class="grid gap-4 sm:grid-cols-2"><label class="text-sm font-medium text-slate-700">Semester<select name="semester" class="mt-2 w-full rounded-xl border-slate-200" required><option>Ganjil</option><option>Genap</option></select></label><label class="text-sm font-medium text-slate-700">Tahun ajaran<input name="tahun_ajaran" value="{{ old('tahun_ajaran', now()->year.'/'.(now()->year + 1)) }}" placeholder="2026/2027" class="mt-2 w-full rounded-xl border-slate-200" required></label></div>
            <button class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-indigo-700">Baca file dan lanjutkan</button>
        </form>
    </div>
</div>
@endsection
