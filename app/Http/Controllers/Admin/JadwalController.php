<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\JamPel;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with([
            'guru',
            'mapel',
            'kelas',
            'jamMulai',
            'jamSelesai',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('hari', 'like', '%' . $search . '%')
                    ->orWhere('tahun_ajaran', 'like', '%' . $search . '%')
                    ->orWhereHas('guru', function ($guru) use ($search) {
                        $guru->where('nama_guru', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('mapel', function ($mapel) use ($search) {
                        $mapel->where('nama_mapel', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('kelas', function ($kelas) use ($search) {
                        $kelas->where('nama_kelas', 'like', '%' . $search . '%');
                    });
            });
        }

        $jadwals = $query
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->get();

        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $gurus = Guru::orderBy('nama_guru')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $jamPels = JamPel::orderBy('jam_ke')->get();

        return view('admin.jadwal.create', compact(
            'gurus',
            'mapels',
            'kelases',
            'jamPels'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_mapel' => 'required|exists:mapels,id_mapel',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'hari' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        Jadwal::create($validated);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $gurus = Guru::orderBy('nama_guru')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $jamPels = JamPel::orderBy('jam_ke')->get();

        return view('admin.jadwal.edit', compact(
            'jadwal',
            'gurus',
            'mapels',
            'kelases',
            'jamPels'
        ));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_mapel' => 'required|exists:mapels,id_mapel',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'hari' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $jadwal->update($validated);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $jadwal->delete();

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}