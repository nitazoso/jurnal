@extends('layouts.admin')
@section('title', 'Impor Jadwal - Jurnify')
@section('page-title', 'Impor Jadwal')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="min-h-[calc(100vh-5rem)] bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke jadwal
        </a>

        @if($errors->any())
            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-exclamation mt-0.5 text-rose-600"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="mt-5 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-indigo-950 via-indigo-800 to-violet-700 px-6 py-7 text-white sm:px-9 sm:py-9">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-xl ring-1 ring-white/20">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-indigo-200">Jurnify · Admin</p>
                        <h1 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl">Impor jadwal pelajaran</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-indigo-100">Unggah jadwal sekolah yang sudah ada. Sistem membaca file, memvalidasi data, lalu langsung mengganti jadwal aktif jika seluruh data valid.</p>
                    </div>
                </div>
            </div>

            <form id="jadwal-upload-form" action="{{ route('admin.jadwal.import.preview-upload') }}" method="POST" enctype="multipart/form-data" class="space-y-7 p-5 sm:p-8">
                @csrf
                <input type="hidden" name="confirm_replace" value="1">

                <div>
                    <label for="jadwal-file" class="mb-2 block text-sm font-bold text-slate-800">File jadwal</label>
                    <label for="jadwal-file" class="group flex cursor-pointer flex-col items-center rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/50 px-5 py-8 text-center transition hover:border-indigo-400 hover:bg-indigo-50 sm:px-8">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-2xl text-indigo-600 shadow-sm ring-1 ring-indigo-100 transition group-hover:scale-105">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </span>
                        <span class="mt-4 text-sm font-bold text-slate-800">Klik untuk memilih file atau tarik file ke sini</span>
                        <span class="mt-1 text-xs text-slate-500">Format Excel, CSV, atau PDF aSc · Maksimal 10 MB</span>
                        <span id="jadwal-file-name" class="mt-3 hidden rounded-full bg-white px-3 py-1 text-xs font-semibold text-indigo-700 shadow-sm"></span>
                        <input id="jadwal-file" class="sr-only" type="file" name="file" accept=".xlsx,.xls,.csv,.pdf" required>
                    </label>
                    <p class="mt-2 text-xs leading-5 text-slate-500"><i class="fa-regular fa-circle-question mr-1 text-indigo-500"></i>PDF harus berisi teks yang dapat disalin, bukan hasil scan. PDF aSc dibaca berdasarkan kisi jadwal tiap kelas.</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block text-sm font-bold text-slate-700">
                        Semester
                        <span class="relative mt-2 block">
                            <select name="semester" class="w-full appearance-none rounded-xl border border-slate-200 bg-white px-4 py-3 pr-10 text-sm font-medium text-slate-800 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100" required>
                                <option value="Ganjil" @selected(old('semester', 'Ganjil') === 'Ganjil')>Ganjil</option>
                                <option value="Genap" @selected(old('semester') === 'Genap')>Genap</option>
                            </select>
                            <i class="fa-solid fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                        </span>
                    </label>
                    <label class="block text-sm font-bold text-slate-700">
                        Tahun ajaran
                        <input name="tahun_ajaran" value="{{ old('tahun_ajaran', now()->year.'/'.(now()->year + 1)) }}" placeholder="2026/2027" pattern="\d{4}/\d{4}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100" required>
                        <span class="mt-1 block text-xs font-normal text-slate-400">Contoh: 2026/2027</span>
                    </label>
                </div>

                <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-950">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5 text-amber-600"></i>
                    <div>
                        <p class="text-sm font-extrabold">Jadwal sebelumnya akan terganti</p>
                        <p class="mt-1 text-xs leading-5 text-amber-800">Semua jadwal aktif akan diganti dengan jadwal dari file ini. Riwayat jurnal tetap tersimpan. Jika file tidak terbaca atau ada data yang tidak valid, jadwal sebelumnya tidak akan diubah.</p>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.jadwal.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Batal</a>
                    <button id="jadwal-submit-button" type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 text-sm font-extrabold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">
                        <i class="fa-solid fa-arrows-rotate"></i> Impor dan ganti jadwal
                    </button>
                </div>
            </form>

            <div id="jadwal-confirm-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-slate-950/55 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="jadwal-confirm-title" aria-hidden="true">
                <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-900/10">
                    <div class="p-6 sm:p-7">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-xl text-amber-700">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h2 id="jadwal-confirm-title" class="mt-4 text-xl font-extrabold text-slate-900">Ganti jadwal sebelumnya?</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Semua jadwal aktif akan diganti dengan isi file yang dipilih. Riwayat jurnal tetap tersimpan. Jika validasi gagal, jadwal lama tidak akan diubah.</p>
                    </div>
                    <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/70 p-4 sm:flex-row sm:justify-end sm:px-6">
                        <button id="jadwal-confirm-cancel" type="button" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 transition hover:bg-slate-100">Batal</button>
                        <button id="jadwal-confirm-continue" type="button" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 text-sm font-extrabold text-white shadow-sm transition hover:bg-rose-700">
                            <i class="fa-solid fa-arrows-rotate text-xs"></i> Ya, ganti jadwal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const jadwalFileInput = document.getElementById('jadwal-file');
