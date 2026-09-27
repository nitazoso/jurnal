<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jurnal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    /**
     * Menampilkan daftar jurnal
     */
    public function index(Request $request)
    {
        $filterBy = in_array($request->input('filter_by'), ['kelas', 'guru'], true)
            ? $request->input('filter_by')
            : 'kelas';

        $query = Jurnal::with([
            'guru',
            'user',
            'kelas',
            'jadwal.mapel',
        ])->whereHas('jadwal');

        if ($filterBy === 'kelas' && $request->filled('id_kelas')) {
            $query->where('id_kelas', $request->input('id_kelas'));
        }

        if ($filterBy === 'guru' && $request->filled('id_guru')) {
            $query->where('id_guru', $request->input('id_guru'));
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('materi', 'like', '%' . $search . '%')
                    ->orWhereHas('guru', function ($guru) use ($search) {
                        $guru->where('nama_guru', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('kelas', function ($kelas) use ($search) {
                        $kelas->where('nama_kelas', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('jadwal.mapel', function ($mapel) use ($search) {
                        $mapel->where('nama_mapel', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status_guru')) {
            $query->where('status_guru', $request->input('status_guru'));
        }

        if ($request->filled('status_validasi_guru')) {
            $query->where('status_validasi_guru', $request->input('status_validasi_guru'));
        }

        $jurnals = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('id_jurnal')
            ->paginate(10)
            ->withQueryString();

        $kelases = Kelas::orderBy('nama_kelas')->get();
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('admin.jurnal.index', compact(
            'jurnals',
            'kelases',
            'gurus',
            'filterBy'
        ));
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