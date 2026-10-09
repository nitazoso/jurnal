@extends('layouts.admin')
@section('title', 'Impor Jadwal Piket - Jurnify')
@section('page-title', 'Impor Jadwal Piket')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
<div class="min-h-[calc(100vh-5rem)] bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl">
        <a href="{{ route('admin.jadwal-piket.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Jadwal Piket
        </a>

        @if($errors->any())
            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800" role="alert">
                <i class="fa-solid fa-circle-exclamation mt-0.5 text-rose-600"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="mt-5 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-indigo-950 via-indigo-800 to-violet-700 px-6 py-7 text-white sm:px-9 sm:py-9">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-xl ring-1 ring-white/20"><i class="fa-solid fa-calendar-days"></i></div>
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-indigo-200">Jurnify · Admin</p>
                        <h1 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl">Impor jadwal piket</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-indigo-100">Unggah PDF jadwal piket bulanan. Sistem membaca tanggal, petugas KBM pagi/siang, koordinator, dan piket Waka dari tabel.</p>
                    </div>
                </div>
            </div>

            <form id="piket-upload-form" action="{{ route('admin.jadwal-piket.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-5 sm:p-8">
                @csrf
                <input type="hidden" name="confirm_replace" value="1">

                <div>
                    <label for="piket-file" class="mb-2 block text-sm font-extrabold text-slate-800">File jadwal piket</label>
                    <label for="piket-file" id="piket-drop-zone" class="group flex cursor-pointer flex-col items-center rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/50 px-5 py-8 text-center transition hover:border-indigo-400 hover:bg-indigo-50 sm:px-8">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-2xl text-indigo-600 shadow-sm ring-1 ring-indigo-100"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                        <span class="mt-4 text-sm font-extrabold text-slate-800">Pilih PDF atau tarik file ke sini</span>
                        <span class="mt-1 text-xs text-slate-500">PDF hasil scan atau PDF teks · Maksimal 20 MB</span>
                        <span id="piket-file-name" class="mt-3 hidden rounded-full bg-white px-3 py-1 text-xs font-bold text-indigo-700 shadow-sm"></span>
                        <input id="piket-file" class="sr-only" type="file" name="file" accept=".pdf,application/pdf" required>
                    </label>
                    <p class="mt-2 text-xs leading-5 text-slate-500"><i class="fa-regular fa-circle-info mr-1 text-indigo-500"></i>PDF scan dibaca dengan Tesseract OCR di server. Nama guru harus cocok dengan data di master Guru.</p>
                </div>

                <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-950">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5 text-amber-600"></i>
                    <div>
                        <p class="text-sm font-extrabold">Jadwal piket pada bulan di PDF akan terganti</p>
                        <p class="mt-1 text-xs leading-5 text-amber-800">Jadwal bulan lain tidak berubah. Jika ada tanggal, nama guru, atau kolom tugas yang tidak terbaca dengan yakin, impor dibatalkan dan jadwal lama tetap aman.</p>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.jadwal-piket.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-600 hover:bg-slate-50">Batal</a>
                    <button id="piket-submit" type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 text-sm font-extrabold text-white shadow-sm hover:bg-indigo-700">
                        <i class="fa-solid fa-file-import"></i> Baca dan impor jadwal
                    </button>
                </div>
            </form>

            <div id="piket-confirm-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-slate-950/55 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="piket-confirm-title" aria-hidden="true">
                <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-900/10">
                    <div class="p-6 sm:p-7">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-xl text-amber-700"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <h2 id="piket-confirm-title" class="mt-4 text-xl font-extrabold text-slate-900">Ganti jadwal piket bulan ini?</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Jadwal piket pada bulan yang terbaca dari PDF akan diganti. Data bulan lain tetap tersimpan. Jika pembacaan gagal, tidak ada jadwal yang dihapus.</p>
                    </div>
                    <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/70 p-4 sm:flex-row sm:justify-end sm:px-6">
                        <button id="piket-confirm-cancel" type="button" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                        <button id="piket-confirm-continue" type="button" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 text-sm font-extrabold text-white hover:bg-rose-700"><i class="fa-solid fa-arrows-rotate text-xs"></i> Ya, ganti bulan itu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const piketFile = document.getElementById('piket-file');
const piketFileName = document.getElementById('piket-file-name');
const piketDropZone = document.getElementById('piket-drop-zone');
const piketForm = document.getElementById('piket-upload-form');
const piketModal = document.getElementById('piket-confirm-modal');
const piketContinue = document.getElementById('piket-confirm-continue');
let piketConfirmed = false;

function updatePiketFileName() {
    const file = piketFile.files[0];
    piketFileName.textContent = file ? file.name : '';
    piketFileName.classList.toggle('hidden', !file);
}

piketFile.addEventListener('change', updatePiketFileName);
piketDropZone.addEventListener('dragover', event => {
    event.preventDefault();
    piketDropZone.classList.add('border-indigo-500', 'bg-indigo-100');
});
piketDropZone.addEventListener('dragleave', () => piketDropZone.classList.remove('border-indigo-500', 'bg-indigo-100'));
piketDropZone.addEventListener('drop', event => {
    event.preventDefault();
    piketDropZone.classList.remove('border-indigo-500', 'bg-indigo-100');
    const file = event.dataTransfer.files[0];
    if (!file || (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf'))) return;
    const transfer = new DataTransfer();
    transfer.items.add(file);
    piketFile.files = transfer.files;
    updatePiketFileName();
});

piketForm.addEventListener('submit', event => {
    if (!piketConfirmed) {
        event.preventDefault();
        piketModal.classList.remove('hidden');
        piketModal.classList.add('flex');
        piketModal.setAttribute('aria-hidden', 'false');
        return;
    }
    const submit = document.getElementById('piket-submit');
    submit.disabled = true;
    submit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Membaca PDF…';
});

function closePiketModal() {
    piketModal.classList.add('hidden');
    piketModal.classList.remove('flex');
    piketModal.setAttribute('aria-hidden', 'true');
}
document.getElementById('piket-confirm-cancel').addEventListener('click', closePiketModal);
piketModal.addEventListener('click', event => { if (event.target === piketModal) closePiketModal(); });
document.addEventListener('keydown', event => { if (event.key === 'Escape') closePiketModal(); });
piketContinue.addEventListener('click', () => {
    piketConfirmed = true;
    closePiketModal();
    piketForm.requestSubmit();
});
</script>
@endsection
