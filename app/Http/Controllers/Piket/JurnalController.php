<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        abort_unless(
            $user?->role === 'Staff Piket' || ($user?->role === 'Guru' && $user->hasPiketToday()),
            403
        );

        $validated = $request->validate([
            'id_kelas' => ['nullable', 'integer', 'exists:kelases,id_kelas'],
        ]);

        $kelases = Kelas::orderBy('nama_kelas')->get();
        $selectedKelas = isset($validated['id_kelas'])
            ? $kelases->firstWhere('id_kelas', $validated['id_kelas'])
            : null;

        $jadwals = $selectedKelas
            ? Jadwal::with(['guru', 'kelas', 'mapel', 'jamMulai', 'jamSelesai'])
                ->withCount(['jurnals as jurnal_hari_ini_count' => fn ($query) => $query->whereDate('tanggal', today())])
                ->where('id_kelas', $selectedKelas->id_kelas)
                ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 ELSE 6 END")
                ->orderBy('id_jam_mulai')
                ->get()
            : collect();

        return view('piket.jurnal.create', compact('kelases', 'selectedKelas', 'jadwals'));
    }

    public function show(Jurnal $jurnal)
    {
        $jurnal->load([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
            'detailAbsensis.siswa',
            'validator',
            'user',
        ]);

        return view('piket.jurnal.show', compact('jurnal'));
    }

    public function rekap(Request $request)
    {
        if (! $request->filled('view')) {
            $request->merge(['view' => 'semua']);
        }

        return $this->index($request);
    }

    public function index(Request $request)
    {
        $view = $request->get('view', 'kelas');
        $statusJurnal = [
            'Menunggu',
            'Disetujui',
            'Perlu Diperbaiki',
            'Ditolak',
        ];

        // =========================
        // DATA KELAS & GURU
        // =========================
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        $gurus = Guru::orderBy('nama_guru', 'asc')->get();

        // =========================
        // QUERY JURNAL
        // =========================
        $jurnalQuery = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ])->whereIn('status_validasi_guru', $statusJurnal);

        // =========================
        // FILTER BERDASARKAN VIEW (KELAS / GURU)
        // =========================
        if ($view === 'kelas' && $request->filled('id_kelas')) {
            $jurnalQuery->where(
                'id_kelas',
                $request->integer('id_kelas')
            );
        } elseif ($view === 'guru' && $request->filled('id_guru')) {
            $jurnalQuery->where(
                'id_guru',
                $request->integer('id_guru')
            );
        }

        // =========================
        // SEARCH (Materi, Guru, Kelas)
        // =========================
        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();

            $jurnalQuery->where(function ($query) use ($search) {
                $query->where(
                    'materi',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas('guru', function ($query) use ($search) {
                    $query->where(
                        'nama_guru',
                        'like',
                        "%{$search}%"
                    );
                })
                ->orWhereHas('kelas', function ($query) use ($search) {
                    $query->where(
                        'nama_kelas',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        // =========================
        // FILTER BULAN
        // =========================
        if ($request->filled('bulan')) {
            $jurnalQuery->whereMonth(
                'tanggal',
                $request->integer('bulan')
            );
        }

        // =========================
        // FILTER TAHUN
        // =========================
        if ($request->filled('tahun')) {
            $jurnalQuery->whereYear(
                'tanggal',
                $request->integer('tahun')
            );
        }

        // =========================
        // AMBIL JURNAL
        // =========================
        // Hanya ambil data jurnal jika kelas/guru sudah dipilih
        $hasSelection = $view === 'semua' ||
            ($view === 'kelas' && $request->filled('id_kelas')) ||
            ($view === 'guru' && $request->filled('id_guru'));

        $jurnals = $hasSelection
            ? $jurnalQuery->orderByDesc('tanggal')->get()
            : collect();

        // =========================
        // ITEM YANG DIPILIH
        // =========================
        $selectedKelas = ($view === 'kelas' && $request->filled('id_kelas'))
            ? $kelases->firstWhere('id_kelas', $request->integer('id_kelas'))
            : null;

        $selectedGuru = ($view === 'guru' && $request->filled('id_guru'))
            ? $gurus->firstWhere('id_guru', $request->integer('id_guru'))
            : null;

        // =========================
        // JUMLAH JURNAL PER KELAS
        // =========================
        $jumlahJurnalPerKelas = Jurnal::whereIn('status_validasi_guru', $statusJurnal)
        ->selectRaw('id_kelas, COUNT(*) as total')
        ->groupBy('id_kelas')
        ->pluck('total', 'id_kelas');

        // =========================
        // STATISTIK
        // =========================
        $totalJurnal = Jurnal::whereIn('status_validasi_guru', $statusJurnal)->count();

        $totalKelas = Kelas::count();

        $jurnalHariIni = Jurnal::whereIn('status_validasi_guru', $statusJurnal)
        ->whereDate('tanggal', today())
        ->count();

        // =========================
        // TAHUN TERSEDIA
        // =========================
        // Dibuat di PHP agar kompatibel dengan MySQL dan SQLite
        $tahunList = Jurnal::whereIn('status_validasi_guru', $statusJurnal)
        ->pluck('tanggal')
        ->map(fn ($tanggal) => (int) date('Y', strtotime($tanggal)))
        ->unique()
        ->sortDesc()
        ->values();

        // =========================
        // KIRIM KE BLADE
        // =========================
        return view('piket.jurnal.index', compact(
            'kelases',
            'gurus',
            'jurnals',
            'jumlahJurnalPerKelas',
            'totalJurnal',
            'totalKelas',
            'jurnalHariIni',
            'tahunList',
            'selectedKelas',
            'selectedGuru'
        ));
    }
}