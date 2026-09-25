<?php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();
        $jurnalsMenunggu = Jurnal::where('status_validasi_guru', 'Menunggu')->count();
        $jurnalsTervalidasiHariIni = Jurnal::whereDate('tanggal', $today)->where('status_validasi_guru', 'Disetujui')->count();
        $jurnalTerbaru = Jurnal::with(['guru', 'kelas', 'jamMulai', 'jamSelesai'])
            ->where('status_validasi_guru', 'Menunggu')->latest()->take(5)->get();

        return view('sekretaris.dashboard', compact('jurnalsMenunggu', 'jurnalsTervalidasiHariIni', 'jurnalTerbaru'));
    }

    public function index(Request $request)
    {
        $query = Jurnal::with(['guru', 'kelas', 'jadwal.mapel', 'jamMulai', 'jamSelesai'])
            ->whereHas('jadwal')
            ->latest('tanggal');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(fn ($q) => $q->whereHas('guru', fn ($guru) => $guru->where('nama_guru', 'like', "%{$search}%"))
                ->orWhereHas('kelas', fn ($kelas) => $kelas->where('nama_kelas', 'like', "%{$search}%")));
        }
        if ($request->filled('kelas_id')) $query->where('id_kelas', $request->integer('kelas_id'));
        if ($request->filled('tanggal')) $query->whereDate('tanggal', $request->input('tanggal'));
        if ($request->filled('status')) $query->where('status_validasi_guru', $request->input('status'));

        $jurnals = $query->paginate(15)->withQueryString();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        return view('sekretaris.validasi-jurnal', compact('jurnals', 'kelases'));
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
        ]);

        return view('sekretaris.jurnal-show', compact('jurnal'));
    }

    public function validateJurnal(Request $request, Jurnal $jurnal)
    {
        $validated = $request->validate([
            'status_validasi_guru' => 'required|in:Disetujui,Ditolak,Perlu Diperbaiki',
            'catatan_revisi' => 'nullable|string|max:1000',
        ]);

        if (blank($jurnal->materi) || blank($jurnal->status_guru)) {
            return back()->withErrors([
                'status_validasi_guru' => 'Jurnal belum diisi dengan lengkap oleh guru. Lihat isi jurnal terlebih dahulu sebelum validasi.',
            ]);
        }

        if ($validated['status_validasi_guru'] !== 'Disetujui' && blank($validated['catatan_revisi'] ?? null)) {
            return back()->withErrors(['catatan_revisi' => 'Catatan revisi wajib diisi saat jurnal tidak disetujui.']);
        }

        $jurnal->update([
            'status_validasi_guru' => $validated['status_validasi_guru'],
            'catatan_revisi' => $validated['status_validasi_guru'] === 'Disetujui' ? null : $validated['catatan_revisi'],
        ]);

        return back()->with('success', 'Status jurnal berhasil diperbarui. Jurnal yang disetujui langsung tersedia untuk staf piket.');
    }

    public function create()
    {
        $jadwals = Jadwal::with(['guru', 'kelas', 'mapel', 'jamMulai', 'jamSelesai'])->orderBy('hari')->orderBy('id_jam_mulai')->get();
        return view('sekretaris.isi-jurnal', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwals,id_jadwal', 'tanggal' => 'required|date', 'materi' => 'required|string|max:255',
            'status_guru' => 'required|in:Izin,Sakit', 'ada_tugas' => 'required|in:Ya,Tidak',
            'deskripsi_tugas' => 'nullable|required_if:ada_tugas,Ya|string', 'jml_hadir' => 'required|integer|min:0',
            'jml_tidak_hadir' => 'required|integer|min:0', 'catatan_umum' => 'required|string|max:255',
        ]);
        $jadwal = Jadwal::findOrFail($validated['id_jadwal']);
        Jurnal::create([
            'id_jadwal' => $jadwal->id_jadwal, 'id_kelas' => $jadwal->id_kelas, 'id_guru' => $jadwal->id_guru,
            'id_user' => $request->user()->id_user, 'id_jam_mulai' => $jadwal->id_jam_mulai, 'id_jam_selesai' => $jadwal->id_jam_selesai,
            'tanggal' => $validated['tanggal'], 'materi' => $validated['materi'], 'status_guru' => $validated['status_guru'],
            'ada_tugas' => $validated['ada_tugas'], 'deskripsi_tugas' => $validated['deskripsi_tugas'] ?? null,
            'jml_hadir' => $validated['jml_hadir'], 'jml_tidak_hadir' => $validated['jml_tidak_hadir'],
            'status_validasi_guru' => 'Disetujui', 'catatan_umum' => $validated['catatan_umum'] ?? null,
        ]);
        return redirect()->route('sekretaris.validasi-jurnal')->with('success', 'Jurnal atas nama guru berhasil disimpan dan langsung tervalidasi.');
    }
}
