<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jurnal;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurnalController extends Controller
{
    /**
     * Menampilkan daftar jurnal
     */
    public function index(Request $request)
    {
        $filterBy = in_array($request->input('filter_by'), ['kelas', 'guru'], true)
            ? $request->input('filter_by')
            : 'kelas';

        $query = Jurnal::with([
            'guru',
            'user',
            'kelas',
            'jadwal.mapel',
        ])->whereHas('jadwal');

        if ($filterBy === 'kelas' && $request->filled('id_kelas')) {
            $query->where('id_kelas', $request->input('id_kelas'));
        }

        if ($filterBy === 'guru' && $request->filled('id_guru')) {
            $query->where('id_guru', $request->input('id_guru'));
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('materi', 'like', '%' . $search . '%')
                    ->orWhereHas('guru', function ($guru) use ($search) {
                        $guru->where('nama_guru', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('kelas', function ($kelas) use ($search) {
                        $kelas->where('nama_kelas', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('jadwal.mapel', function ($mapel) use ($search) {
                        $mapel->where('nama_mapel', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status_guru')) {
            $query->where('status_guru', $request->input('status_guru'));
        }

        if ($request->filled('status_validasi_guru')) {
            $query->where('status_validasi_guru', $request->input('status_validasi_guru'));
        }

        $jurnals = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('id_jurnal')
            ->paginate(10)
            ->withQueryString();

        $statsQuery = Jurnal::query()->whereHas('jadwal');

        if ($filterBy === 'kelas' && $request->filled('id_kelas')) {
            $statsQuery->where('id_kelas', $request->input('id_kelas'));
        }

        if ($filterBy === 'guru' && $request->filled('id_guru')) {
            $statsQuery->where('id_guru', $request->input('id_guru'));
        }

        if ($request->filled('tanggal')) {
            $statsQuery->whereDate('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $statsQuery->where(function ($q) use ($search) {
                $q->where('materi', 'like', '%' . $search . '%')
                    ->orWhereHas('guru', function ($guru) use ($search) {
                        $guru->where('nama_guru', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('kelas', function ($kelas) use ($search) {
                        $kelas->where('nama_kelas', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('jadwal.mapel', function ($mapel) use ($search) {
                        $mapel->where('nama_mapel', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status_guru')) {
            $statsQuery->where('status_guru', $request->input('status_guru'));
        }

        if ($request->filled('status_validasi_guru')) {
            $statsQuery->where('status_validasi_guru', $request->input('status_validasi_guru'));
        }

        $totalJurnal = (clone $statsQuery)->count();
        $jurnalHariIni = (clone $statsQuery)->whereDate('tanggal', now('Asia/Jakarta')->toDateString())->count();
        $totalHadir = (clone $statsQuery)->sum('jml_hadir') ?? 0;
        $totalTidakHadir = (clone $statsQuery)->sum('jml_tidak_hadir') ?? 0;
        $totalDisetujui = (clone $statsQuery)->where('status_validasi_guru', 'Disetujui')->count();
        $totalMenunggu = (clone $statsQuery)->where('status_validasi_guru', 'Menunggu')->count();
        $totalDitolak = (clone $statsQuery)->where('status_validasi_guru', 'Ditolak')->count();
        $validPercentage = $totalJurnal > 0 ? round(($totalDisetujui / $totalJurnal) * 100, 1) : 0;
        $statusSummary = collect(['Disetujui', 'Menunggu', 'Ditolak', 'Perlu Diperbaiki'])->mapWithKeys(function ($status) use ($statsQuery) {
            return [$status => (int) (clone $statsQuery)->where('status_validasi_guru', $status)->count()];
        });

        $kelasTop = (clone $statsQuery)
            ->whereHas('kelas')
            ->select('id_kelas', DB::raw('COUNT(*) as total'))
            ->groupBy('id_kelas')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->kelas->nama_kelas,
                    'total' => (int) $item->total,
                ];
            });

        $guruTop = (clone $statsQuery)
            ->whereHas('guru')
            ->select('id_guru', DB::raw('COUNT(*) as total'))
            ->groupBy('id_guru')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->guru->nama_guru,
                    'total' => (int) $item->total,
                ];
            });

        $monthlyTrend = collect();
        $monthCursor = now('Asia/Jakarta')->copy()->startOfMonth()->subMonths(5);
        for ($i = 0; $i < 6; $i++) {
            $monthStart = $monthCursor->copy()->addMonths($i)->startOfMonth();
            $monthEnd = $monthCursor->copy()->addMonths($i)->endOfMonth();
            $monthlyTrend->push([
                'label' => $monthStart->translatedFormat('M Y'),
                'total' => (clone $statsQuery)->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])->count(),
            ]);
        }

        $recentJournals = (clone $statsQuery)
            ->with(['guru', 'kelas', 'jadwal.mapel'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_jurnal')
            ->limit(5)
            ->get();

        $kelases = Kelas::orderBy('nama_kelas')->get();
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('admin.jurnal.index', compact(
            'jurnals',
            'kelases',
            'gurus',
            'filterBy',
            'totalJurnal',
            'jurnalHariIni',
            'totalHadir',
            'totalTidakHadir',
            'totalDisetujui',
            'totalMenunggu',
            'totalDitolak',
            'validPercentage',
            'statusSummary',
            'kelasTop',
            'guruTop',
            'monthlyTrend',
            'recentJournals'
        ));
    }

    /**
     * Menampilkan detail jurnal
     */
    public function statistics(Request $request)
    {
        return $this->index($request);
    }

    public function show(Jurnal $jurnal)
    {
        /*
        |--------------------------------------------------------------------------
        | Load relasi jurnal
        |--------------------------------------------------------------------------
        |
        | Relasi siswa dipakai untuk menampilkan daftar nama hadir dan tidak hadir
        | langsung pada halaman detail jurnal.
        |
        */
        $jurnal->load([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
            'detailAbsensis.siswa',
        ]);

        return view('admin.jurnal.show', compact('jurnal'));
    }
}
