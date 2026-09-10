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

        $query = Jurnal::with([
            'kelas',
            'jamMulai',
            'jamSelesai',
        ])
        ->where('id_user', $user->id_user);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        $jurnals = $query
            ->latest('tanggal')
            ->get();

        $totalJurnal = Jurnal::where('id_user', $user->id_user)
            ->count();

        $tahunAjaran = '2026/2027';

        return view('guru.dashboard', compact(
            'jurnals',
            'totalJurnal',
            'tahunAjaran'
        ));
    }
}