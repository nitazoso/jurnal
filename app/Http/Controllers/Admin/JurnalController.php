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

    public function edit(Jurnal $jurnal)
    {
        $jurnal->load([
            'guru',
            'kelas',
            'jadwal',
            'jamMulai',
            'jamSelesai',
        ]);

        $jadwals = \App\Models\Jadwal::with(['kelas', 'mapel', 'jamMulai', 'jamSelesai'])
            ->orderBy('hari')
            ->orderBy('id_jam_mulai')
            ->get();

        $guru = \App\Models\Guru::orderBy('nama_guru')->get();
        $kelas = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $jamPels = \App\Models\JamPel::orderBy('jam_mulai')->get();

        return view('admin.jurnal.edit', compact('jurnal', 'jadwals', 'guru', 'kelas', 'jamPels'));
    }

    public function update(Request $request, Jurnal $jurnal)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_jadwal' => 'required|exists:jadwals,id_jadwal',
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'materi' => 'required|string|max:255',
            'status_guru' => 'required|in:Hadir,Izin,Sakit,Tanpa Keterangan',
            'jml_hadir' => 'required|integer|min:0',
            'jml_tidak_hadir' => 'required|integer|min:0',
            'ada_tugas' => 'required|in:Ya,Tidak',
            'deskripsi_tugas' => 'nullable|string',
            'catatan_umum' => 'nullable|string|max:255',
        ]);

        $jurnal->update([
            'tanggal' => $validated['tanggal'],
            'id_jadwal' => $validated['id_jadwal'],
            'id_guru' => $validated['id_guru'],
            'id_kelas' => $validated['id_kelas'],
            'id_jam_mulai' => $validated['id_jam_mulai'],
            'id_jam_selesai' => $validated['id_jam_selesai'],
            'materi' => $validated['materi'],
            'status_guru' => $validated['status_guru'],
            'jml_hadir' => $validated['jml_hadir'],
            'jml_tidak_hadir' => $validated['jml_tidak_hadir'],
            'ada_tugas' => $validated['ada_tugas'],
            'deskripsi_tugas' => $validated['deskripsi_tugas'] ?? null,
            'catatan_umum' => $validated['catatan_umum'] ?? null,
        ]);

        return redirect()->route('admin.jurnal.show', $jurnal)
            ->with('success', 'Jurnal berhasil diperbarui.');
    }
}