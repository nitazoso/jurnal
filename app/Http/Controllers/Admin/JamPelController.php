<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamPel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JamPelController extends Controller
{
    /**
     * Tampilkan daftar jam pelajaran
     */
    public function index(Request $request)
    {
        $query = JamPel::query();

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('klp_hari', 'like', '%' . $search . '%')
                    ->orWhere('jenis', 'like', '%' . $search . '%')
                    ->orWhere('jam_ke', 'like', '%' . $search . '%');
            });
        }

        $jamPels = $query
            ->orderByRaw("FIELD(klp_hari, 'Senin-Kamis', 'Jumat')")
            ->orderBy('jam_mulai')
            ->get();

        $totalJam = JamPel::count();

        return view('admin.jam.index', compact(
            'jamPels',
            'totalJam'
        ));
    }

    /**
     * Form tambah jam pelajaran
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | CEK JADWAL YANG SUDAH ADA
        |--------------------------------------------------------------------------
        |
        | Digunakan oleh create.blade.php untuk:
        | - Menonaktifkan pilihan yang sudah ada
        | - Menentukan pilihan default
        | - Menampilkan informasi kepada admin
        |
        */

        $adaSeninKamis = JamPel::where(
            'klp_hari',
            'Senin-Kamis'
        )->exists();

        $adaJumat = JamPel::where(
            'klp_hari',
            'Jumat'
        )->exists();

        return view(
            'admin.jam.create',
            compact(
                'adaSeninKamis',
                'adaJumat'
            )
        );
    }

    /**
     * Simpan konfigurasi jam pelajaran baru
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'klp_hari' => [
                'required',
                'in:Senin-Kamis,Jumat',
            ],

            'jam_masuk' => [
                'required',
                'date_format:H:i',
            ],

            'jam_pulang' => [
                'required',
                'date_format:H:i',
                'after:jam_masuk',
            ],

            'mode_durasi' => [
                'required',
                'in:seragam,fleksibel,sesuaikan_pulang',
            ],

            'durasi_jp' => [
                'required_if:mode_durasi,seragam,sesuaikan_pulang',
                'nullable',
                'integer',
                'min:5',
            ],

            'durasi_khusus' => [
                'nullable',
                'array',
            ],

            'istirahat' => [
                'nullable',
                'array',
            ],

            'istirahat.*.setelah_jam' => [
                'required',
                'integer',
                'min:1',
            ],

            'istirahat.*.durasi' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT KELOMPOK HARI
        |--------------------------------------------------------------------------
        |
        | Satu kelompok hanya boleh mempunyai satu konfigurasi:
        |
        | Senin-Kamis -> maksimal 1
        | Jumat       -> maksimal 1
        |
        | Jika sudah ada, admin harus menghapus jadwal lama terlebih
        | dahulu sebelum membuat jadwal baru.
        */

        $sudahAda = JamPel::where(
            'klp_hari',
            $request->klp_hari
        )->exists();

        if ($sudahAda) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    "Jadwal {$request->klp_hari} sudah ada. Hapus jadwal lama terlebih dahulu sebelum menambahkan jadwal baru."
                );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DALAM TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($request) {

            $klpHari = $request->klp_hari;

            /*
            |--------------------------------------------------------------------------
            | WAKTU MULAI DAN SELESAI
            |--------------------------------------------------------------------------
            */

            $currentTime = Carbon::createFromTimeString(
                $request->jam_masuk
            );

            $endTime = Carbon::createFromTimeString(
                $request->jam_pulang
            );

            $modeDurasi = $request->mode_durasi;

            $durasiDefault = (int) (
                $request->durasi_jp ?? 40
            );

            /*
            |--------------------------------------------------------------------------
            | MAP ISTIRAHAT
            |--------------------------------------------------------------------------
            */

            $istirahatMap = [];

            foreach (
                $request->input('istirahat', [])
                as $ist
            ) {
                if (
                    isset($ist['setelah_jam']) &&
                    isset($ist['durasi']) &&
                    $ist['setelah_jam'] !== '' &&
                    $ist['durasi'] !== ''
                ) {
                    $istirahatMap[
                        (int) $ist['setelah_jam']
                    ] = (int) $ist['durasi'];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | MAP DURASI KHUSUS
            |--------------------------------------------------------------------------
            */

            $durasiKhususMap = $request->input(
                'durasi_khusus',
                []
            );

            /*
            |--------------------------------------------------------------------------
            | GENERATE JAM PELAJARAN
            |--------------------------------------------------------------------------
            */

            $jamKe = 1;

            while ($currentTime->lt($endTime)) {

                /*
                |--------------------------------------------------------------------------
                | TENTUKAN DURASI
                |--------------------------------------------------------------------------
                */

                $durasiCurrent = $durasiDefault;

                if (
                    $modeDurasi === 'fleksibel' &&
                    isset($durasiKhususMap[$jamKe]) &&
                    $durasiKhususMap[$jamKe] !== ''
                ) {
                    $durasiCurrent = (int)
                        $durasiKhususMap[$jamKe];
                }

                /*
                |--------------------------------------------------------------------------
                | WAKTU MULAI
                |--------------------------------------------------------------------------
                */

                $startSlot = $currentTime->copy();

                /*
                |--------------------------------------------------------------------------
                | HITUNG SISA WAKTU
                |--------------------------------------------------------------------------
                */

                $sisaWaktu = $currentTime->diffInMinutes(
                    $endTime,
                    false
                );

                /*
                |--------------------------------------------------------------------------
                | MODE SESUAIKAN JAM PULANG
                |--------------------------------------------------------------------------
                */

                if (
                    $modeDurasi === 'sesuaikan_pulang' &&
                    $sisaWaktu < $durasiCurrent &&
                    $sisaWaktu > 0
                ) {
                    $durasiCurrent = $sisaWaktu;
                }

                /*
                |--------------------------------------------------------------------------
                | DURASI TIDAK VALID
                |--------------------------------------------------------------------------
                */

                if ($durasiCurrent <= 0) {
                    break;
                }

                /*
                |--------------------------------------------------------------------------
                | WAKTU SELESAI
                |--------------------------------------------------------------------------
                */

                $endSlot = $currentTime
                    ->copy()
                    ->addMinutes($durasiCurrent);

                /*
                |--------------------------------------------------------------------------
                | JANGAN MELEWATI JAM PULANG
                |--------------------------------------------------------------------------
                */

                if (
                    $endSlot->gt($endTime) &&
                    $modeDurasi !== 'sesuaikan_pulang'
                ) {
                    break;
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPAN JAM PELAJARAN
                |--------------------------------------------------------------------------
                */

                JamPel::create([
                    'klp_hari' => $klpHari,
                    'jam_ke' => $jamKe,
                    'jenis' => 'pelajaran',
                    'jam_mulai' => $startSlot->format('H:i:s'),
                    'jam_selesai' => $endSlot->format('H:i:s'),
                    'durasi_menit' => $durasiCurrent,
                ]);

                $currentTime = $endSlot;

                /*
                |--------------------------------------------------------------------------
                | SIMPAN ISTIRAHAT
                |--------------------------------------------------------------------------
                */

                if (isset($istirahatMap[$jamKe])) {

                    $durasiIst =
                        $istirahatMap[$jamKe];

                    $startIst =
                        $currentTime->copy();

                    $endIst =
                        $currentTime
                            ->copy()
                            ->addMinutes($durasiIst);

                    /*
                    |--------------------------------------------------------------------------
                    | ISTIRAHAT HARUS MASIH MUAT
                    |--------------------------------------------------------------------------
                    */

                    if ($endIst->lte($endTime)) {

                        JamPel::create([
                            'klp_hari' => $klpHari,
                            'jam_ke' => null,
                            'jenis' => 'istirahat',
                            'jam_mulai' => $startIst->format('H:i:s'),
                            'jam_selesai' => $endIst->format('H:i:s'),
                            'durasi_menit' => $durasiIst,
                        ]);

                        $currentTime = $endIst;
                    }
                }

                $jamKe++;
            }
        });

        /*
        |--------------------------------------------------------------------------
        | REDIRECT SUKSES
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.jam.index')
            ->with(
                'success',
                "Konfigurasi jadwal {$request->klp_hari} berhasil disimpan!"
            );
    }

    /**
     * Form edit konfigurasi kelompok hari
     */
    public function edit($klp_hari)
    {
        $jamPels = JamPel::where(
            'klp_hari',
            $klp_hari
        )
            ->orderBy('jam_mulai')
            ->get();

        return view(
            'admin.jam.edit',
            compact(
                'klp_hari',
                'jamPels'
            )
        );
    }

    /**
     * Update konfigurasi kelompok hari
     */
    public function update(
        Request $request,
        $klp_hari
    ) {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'jam_masuk' => [
                'required',
                'date_format:H:i',
            ],

            'jam_pulang' => [
                'required',
                'date_format:H:i',
                'after:jam_masuk',
            ],

            'mode_durasi' => [
                'required',
                'in:seragam,fleksibel,sesuaikan_pulang',
            ],

            'durasi_jp' => [
                'required_if:mode_durasi,seragam,sesuaikan_pulang',
                'nullable',
                'integer',
                'min:5',
            ],

            'durasi_khusus' => [
                'nullable',
                'array',
            ],

            'istirahat' => [
                'nullable',
                'array',
            ],

            'istirahat.*.setelah_jam' => [
                'required',
                'integer',
                'min:1',
            ],

            'istirahat.*.durasi' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION UPDATE
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $request,
            $klp_hari
        ) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS KONFIGURASI LAMA
            |--------------------------------------------------------------------------
            */

            JamPel::where(
                'klp_hari',
                $klp_hari
            )->delete();

            /*
            |--------------------------------------------------------------------------
            | WAKTU
            |--------------------------------------------------------------------------
            */

            $currentTime = Carbon::createFromTimeString(
                $request->jam_masuk
            );

            $endTime = Carbon::createFromTimeString(
                $request->jam_pulang
            );

            $modeDurasi = $request->mode_durasi;

            $durasiDefault = (int) (
                $request->durasi_jp ?? 40
            );

            /*
            |--------------------------------------------------------------------------
            | MAP ISTIRAHAT
            |--------------------------------------------------------------------------
            */

            $istirahatMap = [];

            foreach (
                $request->input('istirahat', [])
                as $ist
            ) {
                if (
                    isset($ist['setelah_jam']) &&
                    isset($ist['durasi']) &&
                    $ist['setelah_jam'] !== '' &&
                    $ist['durasi'] !== ''
                ) {
                    $istirahatMap[
                        (int) $ist['setelah_jam']
                    ] = (int) $ist['durasi'];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | MAP DURASI KHUSUS
            |--------------------------------------------------------------------------
            */

            $durasiKhususMap = $request->input(
                'durasi_khusus',
                []
            );

            /*
            |--------------------------------------------------------------------------
            | GENERATE ULANG
            |--------------------------------------------------------------------------
            */

            $jamKe = 1;

            while ($currentTime->lt($endTime)) {

                /*
                |--------------------------------------------------------------------------
                | TENTUKAN DURASI
                |--------------------------------------------------------------------------
                */

                $durasiCurrent = $durasiDefault;

                if (
                    $modeDurasi === 'fleksibel' &&
                    isset($durasiKhususMap[$jamKe]) &&
                    $durasiKhususMap[$jamKe] !== ''
                ) {
                    $durasiCurrent = (int)
                        $durasiKhususMap[$jamKe];
                }

                /*
                |--------------------------------------------------------------------------
                | WAKTU MULAI
                |--------------------------------------------------------------------------
                */

                $startSlot =
                    $currentTime->copy();

                /*
                |--------------------------------------------------------------------------
                | SESUAIKAN JAM PULANG
                |--------------------------------------------------------------------------
                */

                if (
                    $modeDurasi === 'sesuaikan_pulang'
                ) {

                    $sisaWaktu =
                        $currentTime->diffInMinutes(
                            $endTime,
                            false
                        );

                    if (
                        $sisaWaktu < $durasiCurrent &&
                        $sisaWaktu > 0
                    ) {
                        $durasiCurrent =
                            $sisaWaktu;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | DURASI TIDAK VALID
                |--------------------------------------------------------------------------
                */

                if ($durasiCurrent <= 0) {
                    break;
                }

                /*
                |--------------------------------------------------------------------------
                | WAKTU SELESAI
                |--------------------------------------------------------------------------
                */

                $endSlot =
                    $currentTime
                        ->copy()
                        ->addMinutes(
                            $durasiCurrent
                        );

                /*
                |--------------------------------------------------------------------------
                | JANGAN MELEWATI JAM PULANG
                |--------------------------------------------------------------------------
                */

                if (
                    $endSlot->gt($endTime)
                ) {
                    break;
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPAN JAM PELAJARAN
                |--------------------------------------------------------------------------
                */

                JamPel::create([
                    'klp_hari' => $klp_hari,
                    'jam_ke' => $jamKe,
                    'jenis' => 'pelajaran',
                    'jam_mulai' =>
                        $startSlot->format('H:i:s'),
                    'jam_selesai' =>
                        $endSlot->format('H:i:s'),
                    'durasi_menit' =>
                        $durasiCurrent,
                ]);

                $currentTime = $endSlot;

                /*
                |--------------------------------------------------------------------------
                | SIMPAN ISTIRAHAT
                |--------------------------------------------------------------------------
                */

                if (
                    isset($istirahatMap[$jamKe])
                ) {

                    $durasiIst =
                        $istirahatMap[$jamKe];

                    $startIst =
                        $currentTime->copy();

                    $endIst =
                        $currentTime
                            ->copy()
                            ->addMinutes(
                                $durasiIst
                            );

                    if (
                        $endIst->lte($endTime)
                    ) {

                        JamPel::create([
                            'klp_hari' => $klp_hari,
                            'jam_ke' => null,
                            'jenis' => 'istirahat',
                            'jam_mulai' =>
                                $startIst->format('H:i:s'),
                            'jam_selesai' =>
                                $endIst->format('H:i:s'),
                            'durasi_menit' =>
                                $durasiIst,
                        ]);

                        $currentTime =
                            $endIst;
                    }
                }

                $jamKe++;
            }
        });

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.jam.index')
            ->with(
                'success',
                "Jadwal {$klp_hari} berhasil diperbarui!"
            );
    }


    public function destroy($klp_hari)
    {
        JamPel::where(
            'klp_hari',
            $klp_hari
        )->delete();

        return redirect()
            ->route('admin.jam.index')
            ->with(
                'success',
                "Jadwal {$klp_hari} berhasil dihapus!"
            );
    }
}