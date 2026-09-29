<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\Dispen;
use App\Models\Guru;
use App\Models\PiketJadwal;
use App\Models\User;
use App\Models\JamPel;
use App\Models\Kelas;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DispenController extends Controller
{
    public function izinSakitIndex()
    {
        $dispens = Dispen::with(['siswa.kelas', 'submitter'])
            ->whereIn('jenis', ['sakit', 'izin'])
            ->latest('tanggal')
            ->paginate(15);

        return view('piket.izin-sakit.index', compact('dispens'));
    }

    public function izinSakitCreate(Request $request)
    {
        $kelases = Kelas::with(['siswas' => fn ($query) => $query->orderBy('nama_siswa')])
            ->orderBy('nama_kelas')->get();
        $siswaPerKelas = $kelases->mapWithKeys(fn ($kelas) => [
            $kelas->id_kelas => $kelas->siswas->map(fn ($siswa) => [
                'id' => $siswa->id_siswa, 'nama' => $siswa->nama_siswa, 'nis' => $siswa->nis,
            ])->values(),
        ]);

        $jenisDefault = in_array($request->query('jenis'), ['izin', 'sakit'], true) ? $request->query('jenis') : 'izin';
        $dispen = null;

        return view('piket.izin-sakit.create', compact('dispen', 'kelases', 'siswaPerKelas', 'jenisDefault'));
    }

    public function izinSakitStore(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:izin,sakit',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_siswa' => ['required', Rule::exists('siswas', 'id_siswa')->where(fn ($query) => $query->where('id_kelas', $request->input('id_kelas')))],
            'tanggal' => 'required|date',
            'alasan' => 'required|string|max:255',
            'surat' => 'nullable|image|max:5120',
        ]);

        $jamMulai = JamPel::where('jenis', 'pelajaran')->orderBy('jam_ke')->firstOrFail();
        $jamSelesai = JamPel::where('jenis', 'pelajaran')->orderByDesc('jam_ke')->firstOrFail();
        $suratPath = null;
        if ($request->hasFile('surat')) {
            $suratPath = $request->file('surat')->store(
                $validated['jenis'] === 'sakit' ? 'surat-sakit' : 'surat-izin',
                'public'
            );
            if (! $suratPath) {
                return back()->withInput()->withErrors(['surat' => 'Foto surat gagal disimpan. Silakan coba unggah kembali.']);
            }
        }

        $report = Dispen::updateOrCreate(
            ['id_siswa' => $validated['id_siswa'], 'jenis' => $validated['jenis'], 'tanggal' => $validated['tanggal']],
            array_filter([
                'submitted_by' => auth()->id(),
                'id_jam_mulai' => $jamMulai->id_jam,
                'id_jam_selesai' => $jamSelesai->id_jam,
                'alasan' => $validated['alasan'],
                'surat_path' => $suratPath,
                'status' => 'disetujui',
            ], fn ($value) => $value !== null)
        );

        $this->syncAbsenceIntoJournals($report);

        return redirect()->route('piket.izin-sakit.index')
            ->with('success', 'Data izin/sakit tersimpan dan langsung disinkronkan ke jurnal guru pada kelas dan tanggal terkait.');
    }

    public function izinSakitEdit(Dispen $dispen)
    {
        abort_unless(in_array($dispen->jenis, ['izin', 'sakit'], true), 404);
        $kelases = Kelas::with(['siswas' => fn ($query) => $query->orderBy('nama_siswa')])
            ->orderBy('nama_kelas')->get();
        $siswaPerKelas = $kelases->mapWithKeys(fn ($kelas) => [
            $kelas->id_kelas => $kelas->siswas->map(fn ($siswa) => [
                'id' => $siswa->id_siswa, 'nama' => $siswa->nama_siswa, 'nis' => $siswa->nis,
            ])->values(),
        ]);

        return view('piket.izin-sakit.create', compact('dispen', 'kelases', 'siswaPerKelas'));
    }

    public function izinSakitUpdate(Request $request, Dispen $dispen)
    {
        abort_unless(in_array($dispen->jenis, ['izin', 'sakit'], true), 404);
        $validated = $request->validate([
            'jenis' => 'required|in:izin,sakit',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_siswa' => ['required', Rule::exists('siswas', 'id_siswa')->where(fn ($query) => $query->where('id_kelas', $request->input('id_kelas')))],
            'tanggal' => 'required|date', 'alasan' => 'required|string|max:255', 'surat' => 'nullable|image|max:5120',
        ]);

        $oldReportId = $dispen->id_dispen;
        $jamMulai = JamPel::where('jenis', 'pelajaran')->orderBy('jam_ke')->firstOrFail();
        $jamSelesai = JamPel::where('jenis', 'pelajaran')->orderByDesc('jam_ke')->firstOrFail();
        $data = [
            'id_siswa' => $validated['id_siswa'], 'jenis' => $validated['jenis'], 'tanggal' => $validated['tanggal'],
            'id_jam_mulai' => $jamMulai->id_jam, 'id_jam_selesai' => $jamSelesai->id_jam,
            'alasan' => $validated['alasan'], 'status' => 'disetujui',
        ];
        if ($request->hasFile('surat')) {
            $suratPath = $request->file('surat')->store(
                $validated['jenis'] === 'sakit' ? 'surat-sakit' : 'surat-izin',
                'public'
            );
            if (! $suratPath) {
                return back()->withInput()->withErrors(['surat' => 'Foto surat gagal disimpan. Silakan coba unggah kembali.']);
            }
            $data['surat_path'] = $suratPath;
        }
        $dispen->update($data);
        $this->syncAbsenceIntoJournals($dispen->refresh(), $oldReportId);

        return redirect()->route('piket.izin-sakit.index')->with('success', 'Data diperbarui dan disinkronkan ke jurnal guru.');
    }

    public function sickEdit(Dispen $dispen)
    {
        return redirect()->route('piket.izin-sakit.edit', $dispen->id_dispen);
    }

    public function sickUpdate(Request $request, Dispen $dispen)
    {
        $request->merge(['jenis' => $dispen->jenis]);

        return $this->izinSakitUpdate($request, $dispen);
    }

    /**
     * Menampilkan daftar dispen yang berstatus menunggu.
     */
    public function index(Request $request)
    {
        $filterStatus = in_array($request->query('status'), ['menunggu', 'riwayat', 'semua'], true)
            ? $request->query('status')
            : 'menunggu';

        $statusCounts = Dispen::where('jenis', 'dispen')
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $query = Dispen::with([
            'siswa.kelas',
            'jamMulai',
            'jamSelesai',
            'approver',
            'petugasKesiswaan.guru',
        ])->where('jenis', 'dispen');

        if ($filterStatus === 'menunggu') {
            $query->where('status', 'menunggu');
        } elseif ($filterStatus === 'riwayat') {
            $query->whereIn('status', ['disetujui', 'ditolak']);
        }

        $dispens = $query
            ->orderByRaw("CASE WHEN status = 'menunggu' THEN 0 ELSE 1 END")
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'menunggu' => (int) ($statusCounts['menunggu'] ?? 0),
            'riwayat' => (int) ($statusCounts['disetujui'] ?? 0) + (int) ($statusCounts['ditolak'] ?? 0),
            'semua' => (int) $statusCounts->sum(),
        ];

        return view('piket.dispen.index', compact('dispens', 'filterStatus', 'counts'));
    }

    /**
     * Riwayat lama diarahkan ke filter riwayat pada halaman Dispen.
     */
    public function history()
    {
        return redirect()->route('piket.dispen.index', ['status' => 'riwayat']);
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
        return redirect()->route('piket.izin-sakit.create', ['jenis' => 'sakit']);
    }

    public function sickStore(Request $request)
    {
        $request->merge(['jenis' => 'sakit']);

        return $this->izinSakitStore($request);
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

        if (empty($petugas->no_hp)) {
            return back()
                ->withInput()
                ->withErrors(['tanggal' => "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_guru}) belum tersedia."])
                ->with('error', "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_guru}) belum tersedia.");
        }

        $dispen = Dispen::create([
            'id_siswa' => $validated['id_siswa'],
            'id_kesiswaan' => User::where('id_guru', $petugas->id_guru)->value('id_user'),
            'submitted_by' => auth()->id(),
            'tanggal' => $validated['tanggal'],
            'id_jam_mulai' => $validated['id_jam_mulai'],
            'id_jam_selesai' => $validated['id_jam_selesai'],
            'alasan' => $validated['alasan'],
            'status' => 'menunggu',
            'token_verifikasi' => Str::random(64),
        ]);

        return redirect()->route('piket.dispen.summary', $dispen->id_dispen);
    }

    public function summary(Dispen $dispen)
    {
        $dispen->load(['siswa.kelas', 'jamMulai', 'jamSelesai']);
        $petugas = $this->wakaBertugas($dispen->tanggal->format('Y-m-d'));
        $verificationUrl = route('dispen.verifikasi', $dispen->token_verifikasi);

        return view('piket.dispen.summary', compact('dispen', 'petugas', 'verificationUrl'));
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

        $wakaUserId = User::where('id_guru', $petugas->id_guru)->value('id_user');
        if ($dispen->id_kesiswaan !== $wakaUserId) {
            $dispen->update(['id_kesiswaan' => $wakaUserId]);
        }

        if (empty($petugas->no_hp)) {
            return redirect()->route('piket.dispen.index')
                ->with('error', "Nomor WhatsApp untuk petugas Waka/Kesiswaan ({$petugas->nama_guru}) belum tersedia.");
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

        $cleanPhone = preg_replace('/\D+/', '', $petugas->no_hp);
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

        if (empty($petugas->no_hp)) {
            return back()
                ->withInput()
                ->withErrors(['tanggal' => "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_guru}) belum tersedia."])
                ->with('error', "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_guru}) belum tersedia.");
        }

        $dispen->update([
            'id_siswa' => $validated['id_siswa'],
            'id_kesiswaan' => User::where('id_guru', $petugas->id_guru)->value('id_user'),
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

    private function syncAbsenceIntoJournals(Dispen $report, ?int $previousReportId = null): void
    {
        $report->load('siswa');
        $tanggal = $report->tanggal->format('Y-m-d');
        $idSiswa = $report->id_siswa;
        $idKelas = $report->siswa->id_kelas;
        $attendanceStatus = $report->jenis === 'izin' ? 'Izin' : 'Sakit';
        $keterangan = $report->jenis === 'izin' ? 'Surat izin dari Piket' : 'Surat sakit dari Piket';

        DB::transaction(function () use ($report, $previousReportId, $tanggal, $idSiswa, $idKelas, $attendanceStatus, $keterangan) {
            if ($previousReportId) {
                DetailAbsensi::where('id_dispen', $previousReportId)
                    ->where(function ($query) use ($tanggal, $idSiswa, $idKelas) {
                        $query->where('id_siswa', '!=', $idSiswa)
                            ->orWhereHas('jurnal', fn ($jurnal) => $jurnal->whereDate('tanggal', '!=', $tanggal)->orWhere('id_kelas', '!=', $idKelas));
                    })
                    ->with('jurnal')
                    ->get()
                    ->each(function ($detail) {
                        if ($detail->jurnal) {
                            $detail->jurnal->update([
                                'jml_hadir' => $detail->jurnal->jml_hadir + 1,
                                'jml_tidak_hadir' => max(0, $detail->jurnal->jml_tidak_hadir - 1),
                            ]);
                        }
                        $detail->delete();
                    });
            }

            Jurnal::where('id_kelas', $idKelas)->whereDate('tanggal', $tanggal)->get()->each(function ($jurnal) use ($report, $idSiswa, $attendanceStatus, $keterangan) {
                $detail = DetailAbsensi::where('id_jurnal', $jurnal->id_jurnal)
                    ->where('id_siswa', $idSiswa)->first();

                if ($detail) {
                    $detail->update([
                        'id_dispen' => $report->id_dispen,
                        'status' => $attendanceStatus,
                        'keterangan' => $keterangan,
                    ]);
                    return;
                }

                DetailAbsensi::create([
                    'id_jurnal' => $jurnal->id_jurnal,
                    'id_siswa' => $idSiswa,
                    'id_dispen' => $report->id_dispen,
                    'status' => $attendanceStatus,
                    'keterangan' => $keterangan,
                ]);
                $jurnal->update([
                    'jml_hadir' => max(0, $jurnal->jml_hadir - 1),
                    'jml_tidak_hadir' => $jurnal->jml_tidak_hadir + 1,
                ]);
            });
        });
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

        $jadwalWakaMap = PiketJadwal::with('guru')
            ->where('jenis_tugas', 'Piket Waka')
            ->get()
            ->mapWithKeys(function ($jadwal) {
                return [$jadwal->tanggal->format('Y-m-d') => [
                    'nama' => $jadwal->guru?->nama_guru ?? '-',
                    'no_hp' => $jadwal->guru?->no_hp,
                ]];
            });

        return compact('jamPels', 'kelases', 'siswaPerKelas', 'jadwalWakaMap');
    }

    private function wakaBertugas(string $tanggal): ?Guru
    {
        $jadwal = PiketJadwal::with('guru')
            ->whereDate('tanggal', $tanggal)
            ->where('jenis_tugas', 'Piket Waka')
            ->first();

        return $jadwal?->guru;
    }
}
