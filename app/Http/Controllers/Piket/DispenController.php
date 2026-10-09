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
use App\Models\Jadwal;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $validated = $this->validateIzinSakit($request);

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
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'jenis_surat_sakit' => $validated['jenis_surat_sakit'],
                'alasan' => $validated['alasan'],
                'surat_path' => $suratPath,
                'status' => 'disetujui',
            ], fn ($value) => $value !== null)
        );

        $this->syncAbsenceIntoJournals($report);

        $redirectRoute = $request->routeIs('piket.dispen.sakit.store')
            ? 'piket.dispen.sakit.create'
            : 'piket.izin-sakit.index';

        return redirect()->route($redirectRoute)
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
        $validated = $this->validateIzinSakit($request);

        $oldReportId = $dispen->id_dispen;
        $jamMulai = JamPel::where('jenis', 'pelajaran')->orderBy('jam_ke')->firstOrFail();
        $jamSelesai = JamPel::where('jenis', 'pelajaran')->orderByDesc('jam_ke')->firstOrFail();
        $data = [
            'id_siswa' => $validated['id_siswa'], 'jenis' => $validated['jenis'], 'tanggal' => $validated['tanggal'],
            'id_jam_mulai' => $jamMulai->id_jam, 'id_jam_selesai' => $jamSelesai->id_jam,
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'jenis_surat_sakit' => $validated['jenis_surat_sakit'],
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

        $records = Dispen::with([
            'siswa.kelas', 'jamMulai', 'jamSelesai', 'approver', 'petugasKesiswaan.guru',
        ])->where('jenis', 'dispen')->latest('tanggal')->latest('id_dispen')->get();

        $groups = $records->groupBy(fn (Dispen $item) => $item->batch_token ?: 'single-' . $item->id_dispen)
            ->map(function ($items) {
                $primary = $items->first();
                $primary->batchItems = $items->values();
                $primary->student_count = $items->count();
                $primary->class_count = $items->pluck('siswa.id_kelas')->filter()->unique()->count();
                return $primary;
            });

        $counts = [
            'menunggu' => $groups->where('status', 'menunggu')->count(),
            'riwayat' => $groups->whereIn('status', ['disetujui', 'ditolak'])->count(),
            'semua' => $groups->count(),
        ];

        $filtered = match ($filterStatus) {
            'menunggu' => $groups->where('status', 'menunggu'),
            'riwayat' => $groups->whereIn('status', ['disetujui', 'ditolak']),
            default => $groups,
        };
        $perPage = 12;
        $page = max(1, LengthAwarePaginator::resolveCurrentPage());
        $dispens = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

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

        return view('piket.dispen.create', array_merge(
            $this->formData($jamPels),
            ['dispen' => null, 'editing' => false, 'initialStudents' => [['id_kelas' => '', 'id_siswa' => '']]]
        ));
    }

    public function sickCreate()
    {
        return redirect()->route('piket.izin-sakit.create', ['jenis' => 'sakit']);
    }

    public function sickStore(Request $request)
    {
        $request->merge([
            'jenis' => 'sakit',
            'jenis_surat_sakit' => $request->input('jenis_surat_sakit', 'biasa'),
        ]);

        return $this->izinSakitStore($request);
    }

    /**
     * Simpan dispen baru.
     * Waka/Kesiswaan otomatis ditentukan berdasarkan jadwal tugas pada tanggal dispen.
     */
    public function store(Request $request)
    {
        $validated = $this->validateDispenBatch($request);
        $isTerlambat = $validated['jenis_dispen'] === 'terlambat';
        $petugas = $isTerlambat ? null : $this->wakaBertugas($validated['tanggal']);

        if (! $isTerlambat && ! $petugas) {
            $formattedDate = Carbon::parse($validated['tanggal'])->format('d-m-Y');
            return back()->withInput()->withErrors(['tanggal' => "Waka untuk tanggal {$formattedDate} belum ada di jadwal piket."])
                ->with('error', "Waka untuk tanggal {$formattedDate} belum ada di jadwal piket.");
        }
        if (! $isTerlambat && empty($petugas->no_hp)) {
            return back()->withInput()->withErrors(['tanggal' => "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_guru}) belum tersedia."])
                ->with('error', "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_guru}) belum tersedia.");
        }

        $batchToken = (string) Str::uuid();
        $verificationToken = $isTerlambat ? null : Str::random(64);
        $wakaUserId = $petugas ? User::where('id_guru', $petugas->id_guru)->value('id_user') : null;
        $approvedBy = $isTerlambat ? auth()->id() : null;
        $approvedAt = $isTerlambat ? now() : null;
        $items = DB::transaction(function () use ($validated, $batchToken, $verificationToken, $wakaUserId, $approvedBy, $approvedAt) {
            $items = collect();
            foreach ($validated['students'] as $student) {
                $item = Dispen::create([
                    'id_siswa' => $student['id_siswa'],
                    'batch_token' => $batchToken,
                    'jenis' => 'dispen',
                    'jenis_dispen' => $validated['jenis_dispen'],
                    'id_kesiswaan' => $wakaUserId,
                    'submitted_by' => auth()->id(),
                    'tanggal' => $validated['tanggal'],
                    'id_jam_mulai' => $validated['id_jam_mulai'],
                    'id_jam_selesai' => $validated['id_jam_selesai'],
                    'alasan' => $validated['alasan'],
                    'status' => $validated['jenis_dispen'] === 'terlambat' ? 'disetujui' : 'menunggu',
                    'token_verifikasi' => $verificationToken,
                    'disetujui_oleh' => $approvedBy,
                    'disetujui_pada' => $approvedAt,
                ]);
                $items->push($item);
            }
            return $items;
        });

        if ($isTerlambat) {
            $items->each(fn (Dispen $item) => $this->syncLateDispenIntoJournals($item));

            return redirect()->route('piket.dispen.index', ['status' => 'riwayat'])
                ->with('success', 'Dispen terlambat disimpan dan langsung disinkronkan ke jurnal pada jam yang dipilih.');
        }

        return redirect()->route('piket.dispen.summary', $items->first()->id_dispen);
    }

    public function summary(Dispen $dispen)
    {
        $dispens = $this->batchQuery($dispen)->with(['siswa.kelas', 'jamMulai', 'jamSelesai'])->get();
        $dispen = $dispens->first();
        $petugas = $this->wakaBertugas($dispen->tanggal->format('Y-m-d'));
        $verificationUrl = route('dispen.verifikasi', $dispen->token_verifikasi);

        return view('piket.dispen.summary', compact('dispen', 'dispens', 'petugas', 'verificationUrl'));
    }

    public function detail(Request $request, Dispen $dispen)
    {
        $dispens = $this->batchQuery($dispen)
            ->with(['siswa.kelas', 'jamMulai', 'jamSelesai', 'approver', 'petugasKesiswaan.guru'])
            ->get();
        $dispen = $dispens->first();
        $petugas = $this->wakaBertugas($dispen->tanggal->format('Y-m-d'));

        if ($request->ajax()) {
            return view('piket.dispen.partials.detail-content', compact('dispen', 'dispens', 'petugas'));
        }

        return view('piket.dispen.detail', compact('dispen', 'dispens', 'petugas'));
    }

    /**
     * Membuka WhatsApp untuk mengirim permohonan ke Waka yang bertugas pada tanggal Dispen.
     */
    public function whatsapp(Dispen $dispen)
    {
        abort_unless($dispen->status === 'menunggu', 409, 'Dispen ini sudah diproses.');

        $dispens = $this->batchQuery($dispen)->with(['siswa.kelas', 'jamMulai', 'jamSelesai'])->get();
        $dispen = $dispens->first();

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

        $studentLines = $dispens->values()->map(fn ($item, $index) => ($index + 1) . '. '
            . ($item->siswa->nama_siswa ?? '-') . ' — '
            . ($item->siswa->kelas->nama_kelas ?? '-'))->implode("\n");
        $message = "Permohonan Dispensasi Siswa (" . $dispens->count() . " siswa)\n\n"
            . $studentLines . "\n\n"
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
        $jamPels = JamPel::where('jenis', 'pelajaran')->orderBy('jam_ke')->get();
        $items = $this->batchQuery($dispen)->with('siswa')->get();
        $initialStudents = $items->map(fn ($item) => [
            'id_kelas' => $item->siswa?->id_kelas,
            'id_siswa' => $item->id_siswa,
        ])->values()->all();

        return view('piket.dispen.create', array_merge(
            compact('dispen', 'initialStudents'),
            $this->formData($jamPels),
            ['editing' => true]
        ));
    }

    public function update(Request $request, Dispen $dispen)
    {
        abort_unless($dispen->status === 'menunggu', 409, 'Dispen yang sudah diproses tidak dapat diedit.');
        $validated = $this->validateDispenBatch($request);
        $isTerlambat = $validated['jenis_dispen'] === 'terlambat';
        $petugas = $isTerlambat ? null : $this->wakaBertugas($validated['tanggal']);

        if (! $isTerlambat && ! $petugas) {
            $formattedDate = Carbon::parse($validated['tanggal'])->format('d-m-Y');
            return back()->withInput()->withErrors(['tanggal' => "Waka untuk tanggal {$formattedDate} belum ada di jadwal piket."])
                ->with('error', "Waka untuk tanggal {$formattedDate} belum ada di jadwal piket.");
        }
        if (! $isTerlambat && empty($petugas->no_hp)) {
            return back()->withInput()->withErrors(['tanggal' => "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_guru}) belum tersedia."])
                ->with('error', "Nomor WhatsApp untuk Waka yang bertugas ({$petugas->nama_guru}) belum tersedia.");
        }

        $batchToken = $dispen->batch_token ?: (string) Str::uuid();
        $verificationToken = $isTerlambat ? null : ($dispen->token_verifikasi ?: Str::random(64));
        $wakaUserId = $petugas ? User::where('id_guru', $petugas->id_guru)->value('id_user') : null;
        $approvedBy = $isTerlambat ? auth()->id() : null;
        $approvedAt = $isTerlambat ? now() : null;
        DB::transaction(function () use ($dispen, $validated, $batchToken, $verificationToken, $wakaUserId, $approvedBy, $approvedAt, $isTerlambat) {
            $existing = $this->batchQuery($dispen)->get()->keyBy('id_siswa');
            foreach ($validated['students'] as $student) {
                $data = [
                    'batch_token' => $batchToken,
                    'jenis' => 'dispen',
                    'jenis_dispen' => $validated['jenis_dispen'],
                    'id_kesiswaan' => $wakaUserId,
                    'submitted_by' => $dispen->submitted_by ?? auth()->id(),
                    'tanggal' => $validated['tanggal'],
                    'id_jam_mulai' => $validated['id_jam_mulai'],
                    'id_jam_selesai' => $validated['id_jam_selesai'],
                    'alasan' => $validated['alasan'],
                    'status' => $isTerlambat ? 'disetujui' : 'menunggu',
                    'token_verifikasi' => $verificationToken,
                    'disetujui_oleh' => $approvedBy,
                    'disetujui_pada' => $approvedAt,
                ];
                $current = $existing->pull($student['id_siswa']);
                if ($current) {
                    $current->update($data);
                } else {
                    Dispen::create($data + ['id_siswa' => $student['id_siswa']]);
                }
            }
            $existing->each->delete();
            $this->batchQuery($dispen)->update(['batch_token' => $batchToken]);
        });

        if ($isTerlambat) {
            $this->batchQuery($dispen)->get()->each(fn (Dispen $item) => $this->syncLateDispenIntoJournals($item));
        }

        return redirect()->route('piket.dispen.index', $isTerlambat ? ['status' => 'riwayat'] : [])
            ->with('success', 'Pengajuan dispen berhasil diperbarui.');
    }

    /**
     * Hapus dispen.
     */
    public function destroy(Dispen $dispen)
    {
        abort_unless($dispen->status === 'menunggu', 409, 'Dispen yang sudah diproses tidak dapat dihapus.');

        $this->batchQuery($dispen)->delete();

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil dihapus.');
    }

    private function syncAbsenceIntoJournals(Dispen $report, ?int $previousReportId = null): void
    {
        $report->load('siswa');
        $tanggalMulai = $report->tanggal->format('Y-m-d');
        $tanggalSelesai = ($report->tanggal_selesai ?? $report->tanggal)->format('Y-m-d');
        $idSiswa = $report->id_siswa;
        $idKelas = $report->siswa->id_kelas;
        $attendanceStatus = $report->jenis === 'izin' ? 'Izin' : 'Sakit';
        $keterangan = $report->jenis === 'izin' ? 'Surat izin dari Piket' : 'Surat sakit dari Piket';

        DB::transaction(function () use ($report, $previousReportId, $tanggalMulai, $tanggalSelesai, $idSiswa, $idKelas, $attendanceStatus, $keterangan) {
            if ($previousReportId) {
                DetailAbsensi::where('id_dispen', $previousReportId)
                    ->where(function ($query) use ($tanggalMulai, $tanggalSelesai, $idSiswa, $idKelas) {
                        $query->where('id_siswa', '!=', $idSiswa)
                            ->orWhereHas('jurnal', fn ($jurnal) => $jurnal
                                ->where('id_kelas', '!=', $idKelas)
                                ->orWhereDate('tanggal', '<', $tanggalMulai)
                                ->orWhereDate('tanggal', '>', $tanggalSelesai));
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

            Jurnal::where('id_kelas', $idKelas)
                ->whereDate('tanggal', '>=', $tanggalMulai)
                ->whereDate('tanggal', '<=', $tanggalSelesai)
                ->get()->each(function ($jurnal) use ($report, $idSiswa, $attendanceStatus, $keterangan) {
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

    private function validateIzinSakit(Request $request): array
    {
        $validated = $request->validate([
            'jenis' => 'required|in:izin,sakit',
            'id_kelas' => 'required|exists:kelases,id_kelas',
            'id_siswa' => ['required', Rule::exists('siswas', 'id_siswa')->where(fn ($query) => $query->where('id_kelas', $request->input('id_kelas')))],
            'tanggal' => 'required|date',
            'durasi_hari' => 'nullable|integer|min:1|max:365',
            'jenis_surat_sakit' => 'required_if:jenis,sakit|nullable|in:biasa,dokter',
            'alasan' => 'required|string|max:255',
            'surat' => 'nullable|image|max:5120',
        ]);

        $validated['jenis_surat_sakit'] = $validated['jenis'] === 'sakit'
            ? ($validated['jenis_surat_sakit'] ?? 'biasa')
            : null;
        $durasiHari = $validated['jenis'] === 'sakit'
            ? ($validated['jenis_surat_sakit'] === 'dokter' ? 3 : 1)
            : (int) ($validated['durasi_hari'] ?? 1);
        $validated['tanggal_selesai'] = Carbon::parse($validated['tanggal'])
            ->addDays($durasiHari - 1)
            ->toDateString();

        return $validated;
    }

    private function validateDispenBatch(Request $request): array
    {
        $students = $request->input('students', []);
        $rules = [
            'students' => ['required', 'array', 'min:1'],
            'jenis_dispen' => ['nullable', Rule::in(['kegiatan', 'terlambat'])],
            'tanggal' => ['required', 'date'],
            'id_jam_mulai' => ['required', 'exists:jam_pels,id_jam'],
            'id_jam_selesai' => ['required', 'exists:jam_pels,id_jam'],
            'alasan' => ['required', 'string', 'max:255'],
        ];
        foreach (array_keys(is_array($students) ? $students : []) as $index) {
            $classId = data_get($students, "$index.id_kelas");
            $rules["students.$index.id_kelas"] = ['required', 'exists:kelases,id_kelas'];
            $rules["students.$index.id_siswa"] = [
                'required', 'distinct',
                Rule::exists('siswas', 'id_siswa')->where(fn ($query) => $query->where('id_kelas', $classId)),
            ];
        }

        $validated = $request->validate($rules, [
            'students.required' => 'Tambahkan minimal satu siswa.',
            'students.min' => 'Tambahkan minimal satu siswa.',
            'students.*.id_kelas.required' => 'Kelas wajib dipilih untuk setiap siswa.',
            'students.*.id_siswa.required' => 'Siswa wajib dipilih untuk setiap baris.',
            'students.*.id_siswa.distinct' => 'Siswa yang sama tidak boleh ditambahkan dua kali.',
            'students.*.id_siswa.exists' => 'Siswa tidak cocok dengan kelas yang dipilih.',
            'tanggal.required' => 'Tanggal dispensasi wajib diisi.',
            'id_jam_mulai.required' => 'Jam mulai wajib dipilih.',
            'id_jam_selesai.required' => 'Jam selesai wajib dipilih.',
            'alasan.required' => 'Alasan dispensasi wajib diisi.',
        ]);

        $jamMulaiKe = (int) JamPel::whereKey($validated['id_jam_mulai'])->value('jam_ke');
        $jamSelesaiKe = (int) JamPel::whereKey($validated['id_jam_selesai'])->value('jam_ke');
        if ($jamMulaiKe > $jamSelesaiKe) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'id_jam_selesai' => 'Jam selesai harus sama dengan atau setelah jam mulai.',
            ]);
        }

        $validated['jenis_dispen'] = $validated['jenis_dispen'] ?? 'kegiatan';

        return $validated;
    }

    private function syncLateDispenIntoJournals(Dispen $dispen): void
    {
        $dispen->load(['siswa', 'jamMulai', 'jamSelesai']);
        $jamMulaiKe = (int) $dispen->jamMulai?->jam_ke;
        $jamSelesaiKe = (int) $dispen->jamSelesai?->jam_ke;
        $hari = Carbon::parse($dispen->tanggal)->locale('id')->isoFormat('dddd');
        $jadwalJamMulai = Jadwal::query()
            ->where('id_kelas', $dispen->siswa->id_kelas)
            ->where('hari', $hari)
            ->with('jamMulai')
            ->get()
            ->pluck('jamMulai.jam_ke')
            ->filter()
            ->map(fn ($jamKe) => (int) $jamKe)
            ->sort()
            ->values();
        $jamBerikutnya = $jadwalJamMulai->first(fn ($jamKe) => $jamKe > $jamSelesaiKe);

        Jurnal::query()
            ->where('id_kelas', $dispen->siswa->id_kelas)
            ->whereDate('tanggal', $dispen->tanggal)
            ->where(function ($query) use ($jamMulaiKe, $jamSelesaiKe, $jamBerikutnya) {
                $query->where(function ($overlap) use ($jamMulaiKe, $jamSelesaiKe) {
                    $overlap->whereHas('jamSelesai', fn ($period) => $period->where('jam_ke', '>=', $jamMulaiKe))
                        ->whereHas('jamMulai', fn ($period) => $period->where('jam_ke', '<=', $jamSelesaiKe));
                });

                if ($jamBerikutnya !== null) {
                    $query->orWhereHas('jamMulai', fn ($period) => $period->where('jam_ke', $jamBerikutnya));
                }
            })
            ->get()
            ->each(function (Jurnal $jurnal) use ($dispen) {
                $detail = DetailAbsensi::where('id_jurnal', $jurnal->id_jurnal)
                    ->where('id_siswa', $dispen->id_siswa)
                    ->first();

                if ($detail) {
                    $detail->update([
                        'id_dispen' => $dispen->id_dispen,
                        'status' => 'Dispen',
                        'keterangan' => 'Dispensasi terlambat dari Piket',
                    ]);
                    return;
                }

                DetailAbsensi::create([
                    'id_jurnal' => $jurnal->id_jurnal,
                    'id_siswa' => $dispen->id_siswa,
                    'id_dispen' => $dispen->id_dispen,
                    'status' => 'Dispen',
                    'keterangan' => 'Dispensasi terlambat dari Piket',
                ]);
                $jurnal->update([
                    'jml_hadir' => max(0, $jurnal->jml_hadir - 1),
                    'jml_tidak_hadir' => $jurnal->jml_tidak_hadir + 1,
                ]);
            });
    }

    private function batchQuery(Dispen $dispen)
    {
        return $dispen->batch_token
            ? Dispen::where('batch_token', $dispen->batch_token)
            : Dispen::whereKey($dispen->id_dispen);
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