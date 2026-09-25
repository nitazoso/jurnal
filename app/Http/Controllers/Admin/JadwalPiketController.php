<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PiketJadwal;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class JadwalPiketController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * Menampilkan kalender jadwal piket.
     * =========================================================
     */
    public function index(Request $request)
    {

        /*
        |--------------------------------------------------------------------------
        | BULAN YANG DITAMPILKAN
        |--------------------------------------------------------------------------
        */

        $month = $request->input(
            'month',
            now()->format('Y-m')
        );

        try {

            $tanggalAwal = Carbon::createFromFormat(
                'Y-m',
                $month
            )->startOfMonth();

            $tanggalAkhir = Carbon::createFromFormat(
                'Y-m',
                $month
            )->endOfMonth();

        } catch (\Exception $e) {

            $tanggalAwal = now()->startOfMonth();
            $tanggalAkhir = now()->endOfMonth();
            $month = now()->format('Y-m');

        }


        /*
        |--------------------------------------------------------------------------
        | QUERY JADWAL
        |--------------------------------------------------------------------------
        */

        $query = PiketJadwal::with('guru')
            ->whereBetween('tanggal', [
                $tanggalAwal->toDateString(),
                $tanggalAkhir->toDateString(),
            ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'jenis_tugas',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'shift',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'guru',
                    function ($guru) use ($search) {

                        $guru->where(
                            'nama_guru',
                            'like',
                            "%{$search}%"
                        );

                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER SHIFT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('filter')) {

            match ($request->filter) {

                'pagi' => $query->where(
                    'shift',
                    'Pagi'
                ),

                'siang' => $query->where(
                    'shift',
                    'Siang'
                ),

                'waka' => $query->where(
                    'shift',
                    'Waka'
                ),

                default => null,

            };

        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL JADWAL
        |--------------------------------------------------------------------------
        */

        $jadwals = $query
            ->orderBy('tanggal')
            ->orderBy('shift')
            ->orderBy('posisi')
            ->get();

        $piketHours = $this->piketHours();


        /*
        |--------------------------------------------------------------------------
        | GROUP BERDASARKAN TANGGAL
        |--------------------------------------------------------------------------
        */

        $byDate = $jadwals->groupBy(function ($item) {

            return $item->tanggal->format('Y-m-d');

        });


        /*
        |--------------------------------------------------------------------------
        | RANGE KALENDER
        |--------------------------------------------------------------------------
        */

        $calendarStart = $tanggalAwal
            ->copy()
            ->startOfWeek(Carbon::MONDAY);

        $calendarEnd = $tanggalAkhir
            ->copy()
            ->endOfWeek(Carbon::SUNDAY);


        $calendarDays = [];

        for (
            $date = $calendarStart->copy();
            $date->lte($calendarEnd);
            $date->addDay()
        ) {

            $calendarDays[] = $date->copy();

        }


        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK DRAWER DETAIL + EDIT
        |--------------------------------------------------------------------------
        |
        | Di sini ID guru ikut dikirim.
        | Jadi JavaScript bisa mengetahui guru mana yang dipilih
        | ketika mode edit dibuka.
        |
        */

        Carbon::setLocale('id');

        $calendarData = [];


        foreach ($byDate as $date => $rows) {

            /*
            |--------------------------------------------------------------------------
            | PAGI - PETUGAS
            |--------------------------------------------------------------------------
            */

            $pagiPetugas = $rows
                ->where(
                    'jenis_tugas',
                    'Piket KBM Pagi'
                )
                ->map(function ($row) {

                    return [
                        'id_guru' => $row->id_guru,

                        'nama' =>
                            $row->guru?->nama_guru ?? '-',
                    ];

                })
                ->values()
                ->all();


            /*
            |--------------------------------------------------------------------------
            | PAGI - KOORDINATOR
            |--------------------------------------------------------------------------
            */

            $pagiKoordinator =
                $rows
                    ->where(
                        'jenis_tugas',
                        'Koordinator Piket KBM Pagi'
                    )
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | SIANG - PETUGAS
            |--------------------------------------------------------------------------
            */

            $siangPetugas = $rows
                ->where(
                    'jenis_tugas',
                    'Piket KBM Siang'
                )
                ->map(function ($row) {

                    return [
                        'id_guru' => $row->id_guru,

                        'nama' =>
                            $row->guru?->nama_guru ?? '-',
                    ];

                })
                ->values()
                ->all();


            /*
            |--------------------------------------------------------------------------
            | SIANG - KOORDINATOR
            |--------------------------------------------------------------------------
            */

            $siangKoordinator =
                $rows
                    ->where(
                        'jenis_tugas',
                        'Koordinator Piket KBM Siang'
                    )
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | WAKA
            |--------------------------------------------------------------------------
            */

            $wakaPetugas = $rows
                ->where(
                    'jenis_tugas',
                    'Piket Waka'
                )
                ->map(function ($row) {

                    return [
                        'id_guru' => $row->id_guru,

                        'nama' =>
                            $row->guru?->nama_guru ?? '-',
                    ];

                })
                ->values()
                ->all();


            /*
            |--------------------------------------------------------------------------
            | GABUNG DATA PER TANGGAL
            |--------------------------------------------------------------------------
            */

            $pagiJadwal = $rows
                ->where('shift', 'Pagi')
                ->first();

            $siangJadwal = $rows
                ->where('shift', 'Siang')
                ->first();

            $calendarData[$date] = [

                'label' =>
                    Carbon::parse($date)
                        ->translatedFormat(
                            'l, d F Y'
                        ),


                'pagi' => [

                    'jam_mulai' => $pagiJadwal?->jam_mulai,

                    'jam_selesai' => $pagiJadwal?->jam_selesai,

                    'petugas' =>
                        $pagiPetugas,

                    'koordinator' =>
                        $pagiKoordinator
                            ? [
                                'id_guru' =>
                                    $pagiKoordinator->id_guru,

                                'nama' =>
                                    $pagiKoordinator
                                        ->guru?->nama_guru ?? '-',
                            ]
                            : null,

                ],


                'siang' => [

                    'jam_mulai' => $siangJadwal?->jam_mulai,

                    'jam_selesai' => $siangJadwal?->jam_selesai,

                    'petugas' =>
                        $siangPetugas,

                    'koordinator' =>
                        $siangKoordinator
                            ? [
                                'id_guru' =>
                                    $siangKoordinator->id_guru,

                                'nama' =>
                                    $siangKoordinator
                                        ->guru?->nama_guru ?? '-',
                            ]
                            : null,

                ],


                'waka' => [

                    'petugas' =>
                        $wakaPetugas,

                ],

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | SEMUA GURU
        |--------------------------------------------------------------------------
        |
        | Dipakai oleh form edit di drawer.
        |
        */

        $gurus = Guru::query()
            ->orderBy('nama_guru')
            ->get([
                'id_guru',
                'nama_guru',
            ]);


        /*
        |--------------------------------------------------------------------------
        | NAVIGASI BULAN
        |--------------------------------------------------------------------------
        */

        $prevMonth = $tanggalAwal
            ->copy()
            ->subMonth()
            ->format('Y-m');

        $nextMonth = $tanggalAwal
            ->copy()
            ->addMonth()
            ->format('Y-m');


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.jadwal-piket.index',
            compact(
                'jadwals',
                'byDate',
                'calendarData',
                'calendarDays',
                'tanggalAwal',
                'tanggalAkhir',
                'month',
                'prevMonth',
                'nextMonth',
                'gurus',
                'piketHours'
            )
        );
    }


    /**
     * =========================================================
     * CREATE
     * Form tambah jadwal.
     * =========================================================
     */
    public function create()
    {
        $gurus = User::with('guru')
            ->where('role', 'Guru')
            ->whereNotNull('id_guru')
            ->orderBy('username')
            ->get();

        $tanggal = request(
            'tanggal',
            now()->format('Y-m-d')
        );

        $piketHours = $this->piketHours();

        return view(
            'admin.jadwal-piket.create',
            compact(
                'gurus',
                'tanggal',
                'piketHours'
            )
        );
    }

    public function updateHours(Request $request)
    {
        $validated = $request->validate([
            'jam_mulai_pagi' => ['required', 'date_format:H:i'],
            'jam_selesai_pagi' => ['required', 'date_format:H:i', 'after:jam_mulai_pagi'],
            'jam_mulai_siang' => ['required', 'date_format:H:i'],
            'jam_selesai_siang' => ['required', 'date_format:H:i', 'after:jam_mulai_siang'],
        ]);

        DB::transaction(function () use ($validated) {
            $now = now();

            foreach ($validated as $key => $value) {
                DB::table('app_settings')->updateOrInsert(
                    ['key' => 'piket.' . $key],
                    ['value' => $value, 'updated_at' => $now, 'created_at' => $now]
                );
            }

            PiketJadwal::whereIn('jenis_tugas', [
                'Piket KBM Pagi',
                'Koordinator Piket KBM Pagi',
            ])->update([
                'jam_mulai' => $validated['jam_mulai_pagi'],
                'jam_selesai' => $validated['jam_selesai_pagi'],
            ]);

            PiketJadwal::whereIn('jenis_tugas', [
                'Piket KBM Siang',
                'Koordinator Piket KBM Siang',
            ])->update([
                'jam_mulai' => $validated['jam_mulai_siang'],
                'jam_selesai' => $validated['jam_selesai_siang'],
            ]);
        });

        return back()->with('success', 'Jam jadwal piket berhasil disimpan untuk setiap hari.');
    }


    /**
     * =========================================================
     * STORE
     * Simpan jadwal piket satu tanggal.
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'tanggal' => [
                'required',
                'date',
            ],

            'jam_mulai_pagi' => [
                'required',
                'date_format:H:i',
            ],

            'jam_selesai_pagi' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai_pagi',
            ],

            'jam_mulai_siang' => [
                'required',
                'date_format:H:i',
            ],

            'jam_selesai_siang' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai_siang',
            ],


            'pagi_petugas' => [
                'nullable',
                'array',
            ],

            'pagi_petugas.*' => [
                'integer',
                'exists:gurus,id_guru',
            ],

            'pagi_koordinator' => [
                'nullable',
                'integer',
                'exists:gurus,id_guru',
            ],

            'siang_petugas' => [
                'nullable',
                'array',
            ],

            'siang_petugas.*' => [
                'integer',
                'exists:gurus,id_guru',
            ],

            'siang_koordinator' => [
                'nullable',
                'integer',
                'exists:gurus,id_guru',
            ],

            'waka' => [
                'nullable',
                'integer',
                'exists:gurus,id_guru',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT / KOORDINATOR
        |--------------------------------------------------------------------------
        */

        $this->validateAssignment(
            $validated['pagi_petugas'] ?? [],
            $validated['pagi_koordinator'] ?? null,
            'Piket KBM Pagi'
        );

        $this->validateAssignment(
            $validated['siang_petugas'] ?? [],
            $validated['siang_koordinator'] ?? null,
            'Piket KBM Siang'
        );


        /*
        |--------------------------------------------------------------------------
        | CEK MINIMAL SATU JADWAL
        |--------------------------------------------------------------------------
        */

        $adaJadwal =
            !empty($validated['pagi_petugas']) ||
            !empty($validated['pagi_koordinator']) ||
            !empty($validated['siang_petugas']) ||
            !empty($validated['siang_koordinator']) ||
            !empty($validated['waka']);


        if (!$adaJadwal) {

            return back()
                ->withInput()
                ->withErrors([
                    'jadwal' =>
                        'Minimal isi satu petugas atau koordinator.'
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH TANGGAL SUDAH ADA
        |--------------------------------------------------------------------------
        */

        $sudahAda = PiketJadwal::whereDate(
            'tanggal',
            $validated['tanggal']
        )->exists();


        if ($sudahAda) {

            return back()
                ->withInput()
                ->withErrors([
                    'tanggal' =>
                        'Jadwal pada tanggal tersebut sudah ada. Silakan edit jadwal yang sudah tersedia.'
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated) {

            $tanggal =
                $validated['tanggal'];

            $createdBy =
                auth()->user()->id_user;

            $keterangan =
                $validated['keterangan'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | PAGI - PETUGAS
            |--------------------------------------------------------------------------
            */

            foreach (
                $validated['pagi_petugas'] ?? []
                as $idGuru
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $idGuru,

                    'tanggal' =>
                        $tanggal,

                    'shift' =>
                        'Pagi',

                    'jam_mulai' =>
                        $validated['jam_mulai_pagi'],

                    'jam_selesai' =>
                        $validated['jam_selesai_pagi'],

                    'jenis_tugas' =>
                        'Piket KBM Pagi',

                    'posisi' =>
                        'Petugas',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | PAGI - KOORDINATOR
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['pagi_koordinator']
                )
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $validated['pagi_koordinator'],

                    'tanggal' =>
                        $tanggal,

                    'shift' =>
                        'Pagi',

                    'jam_mulai' =>
                        $validated['jam_mulai_pagi'],

                    'jam_selesai' =>
                        $validated['jam_selesai_pagi'],

                    'jenis_tugas' =>
                        'Koordinator Piket KBM Pagi',

                    'posisi' =>
                        'Koordinator',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | SIANG - PETUGAS
            |--------------------------------------------------------------------------
            */

            foreach (
                $validated['siang_petugas'] ?? []
                as $idGuru
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $idGuru,

                    'tanggal' =>
                        $tanggal,

                    'shift' =>
                        'Siang',

                    'jam_mulai' =>
                        $validated['jam_mulai_siang'],

                    'jam_selesai' =>
                        $validated['jam_selesai_siang'],

                    'jenis_tugas' =>
                        'Piket KBM Siang',

                    'posisi' =>
                        'Petugas',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | SIANG - KOORDINATOR
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['siang_koordinator']
                )
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $validated['siang_koordinator'],

                    'tanggal' =>
                        $tanggal,

                    'shift' =>
                        'Siang',

                    'jam_mulai' =>
                        $validated['jam_mulai_siang'],

                    'jam_selesai' =>
                        $validated['jam_selesai_siang'],

                    'jenis_tugas' =>
                        'Koordinator Piket KBM Siang',

                    'posisi' =>
                        'Koordinator',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | WAKA
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['waka']
                )
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $validated['waka'],

                    'tanggal' =>
                        $tanggal,

                    'shift' =>
                        'Waka',

                    'jam_mulai' =>
                        null,

                    'jam_selesai' =>
                        null,

                    'jenis_tugas' =>
                        'Piket Waka',

                    'posisi' =>
                        'Petugas',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }

        });


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.jadwal-piket.index',
                [
                    'month' =>
                        Carbon::parse(
                            $validated['tanggal']
                        )->format('Y-m'),
                ]
            )
            ->with(
                'success',
                'Jadwal piket berhasil ditambahkan.'
            );
    }

    /**
     * =========================================================
     * EDIT
     * Mengambil data satu tanggal.
     *
     * Method ini tetap disediakan untuk route edit.
     * =========================================================
     */
    public function edit($tanggal)
    {
        $tanggal =
            Carbon::parse($tanggal);


        $jadwals =
            PiketJadwal::with('guru')
                ->whereDate(
                    'tanggal',
                    $tanggal
                )
                ->get();


        $gurus =
            Guru::orderBy('nama_guru')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | GROUP SHIFT
        |--------------------------------------------------------------------------
        */

        $pagi =
            $jadwals->where(
                'shift',
                'Pagi'
            );

        $siang =
            $jadwals->where(
                'shift',
                'Siang'
            );

        $waka =
            $jadwals->where(
                'shift',
                'Waka'
            );


        /*
        |--------------------------------------------------------------------------
        | PAGI
        |--------------------------------------------------------------------------
        */

        $pagiPetugas =
            $pagi
                ->where(
                    'jenis_tugas',
                    'Piket KBM Pagi'
                )
                ->pluck('id_guru')
                ->values()
                ->toArray();


        $pagiKoordinator =
            optional(
                $pagi
                    ->where(
                        'jenis_tugas',
                        'Koordinator Piket KBM Pagi'
                    )
                    ->first()
            )->id_guru;

        $pagiPertama = $pagi->first();
        $piketHours = $this->piketHours();
        $jamMulaiPagi = $pagiPertama?->jam_mulai ?? $piketHours['jam_mulai_pagi'];
        $jamSelesaiPagi = $pagiPertama?->jam_selesai ?? $piketHours['jam_selesai_pagi'];


        /*
        |--------------------------------------------------------------------------
        | SIANG
        |--------------------------------------------------------------------------
        */

        $siangPetugas =
            $siang
                ->where(
                    'jenis_tugas',
                    'Piket KBM Siang'
                )
                ->pluck('id_guru')
                ->values()
                ->toArray();


        $siangKoordinator =
            optional(
                $siang
                    ->where(
                        'jenis_tugas',
                        'Koordinator Piket KBM Siang'
                    )
                    ->first()
            )->id_guru;

        $siangPertama = $siang->first();
        $jamMulaiSiang = $siangPertama?->jam_mulai ?? $piketHours['jam_mulai_siang'];
        $jamSelesaiSiang = $siangPertama?->jam_selesai ?? $piketHours['jam_selesai_siang'];


        /*
        |--------------------------------------------------------------------------
        | WAKA
        |--------------------------------------------------------------------------
        */

        $wakaGuru =
            optional(
                $waka
                    ->where(
                        'jenis_tugas',
                        'Piket Waka'
                    )
                    ->first()
            )->id_guru;


        /*
        |--------------------------------------------------------------------------
        | KETERANGAN
        |--------------------------------------------------------------------------
        */

        $keterangan =
            $jadwals->first()?->keterangan;


        return view(
            'admin.jadwal-piket.edit',
            compact(
                'tanggal',
                'gurus',
                'pagiPetugas',
                'pagiKoordinator',
                'jamMulaiPagi',
                'jamSelesaiPagi',
                'siangPetugas',
                'siangKoordinator',
                'jamMulaiSiang',
                'jamSelesaiSiang',
                'wakaGuru',
                'keterangan'
            )
        );
    }


    /**
     * =========================================================
     * UPDATE
     * Update seluruh jadwal satu tanggal.
     * =========================================================
     */
    public function update(
        Request $request,
        $tanggal
    ) {

        $validated = $request->validate([

            'tanggal' => [
                'required',
                'date',
            ],

            'jam_mulai_pagi' => [
                'required',
                'date_format:H:i',
            ],

            'jam_selesai_pagi' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai_pagi',
            ],

            'jam_mulai_siang' => [
                'required',
                'date_format:H:i',
            ],

            'jam_selesai_siang' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai_siang',
            ],

            'pagi_petugas' => [
                'nullable',
                'array',
            ],

            'pagi_petugas.*' => [
                'integer',
                'exists:gurus,id_guru',
            ],

            'pagi_koordinator' => [
                'nullable',
                'integer',
                'exists:gurus,id_guru',
            ],

            'siang_petugas' => [
                'nullable',
                'array',
            ],

            'siang_petugas.*' => [
                'integer',
                'exists:gurus,id_guru',
            ],

            'siang_koordinator' => [
                'nullable',
                'integer',
                'exists:gurus,id_guru',
            ],

            'waka' => [
                'nullable',
                'integer',
                'exists:gurus,id_guru',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PENUGASAN
        |--------------------------------------------------------------------------
        */

        $this->validateAssignment(
            $validated['pagi_petugas'] ?? [],
            $validated['pagi_koordinator'] ?? null,
            'Piket KBM Pagi'
        );


        $this->validateAssignment(
            $validated['siang_petugas'] ?? [],
            $validated['siang_koordinator'] ?? null,
            'Piket KBM Siang'
        );


        /*
        |--------------------------------------------------------------------------
        | MINIMAL SATU JADWAL
        |--------------------------------------------------------------------------
        */

        $adaJadwal =
            !empty($validated['pagi_petugas']) ||
            !empty($validated['pagi_koordinator']) ||
            !empty($validated['siang_petugas']) ||
            !empty($validated['siang_koordinator']) ||
            !empty($validated['waka']);


        if (!$adaJadwal) {

            throw ValidationException::withMessages([
                'jadwal' =>
                    'Minimal isi satu petugas atau koordinator.'
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE DALAM TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $tanggal
        ) {

            /*
            |--------------------------------------------------------------------------
            | HAPUS DATA LAMA
            |--------------------------------------------------------------------------
            */

            PiketJadwal::whereDate(
                'tanggal',
                $tanggal
            )->delete();


            $tanggalBaru =
                $validated['tanggal'];

            $createdBy =
                auth()->user()->id_user;

            $keterangan =
                $validated['keterangan'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | PAGI - PETUGAS
            |--------------------------------------------------------------------------
            */

            foreach (
                $validated['pagi_petugas'] ?? []
                as $idGuru
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $idGuru,

                    'tanggal' =>
                        $tanggalBaru,

                    'shift' =>
                        'Pagi',

                    'jam_mulai' =>
                        $validated['jam_mulai_pagi'],

                    'jam_selesai' =>
                        $validated['jam_selesai_pagi'],

                    'jenis_tugas' =>
                        'Piket KBM Pagi',

                    'posisi' =>
                        'Petugas',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | PAGI - KOORDINATOR
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['pagi_koordinator']
                )
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $validated['pagi_koordinator'],

                    'tanggal' =>
                        $tanggalBaru,

                    'shift' =>
                        'Pagi',

                    'jam_mulai' =>
                        $validated['jam_mulai_pagi'],

                    'jam_selesai' =>
                        $validated['jam_selesai_pagi'],

                    'jenis_tugas' =>
                        'Koordinator Piket KBM Pagi',

                    'posisi' =>
                        'Koordinator',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | SIANG - PETUGAS
            |--------------------------------------------------------------------------
            */

            foreach (
                $validated['siang_petugas'] ?? []
                as $idGuru
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $idGuru,

                    'tanggal' =>
                        $tanggalBaru,

                    'shift' =>
                        'Siang',

                    'jam_mulai' =>
                        $validated['jam_mulai_siang'],

                    'jam_selesai' =>
                        $validated['jam_selesai_siang'],

                    'jenis_tugas' =>
                        'Piket KBM Siang',

                    'posisi' =>
                        'Petugas',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | SIANG - KOORDINATOR
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['siang_koordinator']
                )
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $validated['siang_koordinator'],

                    'tanggal' =>
                        $tanggalBaru,

                    'shift' =>
                        'Siang',

                    'jam_mulai' =>
                        $validated['jam_mulai_siang'],

                    'jam_selesai' =>
                        $validated['jam_selesai_siang'],

                    'jenis_tugas' =>
                        'Koordinator Piket KBM Siang',

                    'posisi' =>
                        'Koordinator',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | WAKA
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['waka']
                )
            ) {

                PiketJadwal::create([

                    'id_guru' =>
                        $validated['waka'],

                    'tanggal' =>
                        $tanggalBaru,

                    'shift' =>
                        'Waka',

                    'jam_mulai' =>
                        null,

                    'jam_selesai' =>
                        null,

                    'jenis_tugas' =>
                        'Piket Waka',

                    'posisi' =>
                        'Petugas',

                    'keterangan' =>
                        $keterangan,

                    'created_by' =>
                        $createdBy,

                ]);

            }

        });


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.jadwal-piket.index',
                [
                    'month' =>
                        Carbon::parse(
                            $validated['tanggal']
                        )->format('Y-m'),
                ]
            )
            ->with(
                'success',
                'Jadwal piket berhasil diperbarui.'
            );
    }


    /**
     * =========================================================
     * DESTROY
     * Hapus seluruh jadwal pada satu tanggal.
     * =========================================================
     */
    public function destroy($tanggal)
    {
        PiketJadwal::whereDate(
            'tanggal',
            $tanggal
        )->delete();


        return redirect()
            ->route(
                'admin.jadwal-piket.index'
            )
            ->with(
                'success',
                'Jadwal piket berhasil dihapus.'
            );
    }


    /**
     * =========================================================
     * VALIDASI ASSIGNMENT
     * =========================================================
     *
     * Cek:
     * 1. Petugas tidak boleh duplikat.
     * 2. Koordinator tidak boleh menjadi petugas di shift yang sama.
     *
     */
    private function piketHours(): array
    {
        $defaults = [
            'jam_mulai_pagi' => '07:00',
            'jam_selesai_pagi' => '11:00',
            'jam_mulai_siang' => '11:00',
            'jam_selesai_siang' => '15:00',
        ];

        foreach (array_keys($defaults) as $key) {
            $defaults[$key] = DB::table('app_settings')
                ->where('key', 'piket.' . $key)
                ->value('value') ?? $defaults[$key];
        }

        return $defaults;
    }

    private function validateAssignment(
        array $petugas,
        $koordinator,
        string $label
    ): void {

        /*
        |--------------------------------------------------------------------------
        | HILANGKAN DUPLIKAT ID GURU
        |--------------------------------------------------------------------------
        */

        $petugas = array_map(
            'intval',
            $petugas
        );


        if (
            count($petugas) !==
            count(array_unique($petugas))
        ) {

            throw ValidationException::withMessages([

                'jadwal' =>
                    "{$label}: guru yang sama tidak boleh dimasukkan dua kali sebagai petugas.",

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | CEK KOORDINATOR
        |--------------------------------------------------------------------------
        */

        if (
            $koordinator &&
            in_array(
                (int) $koordinator,
                $petugas,
                true
            )
        ) {

            throw ValidationException::withMessages([

                'jadwal' =>
                    "{$label}: koordinator tidak boleh menjadi petugas pada shift yang sama.",

            ]);

        }

    }
}
