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
        $kelasesQuery = Kelas::orderBy('nama_kelas', 'asc');

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();
            $kelasesQuery->where('nama_kelas', 'like', "%{$search}%");
        }

        $kelases = $kelasesQuery->get();

        // Ambil jurnal yang sudah masuk dan menunggu validasi agar staf piket bisa melihat data baru
        $jurnalQuery = Jurnal::with(['guru', 'kelas'])
            ->whereIn('status_validasi_guru', ['Menunggu', 'Disetujui']);

        if ($request->filled('id_kelas')) {
            $jurnalQuery->where('id_kelas', $request->integer('id_kelas'));
        }

        // Search hanya berdasarkan nama kelas.
        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();

            $jurnalQuery->where(function ($query) use ($search) {
                $query->whereHas('kelas', function ($query) use ($search) {
                    $query->where('nama_kelas', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('bulan')) {
            $jurnalQuery->whereMonth('tanggal', $request->integer('bulan'));
        }

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

        // Tahun yang tersedia. Ambil tanggal lalu bentuk tahunnya di PHP agar
        // query ini berjalan baik pada MySQL dan SQLite.
        $tahunList = Jurnal::whereIn(
            'status_validasi_guru',
            ['Menunggu', 'Disetujui']
        )
        ->pluck('tanggal')
        ->map(fn ($tanggal) => (int) date('Y', strtotime($tanggal)))
        ->unique()
        ->sortDesc()
        ->values();

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
