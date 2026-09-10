<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // Menampilkan daftar siswa
    public function index(Request $request)
    {
        $kelas_id = $request->get('kelas_id');
        $kelas = Kelas::with('waliKelas')->find($kelas_id);

        $query = Siswa::query();

        // Filter berdasarkan kelas
        if ($kelas_id) {
            $query->where('id_kelas', $kelas_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Diperbaiki: 'nama' diganti jadi 'nama_siswa'
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan no_presensi (A-Z) bukan latest()
        $siswas = $query->orderBy('no_presensi', 'asc')->paginate(10);

        return view('admin.kelas.siswa', compact('siswas', 'kelas', 'kelas_id'));
    }

    // Menampilkan form tambah siswa
    public function create(Request $request)
    {
        $kelas_id = $request->get('kelas_id');
        $kelases = Kelas::all();

        return view('admin.siswa.create', compact('kelases', 'kelas_id'));
    }

    // Menyimpan data siswa baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa'    => 'required|string|max:255', // Diperbaiki: nama -> nama_siswa
            'nis'           => 'required|string|unique:siswas,nis',
            'jenis_kelamin' => 'required|in:L,P',
            'id_kelas'      => 'required|exists:kelases,id_kelas',
        ], [
            'nis.unique'    => 'NIS ini sudah terdaftar!',
        ]);

        $siswa = Siswa::create([
            'nama_siswa'    => $request->nama_siswa, // Diperbaiki
            'nis'           => $request->nis,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas'      => $request->id_kelas,
            'no_presensi'   => 0, // Nilai awal sebelum di-reorder
        ]);

        // Otomatis urutkan presensi di kelas tersebut
        $this->reorderPresensi($siswa->id_kelas);

        return redirect()->route('admin.siswa.index', ['kelas_id' => $request->id_kelas])
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    // Form edit siswa
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelases = Kelas::all();

        return view('admin.siswa.edit', compact('siswa', 'kelases'));
    }

    // Update data siswa
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama_siswa'    => 'required|string|max:255', // Diperbaiki
            'nis'           => 'required|string|unique:siswas,nis,' . $id . ',id_siswa',
            'jenis_kelamin' => 'required|in:L,P',
            'id_kelas'      => 'required|exists:kelases,id_kelas',
        ]);

        $oldKelasId = $siswa->id_kelas;

        $siswa->update([
            'nama_siswa'    => $request->nama_siswa, // Diperbaiki
            'nis'           => $request->nis,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas'      => $request->id_kelas,
        ]);

        // Reorder kelas baru
        $this->reorderPresensi($siswa->id_kelas);

        // Jika siswa pindah kelas, reorder juga kelas lamanya
        if ($oldKelasId != $siswa->id_kelas) {
            $this->reorderPresensi($oldKelasId);
        }

        return redirect()->route('admin.siswa.index', ['kelas_id' => $request->id_kelas])
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    // Soft Delete Siswa
    public function destroy($id)
    {
        $siswa = Siswa::where('id_siswa', $id)->firstOrFail();
        $kelas_id = $siswa->id_kelas;

        $siswa->delete();

        // Reorder ulang kelas setelah siswa dihapus
        $this->reorderPresensi($kelas_id);

        return redirect()->route('admin.siswa.index', ['kelas_id' => $kelas_id])
            ->with('success', 'Data siswa berhasil dihapus!');
    }

    // Helper Private Function untuk Reorder Presensi A-Z per Kelas
    private function reorderPresensi($id_kelas)
    {
        $siswas = Siswa::where('id_kelas', $id_kelas)
            ->orderBy('nama_siswa', 'asc')
            ->get();

        foreach ($siswas as $index => $s) {
            $s->update(['no_presensi' => $index + 1]);
        }
    }
}
