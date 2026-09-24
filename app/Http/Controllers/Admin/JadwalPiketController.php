<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PiketJadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalPiketController extends Controller
{
    public function index(Request $request)
    {
        $query = PiketJadwal::with([
            'guru', 'pembuat', 'petugasKbmPagi', 'koordinatorKbmPagi',
            'petugasKbmSiang', 'koordinatorKbmSiang', 'piketWaka',
        ])
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
        $gurus = User::with('guru')
            ->where('role', 'Guru')
            ->whereNotNull('id_guru')
            ->orderBy('username')
            ->get();

        return view('admin.jadwal-piket.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        PiketJadwal::create($this->validated($request) + ['created_by' => auth()->id()]);

        return redirect()->route('admin.jadwal-piket.index')->with('success', 'Jadwal piket berhasil dibuat.');
    }

    public function edit(PiketJadwal $jadwal)
    {
        $gurus = User::with('guru')
            ->where('role', 'Guru')
            ->whereNotNull('id_guru')
            ->orderBy('username')
            ->get();

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
        $data = $request->validate([
            'tanggal' => 'required|date',
            'petugas_kbm_pagi_id' => ['required', $this->guruUsernameRule()],
            'koordinator_kbm_pagi_id' => ['required', $this->guruUsernameRule()],
            'petugas_kbm_siang_id' => ['required', $this->guruUsernameRule()],
            'koordinator_kbm_siang_id' => ['required', $this->guruUsernameRule()],
            'piket_waka_id' => ['required', $this->guruUsernameRule()],
            'jam_mulai_kbm_pagi' => 'required|string|max:20',
            'jam_selesai_kbm_pagi' => 'required|string|max:20',
            'jam_mulai_koordinator_pagi' => 'required|string|max:20',
            'jam_selesai_koordinator_pagi' => 'required|string|max:20',
            'jam_mulai_kbm_siang' => 'required|string|max:20',
            'jam_selesai_kbm_siang' => 'required|string|max:20',
            'jam_mulai_koordinator_siang' => 'required|string|max:20',
            'jam_selesai_koordinator_siang' => 'required|string|max:20',
        ]);

        $usernameFields = [
            'petugas_kbm_pagi_id',
            'koordinator_kbm_pagi_id',
            'petugas_kbm_siang_id',
            'koordinator_kbm_siang_id',
            'piket_waka_id',
        ];

        $guruIds = User::where('role', 'Guru')
            ->whereIn('username', array_map(fn ($field) => $data[$field], $usernameFields))
            ->pluck('id_guru', 'username');

        foreach ($usernameFields as $field) {
            $data[$field] = $guruIds[$data[$field]];
        }

        return array_merge($data, [
            'id_guru' => $data['petugas_kbm_pagi_id'],
            'shift' => 'Pagi',
            'jam_mulai' => $data['jam_mulai_kbm_pagi'],
            'jam_selesai' => $data['jam_selesai_kbm_pagi'],
            'jenis_tugas' => 'Piket KBM Pagi',
        ]);
    }

    private function guruUsernameRule()
    {
        return Rule::exists('users', 'username')->where(fn ($query) => $query
            ->where('role', 'Guru')
            ->whereNotNull('id_guru'));
    }
}
