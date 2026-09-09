<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
   
    public function index(Request $request)
{
    $kelas = Kelas::with('waliKelas')
        ->withCount('siswas')
        ->when($request->search, function ($query, $search) {
            $query->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhereHas('waliKelas', function ($q) use ($search) {
                      $q->where('nama_guru', 'like', "%{$search}%"); // Sesuaikan 'nama' dengan kolom nama guru di tabel gurus
                  });
        })
        ->get();

    return view('admin.kelas.index', compact('kelas'));
}

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        Kelas::create($request->all());

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
        ]);

        $kelas->update($request->all());

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil dihapus!');
    }
}