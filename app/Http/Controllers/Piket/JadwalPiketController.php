<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PiketJadwal;
use Illuminate\Http\Request;

class JadwalPiketController extends Controller
{
    public function index(Request $request)
    {
        $query = PiketJadwal::with(['guru', 'pembuat'])
            ->orderBy('tanggal', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis_tugas', 'like', "%{$search}%")
                    ->orWhere('shift', 'like', "%{$search}%")
                    ->orWhereHas('guru', fn ($g) => $g->where('nama_guru', 'like', "%{$search}%"));
            });
        }

        $jadwals = $query->get();
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('piket.jadwal.index', compact('jadwals', 'gurus'));
    }

    public function create()
    {
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('piket.jadwal.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_guru' => 'required|exists:gurus,id_guru',
            'tanggal' => 'required|date',
            'shift' => 'required|in:Pagi,Siang,Waka',
            'jam_mulai' => 'required|string|max:20',
            'jam_selesai' => 'required|string|max:20',
            'jenis_tugas' => 'required|in:Piket KBM Pagi,Koordinator Piket KBM Pagi,Piket KBM Siang,Koordinator Piket KBM Siang,Piket Waka',
            'posisi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        PiketJadwal::create($validated);

        return redirect()->route('piket.jadwal.index')->with('success', 'Jadwal piket berhasil dibuat.');
    }

    public function edit(PiketJadwal $jadwal)
    {
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('piket.jadwal.edit', compact('jadwal', 'gurus'));
    }

    public function update(Request $request, PiketJadwal $jadwal)
    {
        $validated = $request->validate([
            'id_guru' => 'required|exists:gurus,id_guru',
            'tanggal' => 'required|date',
            'shift' => 'required|in:Pagi,Siang,Waka',
            'jam_mulai' => 'required|string|max:20',
            'jam_selesai' => 'required|string|max:20',
            'jenis_tugas' => 'required|in:Piket KBM Pagi,Koordinator Piket KBM Pagi,Piket KBM Siang,Koordinator Piket KBM Siang,Piket Waka',
            'posisi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal->update($validated);

        return redirect()->route('piket.jadwal.index')->with('success', 'Jadwal piket berhasil diperbarui.');
    }

    public function destroy(PiketJadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('piket.jadwal.index')->with('success', 'Jadwal piket berhasil dihapus.');
    }
}
