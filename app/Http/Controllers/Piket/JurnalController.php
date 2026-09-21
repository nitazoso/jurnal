<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kelas
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();

        // Ambil jurnal yang sudah masuk dan menunggu validasi agar staf piket bisa melihat data baru
        $jurnalQuery = Jurnal::with(['guru', 'kelas'])
            ->whereIn('status_validasi_guru', ['Menunggu', 'Disetujui']);

        if ($request->filled('id_kelas')) {
            $jurnalQuery->where('id_kelas', $request->integer('id_kelas'));
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $jurnalQuery->where(function ($query) use ($search) {
                $query->where('materi', 'like', "%{$search}%")
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

        // Filter bulan
        if ($request->filled('bulan')) {
            $jurnalQuery->whereMonth(
                'tanggal',
                $request->bulan
            );
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $jurnalQuery->whereYear('tanggal', $request->integer('tahun'));
        }

        $jurnals = $jurnalQuery
            ->orderByDesc('tanggal')
            ->get();

        $kelasTerpilih = $request->filled('id_kelas')
            ? $kelases->firstWhere('id_kelas', $request->integer('id_kelas'))
            : null;

        // Jumlah jurnal per kelas
        $jumlahJurnalPerKelas = Jurnal::whereIn(
            'status_validasi_guru',
            ['Menunggu', 'Disetujui']
        )
        ->selectRaw('id_kelas, COUNT(*) as total')
        ->groupBy('id_kelas')
        ->pluck('total', 'id_kelas');

        // Statistik
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

        // Tahun yang tersedia
        $tahunList = Jurnal::whereIn(
            'status_validasi_guru',
            ['Menunggu', 'Disetujui']
        )
        ->selectRaw('YEAR(tanggal) as tahun')
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');

        return view('piket.jurnal.index', compact(
            'kelases',
            'jurnals',
            'jumlahJurnalPerKelas',
            'totalJurnal',
            'totalKelas',
            'jurnalHariIni',
            'tahunList'
            , 'kelasTerpilih'
        ));
    }
}
