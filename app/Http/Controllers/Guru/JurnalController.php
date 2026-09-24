<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\Dispen;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\GuruQrAttendance;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JurnalController extends Controller
{
    /**
     * Daftar jurnal guru.
     */
    public function index()
    {
        $user = Auth::user();

        $jurnals = Jurnal::with([
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ])
            ->where('id_guru', $user->id_guru)
            ->latest('tanggal')
            ->latest('id_jurnal')
            ->get();

        $totalJurnal = $jurnals->count();

        return view('guru.jurnal.index', compact(
            'jurnals',
            'totalJurnal'
        ));
    }

    /**
     * Halaman pilih jadwal untuk mengisi jurnal.
     *
     * Hanya menampilkan:
     * - jadwal milik guru yang login
     * - jadwal hari ini
     * - jadwal yang jurnal hari ini belum dibuat
     */
    public function create()
    {
        $user = Auth::user();
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
            ->where('hari', $hariIni)
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
            'today'
        ));
    }

    /**
     * Form isi jurnal berdasarkan jadwal.
     */
    public function form($id_jadwal)
    {
        $user = Auth::user();
        $today = Carbon::today();

        /*
         * Ambil jadwal sekaligus memastikan jadwal
         * memang milik guru yang sedang login.
         */
        $jadwal = Jadwal::with([
            'kelas',
            'mapel',
            'jamMulai',
            'jamSelesai',
        ])
            ->where('id_jadwal', $id_jadwal)
            ->where('id_guru', $user->id_guru)
            ->firstOrFail();

        /*
         * Jurnal hanya boleh dibuat sekali untuk jadwal
         * yang sama pada hari yang sama.
         */
        $sudahAdaJurnal = Jurnal::where('id_jadwal', $jadwal->id_jadwal)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($sudahAdaJurnal) {
            return redirect()
                ->route('guru.jurnal.create')
                ->with('error', 'Jurnal untuk jadwal ini hari ini sudah dibuat.');
        }

        /*
         * Pastikan jadwal sedang berlangsung.
         */
        if (! $this->isScheduleWindowOpen($jadwal)) {
            return redirect()
                ->route('guru.jurnal.create')
                ->with('error', 'Jurnal hanya dapat diisi saat jadwal mengajar sedang berlangsung.');
        }

        /*
         * Ambil seluruh siswa kelas.
         */
        $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->orderBy('nama_siswa')
            ->get();

        /*
         * Ambil siswa yang sedang mendapat dispensasi aktif.
         */
        $activeDispenSiswa = $this->activeDispenSiswa(
            $jadwal->id_kelas,
            today()->toDateString()
        );

        $activeDispenBerakhir = $this->activeDispenBerakhir(
            $jadwal->id_kelas,
            today()->toDateString()
        );

        /*
         * QR dianggap sudah diverifikasi jika:
         * - session masih menyimpan status scan, atau
         * - sudah ada record GuruQrAttendance hari ini.
         */
        $qrVerified = session()->get($this->qrSessionKey($jadwal)) === true
            || GuruQrAttendance::where('id_jadwal', $jadwal->id_jadwal)
                ->where('id_guru', $user->id_guru)
                ->whereDate('tanggal', today())
                ->exists();

        return view('guru.jurnal.form', compact(
            'jadwal',
            'siswa',
            'activeDispenSiswa',
            'activeDispenBerakhir',
            'qrVerified'
        ));
    }

    /**
     * Verifikasi QR kelas.
     */
    public function verifyClassQr(Request $request, Jadwal $jadwal)
    {
        abort_unless(
            $jadwal->id_guru === auth()->user()->id_guru,
            403
        );

        $validated = $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        $jadwal->loadMissing('kelas');

        $qrToken = trim((string) ($validated['qr_token'] ?? ''));

        if (! hash_equals(
            (string) $jadwal->kelas?->qr_token,
            $qrToken
        )) {
            return response()->json([
                'message' => 'QR tidak valid untuk kelas ini. Pastikan Anda memindai QR kelas yang benar.',
            ], 422);
        }

        GuruQrAttendance::updateOrCreate(
            [
                'id_jadwal' => $jadwal->id_jadwal,
                'id_guru' => auth()->user()->id_guru,
                'tanggal' => today()->toDateString(),
            ],
            [
                'id_kelas' => $jadwal->id_kelas,
                'discan_pada' => now(),
            ]
        );

        session()->put(
            $this->qrSessionKey($jadwal),
            true
        );

        return response()->json([
            'message' => 'Scan QR berhasil. Kehadiran guru sudah tersimpan.',
            'kelas' => $jadwal->kelas?->nama_kelas,
            'redirect' => route('guru.jurnal.form', $jadwal) . '?scan=success',
        ]);
    }

    /**
     * Halaman konfirmasi kehadiran guru setelah scan QR.
     */
    public function confirmAttendance(Jadwal $jadwal)
    {
        abort_unless(
            $jadwal->id_guru === auth()->user()->id_guru,
            403
        );

        $pending = session('guru.jurnal.qr_pending');

        abort_unless(
            ($pending['jadwal_id'] ?? null) === $jadwal->id_jadwal,
            419,
            'Sesi scan QR sudah berakhir.'
        );

        $jadwal->load([
            'kelas',
            'mapel',
            'jamMulai',
            'jamSelesai',
        ]);

        return view(
            'guru.jurnal.confirm-attendance',
            compact('jadwal')
        );
    }

    /**
     * Simpan konfirmasi kehadiran guru.
     */
    public function storeAttendance(Jadwal $jadwal)
    {
        abort_unless(
            $jadwal->id_guru === auth()->user()->id_guru,
            403
        );

        $pending = session('guru.jurnal.qr_pending');

        abort_unless(
            ($pending['jadwal_id'] ?? null) === $jadwal->id_jadwal,
            419,
            'Sesi scan QR sudah berakhir.'
        );

        $jadwal->load('kelas');

        abort_unless(
            hash_equals(
                (string) $jadwal->kelas?->qr_token,
                (string) ($pending['qr_token'] ?? '')
            ),
            422
        );

        GuruQrAttendance::updateOrCreate(
            [
                'id_jadwal' => $jadwal->id_jadwal,
                'id_guru' => auth()->user()->id_guru,
                'tanggal' => today()->toDateString(),
            ],
            [
                'id_kelas' => $jadwal->id_kelas,
                'discan_pada' => now(),
            ]
        );

        session()->put(
            $this->qrSessionKey($jadwal),
            true
        );

        session()->forget('guru.jurnal.qr_pending');

        return redirect()
            ->route('guru.jurnal.form', $jadwal)
            ->with(
                'success',
                'Kehadiran guru berhasil dikonfirmasi untuk kelas ' .
                $jadwal->kelas->nama_kelas . '.'
            );
    }

    /**
     * Simpan jurnal + detail absensi.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'id_jadwal' => [
                'required',
                'integer',
                'exists:jadwals,id_jadwal',
            ],

            'tanggal' => [
                'required',
                'date',
            ],

            'materi' => [
                'required',
                'string',
            ],

            'keterangan' => [
                'required',
                'string',
            ],

            'status_guru' => [
                'required',
                'in:Hadir,Izin,Sakit',
            ],

            'ada_tugas' => [
                'nullable',
                'in:Ya,Tidak',
            ],

            'deskripsi_tugas' => [
                'nullable',
                'string',
            ],

            'catatan_umum' => [
                'nullable',
                'string',
            ],

            'absensi' => [
                'required',
                'array',
            ],

            'absensi.*' => [
                'required',
                'in:Hadir,Sakit,Izin,Alpha,Dispen',
            ],
        ]);

        $today = Carbon::today();

        /*
         * Tanggal jurnal selalu hari ini.
         * Jangan percaya tanggal dari browser.
         */
        $tanggal = $today->toDateString();

        /*
         * Pastikan jadwal memang milik guru yang login.
         */
        $jadwal = Jadwal::with([
            'kelas',
            'mapel',
            'jamMulai',
            'jamSelesai',
        ])
            ->where('id_jadwal', $validated['id_jadwal'])
            ->where('id_guru', $user->id_guru)
            ->firstOrFail();

        /*
         * Cegah jurnal ganda pada jadwal dan tanggal yang sama.
         */
        $existingJurnal = Jurnal::where('id_jadwal', $jadwal->id_jadwal)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($existingJurnal) {
            return redirect()
                ->route('guru.jurnal.create')
                ->with(
                    'error',
                    'Jurnal untuk jadwal ini hari ini sudah dibuat.'
                );
        }

        /*
         * Pastikan jurnal hanya dapat dibuat ketika
         * jam mengajar sedang berlangsung.
         */
        if (! $this->isScheduleWindowOpen($jadwal)) {
            return back()
                ->withErrors([
                    'id_jadwal' => 'Jurnal hanya dapat diisi saat jadwal mengajar sedang berlangsung.',
                ])
                ->withInput();
        }

        /*
         * Pastikan guru sudah melakukan verifikasi QR.
         *
         * Session digunakan sebagai pengecekan cepat,
         * database digunakan sebagai fallback jika session
         * sudah tidak tersedia.
         */
        $qrVerified = session()->get($this->qrSessionKey($jadwal)) === true
            || GuruQrAttendance::where('id_jadwal', $jadwal->id_jadwal)
                ->where('id_guru', $user->id_guru)
                ->whereDate('tanggal', today())
                ->exists();

        if (! $qrVerified) {
            return back()
                ->withErrors([
                    'qr_token' => 'Silakan scan QR kelas terlebih dahulu sebelum menyimpan jurnal.',
                ])
                ->withInput();
        }

        /*
         * Ambil ID siswa yang memang berada di kelas jadwal.
         */
        $idSiswaKelas = Siswa::where(
            'id_kelas',
            $jadwal->id_kelas
        )->pluck('id_siswa');

        /*
         * Pastikan tidak ada ID siswa asing dari browser.
         */
        $idSiswaDikirim = collect(
            array_keys($validated['absensi'])
        )->map(
            fn ($id) => (int) $id
        );

        if ($idSiswaDikirim->diff($idSiswaKelas)->isNotEmpty()) {
            throw ValidationException::withMessages([
                'absensi' => 'Data absensi harus berasal dari siswa pada kelas jadwal ini.',
            ]);
        }

        /*
         * Dispen yang sudah disetujui dan sedang aktif
         * otomatis dipaksa menjadi Dispen.
         *
         * Jadi status dari browser tidak bisa mengubah
         * siswa yang sebenarnya sedang Dispen menjadi Hadir.
         */
        $this->activeDispenSiswa(
            $jadwal->id_kelas,
            $tanggal
        )->each(function ($idSiswa) use (&$validated) {
            $validated['absensi'][$idSiswa] = 'Dispen';
        });

        /*
         * Filter hanya siswa dari kelas tersebut.
         */
        $absensi = collect($validated['absensi'])
            ->filter(function ($status, $idSiswa) use ($idSiswaKelas) {
                return $idSiswaKelas->contains((int) $idSiswa);
            });

        /*
         * Hitung jumlah hadir dan tidak hadir.
         */
        $jmlHadir = $absensi
            ->filter(fn ($status) => $status === 'Hadir')
            ->count();

        $jmlTidakHadir = $absensi->count() - $jmlHadir;

        try {
            DB::transaction(function () use (
                $validated,
                $jadwal,
                $user,
                $tanggal,
                $absensi,
                $jmlHadir,
                $jmlTidakHadir
            ) {
                /*
                 * Jika guru Hadir:
                 * tugas selalu Tidak.
                 *
                 * Jika guru Izin/Sakit:
                 * tugas mengikuti input.
                 */
                $statusGuru = $validated['status_guru'];

                $adaTugas = in_array(
                    $statusGuru,
                    ['Izin', 'Sakit'],
                    true
                )
                    ? ($validated['ada_tugas'] ?? 'Tidak')
                    : 'Tidak';

                $deskripsiTugas = $adaTugas === 'Ya'
                    ? ($validated['deskripsi_tugas'] ?? null)
                    : null;

                /*
                 * Buat jurnal.
                 */
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

                    'status_guru' => $statusGuru,

                    'ada_tugas' => $adaTugas,
                    'deskripsi_tugas' => $deskripsiTugas,

                    'jml_hadir' => $jmlHadir,
                    'jml_tidak_hadir' => $jmlTidakHadir,

                    'status_validasi_guru' => 'Menunggu',

                    'catatan_umum' => $validated['catatan_umum'] ?? null,
                ]);

                /*
                 * HANYA simpan siswa yang tidak hadir.
                 *
                 * Hadir:
                 * tidak dibuat DetailAbsensi.
                 *
                 * Sakit / Izin / Alpha / Dispen:
                 * dibuat DetailAbsensi.
                 */
                foreach ($absensi as $idSiswa => $status) {
                    if ($status === 'Hadir') {
                        continue;
                    }

                    DetailAbsensi::create([
                        'id_jurnal' => $jurnal->id_jurnal,
                        'id_siswa' => $idSiswa,
                        'status' => $status,
                        'keterangan' => $status === 'Dispen'
                            ? 'Dispensasi otomatis'
                            : null,
                    ]);
                }
            });

            return redirect()
                ->route('guru.jurnal.index')
                ->with(
                    'success',
                    'Jurnal berhasil disimpan dan menunggu validasi.'
                );
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'store' => 'Jurnal gagal disimpan. Silakan coba lagi.',
                ]);
        }
    }

    /**
     * Detail jurnal.
     */
    public function show($id)
    {
        $user = Auth::user();

        $jurnal = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ])
            ->where('id_guru', $user->id_guru)
            ->findOrFail($id);

        return view('guru.jurnal.show', compact('jurnal'));
    }

    /**
     * Session key untuk status QR.
     */
    private function qrSessionKey(Jadwal $jadwal): string
    {
        return 'guru.jurnal.qr_verified.' . $jadwal->id_jadwal;
    }

    /**
     * Cek apakah sekarang berada di dalam jam jadwal.
     */
    private function isScheduleWindowOpen(Jadwal $jadwal): bool
    {
        $jadwal->loadMissing([
            'jamMulai',
            'jamSelesai',
        ]);

        $start = $jadwal->jamMulai?->jam_mulai;
        $end = $jadwal->jamSelesai?->jam_selesai;

        if (! $start || ! $end) {
            return false;
        }

        $todayName = now()
            ->locale('id')
            ->isoFormat('dddd');

        if (
            strtolower((string) $jadwal->hari) !==
            strtolower((string) $todayName)
        ) {
            return false;
        }

        $now = now();

        $startAt = $now
            ->copy()
            ->setTimeFromTimeString($start);

        $endAt = $now
            ->copy()
            ->setTimeFromTimeString($end);

        return $now->gte($startAt)
            && $now->lt($endAt);
    }

    /**
     * ID siswa dengan Dispen yang:
     * - tanggalnya hari ini
     * - statusnya disetujui
     * - berada di kelas yang sesuai
     * - waktunya sedang aktif
     */
    private function activeDispenSiswa(
        int $idKelas,
        string $tanggal
    ) {
        return $this->activeDispenQuery(
            $idKelas,
            $tanggal
        )->pluck('id_siswa');
    }

    /**
     * Mengambil waktu selesai paling awal
     * dari Dispen yang sedang aktif.
     */
    private function activeDispenBerakhir(
        int $idKelas,
        string $tanggal
    ): ?string {
        return $this->activeDispenQuery(
            $idKelas,
            $tanggal
        )
            ->with('jamSelesai:id_jam,jam_selesai')
            ->get()
            ->map(
                fn (Dispen $dispen) =>
                    $dispen->jamSelesai?->jam_selesai
            )
            ->filter()
            ->min();
    }

    /**
     * Query Dispen aktif.
     */
    private function activeDispenQuery(
        int $idKelas,
        string $tanggal
    ) {
        /*
         * Dispen hanya dianggap aktif untuk hari ini.
         */
        if ($tanggal !== today()->toDateString()) {
            return Dispen::query()
                ->whereRaw('1 = 0');
        }

        $waktuSekarang = now()->format('H:i:s');

        return Dispen::query()
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'disetujui')
            ->whereHas('siswa', function ($query) use ($idKelas) {
                $query->where('id_kelas', $idKelas);
            })
            ->whereHas('jamMulai', function ($query) use ($waktuSekarang) {
                $query->where(
                    'jam_mulai',
                    '<=',
                    $waktuSekarang
                );
            })
            ->whereHas('jamSelesai', function ($query) use ($waktuSekarang) {
                $query->where(
                    'jam_selesai',
                    '>=',
                    $waktuSekarang
                );
            });
    }
}