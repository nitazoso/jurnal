<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PiketJadwal;

class PiketController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jadwals = PiketJadwal::with('guru')
            ->where(function ($query) use ($user) {
                $query->where('id_guru', $user->id_guru)
                    ->orWhere('petugas_kbm_pagi_id', $user->id_guru)
                    ->orWhere('koordinator_kbm_pagi_id', $user->id_guru)
                    ->orWhere('petugas_kbm_siang_id', $user->id_guru)
                    ->orWhere('koordinator_kbm_siang_id', $user->id_guru)
                    ->orWhere('piket_waka_id', $user->id_guru);
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('guru.piket.index', compact('jadwals'));
    }
}
