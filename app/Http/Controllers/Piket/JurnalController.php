<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jurnal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'kelas');

        // =========================
        // DATA KELAS & GURU
        // =========================
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        $gurus = Guru::orderBy('nama_guru', 'asc')->get();

        // =========================
        // QUERY JURNAL
        // =========================
        $jurnalQuery = Jurnal::with(['guru', 'kelas'])
            ->whereIn('status_validasi_guru', [
                'Menunggu',
                'Disetujui'
            ]);

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
        // SEARCH
        // =========================
        if ($request->filled('search')) {
            $search = $request->search;

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
        $hasSelection = ($view === 'kelas' && $request->filled('id_kelas')) ||
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
        $jumlahJurnalPerKelas = Jurnal::whereIn(
            'status_validasi_guru',
            ['Menunggu', 'Disetujui']
        )
        ->selectRaw('id_kelas, COUNT(*) as total')
        ->groupBy('id_kelas')
        ->pluck('total', 'id_kelas');

        // =========================
        // STATISTIK
        // =========================
        $totalJurnal = Jurnal::whereIn(
            'status_validasi_guru',
            ['Menunggu', 'Disetujui']
        )->count();

        $totalKelas = Kelas::count();

        $jurnalHariIni = Jurnal::whereIn(
            'status_validasi_guru',
            ['Menunggu', 'Disetujui']
        )
        ->whereDate('tanggal', today())
        ->count();

        // =========================
        // TAHUN TERSEDIA
        // =========================
        $tahunList = Jurnal::whereIn(
            'status_validasi_guru',
            ['Menunggu', 'Disetujui']
        )
        ->selectRaw('YEAR(tanggal) as tahun')
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');

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