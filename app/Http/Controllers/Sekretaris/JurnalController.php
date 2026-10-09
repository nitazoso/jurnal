<?php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();
        $user = auth()->user();
        $kelasId = $user?->id_kelas;

        $jurnalQuery = Jurnal::query()
            ->where('id_kelas', $kelasId)
            ->whereHas('guru')
            ->whereHas('kelas')
            ->whereHas('jadwal', function ($query) {
                $query->whereColumn('jadwals.id_kelas', 'jurnals.id_kelas')
                    ->whereColumn('jadwals.id_guru', 'jurnals.id_guru');
            });

        $jurnalsMenunggu = (clone $jurnalQuery)->where('status_validasi_guru', 'Menunggu')->count();
        $jurnalsTervalidasiHariIni = (clone $jurnalQuery)->whereDate('tanggal', $today)->where('status_validasi_guru', 'Disetujui')->count();
        $jurnalTerbaru = (clone $jurnalQuery)->with(['guru', 'kelas', 'jadwal.mapel', 'jamMulai', 'jamSelesai'])
            ->where('status_validasi_guru', 'Menunggu')->latest()->take(5)->get();

        return view('sekretaris.dashboard', compact('jurnalsMenunggu', 'jurnalsTervalidasiHariIni', 'jurnalTerbaru'));
    }

    public function history()
    {
        $user = auth()->user();

        $jurnals = Jurnal::withTrashed()
            ->with(['guru', 'kelas', 'jadwal.mapel', 'jamMulai', 'jamSelesai', 'validator'])
            ->where('id_kelas', $user->id_kelas)
            ->where('status_validasi_guru', 'Disetujui')
            ->whereNotNull('validated_by')
            ->orderByDesc('tanggal')
            ->orderByDesc('validated_at')
            ->get();

        $riwayatPerHari = $jurnals->groupBy(fn (Jurnal $jurnal) => $jurnal->tanggal->toDateString());

        return view('sekretaris.riwayat-jurnal', compact('riwayatPerHari'));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $tanggalHariIni = now('Asia/Jakarta')->toDateString();
        $tanggalDipilih = $request->filled('tanggal') ? $request->input('tanggal') : $tanggalHariIni;

        $query = Jurnal::with(['guru', 'kelas', 'jadwal.mapel', 'jamMulai', 'jamSelesai', 'user'])
            ->whereHas('jadwal')
            ->whereDate('tanggal', $tanggalDipilih)
            ->latest('tanggal');

        if ($user?->id_kelas) {
            $query->where('id_kelas', $user->id_kelas);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(fn ($q) => $q->whereHas('guru', fn ($guru) => $guru->where('nama_guru', 'like', "%{$search}%"))
                ->orWhereHas('kelas', fn ($kelas) => $kelas->where('nama_kelas', 'like', "%{$search}%")));
        }
        if ($request->filled('kelas_id')) $query->where('id_kelas', $request->integer('kelas_id'));
        if ($request->filled('status')) $query->where('status_validasi_guru', $request->input('status'));

        $jurnals = $query->paginate(15)->withQueryString();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        return view('sekretaris.validasi-jurnal', compact('jurnals', 'kelases', 'tanggalDipilih'));
    }

    public function show(Jurnal $jurnal)
    {
        if (! $jurnal->jadwal) {
            abort(404);
        }

        $jurnal->load([
            'guru',
            'kelas',
            'jadwal.mapel',
            'jamMulai',
            'jamSelesai',
            'user',
            'validator',
        ]);

        return view('sekretaris.jurnal-show', compact('jurnal'));
    }

    public function validateJurnal(Request $request, Jurnal $jurnal)
    {
        $validated = $request->validate([
            'status_kehadiran_validasi' => 'required|in:Hadir,Tidak Hadir',
        ]);

        if (blank($jurnal->materi) || blank($jurnal->status_guru)) {
            return back()->withErrors([
                'status_validasi_guru' => 'Jurnal belum diisi dengan lengkap oleh guru. Lihat isi jurnal terlebih dahulu sebelum validasi.',
            ]);
        }

        $jurnal->update([
            'status_validasi_guru' => 'Disetujui',
            'status_kehadiran_validasi' => $validated['status_kehadiran_validasi'],
            'validated_by' => $request->user()->id_user,
            'validated_at' => now(),
            'catatan_revisi' => null,
        ]);

        return back()->with('success', 'Kehadiran guru berhasil divalidasi.');
    }

}
