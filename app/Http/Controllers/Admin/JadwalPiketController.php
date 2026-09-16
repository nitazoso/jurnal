<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PiketJadwal;
use Illuminate\Http\Request;

class JadwalPiketController extends Controller
{
    public function index(Request $request)
    {
        $query = PiketJadwal::with(['guru', 'pembuat'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($query) use ($search) {
                    $query->where('jenis_tugas', 'like', "%{$search}%")
                        ->orWhere('shift', 'like', "%{$search}%")
                        ->orWhereHas('guru', fn ($guru) => $guru->where('nama_guru', 'like', "%{$search}%"));
                });
            })
            ->orderBy('tanggal')
            ->orderBy('jam_mulai');

        $jadwals = $query->get();

        return view('admin.jadwal-piket.index', compact('jadwals'));
    }

    public function create()
    {
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('admin.jadwal-piket.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        PiketJadwal::create($this->validated($request) + ['created_by' => auth()->id()]);

        return redirect()->route('admin.jadwal-piket.index')->with('success', 'Jadwal piket berhasil dibuat.');
    }

    public function edit(PiketJadwal $jadwal)
    {
        $gurus = Guru::orderBy('nama_guru')->get();

        return view('admin.jadwal-piket.edit', compact('jadwal', 'gurus'));
    }

    public function update(Request $request, PiketJadwal $jadwal)
    {
        $jadwal->update($this->validated($request));

        return redirect()->route('admin.jadwal-piket.index')->with('success', 'Jadwal piket berhasil diperbarui.');
    }

    public function destroy(PiketJadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('admin.jadwal-piket.index')->with('success', 'Jadwal piket berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'id_guru' => 'required|exists:gurus,id_guru',
            'tanggal' => 'required|date',
            'shift' => 'required|in:Pagi,Siang,Waka',
            'jam_mulai' => 'required|string|max:20',
            'jam_selesai' => 'required|string|max:20',
            'jenis_tugas' => 'required|in:Piket KBM Pagi,Koordinator Piket KBM Pagi,Piket KBM Siang,Koordinator Piket KBM Siang,Piket Waka',
            'posisi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);
    }
}
