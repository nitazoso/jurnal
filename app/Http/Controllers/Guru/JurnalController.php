<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\Dispen;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\GuruQrAttendance;
use App\Models\Siswa;
use Illuminate\Http\Request;
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
        // Jurnal dapat diisi langsung oleh guru atau dibantu sekretaris.
        // Kepemilikan jurnal pembelajaran tetap mengikuti guru pengampu.
        ->where('id_guru', $user->id_guru)
        ->latest('tanggal')
        ->get();

        return view('guru.jurnal.index', compact('jurnals'));
    }

    public function create()
    {
        $user = auth()->user();

        $jadwals = Jadwal::with([
            'kelas',
            'mapel',
            'jamMulai',
            'jamSelesai',
        ])
        ->where('id_guru', $user->id_guru)
        ->whereDoesntHave('jurnals')
        ->orderBy('hari')
        ->orderBy('id_jam_mulai')
        ->get();

        return view('guru.jurnal.create', compact('jadwals'));
    }

    public function form(Jadwal $jadwal)
    {
        $user = auth()->user();

        // Pastikan jadwal memang milik guru yang sedang login
        if ($jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        if ($jadwal->jurnals()->exists()) {
            return redirect()
                ->route('guru.jurnal.index')
                ->with('info', 'Jurnal untuk jadwal ini sudah tersimpan di riwayat.');
        }

        // Ambil data relasi jadwal
        $jadwal->load([
            'kelas',
            'mapel',
            'jamMulai',
            'jamSelesai',
        ]);

        // Ambil semua siswa dari kelas jadwal tersebut
        $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->orderBy('nama_siswa')
            ->get();

        $activeDispenSiswa = $this->activeDispenSiswa(
            $jadwal->id_kelas,
            today()->toDateString()
        );
        $activeDispenBerakhir = $this->activeDispenBerakhir(
            $jadwal->id_kelas,
            today()->toDateString()
        );
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

    public function verifyClassQr(Request $request, Jadwal $jadwal)
    {
        abort_unless($jadwal->id_guru === auth()->user()->id_guru, 403);

        $validated = $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        $jadwal->loadMissing('kelas');
        $qrToken = trim((string) ($validated['qr_token'] ?? ''));

        if (! hash_equals((string) $jadwal->kelas?->qr_token, $qrToken)) {
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

        session()->put($this->qrSessionKey($jadwal), true);

        return response()->json([
            'message' => 'Scan QR berhasil. Kehadiran guru sudah tersimpan.',
            'kelas' => $jadwal->kelas?->nama_kelas,
            'redirect' => route('guru.jurnal.form', $jadwal).'?scan=success',
        ]);
    }

    public function confirmAttendance(Jadwal $jadwal)
    {
        abort_unless($jadwal->id_guru === auth()->user()->id_guru, 403);

        $pending = session('guru.jurnal.qr_pending');
        abort_unless(($pending['jadwal_id'] ?? null) === $jadwal->id_jadwal, 419, 'Sesi scan QR sudah berakhir.');

        $jadwal->load(['kelas', 'mapel', 'jamMulai', 'jamSelesai']);

        return view('guru.jurnal.confirm-attendance', compact('jadwal'));
    }

    public function storeAttendance(Jadwal $jadwal)
    {
        abort_unless($jadwal->id_guru === auth()->user()->id_guru, 403);

        $pending = session('guru.jurnal.qr_pending');
        abort_unless(($pending['jadwal_id'] ?? null) === $jadwal->id_jadwal, 419, 'Sesi scan QR sudah berakhir.');

        $jadwal->load('kelas');
        abort_unless(hash_equals((string) $jadwal->kelas?->qr_token, (string) ($pending['qr_token'] ?? '')), 422);

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

        session()->put($this->qrSessionKey($jadwal), true);
        session()->forget('guru.jurnal.qr_pending');

        return redirect()->route('guru.jurnal.form', $jadwal)
            ->with('success', 'Kehadiran guru berhasil dikonfirmasi untuk kelas '.$jadwal->kelas->nama_kelas.'.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwals,id_jadwal',
            'tanggal' => 'required|date',
            'materi' => 'required|string|max:255',

            'status_guru' => [
                'required',
                'in:Hadir,Izin,Sakit',
            ],

            'ada_tugas' => [
                'required',
                'in:Ya,Tidak',
            ],

            'deskripsi_tugas' => 'nullable|string',
            'catatan_umum' => 'nullable|string|max:255',

            'absensi' => 'required|array',
            'absensi.*' => [
                'required',
                'in:Hadir,Sakit,Izin,Alpha,Dispen',
            ],
        ]);

        $user = auth()->user();

        // Ambil jadwal
        $jadwal = Jadwal::findOrFail($validated['id_jadwal']);

        // Pastikan jadwal milik guru yang login
        if ($jadwal->id_guru != $user->id_guru) {
            abort(403);
        }

        if (session()->get($this->qrSessionKey($jadwal)) !== true) {
            return back()->withErrors([
                'qr_token' => 'Silakan scan QR kelas terlebih dahulu sebelum menyimpan jurnal.',
            ])->withInput();
        }

        $idSiswaKelas = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->pluck('id_siswa');

        $idSiswaDikirim = collect(array_keys($validated['absensi']))
            ->map(fn ($id) => (int) $id);

        if ($idSiswaDikirim->diff($idSiswaKelas)->isNotEmpty()) {
            throw ValidationException::withMessages([
                'absensi' => 'Data absensi harus berasal dari siswa pada kelas jadwal ini.',
            ]);
        }

        // Dispen yang sudah disetujui hanya dikunci selama jam dispensasi
        // berlangsung. Status dari browser selalu ditimpa di server.
        $this->activeDispenSiswa($jadwal->id_kelas, $validated['tanggal'])
            ->each(function ($idSiswa) use (&$validated) {
                $validated['absensi'][$idSiswa] = 'Dispen';
            });

        // Hitung jumlah hadir dan tidak hadir
        $jmlHadir = 0;
        $jmlTidakHadir = 0;

        foreach ($validated['absensi'] as $status) {
            if ($status === 'Hadir') {
                $jmlHadir++;
            } else {
                $jmlTidakHadir++;
            }
        }

        // Simpan jurnal
        $jurnal = Jurnal::create([
            'id_jadwal' => $jadwal->id_jadwal,
            'id_kelas' => $jadwal->id_kelas,
            'id_guru' => $jadwal->id_guru,
            'id_user' => $user->id_user,

            'id_jam_mulai' => $jadwal->id_jam_mulai,
            'id_jam_selesai' => $jadwal->id_jam_selesai,

            'tanggal' => $validated['tanggal'],
            'materi' => $validated['materi'],

            'status_guru' => $validated['status_guru'],

            'ada_tugas' => $validated['ada_tugas'],
            'deskripsi_tugas' => $validated['deskripsi_tugas'] ?? null,

            'jml_hadir' => $jmlHadir,
            'jml_tidak_hadir' => $jmlTidakHadir,

            'status_validasi_guru' => 'Menunggu',

            'catatan_umum' => $validated['catatan_umum'] ?? null,
        ]);

        // Simpan detail siswa yang tidak hadir
        foreach ($validated['absensi'] as $idSiswa => $status) {

            // Hadir tidak perlu disimpan
            // karena jumlah hadir sudah disimpan di jurnal
            if ($status === 'Hadir') {
                continue;
            }

            DetailAbsensi::create([
                'id_jurnal' => $jurnal->id_jurnal,
                'id_siswa' => $idSiswa,
                'status' => $status,
                'keterangan' => null,
            ]);
        }

        return redirect()
            ->route('guru.jurnal.index')
            ->with('success', 'Jurnal berhasil disimpan.');
    }

    private function qrSessionKey(Jadwal $jadwal): string
    {
        return 'guru.jurnal.qr_verified.'.$jadwal->id_jadwal;
    }

    public function show(Jurnal $jurnal)
    {
        $user = auth()->user();

        // Guru hanya boleh melihat jurnal miliknya
        if ($jurnal->id_guru != $user->id_guru) {
            abort(403);
        }

        $jurnal->load([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ]);

        return view('guru.jurnal.show', compact('jurnal'));
    }

    /**
     * ID siswa dengan dispen yang telah disetujui dan masih berada dalam
     * rentang jam dispen pada hari ini.
     */
    private function activeDispenSiswa(int $idKelas, string $tanggal)
    {
        return $this->activeDispenQuery($idKelas, $tanggal)
            ->pluck('id_siswa');
    }

    /** Waktu selesai paling awal dari dispen yang sedang aktif. */
    private function activeDispenBerakhir(int $idKelas, string $tanggal): ?string
    {
        return $this->activeDispenQuery($idKelas, $tanggal)
            ->with('jamSelesai:id_jam,jam_selesai')
            ->get()
            ->map(fn (Dispen $dispen) => $dispen->jamSelesai?->jam_selesai)
            ->filter()
            ->min();
    }

    private function activeDispenQuery(int $idKelas, string $tanggal)
    {
        if ($tanggal !== today()->toDateString()) {
            return Dispen::query()->whereRaw('1 = 0');
        }

        $waktuSekarang = now()->format('H:i:s');

        return Dispen::query()
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'disetujui')
            ->whereHas('siswa', fn ($query) => $query->where('id_kelas', $idKelas))
            ->whereHas('jamMulai', fn ($query) => $query->where('jam_mulai', '<=', $waktuSekarang))
            ->whereHas('jamSelesai', fn ($query) => $query->where('jam_selesai', '>=', $waktuSekarang));
    }
}
