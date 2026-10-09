<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\AcademicPeriod;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ZipArchive;

class JurnalController extends Controller
{
    public function harian(Request $request)
    {
        $tanggal = $this->validateTanggal($request);
        $hariDipilih = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd');
        $periodeAktif = AcademicPeriod::current() ?? Jadwal::latestAcademicPeriod();

        $jumlahPerKelas = Jurnal::query()
            ->whereDate('tanggal', $tanggal)
            ->selectRaw(
                'id_kelas,
                COUNT(*) as total,
                SUM(CASE WHEN status_validasi_guru = ? THEN 1 ELSE 0 END) as layak_approve,
                SUM(CASE WHEN piket_approved_at IS NOT NULL THEN 1 ELSE 0 END) as sudah_diapprove',
                ['Disetujui']
            )
            ->groupBy('id_kelas')
            ->get()
            ->keyBy('id_kelas');

        $jadwalsHariIni = Jadwal::query()
            ->with(['mapel', 'jamMulai', 'jamSelesai', 'guru'])
            ->where('hari', $hariDipilih)
            ->when(
                $periodeAktif?->semester,
                fn ($query, $semester) => $query->where('semester', $semester)
            )
            ->when(
                $periodeAktif?->tahun_ajaran,
                fn ($query, $tahunAjaran) => $query->where('tahun_ajaran', $tahunAjaran)
            )
            ->orderBy('id_kelas')
            ->orderBy('id_jam_mulai')
            ->get();

        $jurnalPerJadwal = Jurnal::query()
            ->with(['guru', 'jadwal.mapel', 'jamMulai', 'jamSelesai'])
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->groupBy('id_jadwal');

        $jadwalPerKelas = $jadwalsHariIni->groupBy('id_kelas');

        $kelases = Kelas::query()
            ->orderBy('nama_kelas')
            ->get()
            ->map(function ($kelas) use (
                $jumlahPerKelas,
                $jadwalPerKelas,
                $jurnalPerJadwal
            ) {
                $ringkasan = $jumlahPerKelas->get($kelas->id_kelas);
                $jadwals = $jadwalPerKelas->get($kelas->id_kelas, collect());

                $kelas->jumlah_jurnal_harian = (int) ($ringkasan->total ?? 0);
                $kelas->jumlah_jurnal_wajib_harian = $jadwals->count();
                $kelas->jumlah_layak_approve_harian = (int) ($ringkasan->layak_approve ?? 0);
                $kelas->jumlah_sudah_diapprove_harian = (int) ($ringkasan->sudah_diapprove ?? 0);

                $kelas->jadwal_harian = $jadwals->map(function ($jadwal) use ($jurnalPerJadwal) {
                    $jurnals = $jurnalPerJadwal->get($jadwal->id_jadwal, collect());

                    $jadwal->jurnal_harian = $jurnals->first();
                    $jadwal->status_jurnal_harian = $jurnals->isEmpty()
                        ? 'Belum Ada Jurnal'
                        : $jurnals->first()->status_validasi_guru;

                    $jadwal->piket_sudah_approve = $jurnals->contains(
                        fn ($jurnal) => $jurnal->piket_approved_at !== null
                    );

                    return $jadwal;
                });

                return $kelas;
            });

        return view('piket.jurnal-harian.index', compact('tanggal', 'kelases'));
    }

