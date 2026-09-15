<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\PiketJadwal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->role !== 'Guru') {
            abort(403, 'Akses ditolak.');
        }

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

        $piketTerdekat = PiketJadwal::with('guru')
            ->where('id_guru', $user->id_guru)
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->first();

        $piketHariIni = PiketJadwal::with('guru')
            ->where('id_guru', $user->id_guru)
            ->where('tanggal', now()->toDateString())
            ->first();

        $tahunAjaran = '2026/2027 Ganjil';

        return view('guru.dashboard', compact(
            'jurnals',
            'totalJurnal',
            'piketTerdekat',
            'piketHariIni',
            'tahunAjaran'
        ));
    }
}