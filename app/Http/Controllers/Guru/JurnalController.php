<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\Dispen;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JurnalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jurnals = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ])
            ->whereHas('jadwal', function ($query) use ($user) {
                $query->where('id_guru', $user->id_guru);
            })
            ->latest('tanggal')
            ->get();

        return view('guru.jurnal.index', compact('jurnals'));
    }

    public function waliKelasRekap(Request $request)
    {
        $kelases = auth()->user()->guru?->kelasWali()->orderBy('nama_kelas')->get() ?? collect();
        abort_if($kelases->isEmpty(), 403);

        $validated = $request->validate([
            'id_kelas' => ['nullable', 'integer'],
            'bulan' => ['nullable', 'date_format:Y-m'],
            'status' => ['nullable', 'in:Menunggu,Disetujui,Ditolak,Perlu Diperbaiki'],
        ]);

        $selectedKelas = isset($validated['id_kelas'])
            ? $kelases->firstWhere('id_kelas', (int) $validated['id_kelas'])
            : $kelases->first();
        abort_if(! $selectedKelas, 404);

        $baseQuery = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ])
            ->where('id_kelas', $selectedKelas->id_kelas)
            ->whereHas('jadwal', function ($query) {
                $query->whereColumn('jadwals.id_kelas', 'jurnals.id_kelas');
            });

        $summary = [
            'total' => (clone $baseQuery)->count(),
            'menunggu' => (clone $baseQuery)->where('status_validasi_guru', 'Menunggu')->count(),
            'disetujui' => (clone $baseQuery)->where('status_validasi_guru', 'Disetujui')->count(),
            'perlu_diperbaiki' => (clone $baseQuery)->whereIn('status_validasi_guru', ['Ditolak', 'Perlu Diperbaiki'])->count(),
        ];

        $jurnals = (clone $baseQuery)
            ->when(isset($validated['bulan']), function ($query) use ($validated) {
                $month = Carbon::createFromFormat('Y-m', $validated['bulan']);
                $query->whereBetween('tanggal', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()]);
            })
            ->when(isset($validated['status']), fn ($query) => $query->where('status_validasi_guru', $validated['status']))
            ->orderByDesc('tanggal')
            ->orderByDesc('id_jurnal')
            ->paginate(15)
            ->withQueryString();

        return view('guru.jurnal.wali-kelas-rekap', compact(
            'kelases',
            'selectedKelas',
            'jurnals',
            'summary',
            'validated'
        ));
    }

    /**
     * Halaman pilih jadwal untuk mengisi jurnal.
     *
     * Hanya menampilkan:
     * - jadwal milik guru yang login
     * - jadwal hari ini
     * - jadwal yang jurnal HARI INI belum dibuat (bukan sepanjang masa,
     *   karena jadwal berulang tiap minggu)
     */
    public function create()
    {
        $allDisabled = Cache::get('jadwal_all_disabled', false);

        if ($allDisabled) {
            return view('guru.jurnal.create', [
                'jadwals' => collect(),
                'hariIni' => 'Pelajaran dinonaktifkan',
                'today' => Carbon::today(),
                'allDisabled' => $allDisabled,
            ]);
        }

        $user = auth()->user();
        $today = Carbon::today();

        $hariIndonesia = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];

        $hariIni = $hariIndonesia[$today->format('l')] ?? $today->format('l');

        $jadwals = Jadwal::with([
            'kelas',
            'mapel',
            'jamMulai',
            'jamSelesai',
        ])
            ->where('id_guru', $user->id_guru)
            ->when(! config('app.jurnal_bebas_testing'), fn ($query) =>
                $query->where('hari', $hariIni)
            )
            ->whereNotNull('id_jam_mulai')
            ->whereNotNull('id_jam_selesai')
            ->whereHas('jamMulai')
            ->whereHas('jamSelesai')
            ->whereDoesntHave('jurnals', function ($query) use ($today) {
                $query->whereDate('tanggal', $today);
            })
            ->get()
            ->sortBy(function ($jadwal) {
                return $jadwal->jamMulai->jam_mulai ?? '';
            })
            ->values();

        return view('guru.jurnal.create', compact(
            'jadwals',
            'hariIni',
            'today',
            'allDisabled'
        ));
    }

    public function form(Jadwal $jadwal)
    {
        if (Cache::get('jadwal_all_disabled', false)) {
            return redirect()->route('guru.jurnal.create')->with('error', 'Pelajaran sedang dinonaktifkan karena event khusus.');
        }

        $user = auth()->user();
        $today = Carbon::today();

        if ($jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        if (! $jadwal->jamMulai || ! $jadwal->jamSelesai) {
            return redirect()
                ->route('guru.jurnal.create')
                ->with('error', 'Jadwal ini tidak memiliki jam pelajaran aktif.');
        }

        if ($jadwal->jurnals()->whereDate('tanggal', $today)->exists()) {
            return redirect()
                ->route('guru.jurnal.index')
                ->with('info', 'Jurnal untuk jadwal ini hari ini sudah tersimpan di riwayat.');
        }

        if (! config('app.jurnal_bebas_testing') && ! $this->isScheduleWindowOpen($jadwal)) {
            return redirect()
                ->route('guru.jurnal.create')
                ->with('error', 'Jurnal hanya dapat diisi saat jadwal mengajar sedang berlangsung.');
        }

        $jadwal->load([
            'kelas',
            'mapel',
            'jamMulai',
            'jamSelesai',
        ]);

        $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->orderBy('nama_siswa')
            ->get();

        $activeDispenSiswa = $this->activeDispenSiswa(
            $jadwal->id_kelas,
            $today->toDateString()
        );
        $activeSickReports = $this->activeSickReports(
            $jadwal->id_kelas,
            $today->toDateString()
        );

        $activeDispenBerakhir = $this->activeDispenBerakhir(
            $jadwal->id_kelas,
            $today->toDateString()
        );

        // Daftar siswa dengan dispen aktif, dipakai untuk notice
        // "Siswa Dispensasi" di Blade.
        $siswaDispen = $siswa->whereIn('id_siswa', $activeDispenSiswa)->values();
        $isPiketEntry = false;

        return view('guru.jurnal.form', compact(
            'jadwal',
            'siswa',
            'activeDispenSiswa',
            'activeSickReports',
            'activeDispenBerakhir',
            'siswaDispen',
            'isPiketEntry'
        ));
    }

    public function formForPiket(Jadwal $jadwal)
    {
        if (Cache::get('jadwal_all_disabled', false)) {
            return redirect()->route('piket.jurnal.create')->with('error', 'Pelajaran sedang dinonaktifkan karena event khusus.');
        }

        $user = auth()->user();
        abort_unless(
            $user?->role === 'Staff Piket' || ($user?->role === 'Guru' && $user->hasPiketToday()),
            403
        );

        $today = Carbon::today();

        if (! $jadwal->jamMulai || ! $jadwal->jamSelesai) {
            return redirect()
                ->route('piket.jurnal.create', ['id_kelas' => $jadwal->id_kelas])
                ->with('error', 'Jadwal ini tidak memiliki jam pelajaran aktif.');
        }

        if ($jadwal->jurnals()->whereDate('tanggal', $today)->exists()) {
            return redirect()
                ->route('piket.jurnal.create', ['id_kelas' => $jadwal->id_kelas])
                ->with('info', 'Jurnal untuk jadwal ini hari ini sudah tersimpan.');
        }

        $jadwal->load(['kelas', 'mapel', 'guru', 'jamMulai', 'jamSelesai']);
        $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)->orderBy('nama_siswa')->get();
        $activeDispenSiswa = $this->activeDispenSiswa($jadwal->id_kelas, $today->toDateString());
        $activeSickReports = $this->activeSickReports($jadwal->id_kelas, $today->toDateString());
        $activeDispenBerakhir = $this->activeDispenBerakhir($jadwal->id_kelas, $today->toDateString());
        $siswaDispen = $siswa->whereIn('id_siswa', $activeDispenSiswa)->values();
        $isPiketEntry = true;

        return view('guru.jurnal.form', compact(
            'jadwal',
            'siswa',
            'activeDispenSiswa',
            'activeSickReports',
            'activeDispenBerakhir',
            'siswaDispen',
            'isPiketEntry'
        ));
    }

    public function store(Request $request)
    {
        $isPiketEntry = $request->routeIs('piket.jurnal.store');

        if (Cache::get('jadwal_all_disabled', false)) {
            $createRoute = $isPiketEntry ? 'piket.jurnal.create' : 'guru.jurnal.create';

            return redirect()->route($createRoute)->with('error', 'Pelajaran sedang dinonaktifkan karena event khusus.');
        }

        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwals,id_jadwal',
            'tanggal' => 'required|date',
            'materi' => 'required|string|max:255',
            'keterangan' => 'required|string',

            'ada_tugas' => [
                'required',
                'in:Ya,Tidak',
            ],

            'deskripsi_tugas' => 'nullable|string',
            'catatan_umum' => 'nullable|string|max:255',

            'absensi' => 'nullable|array',

            'absensi.*' => [
                'required',
                'in:Hadir,Sakit,Izin,Alpha,Dispen',
            ],
            'status_guru' => $isPiketEntry ? 'required|in:Sakit,Izin' : 'prohibited',
        ]);

        $user = auth()->user();
        abort_if(
            $isPiketEntry && ! ($user?->role === 'Staff Piket' || ($user?->role === 'Guru' && $user->hasPiketToday())),
            403
        );

        // Tanggal jurnal selalu hari ini di server.
        // Jangan percaya tanggal dari browser.
        $today = Carbon::today();
        $tanggal = $today->toDateString();

        $jadwal = Jadwal::findOrFail(
            $validated['id_jadwal']
        );

        if (! $isPiketEntry && $jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        // Cegah jurnal ganda untuk jadwal yang sama di hari yang sama.
        if (Jurnal::where('id_jadwal', $jadwal->id_jadwal)->whereDate('tanggal', $tanggal)->exists()) {
            return redirect()
                ->route($isPiketEntry ? 'piket.jurnal.create' : 'guru.jurnal.create', $isPiketEntry ? ['id_kelas' => $jadwal->id_kelas] : [])
                ->with('error', 'Jurnal untuk jadwal ini hari ini sudah dibuat.');
        }

        if (! $isPiketEntry && ! config('app.jurnal_bebas_testing') && ! $this->isScheduleWindowOpen($jadwal)) {
            return back()
                ->withErrors([
                    'id_jadwal' =>
                        'Jurnal hanya dapat diisi saat jadwal mengajar sedang berlangsung.',
                ])
                ->withInput();
        }

        $idSiswaKelas = Siswa::where(
            'id_kelas',
            $jadwal->id_kelas
        )->pluck('id_siswa');

        $validated['absensi'] = $validated['absensi'] ?? [];

        $idSiswaDikirim = collect(
            array_keys($validated['absensi'])
        )->map(
            fn ($id) => (int) $id
        );

        if (
            $idSiswaDikirim
                ->diff($idSiswaKelas)
                ->isNotEmpty()
        ) {
            throw ValidationException::withMessages([
                'absensi' =>
                    'Data absensi harus berasal dari siswa pada kelas jadwal ini.',
            ]);
        }

        $activeSickReports = $this->activeSickReports($jadwal->id_kelas, $tanggal);
        $activeDispenReports = $this->activeDispenQuery($jadwal->id_kelas, $tanggal)
            ->get()
            ->keyBy('id_siswa');

        $activeSickReports->each(function ($report, $idSiswa) use (&$validated) {
            $validated['absensi'][$idSiswa] = $report->jenis === 'izin' ? 'Izin' : 'Sakit';
        });

        $activeDispenReports->each(function ($report, $idSiswa) use (&$validated) {
            $validated['absensi'][$idSiswa] = 'Dispen';
        });

        $jmlHadir = 0;
        $jmlTidakHadir = 0;

        foreach ($validated['absensi'] as $status) {
            if ($status === 'Hadir') {
                $jmlHadir++;
            } else {
                $jmlTidakHadir++;
            }
        }

        try {
            DB::transaction(function () use ($validated, $jadwal, $user, $tanggal, $jmlHadir, $jmlTidakHadir, $activeSickReports, $activeDispenReports, $isPiketEntry) {
                $jurnal = Jurnal::create([
                    'id_jadwal' => $jadwal->id_jadwal,
                    'id_kelas' => $jadwal->id_kelas,
                    'id_guru' => $jadwal->id_guru,
                    'id_user' => $user->id_user ?? $user->id,

                    'id_jam_mulai' => $jadwal->id_jam_mulai,
                    'id_jam_selesai' => $jadwal->id_jam_selesai,

                    'tanggal' => $tanggal,
                    'materi' => $validated['materi'],
                    'keterangan' => $validated['keterangan'],

                    'status_guru' => $isPiketEntry ? $validated['status_guru'] : 'Hadir',

                    'ada_tugas' => $validated['ada_tugas'],
                    'deskripsi_tugas' =>
                        $validated['deskripsi_tugas'] ?? null,

                    'jml_hadir' => $jmlHadir,
                    'jml_tidak_hadir' => $jmlTidakHadir,

                    'status_kehadiran_validasi' => $isPiketEntry ? 'Tidak Hadir' : null,
                    'status_validasi_guru' => $isPiketEntry ? 'Disetujui' : 'Menunggu',
                    'diisi_oleh_piket' => $isPiketEntry,
                    'validated_at' => $isPiketEntry ? now() : null,

                    'catatan_umum' =>
                        $validated['catatan_umum'] ?? null,
                ]);

                foreach (
                    $validated['absensi'] as $idSiswa => $status
                ) {
                    if ($status === 'Hadir') {
                        continue;
                    }

                    DetailAbsensi::create([
                        'id_jurnal' => $jurnal->id_jurnal,
                        'id_siswa' => $idSiswa,
                        'id_dispen' => $activeDispenReports->get($idSiswa)?->id_dispen
                            ?? $activeSickReports->get($idSiswa)?->id_dispen,
                        'status' => $status,
                        'keterangan' => $status === 'Dispen'
                            ? 'Dispensasi otomatis'
                            : ($activeSickReports->has($idSiswa)
                                ? ($activeSickReports->get($idSiswa)->jenis === 'izin' ? 'Surat izin dari Piket' : 'Surat sakit dari Piket')
                                : null),
                    ]);
                }
            });

            return redirect()
                ->route($isPiketEntry ? 'piket.jurnal.create' : 'guru.jurnal.index', $isPiketEntry ? ['id_kelas' => $jadwal->id_kelas] : [])
                ->with(
                    'success',
                    'Jurnal berhasil disimpan.'
                );
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'store' => 'Jurnal gagal disimpan. Silakan coba lagi.',
                ]);
        }
    }

    public function show(Request $request, Jurnal $jurnal)
    {
        $user = auth()->user();
        $jadwal = $jurnal->jadwal;
        $isOwnJournal = $jadwal && (int) $jadwal->id_guru === (int) $user->id_guru;
        $isWaliForJournalClass = $jadwal
            && (int) $jadwal->id_kelas === (int) $jurnal->id_kelas
            && $user->guru?->kelasWali()->where('id_kelas', $jurnal->id_kelas)->exists();

        if (! $jadwal || (! $isOwnJournal && ! $isWaliForJournalClass)) {
            abort(403);
        }

        $fromWaliKelasRekap = $isWaliForJournalClass && $request->query('from') === 'wali-kelas-rekap';
        $backUrl = route('guru.jurnal.index');
        if ($fromWaliKelasRekap) {
            $backQuery = ['id_kelas' => $jurnal->id_kelas];
            $month = (string) $request->query('bulan', '');
            $status = $request->query('status');

            if (preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
                $backQuery['bulan'] = $month;
            }

            if (in_array($status, ['Menunggu', 'Disetujui', 'Ditolak', 'Perlu Diperbaiki'], true)) {
                $backQuery['status'] = $status;
            }

            $backUrl = route('guru.jurnal.wali-kelas-rekap', $backQuery);
        }

        $jurnal->load([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
            'detailAbsensis.siswa',
            'detailAbsensis.dispen',
            'validator',
            'user',
        ]);

        $siswaKelas = Siswa::where('id_kelas', $jurnal->id_kelas)
            ->orderBy('no_presensi')
            ->orderBy('nama_siswa')
            ->get();
        $absensiBySiswa = $jurnal->detailAbsensis->keyBy('id_siswa');
        $detailAbsensiLengkap = $jurnal->detailAbsensis->count() >= (int) ($jurnal->jml_tidak_hadir ?? 0);

        return view(
            'guru.jurnal.show',
            compact('jurnal', 'siswaKelas', 'absensiBySiswa', 'detailAbsensiLengkap', 'backUrl', 'fromWaliKelasRekap')
        );
    }

    private function isScheduleWindowOpen(Jadwal $jadwal): bool
    {
        $jadwal->loadMissing(['jamMulai', 'jamSelesai']);

        $start = $jadwal->jamMulai?->jam_mulai;
        $end = $jadwal->jamSelesai?->jam_selesai;

        if (! $start || ! $end) {
            return false;
        }

        $todayName = now()->locale('id')->isoFormat('dddd');

        if (strtolower((string) $jadwal->hari) !== strtolower((string) $todayName)) {
            return false;
        }

        $now = now();
        $startAt = $now->copy()->setTimeFromTimeString($start);
        $endAt = $now->copy()->setTimeFromTimeString($end);

        return $now->gte($startAt) && $now->lt($endAt);
    }

    private function activeDispenSiswa(
        int $idKelas,
        string $tanggal
    ) {
        return $this->activeDispenQuery(
            $idKelas,
            $tanggal
        )->pluck('id_siswa');
    }

    private function activeSickReports(int $idKelas, string $tanggal)
    {
        return Dispen::query()
            ->whereIn('jenis', ['sakit', 'izin'])
            ->whereDate('tanggal', $tanggal)
            ->whereHas('siswa', fn ($query) => $query->where('id_kelas', $idKelas))
            ->latest('id_dispen')
            ->get(['id_dispen', 'id_siswa', 'jenis', 'surat_path'])
            ->unique('id_siswa')
            ->keyBy('id_siswa');
    }

    private function activeDispenBerakhir(
        int $idKelas,
        string $tanggal
    ): ?string {
        return $this->activeDispenQuery(
            $idKelas,
            $tanggal
        )
            ->with(
                'jamSelesai:id_jam,jam_selesai'
            )
            ->get()
            ->map(
                fn (Dispen $dispen) =>
                    $dispen->jamSelesai?->jam_selesai
            )
            ->filter()
            ->min();
    }

    private function activeDispenQuery(
        int $idKelas,
        string $tanggal
    ) {
        if ($tanggal !== today()->toDateString()) {
            return Dispen::query()
                ->whereRaw('1 = 0');
        }

        $waktuSekarang = now()->format('H:i:s');

        return Dispen::query()
            ->where('jenis', 'dispen')
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'disetujui')
            ->whereHas(
                'siswa',
                fn ($query) =>
                    $query->where(
                        'id_kelas',
                        $idKelas
                    )
            )
            ->whereHas(
                'jamMulai',
                fn ($query) =>
                    $query->where(
                        'jam_mulai',
                        '<=',
                        $waktuSekarang
                    )
            )
            ->whereHas(
                'jamSelesai',
                fn ($query) =>
                    $query->where(
                        'jam_selesai',
                        '>=',
                        $waktuSekarang
                    )
            );
    }
}