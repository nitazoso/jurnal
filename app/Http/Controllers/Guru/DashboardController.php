<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $jurnals = Jurnal::with([
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ])
        ->where('id_user', $user->id_user)
        ->latest('tanggal')
        ->take(5)
        ->get();

        $totalJurnal = Jurnal::where('id_user', $user->id_user)
            ->count();

        $tahunAjaran = '2026/2027 Ganjil';

        return view('guru.dashboard', compact(
            'jurnals',
            'totalJurnal',
            'tahunAjaran'
        ));
    }
}