    public function harianKelas(Request $request, Kelas $kelas)
    {
        $tanggal = $this->validateTanggal($request);
        $hariDipilih = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd');
        $periodeAktif = AcademicPeriod::current() ?? Jadwal::latestAcademicPeriod();

        $jurnalHariIni = Jurnal::query()
            ->where('id_kelas', $kelas->id_kelas)
            ->whereDate('tanggal', $tanggal);

        $jumlahJurnalTerisi = (clone $jurnalHariIni)->count();

        $jadwalsHariIni = Jadwal::query()
            ->with(['mapel', 'guru', 'jamMulai', 'jamSelesai'])
            ->where('id_kelas', $kelas->id_kelas)
            ->where('hari', $hariDipilih)
            ->when(
                $periodeAktif?->semester,
                fn ($query, $semester) => $query->where('semester', $semester)
            )
            ->when(
                $periodeAktif?->tahun_ajaran,
                fn ($query, $tahunAjaran) => $query->where('tahun_ajaran', $tahunAjaran)
            )
            ->orderBy('id_jam_mulai')
            ->get();

        $jumlahJurnalWajib = $jadwalsHariIni->count();

        $jurnalByJadwal = (clone $jurnalHariIni)
            ->with(['guru', 'jadwal.mapel', 'jamMulai', 'jamSelesai', 'detailAbsensis', 'piketApprover'])
            ->get()
            ->groupBy('id_jadwal');

        $jadwalHarian = $jadwalsHariIni->map(function ($jadwal) use ($jurnalByJadwal) {
            $jurnals = $jurnalByJadwal->get($jadwal->id_jadwal, collect());
            $jadwal->jurnal_harian = $jurnals->first();
            $jadwal->status_jurnal_harian = $jurnals->isEmpty()
                ? 'Belum Ada Jurnal'
                : $jurnals->first()->status_validasi_guru;

            return $jadwal;
        });

        $adaMenungguVerifikasi = (clone $jurnalHariIni)
            ->where('status_validasi_guru', 'Menunggu')
            ->exists();

        $jurnals = (clone $jurnalHariIni)
            ->with([
                'guru',
                'jadwal.mapel',
                'jamMulai',
                'jamSelesai',
                'detailAbsensis',
                'piketApprover',
            ])
            ->whereIn('status_validasi_guru', [
                'Menunggu',
                'Disetujui',
                'Ditolak',
                'Perlu Diperbaiki',
            ])
            ->get()
            ->sortBy(fn ($jurnal) => $jurnal->jamMulai?->jam_mulai ?? '99:99:99')
            ->values();

        $waktuSekarang = now('Asia/Jakarta');
        $jadwalTerakhir = $jadwalsHariIni->sortByDesc(
            fn ($jadwal) => $jadwal->jamSelesai?->jam_selesai ?? '00:00:00'
        )->first();

        $jadwalTerakhirSelesai = $jadwalTerakhir
            && $jadwalTerakhir->jamSelesai?->jam_selesai
            && $waktuSekarang->format('H:i:s') >= $jadwalTerakhir->jamSelesai->jam_selesai;

        return view('piket.jurnal-harian.show', compact(
            'kelas',
            'tanggal',
            'jurnals',
            'jadwalHarian',
            'adaMenungguVerifikasi',
            'jumlahJurnalTerisi',
            'jumlahJurnalWajib',
            'jadwalTerakhirSelesai'
        ));
    }

    public function approveHarian(Request $request, Kelas $kelas)
    {
        $user = $request->user();

        abort_unless(
            $user?->role === 'Staff Piket'
                || ($user?->role === 'Guru' && $user->hasPiketToday()),
            403
        );

        $validated = $request->validate([
            'tanggal' => ['required', 'date_format:Y-m-d'],
        ]);

        $waktuApprove = now('Asia/Jakarta');

        $jumlahDiapprove = Jurnal::query()
            ->where('id_kelas', $kelas->id_kelas)
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('status_validasi_guru', 'Disetujui')
            ->whereNull('piket_approved_at')
            ->update([
                'piket_approved_by' => $user->id_user,
                'piket_approved_at' => $waktuApprove,
                'updated_at' => $waktuApprove,
            ]);

        if ($jumlahDiapprove === 0) {
            return back()->with('info', 'Tidak ada jurnal baru yang perlu di-approve.');
        }

        return back()->with('success', $jumlahDiapprove . ' jurnal berhasil di-approve.');
    }

