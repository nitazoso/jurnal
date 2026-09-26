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
            ->where('hari', $hariIni)
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
            'today'
        ));
    }

    public function form(Jadwal $jadwal)
    {
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

        if (! $this->isScheduleWindowOpen($jadwal)) {
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

        $activeDispenBerakhir = $this->activeDispenBerakhir(
            $jadwal->id_kelas,
            $today->toDateString()
        );

        // Daftar siswa dengan dispen aktif, dipakai untuk notice
        // "Siswa Dispensasi" di Blade.
        $siswaDispen = $siswa->whereIn('id_siswa', $activeDispenSiswa)->values();

        return view('guru.jurnal.form', compact(
            'jadwal',
            'siswa',
            'activeDispenSiswa',
            'activeDispenBerakhir',
            'siswaDispen'
        ));
    }

    public function store(Request $request)
    {
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
        ]);

        $user = auth()->user();

        // Tanggal jurnal selalu hari ini di server.
        // Jangan percaya tanggal dari browser.
        $today = Carbon::today();
        $tanggal = $today->toDateString();

        $jadwal = Jadwal::findOrFail(
            $validated['id_jadwal']
        );

        if ($jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        // Cegah jurnal ganda untuk jadwal yang sama di hari yang sama.
        if (Jurnal::where('id_jadwal', $jadwal->id_jadwal)->whereDate('tanggal', $tanggal)->exists()) {
            return redirect()
                ->route('guru.jurnal.create')
                ->with('error', 'Jurnal untuk jadwal ini hari ini sudah dibuat.');
        }

        if (! $this->isScheduleWindowOpen($jadwal)) {
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

        $this->activeDispenSiswa(
            $jadwal->id_kelas,
            $tanggal
        )->each(function ($idSiswa) use (&$validated) {
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
            DB::transaction(function () use ($validated, $jadwal, $user, $tanggal, $jmlHadir, $jmlTidakHadir) {
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

                    'status_guru' => 'Hadir',

                    'ada_tugas' => $validated['ada_tugas'],
                    'deskripsi_tugas' =>
                        $validated['deskripsi_tugas'] ?? null,

                    'jml_hadir' => $jmlHadir,
                    'jml_tidak_hadir' => $jmlTidakHadir,

                    'status_validasi_guru' => 'Menunggu',

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

    public function show(Jurnal $jurnal)
    {
        $user = auth()->user();

        if (! $jurnal->jadwal || $jurnal->jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        $jurnal->load([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ]);

        return view(
            'guru.jurnal.show',
            compact('jurnal')
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