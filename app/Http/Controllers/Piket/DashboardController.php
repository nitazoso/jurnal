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

        $today = now()->toDateString();

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
            ->where(function ($query) use ($user) {
                $query->where('id_guru', $user->id_guru)
                    ->orWhere('petugas_kbm_pagi_id', $user->id_guru)
                    ->orWhere('koordinator_kbm_pagi_id', $user->id_guru)
                    ->orWhere('petugas_kbm_siang_id', $user->id_guru)
                    ->orWhere('koordinator_kbm_siang_id', $user->id_guru)
                    ->orWhere('piket_waka_id', $user->id_guru);
            })
            ->where('tanggal', $today)
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

    public function jurnalIndex(Request $request)
    {
        $query = Jurnal::with(['guru', 'kelas', 'jadwal.mapel', 'jamMulai', 'jamSelesai'])
            ->where('status_validasi_guru', 'Disetujui')
            ->latest('tanggal');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('guru', fn ($g) => $g->where('nama_guru', 'like', "%{$search}%"))
                    ->orWhereHas('kelas', fn ($k) => $k->where('nama_kelas', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('id_kelas', $request->kelas_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $jurnals = $query->get();
        $kelases = \App\Models\Kelas::orderBy('nama_kelas')->get();

        return view('piket.jurnal.index', compact('jurnals', 'kelases'));
    }
}
