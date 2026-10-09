<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;

use App\Models\Guru; 
use Illuminate\Validation\Rule;
class KelasController extends Controller
{
public function index(Request $request)
    {
        $kelas = Kelas::with('waliKelas')
            ->withCount('siswas')
            ->when($request->search, function ($query, $search) {
                $query->where('nama_kelas', 'like', "%{$search}%")
                      ->orWhereHas('waliKelas', function ($q) use ($search) {
                          $q->where('nama_guru', 'like', "%{$search}%");
                      });
            })
            ->get();

        $waliKelasIds = Kelas::whereNull('deleted_at')
            ->whereNotNull('wali_kelas')
            ->pluck('wali_kelas')
            ->toArray();

        $gurus = Guru::whereNotIn('id_guru', $waliKelasIds)->get();

        $lastKelas = Kelas::withTrashed()->orderBy('id_kelas', 'desc')->first();
        $nextId = $lastKelas ? ($lastKelas->id_kelas + 1) : 1;

        return view('admin.kelas.index', compact('kelas', 'gurus', 'nextId'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }


public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas' => [
                'required',
                'exists:gurus,id_guru',
                // Pengecekan unique HANYA untuk kelas yang belum ter-soft delete
                Rule::unique('kelases', 'wali_kelas')->whereNull('deleted_at'),
            ],
        ], [
            'wali_kelas.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain!'
        ]);

        Kelas::create([
            'id_kelas' => $request->id_kelas,
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas' => $request->wali_kelas,
        ]);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil disimpan!');
    }

    public function edit(Kelas $kelas)
    {
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',

            'wali_kelas' => 'required|exists:gurus,id_guru',
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