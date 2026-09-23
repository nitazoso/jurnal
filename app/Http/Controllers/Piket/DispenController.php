<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Dispen;
use App\Models\JamPel;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DispenController extends Controller
{
    /**
     * Menampilkan daftar dispen.
     */
    public function index()
    {
        $dispens = Dispen::with([
            'siswa',
            'jamMulai',
            'jamSelesai',
            'approver'
        ])
            ->where('status', 'menunggu')
            ->latest('tanggal')
            ->paginate(10);

        return view('piket.dispen.index', compact('dispens'));
    }

    /**
     * Menampilkan riwayat dispen yang sudah dikonfirmasi.
     */
    public function history()
    {
        $dispens = Dispen::with([
            'siswa',
            'jamMulai',
            'jamSelesai',
            'approver',
        ])
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->latest('disetujui_pada')
            ->paginate(10);

        return view('piket.dispen.history', compact('dispens'));
    }

    /**
     * Form tambah dispen.
     */
    public function create()
    {
        $jamPels = JamPel::where('jenis', 'pelajaran')
            ->orderBy('jam_ke')
            ->get();

        return view('piket.dispen.create', $this->formData($jamPels));
    }

    /**
     * Simpan dispen baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_siswa' => [
                'required',
                Rule::exists('siswas', 'id_siswa')->where(
                    fn ($query) => $query->where('id_kelas', $request->input('id_kelas'))
                ),
            ],
            'id_kesiswaan' => [
                'nullable',
                Rule::exists('users', 'id_user')->where('role', 'Kesiswaan'),
            ],
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ]);

        $dispen = Dispen::create([
            'id_siswa' => $validated['id_siswa'],
            'id_kesiswaan' => $validated['id_kesiswaan'] ?? null,
            'tanggal' => $validated['tanggal'],
            'id_jam_mulai' => $validated['id_jam_mulai'],
            'id_jam_selesai' => $validated['id_jam_selesai'],
            'alasan' => $validated['alasan'],
            'status' => 'menunggu',
        ]);

        return redirect()->route('piket.dispen.whatsapp', $dispen);
    }

    /**
     * Membuka WhatsApp untuk mengirim permohonan konfirmasi ke petugas kesiswaan.
     */
    public function whatsapp(Dispen $dispen)
    {
        $dispen->load([
            'siswa.kelas',
            'jamMulai',
            'jamSelesai',
            'petugasKesiswaan'
        ]);

        $linkVerifikasi = route('kesiswaan.dispen.show', $dispen);
        $loginUrl = route('login') . '?redirect=' . urlencode($linkVerifikasi);

        $message =
            "Permohonan Dispensasi Siswa\n\n"
            . "Nama Siswa: " . $dispen->siswa->nama_siswa . "\n"
            . "Kelas: " . ($dispen->siswa->kelas->nama_kelas ?? '-') . "\n"
            . "Tanggal: " . $dispen->tanggal->format('d-m-Y') . "\n"
            . "Jam: "
            . ($dispen->jamMulai->jam_mulai ?? '-')
            . " - "
            . ($dispen->jamSelesai->jam_selesai ?? '-')
            . "\n"
            . "Alasan: " . $dispen->alasan . "\n\n"
            . "Silakan masuk ke akun Anda untuk meninjau dan mengonfirmasi pengajuan:\n"
            . $loginUrl;

        $whatsappUrl = 'https://wa.me/?text=' . rawurlencode($message);

        return redirect()->away($whatsappUrl);
    }

    /**
     * Form edit dispen.
     */
    public function edit(Dispen $dispen)
    {
        $jamPels = JamPel::where('jenis', 'pelajaran')
            ->orderBy('jam_ke')
            ->get();

        return view('piket.dispen.edit', array_merge(
            compact('dispen'),
            $this->formData($jamPels)
        ));
    }

    /**
     * Update dispen.
     */
    public function update(Request $request, Dispen $dispen)
    {
        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_siswa' => [
                'required',
                Rule::exists('siswas', 'id_siswa')->where(
                    fn ($query) => $query->where('id_kelas', $request->input('id_kelas'))
                ),
            ],
            'id_kesiswaan' => [
                'required',
                Rule::exists('users', 'id_user')->where('role', 'Kesiswaan'),
            ],
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ]);

        $dispen->update(collect($validated)->except('id_kelas')->all());

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil diperbarui.');
    }

    /**
     * Hapus dispen.
     */
    public function destroy(Dispen $dispen)
    {
        $dispen->delete();

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil dihapus.');
    }

    /**
     * Data kelas dan siswa untuk pemilihan siswa pada formulir dispen.
     */
    private function formData($jamPels): array
    {
        $kelases = Kelas::with([
            'siswas' => fn ($query) => $query->orderBy('nama_siswa'),
        ])->orderBy('nama_kelas')->get();

        $petugasKesiswaans = User::query()
            ->where('role', 'Kesiswaan')
            ->orderBy('nama_user')
            ->get(['id_user', 'nama_user']);

        $siswaPerKelas = $kelases->mapWithKeys(fn ($kelas) => [
            $kelas->id_kelas => $kelas->siswas->map(fn ($siswa) => [
                'id' => $siswa->id_siswa,
                'nama' => $siswa->nama_siswa,
                'nis' => $siswa->nis,
            ])->values(),
        ]);

        return compact('jamPels', 'kelases', 'siswaPerKelas', 'petugasKesiswaans');
    }
}
