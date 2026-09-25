<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\JamPel;
use App\Models\Jurnal;
use App\Models\GuruQrAttendance;
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

        $beforeUpdate = $jadwal->only([
            'id_guru',
            'id_mapel',
            'id_kelas',
            'id_jam_mulai',
            'id_jam_selesai',
            'hari',
        ]);

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

        $assignmentChanged = collect($beforeUpdate)->some(function ($value, $key) use ($validated) {
            return ($validated[$key] ?? null) !== $value;
        });

        if ($assignmentChanged) {
            Jurnal::where(function ($query) use ($beforeUpdate, $jadwal) {
                $query->where('id_jadwal', $jadwal->id_jadwal)
                    ->orWhere(function ($nested) use ($beforeUpdate) {
                        $nested->where('id_guru', $beforeUpdate['id_guru'])
                            ->where('id_kelas', $beforeUpdate['id_kelas'])
                            ->where('id_jam_mulai', $beforeUpdate['id_jam_mulai'])
                            ->where('id_jam_selesai', $beforeUpdate['id_jam_selesai']);
                    });
            })->delete();

            GuruQrAttendance::where('id_jadwal', $jadwal->id_jadwal)->delete();
        }

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        Jurnal::where(function ($query) use ($jadwal) {
            $query->where('id_jadwal', $jadwal->id_jadwal)
                ->orWhere(function ($nested) use ($jadwal) {
                    $nested->where('id_guru', $jadwal->id_guru)
                        ->where('id_kelas', $jadwal->id_kelas)
                        ->where('id_jam_mulai', $jadwal->id_jam_mulai)
                        ->where('id_jam_selesai', $jadwal->id_jam_selesai);
                });
        })->delete();

        GuruQrAttendance::where('id_jadwal', $jadwal->id_jadwal)->delete();
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }
}