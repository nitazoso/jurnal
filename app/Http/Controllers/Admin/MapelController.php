<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('nama_mapel', 'like', '%' . $search . '%');
        }

        $mapels = $query
            ->latest('id_mapel')
            ->paginate(10)
            ->withQueryString();

        $totalMapel = Mapel::count();

        return view('admin.mapel.index', compact(
            'mapels',
            'totalMapel'
        ));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mapels,nama_mapel',
        ]);

        Mapel::create($validated);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);

        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);

        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mapels,nama_mapel,' . $mapel->id_mapel . ',id_mapel',
        ]);

        $mapel->update($validated);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);

        $mapel->delete();

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}