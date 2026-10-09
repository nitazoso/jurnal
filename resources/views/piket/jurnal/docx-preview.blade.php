@extends('layouts.piket')

@section('title', 'Preview Rekap DOCX - Jurnify')
@section('page-title', 'Preview Rekap Aktivitas')

@section('content')
<div class="mx-auto w-full max-w-7xl space-y-5 px-3 py-4 sm:space-y-6 sm:px-6 sm:py-6">
    <header class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white p-4 sm:rounded-3xl sm:p-7">
        <a href="{{ route('piket.jurnal.rekap') }}" class="inline-flex items-center gap-1 text-sm font-bold text-indigo-700 transition hover:text-indigo-900"><span aria-hidden="true">←</span> Kembali ke Rekap</a>
        <h1 class="mt-4 text-xl font-extrabold text-slate-900 sm:text-2xl">Preview Rekap Aktivitas</h1>
        <p class="mt-1 break-words text-sm text-slate-600 sm:text-base">{{ $judul }} <span class="hidden sm:inline">·</span><br class="sm:hidden">{{ $mulai->format('d M Y') }} sampai {{ $sampai->format('d M Y') }}</p>
        <div class="mt-4 grid grid-cols-1 gap-2 min-[420px]:grid-cols-3 sm:mt-5 sm:gap-3">
            <div class="rounded-xl border border-slate-100 bg-white p-3 sm:p-4"><span class="block text-xl font-extrabold text-indigo-700">{{ $jurnals->count() }}</span><span class="text-xs font-semibold text-slate-500 sm:text-sm">Jurnal</span></div>
            <div class="rounded-xl border border-slate-100 bg-white p-3 sm:p-4"><span class="block text-xl font-extrabold text-emerald-700">{{ $totalHadir }}</span><span class="text-xs font-semibold text-slate-500 sm:text-sm">Catatan hadir</span></div>
            <div class="rounded-xl border border-slate-100 bg-white p-3 sm:p-4"><span class="block text-xl font-extrabold text-amber-700">{{ $totalTidakHadir }}</span><span class="text-xs font-semibold text-slate-500 sm:text-sm">Catatan tidak hadir</span></div>
        </div>
    </header>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm sm:rounded-3xl">
        <div class="flex flex-col gap-3 border-b border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <div><h2 class="font-extrabold text-slate-800">Rincian Jurnal</h2><p class="mt-0.5 text-sm text-slate-500">Periksa isi rekap sebelum dibuat menjadi dokumen Word.</p></div>
            @if($jurnals->isNotEmpty())
                <a href="{{ route('piket.jurnal.rekap.docx', request()->query()) }}" class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-indigo-700 sm:w-auto"><span class="material-symbols-outlined text-lg">download</span>Unduh DOCX</a>
            @endif
        </div>
        @if($jurnals->isEmpty())
            <div class="px-5 py-10 text-center sm:px-8 sm:py-14">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><span class="material-symbols-outlined text-3xl">event_busy</span></div>
                <h3 class="mt-4 text-lg font-extrabold text-slate-800">Belum ada jurnal untuk pilihan ini</h3>
                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">Belum ditemukan aktivitas jurnal untuk {{ $judul }} pada periode {{ $mulai->format('d M Y') }}–{{ $sampai->format('d M Y') }}. Coba pilih periode lain atau periksa kembali pilihan kelas/guru.</p>
                <a href="{{ route('piket.jurnal.rekap') }}" class="mt-5 inline-flex min-h-11 items-center justify-center rounded-xl border border-indigo-200 bg-white px-4 py-2.5 text-sm font-bold text-indigo-700 transition hover:bg-indigo-50">Ubah pilihan rekap</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-[760px] divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500"><tr><th class="whitespace-nowrap px-4 py-3">Tanggal / Jam</th><th class="px-4 py-3">Kelas / Guru</th><th class="px-4 py-3">Mata Pelajaran</th><th class="px-4 py-3">Materi</th><th class="px-4 py-3">Kehadiran</th><th class="px-4 py-3">Validasi</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @foreach($jurnals as $jurnal)
                        @php
                            $hadir = $jurnal->detailAbsensis->filter(fn($item) => mb_strtolower((string)$item->status) === 'hadir')->count();
                            $absen = $jurnal->detailAbsensis->reject(fn($item) => mb_strtolower((string)$item->status) === 'hadir');
                        @endphp
                        <tr class="align-top transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-4 py-3 text-slate-700">{{ \Illuminate\Support\Carbon::parse($jurnal->tanggal)->format('d-m-Y') }}<br><span class="text-xs text-slate-500">{{ $jurnal->jamMulai->jam ?? '-' }} – {{ $jurnal->jamSelesai->jam ?? '-' }}</span></td>
                            <td class="px-4 py-3 text-slate-700">{{ $jurnal->kelas->nama_kelas ?? '-' }}<br><span class="text-xs text-slate-500">{{ $jurnal->guru->nama_guru ?? '-' }}</span></td>
                            <td class="px-4 py-3 text-slate-700">{{ $jurnal->jadwal->mapel->nama_mapel ?? '-' }}</td>
                            <td class="min-w-48 whitespace-normal px-4 py-3 text-slate-700">{{ $jurnal->materi ?: '-' }}</td>
                            <td class="min-w-52 whitespace-normal px-4 py-3 text-slate-700"><span class="font-bold">Hadir: {{ $hadir }}</span>@if($absen->isNotEmpty())<ul class="mt-1 space-y-1 text-xs text-slate-500">@foreach($absen as $item)<li>{{ $item->siswa->nama_siswa ?? 'Siswa' }} · {{ $item->status }}{{ $item->keterangan ? ' — '.$item->keterangan : '' }}</li>@endforeach</ul>@endif</td>
                            <td class="px-4 py-3 text-slate-700">{{ $jurnal->status_validasi_guru ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end border-t border-slate-200 p-4"><a href="{{ route('piket.jurnal.rekap.docx', request()->query()) }}" class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-indigo-700 sm:w-auto"><span class="material-symbols-outlined text-lg">download</span>Unduh DOCX</a></div>
        @endif
    </section>
</div>
@endsection
