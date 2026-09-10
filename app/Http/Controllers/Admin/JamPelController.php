<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamPel;
use Illuminate\Http\Request;

class JamPelController extends Controller
{
    public function index(Request $request)
    {
        $query = JamPel::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('klp_hari', 'like', '%' . $search . '%')
                  ->orWhere('jenis', 'like', '%' . $search . '%')
                  ->orWhere('jam_ke', 'like', '%' . $search . '%');
            });
        }

        $jamPels = $query
            ->orderBy('klp_hari')
            ->orderBy('jam_ke')
            ->paginate(10)
            ->withQueryString();

        $totalJam = JamPel::count();

        return view('admin.jam.index', compact(
            'jamPels',
            'totalJam'
        ));
    }

    public function create()
    {
        return view('admin.jam.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'klp_hari' => 'required|string|max:50',
            'jam_ke' => 'required|integer|min:1',
            'jenis' => 'required|string|max:50',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        JamPel::create($validated);

        return redirect()
            ->route('admin.jam.index')
            ->with('success', 'Jam pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jamPel = JamPel::findOrFail($id);

        return view('admin.jam.edit', compact('jamPel'));
    }

    public function update(Request $request, $id)
    {
        $jamPel = JamPel::findOrFail($id);

        $validated = $request->validate([
            'klp_hari' => 'required|string|max:50',
            'jam_ke' => 'required|integer|min:1',
            'jenis' => 'required|string|max:50',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $jamPel->update($validated);

        return redirect()
            ->route('admin.jam.index')
            ->with('success', 'Jam pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jamPel = JamPel::findOrFail($id);

        $jamPel->delete();

        return redirect()
            ->route('admin.jam.index')
            ->with('success', 'Jam pelajaran berhasil dihapus.');
    }
}