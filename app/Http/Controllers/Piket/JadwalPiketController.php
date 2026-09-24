<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\PiketJadwal;
use Illuminate\Http\Request;

class JadwalPiketController extends Controller
{
    public function index(Request $request)
    {
        $query = PiketJadwal::with(['guru', 'pembuat'])
            ->where(function ($query) {
                $guruId = auth()->user()->id_guru;
                $query->where('id_guru', $guruId)
                    ->orWhere('petugas_kbm_pagi_id', $guruId)
                    ->orWhere('koordinator_kbm_pagi_id', $guruId)
                    ->orWhere('petugas_kbm_siang_id', $guruId)
                    ->orWhere('koordinator_kbm_siang_id', $guruId)
                    ->orWhere('piket_waka_id', $guruId);
            })
            ->orderBy('tanggal', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis_tugas', 'like', "%{$search}%")
                    ->orWhere('shift', 'like', "%{$search}%")
                    ->orWhereHas('guru', fn ($g) => $g->where('nama_guru', 'like', "%{$search}%"));
            });
        }

        $jadwals = $query->get();

        return view('piket.jadwal.index', compact('jadwals'));
    }
}