    public function approveHarianMassal(Request $request)
    {
        $user = $request->user();

        abort_unless(
            $user?->role === 'Staff Piket'
                || ($user?->role === 'Guru' && $user->hasPiketToday()),
            403
        );

        $validated = $request->validate([
            'tanggal' => ['required', 'date_format:Y-m-d'],
            'kelas_ids' => ['required', 'array', 'min:1'],
            'kelas_ids.*' => ['required', 'integer', 'exists:kelases,id_kelas'],
        ]);

        $tanggal = $validated['tanggal'];
        $kelasIds = collect($validated['kelas_ids'])->unique()->values();

        $hariDipilih = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd');
        $periodeAktif = AcademicPeriod::current() ?? Jadwal::latestAcademicPeriod();

        $jadwalPerKelas = Jadwal::query()
            ->whereIn('id_kelas', $kelasIds)
            ->where('hari', $hariDipilih)
            ->when(
                $periodeAktif?->semester,
                fn ($query, $semester) => $query->where('semester', $semester)
            )
            ->when(
                $periodeAktif?->tahun_ajaran,
                fn ($query, $tahunAjaran) => $query->where('tahun_ajaran', $tahunAjaran)
            )
            ->get()
            ->groupBy('id_kelas');

        $waktuApprove = now('Asia/Jakarta');
        $jumlahDiapprove = 0;
        $kelasBelumLengkap = [];

        DB::transaction(function () use (
            $kelasIds,
            $tanggal,
            $jadwalPerKelas,
            $waktuApprove,
            $user,
            &$jumlahDiapprove,
            &$kelasBelumLengkap
        ) {
            foreach ($kelasIds as $kelasId) {
                $jadwals = $jadwalPerKelas->get($kelasId, collect());

                $jurnals = Jurnal::query()
                    ->where('id_kelas', $kelasId)
                    ->whereDate('tanggal', $tanggal)
                    ->get()
                    ->keyBy('id_jadwal');

                $semuaJadwalTerisi = $jadwals->every(
                    fn ($jadwal) => $jurnals->has($jadwal->id_jadwal)
                );

                $semuaSekretarisSetuju = $jadwals->every(function ($jadwal) use ($jurnals) {
                    $jurnal = $jurnals->get($jadwal->id_jadwal);

                    return $jurnal
                        && $jurnal->status_validasi_guru === 'Disetujui';
                });

                $jadwalTerakhir = $jadwals->sortByDesc('id_jam_selesai')->first();
                $jamSelesaiTerakhir = $jadwalTerakhir?->jam_selesai;

                $bolehLewatBatasWaktu = false;

                if ($jamSelesaiTerakhir) {
                    $bolehLewatBatasWaktu = now('Asia/Jakarta')->format('H:i:s') >= $jamSelesaiTerakhir;
                }

                if (! $bolehLewatBatasWaktu && (! $semuaJadwalTerisi || ! $semuaSekretarisSetuju)) {
                    $kelasBelumLengkap[] = $kelasId;
                    continue;
                }

                $query = Jurnal::query()
                    ->where('id_kelas', $kelasId)
                    ->whereDate('tanggal', $tanggal)
                    ->whereNull('piket_approved_at');

                if (! $bolehLewatBatasWaktu) {
                    $query->where('status_validasi_guru', 'Disetujui');
                }

                $jumlahDiapprove += $query->update([
                    'piket_approved_by' => $user->id_user,
                    'piket_approved_at' => $waktuApprove,
                    'updated_at' => $waktuApprove,
                ]);
            }
        });

        if ($jumlahDiapprove === 0 && count($kelasBelumLengkap) > 0) {
            return back()->with(
                'info',
                'Belum bisa approve: pastikan semua jurnal sesuai jadwal sudah diisi dan divalidasi sekretaris, atau tunggu sampai jam pelajaran terakhir selesai.'
            );
        }

        $pesan = $jumlahDiapprove . ' jurnal berhasil di-approve.';
        if (count($kelasBelumLengkap) > 0) {
            $pesan .= ' ' . count($kelasBelumLengkap) . ' kelas belum memenuhi syarat.';
        }

        return back()->with('success', $pesan);
    }

    public function docxPreview(Request $request)
    {
        return view('piket.jurnal.docx-preview', $this->docxReportData($request));
    }

    public function docxDownload(Request $request)
    {
        $data = $this->docxReportData($request);

        if ($data['jurnals']->isEmpty()) {
            return view('piket.jurnal.docx-preview', $data);
        }

        $parts = app(\App\Services\PiketJurnalDocx::class)->make($data);
        $path = tempnam(sys_get_temp_dir(), 'jurnify-');
        $zip = new ZipArchive();

        if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
            @unlink($path);

            return back()->with('error', 'Dokumen rekap tidak dapat dibuat.');
        }

