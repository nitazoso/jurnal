<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Dispen;
use App\Models\JamPel;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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

    public function sickCreate()
    {
        $kelases = Kelas::with(['siswas' => fn ($query) => $query->orderBy('nama_siswa')])
            ->orderBy('nama_kelas')->get();

        $siswaPerKelas = $kelases->mapWithKeys(fn ($kelas) => [
            $kelas->id_kelas => $kelas->siswas->map(fn ($siswa) => [
                'id' => $siswa->id_siswa,
                'nama' => $siswa->nama_siswa,
                'nis' => $siswa->nis,
            ])->values(),
        ]);

        $sickReports = Dispen::with('siswa.kelas')
            ->where('jenis', 'sakit')
            ->whereDate('tanggal', today())
            ->latest()
            ->get();

        return view('piket.dispen.sakit-create', compact('kelases', 'siswaPerKelas', 'sickReports'));
    }

    public function sickStore(Request $request)
    {
        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_siswa' => [
                'required',
                Rule::exists('siswas', 'id_siswa')->where(fn ($query) =>
                    $query->where('id_kelas', $request->input('id_kelas'))
                ),
            ],
            'tanggal' => 'required|date',
            'alasan' => 'required|string|max:255',
            'surat' => 'nullable|image|max:5120',
        ]);

        $suratPath = $request->hasFile('surat')
            ? $request->file('surat')->store('surat-sakit', 'public')
            : null;

        $jamMulai = JamPel::where('jenis', 'pelajaran')->orderBy('jam_ke')->firstOrFail();
        $jamSelesai = JamPel::where('jenis', 'pelajaran')->orderByDesc('jam_ke')->firstOrFail();

        $sickData = [
            'id_kesiswaan' => null,
            'submitted_by' => auth()->user()->id_user,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'alasan' => $validated['alasan'],
            'status' => 'disetujui',
            'disetujui_oleh' => auth()->user()->id_user,
            'disetujui_pada' => now(),
        ];

        if ($suratPath) {
            $sickData['surat_path'] = $suratPath;
        }

        Dispen::updateOrCreate(
            [
                'id_siswa' => $validated['id_siswa'],
                'jenis' => 'sakit',
                'tanggal' => $validated['tanggal'],
            ],
            $sickData
        );

        return redirect()->route('piket.dispen.sakit.create')
            ->with('success', 'Surat sakit berhasil dikirim dan akan tersinkron ke jurnal guru.');
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
            'id_kesiswaan' => ['required', Rule::exists('users', 'id_user')],
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ]);

        $dispen = Dispen::create([
            'id_siswa' => $validated['id_siswa'],
            'id_kesiswaan' => $validated['id_kesiswaan'],
            'submitted_by' => auth()->id(),
            'tanggal' => $validated['tanggal'],
            'id_jam_mulai' => $validated['id_jam_mulai'],
            'id_jam_selesai' => $validated['id_jam_selesai'],
            'alasan' => $validated['alasan'],
            'status' => 'menunggu',
            'token_verifikasi' => Str::random(64),
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
            'petugasKesiswaan.guru',
        ]);

        $petugas = $dispen->petugasKesiswaan;
        $nomorWa = $petugas?->no_wa ?? $petugas?->guru?->no_hp ?? null;

        if (! $nomorWa) {
            return redirect()->route('piket.dispen.index')
                ->with('error', 'Nomor WhatsApp petugas kesiswaan belum diisi.');
        }

        $linkVerifikasi = route('dispen.verifikasi', $dispen->token_verifikasi);
        $message =
            "Permohonan Dispensasi Siswa\n\n"
            . "Nama Siswa: " . ($dispen->siswa->nama_siswa ?? '-') . "\n"
            . "Kelas: " . ($dispen->siswa->kelas->nama_kelas ?? '-') . "\n"
            . "Tanggal: " . $dispen->tanggal->format('d-m-Y') . "\n"
            . "Jam: "
            . ($dispen->jamMulai->jam_mulai ?? '-')
            . " - "
            . ($dispen->jamSelesai->jam_selesai ?? '-')
            . "\n"
            . "Alasan: " . $dispen->alasan . "\n\n"
            . "Silakan tinjau dan konfirmasi pengajuan dispen berikut:\n"
            . $linkVerifikasi;

        $cleanPhone = preg_replace('/\D+/', '', $nomorWa);
        $waUrl = $cleanPhone !== ''
            ? 'https://wa.me/' . $cleanPhone . '?text=' . rawurlencode($message)
            : 'https://wa.me/?text=' . rawurlencode($message);

        return redirect()->away($waUrl);
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
            'id_kesiswaan' => ['required', Rule::exists('users', 'id_user')],
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
            ->with('guru')
            ->orderBy('nama_user')
            ->get();

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
