<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kelas - Jurnify</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Font Inter/Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen text-slate-800 flex">

    <!-- SIDEBAR DARI KOMPONEN -->
    <x-admin-sidebar />

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">

        <!-- TOPBAR DARI KOMPONEN (ATAU HEADER MANUAL) -->
        <x-admin-topbar />

        <!-- Main Container -->
        <div class="p-8">

            <!-- Card Direktori Kelas -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                
                <!-- Card Header & Filter Search -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Direktori Kelas</h3>
                    
                    <div class="flex items-center gap-2">
                        <!-- Search Bar -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" placeholder="Cari kelas atau wali..." class="w-64 bg-slate-100/70 border-0 rounded-xl py-2 pl-9 pr-4 text-xs text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition outline-none">
                        </div>

                        <!-- Filter Button -->
                        <button class="bg-slate-100/70 hover:bg-slate-200/80 text-slate-600 p-2.5 rounded-xl transition flex items-center justify-center">
                            <i class="fa-solid fa-sliders text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100/60 text-[11px] font-bold text-slate-500 uppercase tracking-wider rounded-lg">
                                <th class="py-3 px-6 rounded-l-xl">ID</th>
                                <th class="py-3 px-6">NAMA KELAS</th>
                                <th class="py-3 px-6">WALI KELAS</th>
                                <th class="py-3 px-6 text-center">SISWA</th>
                                <th class="py-3 px-6 rounded-r-xl text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            
                            @forelse($kelas as $item)
                            <tr class="hover:bg-slate-50/80 transition rounded-xl">
                                <td class="py-4 px-6 font-medium text-slate-600">
                                    KLS-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    {{ $item->nama_kelas }}
                                </td>
                                <td class="py-4 px-6 text-slate-600 font-medium">
                                    {{ $item->waliKelas->name ?? '-' }}
                                </td>
                                <td class="py-4 px-6 text-center text-slate-600 font-medium">
                                    {{ $item->siswas_count ?? 0 }}
                                </td>
                                <td class="py-4 px-6 text-center text-slate-400 hover:text-slate-600">
                                <a href="{{ route('admin.kelas.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">Belum ada data kelas.</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </main>

</body>
</html>