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
        ->where('id_guru', $user->id_guru)
        ->latest('tanggal')
        ->take(5)
        ->get();

        $totalJurnal = Jurnal::where('id_guru', $user->id_guru)
            ->count();

        $today = now('Asia/Jakarta')->toDateString();

        $piketTerdekat = PiketJadwal::with('guru')
            ->forGuru($user->id_guru)
            ->whereDate('tanggal', '>=', $today)
            ->orderBy('tanggal')
            ->first();

        $piketHariIni = PiketJadwal::with('guru')
            ->forGuru($user->id_guru)
            ->whereDate('tanggal', $today)
            ->first();

        return view('guru.dashboard', compact(
            'jurnals',
            'totalJurnal',
            'piketTerdekat',
            'piketHariIni'
        ));
    }
}
