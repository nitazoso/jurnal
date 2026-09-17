<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKesiswaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalKesiswaanController extends Controller
{
    public function index()
    {
        $jadwals = JadwalKesiswaan::with('user')
            ->orderBy('tanggal')
            ->get();

        return view('admin.jadwal-kesiswaan.index', compact('jadwals'));
    }

    public function create()
    {
        $users = User::where('role', 'Kesiswaan')->orderBy('nama_user')->get();

        return view('admin.jadwal-kesiswaan.create', compact('users'));
    }

    public function store(Request $request)
    {
        JadwalKesiswaan::create($this->validated($request));

        return redirect()->route('admin.jadwal-kesiswaan.index')
            ->with('success', 'Jadwal kesiswaan berhasil dibuat.');
    }

    public function edit(JadwalKesiswaan $jadwalKesiswaan)
    {
        $users = User::where('role', 'Kesiswaan')->orderBy('nama_user')->get();

        return view('admin.jadwal-kesiswaan.edit', [
            'jadwal' => $jadwalKesiswaan,
            'users' => $users,
        ]);
    }

    public function update(Request $request, JadwalKesiswaan $jadwalKesiswaan)
    {
        $jadwalKesiswaan->update($this->validated($request, $jadwalKesiswaan));

        return redirect()->route('admin.jadwal-kesiswaan.index')
            ->with('success', 'Jadwal kesiswaan berhasil diperbarui.');
    }

    public function destroy(JadwalKesiswaan $jadwalKesiswaan)
    {
        $jadwalKesiswaan->delete();

        return redirect()->route('admin.jadwal-kesiswaan.index')
            ->with('success', 'Jadwal kesiswaan berhasil dihapus.');
    }

    private function validated(Request $request, ?JadwalKesiswaan $jadwal = null): array
    {
        return $request->validate([
            'id_user' => ['required', Rule::exists('users', 'id_user')->where('role', 'Kesiswaan')],
            'tanggal' => [
                'required',
                'date',
                Rule::unique('jadwal_kesiswaans', 'tanggal')->ignore($jadwal?->id_jadwal_kesiswaan, 'id_jadwal_kesiswaan'),
            ],
        ]);
    }
}