        foreach ($parts as $name => $contents) {
            $zip->addFromString($name, $contents);
        }

        $zip->close();

        $filename = 'rekap-' . Str::slug($data['judul']) . '-'
            . $data['mulai']->format('Ymd') . '-'
            . $data['sampai']->format('Ymd') . '.docx';

        return response()
            ->download($path, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])
            ->deleteFileAfterSend(true);
    }

    private function docxReportData(Request $request): array
    {
        $validated = $request->validate([
            'jenis' => 'required|in:kelas,guru',
            'objek' => 'required|integer',
            'mulai' => 'required|date',
            'sampai' => 'required|date|after_or_equal:mulai',
        ]);

        $objek = $validated['jenis'] === 'kelas'
            ? Kelas::where('id_kelas', $validated['objek'])->firstOrFail()
            : Guru::where('id_guru', $validated['objek'])->firstOrFail();

        $query = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
            'detailAbsensis.siswa',
        ])
            ->whereIn('status_validasi_guru', [
                'Menunggu',
                'Disetujui',
                'Perlu Diperbaiki',
                'Ditolak',
            ])
            ->whereBetween('tanggal', [
                $validated['mulai'],
                $validated['sampai'],
            ]);

        $query->where(
            $validated['jenis'] === 'kelas' ? 'id_kelas' : 'id_guru',
            $validated['objek']
        );

        $jurnals = $query
            ->orderBy('tanggal')
            ->orderBy('id_jam_mulai')
            ->get();

        $totalHadir = $jurnals->sum(
            fn ($jurnal) => $jurnal->detailAbsensis
                ->filter(fn ($item) => mb_strtolower((string) $item->status) === 'hadir')
                ->count()
        );

        $totalTidakHadir = $jurnals->sum(
            fn ($jurnal) => $jurnal->detailAbsensis
                ->filter(fn ($item) => mb_strtolower((string) $item->status) !== 'hadir')
                ->count()
        );

        return [
            'jenis' => $validated['jenis'],
            'objekId' => $validated['objek'],
            'objek' => $objek,
            'judul' => $validated['jenis'] === 'kelas'
                ? 'Kelas ' . $objek->nama_kelas
                : $objek->nama_guru,
            'mulai' => Carbon::parse($validated['mulai']),
            'sampai' => Carbon::parse($validated['sampai']),
            'jurnals' => $jurnals,
            'totalHadir' => $totalHadir,
            'totalTidakHadir' => $totalTidakHadir,
        ];
    }

    public function create(Request $request)
    {
        $hariIni = now('Asia/Jakarta')->locale('id')->isoFormat('dddd');

        if (Cache::get('jadwal_all_disabled', false)) {
            return view('piket.jurnal.create', [
                'kelases' => collect(),
                'selectedKelas' => null,
                'jadwals' => collect(),
                'allDisabled' => true,
                'hariIni' => $hariIni,
            ]);
        }

        $user = $request->user();

        abort_unless(
            $user?->role === 'Staff Piket'
                || ($user?->role === 'Guru' && $user->hasPiketToday()),
            403
        );

        $validated = $request->validate([
            'id_kelas' => ['nullable', 'integer', 'exists:kelases,id_kelas'],
        ]);

        $kelases = Kelas::orderBy('nama_kelas')->get();

        $selectedKelas = isset($validated['id_kelas'])
            ? $kelases->firstWhere('id_kelas', $validated['id_kelas'])
            : null;

        $jadwals = $selectedKelas
            ? Jadwal::with(['guru', 'kelas', 'mapel', 'jamMulai', 'jamSelesai'])
                ->withCount([
                    'jurnals as jurnal_hari_ini_count' => fn ($query) => $query->whereDate('tanggal', today()),
                ])
                ->where('id_kelas', $selectedKelas->id_kelas)
                ->orderByRaw('CASE WHEN hari = ? THEN 0 ELSE 1 END', [$hariIni])
                ->orderByRaw(
                    "CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 ELSE 6 END"
                )
                ->orderBy('id_jam_mulai')
                ->get()
            : collect();

        return view(
            'piket.jurnal.create',
            compact('kelases', 'selectedKelas', 'jadwals', 'hariIni') + ['allDisabled' => false]
        );
    }

    public function show(Jurnal $jurnal)
    {
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

        return view('piket.jurnal.show', compact('jurnal'));
    }

    public function rekap(Request $request)
    {
        if (! $request->filled('view')) {
            $request->merge(['view' => 'semua']);
        }

        return $this->index($request);
    }

    public function index(Request $request)
    {
        $view = $request->get('view', 'kelas');

        $statusJurnal = [
            'Menunggu',
            'Disetujui',
            'Perlu Diperbaiki',
            'Ditolak',
        ];

        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        $gurus = Guru::orderBy('nama_guru', 'asc')->get();

        $jurnalQuery = Jurnal::with([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
        ])->whereIn('status_validasi_guru', $statusJurnal);

        if ($view === 'kelas' && $request->filled('id_kelas')) {
            $jurnalQuery->where('id_kelas', $request->integer('id_kelas'));
        } elseif ($view === 'guru' && $request->filled('id_guru')) {
            $jurnalQuery->where('id_guru', $request->integer('id_guru'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();

            $jurnalQuery->where(function ($query) use ($search) {
                $query->where('materi', 'like', "%{$search}%")
                    ->orWhereHas('guru', fn ($query) => $query->where('nama_guru', 'like', "%{$search}%"))
                    ->orWhereHas('kelas', fn ($query) => $query->where('nama_kelas', 'like', "%{$search}%"));
            });
        }

        $periode = (string) $request->input('periode', '');

        if ($periode === 'all') {
            $request->merge(['bulan' => null, 'tahun' => null]);
        } elseif (preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', $periode, $matches)) {
            $request->merge([
                'tahun' => (int) $matches[1],
                'bulan' => (int) $matches[2],
            ]);
        }

        if ($request->filled('bulan')) {
            $jurnalQuery->whereMonth('tanggal', $request->integer('bulan'));
        }

        if ($request->filled('tahun')) {
            $jurnalQuery->whereYear('tanggal', $request->integer('tahun'));
        }

        if ($request->filled('tanggal')) {
            $jurnalQuery->whereDate('tanggal', $request->input('tanggal'));
        }

        $hasSelection = $view === 'semua'
            || ($view === 'kelas' && $request->filled('id_kelas'))
            || ($view === 'guru' && $request->filled('id_guru'));

        $jurnals = $hasSelection
            ? $jurnalQuery->orderByDesc('tanggal')->get()
            : collect();

        $selectedKelas = ($view === 'kelas' && $request->filled('id_kelas'))
            ? $kelases->firstWhere('id_kelas', $request->integer('id_kelas'))
            : null;

        $selectedGuru = ($view === 'guru' && $request->filled('id_guru'))
            ? $gurus->firstWhere('id_guru', $request->integer('id_guru'))
            : null;

        $jumlahJurnalPerKelas = Jurnal::whereIn('status_validasi_guru', $statusJurnal)
            ->selectRaw('id_kelas, COUNT(*) as total')
            ->groupBy('id_kelas')
            ->pluck('total', 'id_kelas');

        $totalJurnal = Jurnal::whereIn('status_validasi_guru', $statusJurnal)->count();
        $totalKelas = Kelas::count();

        $jurnalHariIni = Jurnal::whereIn('status_validasi_guru', $statusJurnal)
            ->whereDate('tanggal', today())
            ->count();

        $tahunList = Jurnal::whereIn('status_validasi_guru', $statusJurnal)
            ->pluck('tanggal')
            ->map(fn ($tanggal) => (int) date('Y', strtotime($tanggal)))
            ->unique()
            ->sortDesc()
            ->values();

        return view('piket.jurnal.index', compact(
            'kelases',
            'gurus',
            'jurnals',
            'jumlahJurnalPerKelas',
            'totalJurnal',
            'totalKelas',
            'jurnalHariIni',
            'tahunList',
            'selectedKelas',
            'selectedGuru'
        ));
    }

    private function validateTanggal(Request $request): string
    {
        $tanggal = $request->input(
            'tanggal',
            now('Asia/Jakarta')->toDateString()
        );

        return validator(['tanggal' => $tanggal], [
            'tanggal' => ['required', 'date_format:Y-m-d'],
        ])->validate()['tanggal'];
    }
}