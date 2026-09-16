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
            ->where('id_guru', $user->id_guru)
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('guru.piket.index', compact('jadwals'));
    }
}
