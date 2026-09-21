<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dispen - Jurnify</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Smooth Entrance Animation */
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

        /* Subtle Glow on Action */
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
        
        <!-- Header Kartu (Gaya Navy Blue Jurnify) -->
        <header class="bg-[#1e254b] p-6 text-white relative overflow-hidden">
            <!-- Pattern Ornamen Halus -->
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full blur-xl pointer-events-none"></div>

            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <!-- Icon Buku / Logo Jurnify -->
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
            
            <!-- Detail Informasi Siswa -->
            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <span class="text-xs font-medium text-slate-400 block mb-0.5">Siswa</span>
                    <p class="text-sm font-bold text-slate-800">{{ $dispen->siswa->nama_siswa ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400 block mb-0.5">Kelas</span>
                    <p class="text-sm font-bold text-slate-800">{{ $dispen->siswa->kelas->nama_kelas ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400 block mb-0.5">Tanggal</span>
                    <p class="text-sm font-bold text-slate-800">{{ $dispen->tanggal->format('d-m-Y') }}</p>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400 block mb-0.5">Jam Ke-</span>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $dispen->jamMulai->jam_mulai ?? '-' }} - {{ $dispen->jamSelesai->jam_selesai ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- Alasan -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <span class="text-xs font-medium text-slate-400 block mb-1">Alasan Dispensasi</span>
                <p class="text-sm text-slate-700 leading-relaxed font-normal">{{ $dispen->alasan }}</p>
            </div>

            <!-- Section Form Action -->
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
                                placeholder="Tuliskan alasan penolakan..."
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1e254b]/20 focus:border-[#1e254b] transition-all duration-200 resize-none"
                            ></textarea>
                        </div>
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold rounded-xl border border-rose-200/80 transition-all duration-200 active:scale-[0.99] btn-glow-rose flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            TOLAK DISPENSASI
                        </button>
                    </form>
                </div>
            @else
                <!-- Keterangan Jika Link Sudah Digunakan -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center space-y-1">
                    <p class="text-xs text-slate-500">Link verifikasi ini sudah tidak aktif.</p>
                    <p class="text-sm font-semibold text-slate-700">
                        Status Terakhir: <span class="text-[#1e254b] font-bold">{{ ucfirst($dispen->status) }}</span>
                    </p>
                    @if ($dispen->catatan_persetujuan)
                        <div class="mt-3 pt-3 border-t border-slate-200 text-left">
                            <span class="text-xs text-slate-400 font-medium block mb-0.5">Catatan Penolakan:</span>
                            <p class="text-sm text-slate-600 italic">"{{ $dispen->catatan_persetujuan }}"</p>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- Footer Ringkas -->
        <footer class="px-6 py-3 bg-slate-50/80 border-t border-slate-100 text-center">
            <p class="text-[11px] text-slate-400">© {{ date('Y') }} Jurnify — Kementerian Pendidikan</p>
        </footer>
    </main>

</body>
</html>