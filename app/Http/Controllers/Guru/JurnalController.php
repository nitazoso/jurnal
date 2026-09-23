<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
         * Pastikan jurnal untuk jadwal ini belum dibuat hari ini.
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
         * Ambil seluruh siswa kelas.
         *
         * Semua siswa tetap dikirim ke Blade karena:
         * - total siswa harus tetap dihitung
         * - siswa Hadir tetap dikirim sebagai absensi[id_siswa]=Hadir
         * - hanya siswa yang tidak hadir yang ditampilkan oleh JavaScript
         */
        $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->orderBy('nama_siswa')
            ->get();

        /*
         * SUMBER DISPEN
         *
         * Saat ini source/model Dispen belum terverifikasi dari project.
         * Jangan mengarang nama tabel/model karena bisa merusak BE.
         *
         * Blade sudah siap menerima:
         * - id_siswa
         * - nama_siswa
         *
         * Ganti collection ini dengan query Dispen asli project
         * setelah model/tabel Dispen sudah tersedia.
         */
        $siswaDispen = collect();

        return view('guru.jurnal.form', compact(
            'jadwal',
            'siswa',
            'siswaDispen'
        ));
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
         * Pastikan jadwal benar-benar milik guru yang login.
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
         * Cegah jurnal ganda untuk jadwal yang sama di hari yang sama.
         */
        $existingJurnal = Jurnal::where('id_jadwal', $jadwal->id_jadwal)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($existingJurnal) {
            return redirect()
                ->route('guru.jurnal.create')
                ->with('error', 'Jurnal untuk jadwal ini hari ini sudah dibuat.');
        }

        /*
         * Validasi absensi agar hanya siswa dari kelas jadwal
         * yang boleh dikirim.
         */
        $siswaKelas = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->pluck('id_siswa')
            ->map(fn ($id) => (string) $id)
            ->toArray();

        $absensi = collect($validated['absensi'])
            ->filter(function ($status, $idSiswa) use ($siswaKelas) {
                return in_array((string) $idSiswa, $siswaKelas, true);
            });

        /*
         * Hitung seluruh siswa.
         *
         * Hadir:
         *     status Hadir
         *
         * Tidak hadir:
         *     Sakit
         *     Izin
         *     Alpha
         *     Dispen
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
                 * tugas tetap mengikuti input default Tidak.
                 *
                 * Jika guru Izin/Sakit:
                 * tugas bisa diisi.
                 */
                $statusGuru = $validated['status_guru'];

                $adaTugas = in_array($statusGuru, ['Izin', 'Sakit'], true)
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
                 * Detail absensi:
                 * HANYA simpan siswa yang tidak hadir.
                 *
                 * Siswa Hadir tetap dikirim dari Blade,
                 * tetapi tidak perlu dibuat sebagai DetailAbsensi.
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
                ->with('success', 'Jurnal berhasil disimpan dan menunggu validasi.');
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
}