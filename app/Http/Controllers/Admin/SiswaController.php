<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelas_id = $request->get('kelas_id');

        $kelas = Kelas::with('waliKelas')->find($kelas_id);

        $query = Siswa::query();

        if ($kelas_id) {
            $query->where('id_kelas', $kelas_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $siswas = $query
            ->orderBy('no_presensi', 'asc')
            ->paginate(10);

        // Ambil ID guru yang sedang aktif menjadi wali kelas
        $waliKelasAktifIds = Kelas::whereNull('deleted_at')
            ->whereNotNull('wali_kelas')
            ->pluck('wali_kelas')
            ->toArray();

        // Ambil guru yang belum menjadi wali kelas
        $gurus = Guru::whereNotIn('id_guru', $waliKelasAktifIds)
            ->when($kelas && $kelas->wali_kelas, function ($q) use ($kelas) {
                return $q->orWhere('id_guru', $kelas->wali_kelas);
            })
            ->orderBy('nama_guru', 'asc')
            ->get();

        return view('admin.kelas.siswa', compact(
            'siswas',
            'kelas',
            'kelas_id',
            'gurus'
        ));
    }

    // =========================================================
    // FORM TAMBAH SISWA
    // =========================================================

    public function create(Request $request)
    {
        $kelas_id = $request->get('kelas_id');

        $kelases = Kelas::all();

        return view('admin.siswa.create', compact(
            'kelases',
            'kelas_id'
        ));
    }

    // =========================================================
    // SIMPAN SISWA BARU
    // =========================================================

    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa'    => 'required|string|max:255',
            'nis'           => 'required|string|unique:siswas,nis',
            'jenis_kelamin' => 'required|in:L,P',
            'id_kelas'      => 'required|exists:kelases,id_kelas',
        ], [
            'nama_siswa.required'    => 'Nama siswa wajib diisi.',
            'nis.required'           => 'NIS wajib diisi.',
            'nis.unique'             => 'NIS sudah terdaftar. Silakan gunakan NIS yang berbeda.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'       => 'Jenis kelamin yang dipilih tidak valid.',
            'id_kelas.required'      => 'Kelas wajib dipilih.',
            'id_kelas.exists'        => 'Kelas yang dipilih tidak ditemukan.',
        ]);

        $siswa = Siswa::create([
            'nama_siswa'    => $request->nama_siswa,
            'nis'           => $request->nis,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas'      => $request->id_kelas,
            'no_presensi'   => 0,
        ]);

        // Urutkan ulang nomor presensi
        $this->reorderPresensi($siswa->id_kelas);

        return redirect()
            ->route('admin.kelas.siswa', [
                'kelas_id' => $request->id_kelas
            ])
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    // =========================================================
    // CEK NIS
    // Dipakai AJAX sebelum modal verifikasi dibuka
    // =========================================================

    public function checkNis(Request $request)
    {
        $nis = trim((string) $request->get('nis'));
        $excludeId = $request->get('exclude_id');

        // Kalau NIS kosong
        if ($nis === '') {
            return response()->json([
                'exists' => false,
                'nama_siswa' => null,
            ]);
        }

        $query = Siswa::where('nis', $nis);

        // Kalau sedang edit siswa,
        // data siswa itu sendiri tidak dianggap duplikat.
        if ($excludeId) {
            $query->where('id_siswa', '!=', $excludeId);
        }

        $siswa = $query->first();

        return response()->json([
            'exists' => $siswa !== null,
            'nama_siswa' => $siswa?->nama_siswa,
        ]);
    }

    // =========================================================
    // FORM EDIT SISWA
    // =========================================================

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);

        $kelases = Kelas::all();

        return view('admin.siswa.edit', compact(
            'siswa',
            'kelases'
        ));
    }

    // =========================================================
    // UPDATE SISWA
    // =========================================================

    public function update(Request $request, $id)
    {
        $siswa = Siswa::where('id_siswa', $id)->firstOrFail();

        $request->validate([
            'nama_siswa' => 'required|string|max:255',

            'nis' => [
                'required',
                'string',
                Rule::unique('siswas', 'nis')
                    ->ignore($siswa->id_siswa, 'id_siswa'),
            ],

            'jenis_kelamin' => 'required|in:L,P',

            'id_kelas' => 'nullable|exists:kelases,id_kelas',
        ], [
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin yang dipilih tidak valid.',
            'id_kelas.exists' => 'Kelas yang dipilih tidak ditemukan.',
        ]);

        $oldKelasId = $siswa->id_kelas;

        $newKelasId = $request->id_kelas ?? $siswa->id_kelas;

        $siswa->update([
            'nama_siswa'    => $request->nama_siswa,
            'nis'           => $request->nis,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas'      => $newKelasId,
        ]);

        // Urutkan kelas baru
        $this->reorderPresensi($newKelasId);

        // Kalau pindah kelas, urutkan ulang kelas lama juga
        if ($oldKelasId != $newKelasId) {
            $this->reorderPresensi($oldKelasId);
        }

        return redirect()
            ->route('admin.kelas.siswa', [
                'kelas_id' => $newKelasId
            ])
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    // =========================================================
    // HAPUS SISWA
    // =========================================================

    public function destroy($id)
    {
        $siswa = Siswa::where('id_siswa', $id)->firstOrFail();

        $kelas_id = $siswa->id_kelas;

        $siswa->delete();

        // Urutkan ulang nomor presensi
        $this->reorderPresensi($kelas_id);

        return redirect()
            ->route('admin.kelas.siswa', [
                'kelas_id' => $kelas_id
            ])
            ->with('success', 'Data siswa berhasil dihapus!');
    }

    // =========================================================
    // REORDER NOMOR PRESENSI
    // =========================================================

    private function reorderPresensi($id_kelas)
    {
        $siswas = Siswa::where('id_kelas', $id_kelas)
            ->orderBy('nama_siswa', 'asc')
            ->get();

        foreach ($siswas as $index => $siswa) {
            $siswa->update([
                'no_presensi' => $index + 1
            ]);
        }
    }
}