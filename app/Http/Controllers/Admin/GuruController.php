<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query();

        // Pencarian NIP atau nama guru
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', '%' . $search . '%')
                  ->orWhere('nama_guru', 'like', '%' . $search . '%');
            });
        }

        // Data guru terbaru
        $gurus = $query
            ->latest('id_guru')
            ->paginate(10)
            ->withQueryString();

        // Total guru
        $totalGuru = Guru::count();

        return view('admin.guru.index', compact(
            'gurus',
            'totalGuru'
        ));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:50|unique:gurus,nip',
            'nama_guru' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        Guru::create($validated);

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $guru = Guru::findOrFail($id);

        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $validated = $request->validate([
            'nip' => 'required|string|max:50|unique:gurus,nip,' . $guru->id_guru . ',id_guru',
            'nama_guru' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $guru->update($validated);

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        $guru->delete();

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}