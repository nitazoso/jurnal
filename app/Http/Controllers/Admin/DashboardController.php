<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jurnal;

class DashboardController extends Controller
{
    public function index()
{
    $jumlahGuru = Guru::count();
    $jumlahKelas = Kelas::count();
    $jurnalHariIni = Jurnal::whereDate('tanggal', today())->count();

    $jurnalTerbaru = Jurnal::latest('tanggal')
        ->take(5)
        ->get();

    return view('admin.dashboard', compact(
        'jumlahGuru',
        'jumlahKelas',
        'jurnalHariIni',
        'jurnalTerbaru'
    ));
}
}
