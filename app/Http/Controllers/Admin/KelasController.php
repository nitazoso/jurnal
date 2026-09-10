<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Guru;

use Illuminate\Validation\Rule;
class KelasController extends Controller
{

    /**
     * Menampilkan daftar kelas.
     */
    public function index(Request $request)
    {
        $query = Kelas::with('waliKelas')
            ->withCount('siswas');

        // Search berdasarkan nama kelas atau nama wali kelas
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_kelas', 'like', '%' . $search . '%')
                  ->orWhereHas('waliKelas', function ($guru) use ($search) {
                      $guru->where('nama_guru', 'like', '%' . $search . '%')
                           ->orWhere('nip', 'like', '%' . $search . '%');
                  });
            });
        }

        $kelas = $query
            ->latest('id_kelas')
            ->get();

        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * Menampilkan form tambah kelas.
     */
    public function create()
    {
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('admin.kelas.create', compact('gurus'));
    }


    /**
     * Menyimpan kelas baru.
     */
    public function store(Request $request)

    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',

            'wali_kelas' => 'nullable|exists:gurus,id_guru',
            'jumlah_siswa' => 'required|integer|min:0',
        ]);

        Kelas::create($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');

    }

    /**
     * Menampilkan form edit kelas.
     */
    public function edit(Kelas $kelas)
    {
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('admin.kelas.edit', compact(
            'kelas',
            'gurus'
        ));
    }

    /**
     * Memperbarui data kelas.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas' => 'nullable|exists:gurus,id_guru',
            'jumlah_siswa' => 'required|integer|min:0',

        ]);

        $kelas->update($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Menghapus kelas.
     */
    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}