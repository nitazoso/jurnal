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

        $today = now('Asia/Jakarta')->toDateString();
        $journalQuery = fn () => Jurnal::where('id_guru', $user->id_guru);
        $totalJurnal = $journalQuery()->count();
        $jurnalBulanIni = $journalQuery()
            ->whereBetween('tanggal', [now('Asia/Jakarta')->startOfMonth()->toDateString(), $today])
            ->count();
        $totalDisetujui = $journalQuery()->where('status_validasi_guru', 'Disetujui')->count();
        $totalMenunggu = $journalQuery()->where('status_validasi_guru', 'Menunggu')->count();
        $totalDitolak = $journalQuery()->where('status_validasi_guru', 'Ditolak')->count();

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
            'jurnalBulanIni',
            'totalDisetujui',
            'totalMenunggu',
            'totalDitolak',
            'monthlyTrend',
            'piketTerdekat',
            'piketHariIni'
        ));
    }
}
