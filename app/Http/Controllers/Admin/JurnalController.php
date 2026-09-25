<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    /**
     * Menampilkan daftar jurnal
     */
    public function index(Request $request)
    {
        $query = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
        ]);

        // =========================
        // SEARCH
        // =========================
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('materi', 'like', '%' . $search . '%')
                    ->orWhereHas('guru', function ($guru) use ($search) {
                        $guru->where('nama_guru', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('kelas', function ($kelas) use ($search) {
                        $kelas->where('nama_kelas', 'like', '%' . $search . '%');
                    });
            });
        }

        // =========================
        // FILTER STATUS GURU
        // =========================
        if ($request->filled('status_guru')) {
            $query->where('status_guru', $request->status_guru);
        }

        // =========================
        // FILTER VALIDASI
        // =========================
        if ($request->filled('status_validasi_guru')) {
            $query->where('status_validasi_guru', $request->status_validasi_guru);
        }

        // =========================
        // DATA JURNAL
        // =========================
        $jurnals = $query
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jurnal.index', compact('jurnals'));
    }

    /**
     * Menampilkan detail jurnal
     */
    public function show(Jurnal $jurnal)
    {
        /*
        |--------------------------------------------------------------------------
        | Load relasi jurnal
        |--------------------------------------------------------------------------
        |
        | detailAbsensis.siswa dipakai untuk menampilkan nama siswa
        | ketika kotak "Hadir" / "Tidak Hadir" diklik.
        |
        */
        $jurnal->load([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
            'detailAbsensis.siswa',
        ]);

        return view('admin.jurnal.show', compact('jurnal'));
    }
}