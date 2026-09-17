<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\JamPel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
public function index(Request $request)
{
    $kelases = Kelas::orderBy('nama_kelas')->get();

    $selectedKelasId = $request->get(
        'id_kelas',
        $kelases->first()?->id_kelas
    );

    $selectedKelas = $kelases
        ->where('id_kelas', $selectedKelasId)
        ->first();

    $allJamPels = JamPel::orderBy('jam_mulai', 'asc')->get();

    $jamPelsGrouped = [];
    foreach ($allJamPels as $jam) {
        $jamPelsGrouped[$jam->jam_ke][$jam->klp_hari] = $jam;
    }

    $jamPels = $allJamPels->pluck('jam_ke')->unique()->filter()->values();

    $maxJamQuery = DB::table('jadwals')
        ->join('jam_pels', 'jadwals.id_jam_selesai', '=', 'jam_pels.id_jam')
        ->whereNull('jadwals.deleted_at')
        ->when($selectedKelasId, function ($q) use ($selectedKelasId) {
            $q->where('jadwals.id_kelas', $selectedKelasId);
        })
        ->select('jadwals.hari', DB::raw('MAX(jam_pels.jam_ke) as max_jam'))
        ->groupBy('jadwals.hari')
        ->pluck('max_jam', 'hari')
        ->toArray();

    $defaultMaxJam = $jamPels->max() ?? 10;
    $maxSeninKamis = $allJamPels->where('klp_hari', 'Senin-Kamis')->max('jam_ke') ?? 11;
    $maxJumat      = $allJamPels->where('klp_hari', 'Jumat')->max('jam_ke') ?? 6;

    $maxJamPerHari = [
        'Senin'  => $maxSeninKamis,
        'Selasa' => $maxSeninKamis,
        'Rabu'   => $maxSeninKamis,
        'Kamis'  => $maxSeninKamis,
        'Jumat'  => $maxJumat,
    ];
    $jadwals = Jadwal::with(['guru', 'mapel', 'jamMulai', 'jamSelesai'])
        ->when($selectedKelasId, function ($q) use ($selectedKelasId) {
            $q->where('id_kelas', $selectedKelasId);
        })
        ->get();

    $gurus = Guru::orderBy('nama_guru')->get();
    $mapels = Mapel::orderBy('nama_mapel')->get();

return view('admin.jadwal.index', compact(
    'kelases', 
    'selectedKelasId', 
    'selectedKelas', 
    'jamPels', 
    'jamPelsGrouped', 
    'jadwals', 
    'mapels', 
    'gurus', 
    'maxJamPerHari'
));}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_mapel' => 'required|exists:mapels,id_mapel',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        Jadwal::create($validated);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_mapel' => 'required|exists:mapels,id_mapel',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        $jadwal->update($validated);

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }
}