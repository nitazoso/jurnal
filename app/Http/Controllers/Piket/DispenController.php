<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Dispen;
use App\Models\PiketJadwal;
use App\Models\User;
use App\Models\JamPel;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DispenController extends Controller
{
    /**
     * Menampilkan daftar dispen yang berstatus menunggu.
     */
    public function index()
    {
        $dispens = Dispen::with([
            'siswa.kelas',
            'jamMulai',
            'jamSelesai',
            'approver',
            'petugasKesiswaan',
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
            'siswa.kelas',
            'jamMulai',
            'jamSelesai',
            'approver',
            'petugasKesiswaan',
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
     * Waka/Kesiswaan otomatis ditentukan berdasarkan jadwal tugas pada tanggal dispen.
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
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ], [
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'id_siswa.required' => 'Siswa wajib dipilih.',
            'id_siswa.exists' => 'Siswa tidak ditemukan pada kelas yang dipilih.',
            'tanggal.required' => 'Tanggal dispensasi wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'id_jam_mulai.required' => 'Jam mulai wajib dipilih.',
            'id_jam_selesai.required' => 'Jam selesai wajib dipilih.',
            'alasan.required' => 'Alasan dispensasi wajib diisi.',
        ]);

        // Cari petugas Waka/Kesiswaan berdasarkan jadwal pada tanggal tersebut
        $petugas = $this->wakaBertugas($validated['tanggal']);

        if (! $petugas) {
            $formattedDate = Carbon::parse($validated['tanggal'])->format('d-m-Y');
            return back()
                ->withInput()
                ->withErrors(['tanggal' => "Waka untuk tanggal {$formattedDate} belum ada di jadwal piket."])
                ->with('error', "Waka untuk tanggal {$formattedDate} belum ada di jadwal piket.");
        }

        if (empty($petugas->no_wa)) {
            return back()
                ->withInput()
                ->withErrors(['tanggal' => "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_user}) belum tersedia."])
                ->with('error', "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_user}) belum tersedia.");
        }

        $dispen = Dispen::create([
            'id_siswa' => $validated['id_siswa'],
            'id_kesiswaan' => $petugas->id_user,
            'submitted_by' => auth()->id(),
            'tanggal' => $validated['tanggal'],
            'id_jam_mulai' => $validated['id_jam_mulai'],
            'id_jam_selesai' => $validated['id_jam_selesai'],
            'alasan' => $validated['alasan'],
            'status' => 'menunggu',
            'token_verifikasi' => Str::random(64),
        ]);

        return redirect()->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil dibuat. Silakan klik "Kirim WhatsApp" untuk meneruskan permohonan ke Waka yang bertugas.');
    }

    /**
     * Membuka WhatsApp untuk mengirim permohonan ke Waka yang bertugas pada tanggal Dispen.
     */
    public function whatsapp(Dispen $dispen)
    {
        abort_unless($dispen->status === 'menunggu', 409, 'Dispen ini sudah diproses.');

        $dispen->load(['siswa.kelas', 'jamMulai', 'jamSelesai']);

        // Ambil penugasan Waka terbaru untuk tanggal Dispen, termasuk pengajuan lama.
        $petugas = $this->wakaBertugas($dispen->tanggal->format('Y-m-d'));

        if (! $petugas) {
            $formattedDate = $dispen->tanggal->format('d-m-Y');
            return redirect()->route('piket.dispen.index')
                ->with('error', "Waka untuk tanggal {$formattedDate} belum ada di jadwal piket.");
        }

        if ($dispen->id_kesiswaan !== $petugas->id_user) {
            $dispen->update(['id_kesiswaan' => $petugas->id_user]);
        }

        if (empty($petugas->no_wa)) {
            return redirect()->route('piket.dispen.index')
                ->with('error', "Nomor WhatsApp untuk petugas Waka/Kesiswaan ({$petugas->nama_user}) belum tersedia.");
        }

        // Pastikan token verifikasi ada
        if (empty($dispen->token_verifikasi)) {
            $dispen->update(['token_verifikasi' => Str::random(64)]);
        }

        $linkVerifikasi = route('dispen.verifikasi', $dispen->token_verifikasi);

        $jamMulaiText = $dispen->jamMulai ? substr($dispen->jamMulai->jam_mulai, 0, 5) : '-';
        $jamSelesaiText = $dispen->jamSelesai ? substr($dispen->jamSelesai->jam_selesai, 0, 5) : '-';

        $message = "Permohonan Dispensasi Siswa\n\n"
            . "Nama siswa: " . ($dispen->siswa->nama_siswa ?? '-') . "\n"
            . "Kelas: " . ($dispen->siswa->kelas->nama_kelas ?? '-') . "\n"
            . "Tanggal: " . $dispen->tanggal->format('d-m-Y') . "\n"
            . "Jam: " . $jamMulaiText . " - " . $jamSelesaiText . "\n"
            . "Alasan: " . $dispen->alasan . "\n\n"
            . "Silakan melakukan verifikasi melalui link berikut:\n"
            . $linkVerifikasi;

        $cleanPhone = preg_replace('/\D+/', '', $petugas->no_wa);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $waUrl = 'https://wa.me/' . $cleanPhone . '?text=' . rawurlencode($message);

        return redirect()->away($waUrl);
    }

    /**
     * Form edit dispen.
     */
    public function edit(Dispen $dispen)
    {
        abort_unless($dispen->status === 'menunggu', 409, 'Dispen yang sudah diproses tidak dapat diedit.');

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
        abort_unless($dispen->status === 'menunggu', 409, 'Dispen yang sudah diproses tidak dapat diedit.');

        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_siswa' => [
                'required',
                Rule::exists('siswas', 'id_siswa')->where(
                    fn ($query) => $query->where('id_kelas', $request->input('id_kelas'))
                ),
            ],
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ], [
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'id_siswa.required' => 'Siswa wajib dipilih.',
            'id_siswa.exists' => 'Siswa tidak ditemukan pada kelas yang dipilih.',
            'tanggal.required' => 'Tanggal dispensasi wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'id_jam_mulai.required' => 'Jam mulai wajib dipilih.',
            'id_jam_selesai.required' => 'Jam selesai wajib dipilih.',
            'alasan.required' => 'Alasan dispensasi wajib diisi.',
        ]);

        $petugas = $this->wakaBertugas($validated['tanggal']);

        if (! $petugas) {
            $formattedDate = Carbon::parse($validated['tanggal'])->format('d-m-Y');
            return back()
                ->withInput()
                ->withErrors(['tanggal' => "Waka untuk tanggal {$formattedDate} belum ada di jadwal piket."])
                ->with('error', "Waka untuk tanggal {$formattedDate} belum ada di jadwal piket.");
        }

        if (empty($petugas->no_wa)) {
            return back()
                ->withInput()
                ->withErrors(['tanggal' => "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_user}) belum tersedia."])
                ->with('error', "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_user}) belum tersedia.");
        }

        $dispen->update([
            'id_siswa' => $validated['id_siswa'],
            'id_kesiswaan' => $petugas->id_user,
            'tanggal' => $validated['tanggal'],
            'id_jam_mulai' => $validated['id_jam_mulai'],
            'id_jam_selesai' => $validated['id_jam_selesai'],
            'alasan' => $validated['alasan'],
        ]);

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil diperbarui.');
    }

    /**
     * Hapus dispen.
     */
    public function destroy(Dispen $dispen)
    {
        abort_unless($dispen->status === 'menunggu', 409, 'Dispen yang sudah diproses tidak dapat dihapus.');

        $dispen->delete();

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil dihapus.');
    }

    /**
     * Data kelas, siswa, dan jadwal Waka untuk form dispen.
     */
    private function formData($jamPels): array
    {
        $kelases = Kelas::with([
            'siswas' => fn ($query) => $query->orderBy('nama_siswa'),
        ])->orderBy('nama_kelas')->get();

        $siswaPerKelas = $kelases->mapWithKeys(fn ($kelas) => [
            $kelas->id_kelas => $kelas->siswas->map(fn ($siswa) => [
                'id' => $siswa->id_siswa,
                'nama' => $siswa->nama_siswa,
                'nis' => $siswa->nis,
            ])->values(),
        ]);

        $wakaUsers = User::whereNotNull('id_guru')->get()->keyBy('id_guru');
        $jadwalWakaMap = PiketJadwal::with('guru')
            ->where('jenis_tugas', 'Piket Waka')
            ->get()
            ->mapWithKeys(function ($jadwal) use ($wakaUsers) {
                $user = $wakaUsers->get($jadwal->id_guru);

                return [$jadwal->tanggal->format('Y-m-d') => [
                    'nama' => $jadwal->guru?->nama_guru ?? $user?->nama_user ?? '-',
                    'no_wa' => $user?->no_wa,
                ]];
            });

        return compact('jamPels', 'kelases', 'siswaPerKelas', 'jadwalWakaMap');
    }

    private function wakaBertugas(string $tanggal): ?User
    {
        $jadwal = PiketJadwal::whereDate('tanggal', $tanggal)
            ->where('jenis_tugas', 'Piket Waka')
            ->first();

        return $jadwal
            ? User::where('id_guru', $jadwal->id_guru)->first()
            : null;
    }
}
