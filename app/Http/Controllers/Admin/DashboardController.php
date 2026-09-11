<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jurnal;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $jumlahGuru = Guru::count();
        $jumlahKelas = Kelas::count();
        $jurnalHariIni = Jurnal::whereDate('tanggal', today())->count();

        $totalJurnal = Jurnal::count();

        $jurnalTerbaru = Jurnal::latest('tanggal')
            ->take(5)
            ->get();

        $jurnals = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ])
        ->latest('tanggal')
        ->take(5)
        ->get();

        return view('admin.dashboard', compact(
            'jumlahGuru',
            'jumlahKelas',
            'jurnalHariIni',
            'totalJurnal',
            'jurnalTerbaru',

            'jurnals'
        ));
    }
}