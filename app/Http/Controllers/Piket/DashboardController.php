<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Dispen;
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

        if (! $user->hasPiketToday()) {
            return redirect()->route('guru.dashboard')->with('info', 'Anda tidak memiliki jadwal piket hari ini.');
        }

        $today = now('Asia/Jakarta')->toDateString();

        $jurnals = Jurnal::with(['guru', 'kelas', 'jadwal.mapel', 'jamMulai', 'jamSelesai'])
            ->whereIn('status_validasi_guru', ['Menunggu', 'Disetujui'])
            ->latest('tanggal')
            ->take(5)
            ->get();

        $totalJurnalHariIni = Jurnal::whereDate('tanggal', $today)
            ->whereIn('status_validasi_guru', ['Menunggu', 'Disetujui'])
            ->count();
        $totalHadir = Jurnal::whereDate('tanggal', $today)
            ->whereIn('status_validasi_guru', ['Menunggu', 'Disetujui'])
            ->sum('jml_hadir');
        $totalAbsen = Jurnal::whereDate('tanggal', $today)
            ->whereIn('status_validasi_guru', ['Menunggu', 'Disetujui'])
            ->sum('jml_tidak_hadir');

        $dispens = Dispen::with(['siswa.kelas', 'jamMulai', 'jamSelesai'])
            ->latest('tanggal')
            ->take(5)
            ->get();

        $piketHariIni = PiketJadwal::with('guru')
            ->forGuru($user->id_guru)
            ->whereDate('tanggal', $today)
            ->orderBy('jam_mulai')
            ->get();

        return view('piket.dashboard', compact(
            'jurnals',
            'dispens',
            'piketHariIni',
            'totalJurnalHariIni',
            'totalHadir',
            'totalAbsen'
        ));
    }

}
