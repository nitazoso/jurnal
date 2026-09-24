<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\User;
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

        $users = User::with(['guru', 'kelas'])
            ->latest('id_user')
            ->paginate(10, ['*'], 'users_page')
            ->withQueryString();

        $totalUser = User::count();
        $totalGuru = User::where('role', 'Guru')->count();
        $totalStaffPiket = User::where('role', 'Staff Piket')->count();
        $totalSekretaris = User::where('role', 'Sekretaris')->count();

        return view('admin.jurnal.index', compact(
            'jurnals',
            'users',
            'totalUser',
            'totalGuru',
            'totalStaffPiket',
            'totalSekretaris'
        ));
    }

    public function show(Jurnal $jurnal)
    {
        $jurnal->load([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ]);

        return view('admin.jurnal.show', compact('jurnal'));
    }
}