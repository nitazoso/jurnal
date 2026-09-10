<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
        ]);

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

        if ($request->filled('status_guru')) {
            $query->where('status_guru', $request->status_guru);
        }

        if ($request->filled('status_validasi_guru')) {
            $query->where(
                'status_validasi_guru',
                $request->status_validasi_guru
            );
        }

        $jurnals = $query
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jurnal.index', compact('jurnals'));
    }
}