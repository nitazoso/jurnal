<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Dispen;
use App\Models\Siswa;
use App\Models\JamPel;
use Illuminate\Http\Request;

class DispenController extends Controller
{
    public function index()
    {
        $dispens = Dispen::with('siswa', 'jamMulai', 'jamSelesai')
            ->latest('tanggal')
            ->paginate(10);

        return view('piket.dispen.index', compact('dispens'));
    }

    public function create()
    {
        $siswa = Siswa::orderBy('nama_siswa')->get();

        $jamPels = JamPel::where('jenis', 'pelajaran')
            ->orderBy('jam_ke')
            ->get();

        return view('piket.dispen.create', compact('siswa', 'jamPels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ]);

        Dispen::create($validated);

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil ditambahkan.');
    }

    public function edit(Dispen $dispen)
    {
        $siswa = Siswa::orderBy('nama_siswa')->get();

        $jamPels = JamPel::where('jenis', 'pelajaran')
            ->orderBy('jam_ke')
            ->get();

        return view('piket.dispen.edit', compact(
            'dispen',
            'siswa',
            'jamPels'
        ));
    }

    public function update(Request $request, Dispen $dispen)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ]);

        $dispen->update($validated);

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil diperbarui.');
    }

    public function destroy(Dispen $dispen)
    {
        $dispen->delete();

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil dihapus.');
    }
}