<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\Siswa;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jadwals = Jadwal::with([
            'mapel',
            'kelas',
            'jamMulai',
            'jamSelesai',
        ])
        ->where('id_guru', $user->id_guru)
        ->get();

        $jurnals = Jurnal::where('id_user', $user->id_user)
            ->latest('tanggal')
            ->get();

        return view('guru.jurnal.index', compact(
            'jadwals',
            'jurnals'
        ));
    }

    public function create(Jadwal $jadwal)
    {
        $user = auth()->user();

        if ($jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->orderBy('nama_siswa')
            ->get();

        return view('guru.jurnal.create', compact(
            'jadwal',
            'siswa'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwals,id_jadwal',
            'tanggal' => 'required|date',
            'materi' => 'required|string|max:255',
            'status_guru' => 'required|in:Hadir,Izin,Sakit,Tanpa Keterangan',
            'ada_tugas' => 'required|in:Ya,Tidak',
            'deskripsi_tugas' => 'nullable|string',
            'catatan_umum' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();

        $jadwal = Jadwal::findOrFail($validated['id_jadwal']);

        if ($jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        $validated['id_kelas'] = $jadwal->id_kelas;
        $validated['id_guru'] = $jadwal->id_guru;
        $validated['id_user'] = $user->id_user;
        $validated['id_jam_mulai'] = $jadwal->id_jam_mulai;
        $validated['id_jam_selesai'] = $jadwal->id_jam_selesai;
        $validated['status_validasi_guru'] = 'Menunggu';

        $validated['jml_hadir'] = 0;
        $validated['jml_tidak_hadir'] = 0;

        Jurnal::create($validated);

        return redirect()
            ->route('guru.jurnal.index')
            ->with('success', 'Jurnal berhasil disimpan.');
    }
}