const jadwalFileName = document.getElementById('jadwal-file-name');
const jadwalUploadForm = document.getElementById('jadwal-upload-form');
const jadwalSubmitButton = document.getElementById('jadwal-submit-button');
const jadwalDropZone = jadwalFileInput.closest('label');
const jadwalConfirmModal = document.getElementById('jadwal-confirm-modal');
const jadwalConfirmCancel = document.getElementById('jadwal-confirm-cancel');
const jadwalConfirmContinue = document.getElementById('jadwal-confirm-continue');
let jadwalImportConfirmed = false;

const showJadwalFileName = () => {
    const file = jadwalFileInput.files[0];
    jadwalFileName.textContent = file ? file.name : '';
    jadwalFileName.classList.toggle('hidden', !file);
};

jadwalFileInput.addEventListener('change', () => {
    showJadwalFileName();
});

jadwalDropZone.addEventListener('dragover', (event) => {
    event.preventDefault();
    jadwalDropZone.classList.add('border-indigo-500', 'bg-indigo-100');
});

jadwalDropZone.addEventListener('dragleave', () => {
    jadwalDropZone.classList.remove('border-indigo-500', 'bg-indigo-100');
});

jadwalDropZone.addEventListener('drop', (event) => {
    event.preventDefault();
    jadwalDropZone.classList.remove('border-indigo-500', 'bg-indigo-100');
    const file = event.dataTransfer.files[0];
    if (!file) return;

    const transfer = new DataTransfer();
    transfer.items.add(file);
    jadwalFileInput.files = transfer.files;
    showJadwalFileName();
});

jadwalUploadForm.addEventListener('submit', (event) => {
    if (!jadwalImportConfirmed) {
        event.preventDefault();
        jadwalConfirmModal.classList.remove('hidden');
        jadwalConfirmModal.classList.add('flex');
        jadwalConfirmModal.setAttribute('aria-hidden', 'false');
        return;
    }

    jadwalSubmitButton.disabled = true;
    jadwalSubmitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Membaca dan menyimpan…';
    jadwalSubmitButton.classList.add('cursor-wait', 'opacity-75');
});

const closeJadwalConfirm = () => {
    jadwalConfirmModal.classList.add('hidden');
    jadwalConfirmModal.classList.remove('flex');
    jadwalConfirmModal.setAttribute('aria-hidden', 'true');
};

jadwalConfirmCancel.addEventListener('click', closeJadwalConfirm);
jadwalConfirmModal.addEventListener('click', (event) => {
    if (event.target === jadwalConfirmModal) closeJadwalConfirm();
});
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !jadwalConfirmModal.classList.contains('hidden')) closeJadwalConfirm();
});
jadwalConfirmContinue.addEventListener('click', () => {
    jadwalImportConfirmed = true;
    closeJadwalConfirm();
    jadwalUploadForm.requestSubmit();
});
</script>
@endsection
