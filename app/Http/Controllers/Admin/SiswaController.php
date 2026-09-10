<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Kelas $kelas)
    {
        $siswas = Siswa::where('id_kelas', $kelas->id_kelas)
            ->orderBy('nama_siswa')
            ->get();

        return view('admin.kelas.siswa.index', compact(
            'kelas',
            'siswas'
        ));
    }

    public function create(Kelas $kelas)
    {
        return view('admin.kelas.siswa.create', compact('kelas'));
    }

    public function store(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:50|unique:siswas,nis',
            'nama_siswa' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $validated['id_kelas'] = $kelas->id_kelas;

        Siswa::create($validated);

        $kelas->update([
            'jumlah_siswa' => Siswa::where(
                'id_kelas',
                $kelas->id_kelas
            )->count(),
        ]);

        return redirect()
            ->route('admin.kelas.siswa.index', $kelas->id_kelas)
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas, Siswa $siswa)
    {
        if ($siswa->id_kelas != $kelas->id_kelas) {
            abort(404);
        }

        return view('admin.kelas.siswa.edit', compact(
            'kelas',
            'siswa'
        ));
    }

    public function update(
        Request $request,
        Kelas $kelas,
        Siswa $siswa
    ) {
        if ($siswa->id_kelas != $kelas->id_kelas) {
            abort(404);
        }

        $validated = $request->validate([
            'nis' => 'required|string|max:50|unique:siswas,nis,' .
                $siswa->id_siswa . ',id_siswa',
            'nama_siswa' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $siswa->update($validated);

        return redirect()
            ->route('admin.kelas.siswa.index', $kelas->id_kelas)
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas, Siswa $siswa)
    {
        if ($siswa->id_kelas != $kelas->id_kelas) {
            abort(404);
        }

        $siswa->delete();

        $kelas->update([
            'jumlah_siswa' => Siswa::where(
                'id_kelas',
                $kelas->id_kelas
            )->count(),
        ]);

        return redirect()
            ->route('admin.kelas.siswa.index', $kelas->id_kelas)
            ->with('success', 'Siswa berhasil dihapus.');
    }
}