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

        if (! in_array($user->role, ['Guru', 'Staff Piket'], true)) {
            abort(403, 'Akses ditolak.');
        }

        if ($user->role === 'Guru' && ! $user->hasPiketToday()) {
            return redirect()->route('guru.dashboard')->with('info', 'Anda tidak memiliki jadwal piket hari ini.');
        }

        $today = now('Asia/Jakarta')->toDateString();
        $journalQuery = fn () => Jurnal::whereIn('status_validasi_guru', ['Menunggu', 'Disetujui']);

        $jurnals = $journalQuery()
            ->with(['guru', 'kelas', 'jadwal.mapel', 'jamMulai', 'jamSelesai'])
            ->latest('tanggal')
            ->take(5)
            ->get();

        $totalJurnalHariIni = $journalQuery()->whereDate('tanggal', $today)->count();
        $totalHadir = $journalQuery()->whereDate('tanggal', $today)->sum('jml_hadir');
        $totalAbsen = $journalQuery()->whereDate('tanggal', $today)->sum('jml_tidak_hadir');
        $totalDispenHariIni = Dispen::whereDate('tanggal', $today)->count();

        $dispens = Dispen::with(['siswa.kelas', 'jamMulai', 'jamSelesai'])
            ->whereDate('tanggal', $today)
            ->latest('tanggal')
            ->take(5)
            ->get();

        $piketHariIniQuery = PiketJadwal::with('guru')->whereDate('tanggal', $today);
        if ($user->role === 'Guru') {
            $piketHariIniQuery->forGuru($user->id_guru);
        }
        $piketHariIni = $piketHariIniQuery->orderBy('jam_mulai')->get();

        $monthlyTrend = collect();
        $monthCursor = now('Asia/Jakarta')->copy()->startOfMonth()->subMonths(5);
        for ($monthOffset = 0; $monthOffset < 6; $monthOffset++) {
            $monthStart = $monthCursor->copy()->addMonths($monthOffset)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $monthlyTrend->push([
                'label' => $monthStart->translatedFormat('M'),
                'total' => $journalQuery()
                    ->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->count(),
            ]);
        }

        return view('piket.dashboard', compact(
            'jurnals',
            'dispens',
            'piketHariIni',
            'totalJurnalHariIni',
            'totalHadir',
            'totalAbsen',
            'totalDispenHariIni',
            'monthlyTrend'
        ));
    }

}
