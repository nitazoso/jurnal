<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\JamPel;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index()
    {
        $jurnals = Jurnal::latest('tanggal')->paginate(10);

        return view('admin.jurnal.index', compact('jurnals'));
    }

    public function create()
    {
        $guru = Guru::all();
        $kelas = Kelas::all();
        $jadwals = Jadwal::all();
        $jamPels = JamPel::all();

        return view('admin.jurnal.create', compact(
            'guru',
            'kelas',
            'jadwals',
            'jamPels'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwals,id_jadwal',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'tanggal' => 'required|date',
            'materi' => 'required|string|max:255',
            'status_guru' => 'required|in:Hadir,Izin,Sakit,Tanpa Keterangan',
            'ada_tugas' => 'required|in:Ya,Tidak',
            'deskripsi_tugas' => 'nullable|string',
            'jml_hadir' => 'required|integer|min:0',
            'jml_tidak_hadir' => 'required|integer|min:0',
            'catatan_umum' => 'nullable|string|max:255',
        ]);

        $validated['id_user'] = auth()->id() ?? 1;
        $validated['status_validasi_guru'] = 'Menunggu';

        Jurnal::create($validated);

        return redirect()
            ->route('admin.jurnal.index')
            ->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function edit(Jurnal $jurnal)
    {
        $guru = Guru::all();
        $kelas = Kelas::all();
        $jadwals = Jadwal::all();
        $jamPels = JamPel::all();

        return view('admin.jurnal.edit', compact(
            'jurnal',
            'guru',
            'kelas',
            'jadwals',
            'jamPels'
        ));
    }

    public function update(Request $request, Jurnal $jurnal)
    {
        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwals,id_jadwal',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'tanggal' => 'required|date',
            'materi' => 'required|string|max:255',
            'status_guru' => 'required|in:Hadir,Izin,Sakit,Tanpa Keterangan',
            'ada_tugas' => 'required|in:Ya,Tidak',
            'deskripsi_tugas' => 'nullable|string',
            'jml_hadir' => 'required|integer|min:0',
            'jml_tidak_hadir' => 'required|integer|min:0',
            'catatan_umum' => 'nullable|string|max:255',
        ]);

        $jurnal->update($validated);

        return redirect()
            ->route('admin.jurnal.index')
            ->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy(Jurnal $jurnal)
    {
        $jurnal->delete();

        return redirect()
            ->route('admin.jurnal.index')
            ->with('success', 'Jurnal berhasil dihapus.');
    }
}