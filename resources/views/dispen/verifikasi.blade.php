<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dispen - Jurnify</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes cardPop {
            0% {
                opacity: 0;
                transform: translateY(16px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-card {
            animation: cardPop 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .btn-glow-emerald:hover {
            box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.35);
        }
        
        .btn-glow-rose:hover {
            box-shadow: 0 8px 20px -4px rgba(244, 63, 94, 0.25);
        }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center p-4 font-sans text-slate-800 antialiased">

    <!-- Container Kartu Utama -->
    <main class="w-full max-w-lg bg-white border border-slate-200/80 rounded-2xl shadow-xl shadow-slate-200/60 overflow-hidden animate-card">
        
        <!-- Header Kartu -->
        <header class="bg-[#1e254b] p-6 text-white relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full blur-xl pointer-events-none"></div>

            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold tracking-wider text-slate-300 uppercase block">Jurnify System</span>
                        <h1 class="text-xl font-bold tracking-tight">Verifikasi Dispensasi</h1>
                    </div>
                </div>

                <!-- Badge Status -->
                @php
                    $statusBadge = [
                        'menunggu' => 'bg-amber-500/20 text-amber-300 border-amber-400/30',
                        'disetujui' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30',
                        'ditolak' => 'bg-rose-500/20 text-rose-300 border-rose-400/30',
                    ];
                    $badgeClass = $statusBadge[strtolower($dispen->status)] ?? 'bg-white/10 text-slate-200 border-white/20';
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold border capitalize backdrop-blur-sm {{ $badgeClass }}">
                    {{ ucfirst($dispen->status) }}
                </span>
            </div>
        </header>

        <!-- Body Content -->
        <div class="p-6 space-y-5">

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Daftar Siswa dalam Satu Pengajuan -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800">Daftar Siswa</h2>
                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700">{{ $dispens->count() }} siswa</span>
                </div>
                <div class="space-y-2">
                    @foreach($dispens as $item)
                        <div class="grid grid-cols-[28px_1fr_1fr] gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">{{ $loop->iteration }}</span>
                            <div><span class="mb-0.5 block text-[10px] text-slate-400">Siswa</span><p class="text-sm font-bold text-slate-800">{{ $item->siswa->nama_siswa ?? '-' }}</p></div>
                            <div><span class="mb-0.5 block text-[10px] text-slate-400">Kelas</span><p class="text-sm font-bold text-slate-800">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</p></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                <div><span class="mb-0.5 block text-xs font-medium text-slate-400">Tanggal</span><p class="text-sm font-bold text-slate-800">{{ $dispen->tanggal?->format('d-m-Y') ?? '-' }}</p></div>
                <div><span class="mb-0.5 block text-xs font-medium text-slate-400">Jam</span><p class="text-sm font-bold text-slate-800">{{ $dispen->jamMulai ? substr($dispen->jamMulai->jam_mulai, 0, 5) : '-' }} - {{ $dispen->jamSelesai ? substr($dispen->jamSelesai->jam_selesai, 0, 5) : '-' }}</p></div>
            </div>
            <!-- Alasan -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <span class="text-xs font-medium text-slate-400 block mb-1">Alasan Dispensasi</span>
                <p class="text-sm text-slate-700 leading-relaxed font-normal">{{ $dispen->alasan }}</p>
            </div>

            <!-- Section Form Action atau Status Selesai -->
            @if ($dispen->status === 'menunggu')
                <div class="pt-2 space-y-4">
                    <!-- Tombol Setujui -->
                    <form action="{{ route('dispen.verifikasi.approve', $dispen->token_verifikasi) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 active:scale-[0.99] btn-glow-emerald flex items-center justify-center gap-2 text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            SETUJUI DISPENSASI
                        </button>
                    </form>

                    <div class="relative flex items-center justify-center my-2">
                        <div class="w-full border-t border-slate-200"></div>
                        <span class="absolute bg-white px-3 text-xs font-medium text-slate-400 uppercase tracking-wider">Atau</span>
                    </div>

                    <!-- Form Penolakan -->
                    <form action="{{ route('dispen.verifikasi.reject', $dispen->token_verifikasi) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label for="catatan_persetujuan" class="block text-xs font-semibold text-slate-600 mb-1">
                                Alasan Penolakan <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                id="catatan_persetujuan" 
                                name="catatan_persetujuan" 
                                rows="3" 
                                required 
                                placeholder="Wajib menuliskan alasan penolakan..."
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1e254b]/20 focus:border-[#1e254b] transition-all duration-200 resize-none"
                            >{{ old('catatan_persetujuan') }}</textarea>
                        </div>
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold rounded-xl border border-rose-200/80 transition-all duration-200 active:scale-[0.99] btn-glow-rose flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            TOLAK DISPENSASI
                        </button>
                    </form>
                </div>
            @else
                <!-- Keterangan Jika Dispen Sudah Diverifikasi (Pencegahan Proses Ulang) -->
                <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 text-center space-y-2">
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $dispen->status === 'disetujui' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                        @if ($dispen->status === 'disetujui')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        @endif
                    </div>
                    <p class="text-sm font-bold text-slate-800">
                        Dispensasi ini sudah diverifikasi
                    </p>
                    <p class="text-xs text-slate-500">
                        Keputusan tidak dapat diubah kembali melalui link ini.
                    </p>
                    <div class="pt-2 border-t border-slate-200 text-xs text-slate-600 space-y-1">
                        <div>
                            Status: <strong class="capitalize {{ $dispen->status === 'disetujui' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $dispen->status }}</strong>
                        </div>
                        @if ($dispen->approver)
                            <div>Diverifikasi oleh: <strong>{{ $dispen->approver->nama_user }}</strong></div>
                        @endif
                        @if ($dispen->disetujui_pada)
                            <div>Waktu verifikasi: <strong>{{ $dispen->disetujui_pada->format('d-m-Y H:i:s') }}</strong></div>
                        @endif
                    </div>

                    @if ($dispen->catatan_persetujuan)
                        <div class="mt-3 pt-3 border-t border-slate-200 text-left">
                            <span class="text-xs text-slate-400 font-medium block mb-0.5">Alasan Penolakan:</span>
                            <p class="text-sm text-slate-700 italic bg-white p-2.5 rounded-lg border border-slate-200">"{{ $dispen->catatan_persetujuan }}"</p>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- Footer -->
        <footer class="px-6 py-3 bg-slate-50/80 border-t border-slate-100 text-center">
            <p class="text-[11px] text-slate-400">© {{ date('Y') }} Jurnify — Sistem Informasi Dispensasi</p>
        </footer>
    </main>

</body>
</html>