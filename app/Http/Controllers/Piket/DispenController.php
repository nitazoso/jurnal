<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Dispen;
use App\Models\JadwalKesiswaan;
use App\Models\JamPel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DispenController extends Controller
{
    public function index()
    {
        $dispens = Dispen::with('siswa', 'jamMulai', 'jamSelesai')
            ->latest('tanggal')
            ->paginate(10);

        return view('piket.dispen.index', compact('dispens'));
    }

    public function create()
    {
        $siswa = Siswa::orderBy('nama_siswa')->get();

        $jamPels = JamPel::where('jenis', 'pelajaran')
            ->orderBy('jam_ke')
            ->get();

        return view('piket.dispen.create', compact('siswa', 'jamPels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ]);

        $jadwal = JadwalKesiswaan::with('user')
            ->whereDate('tanggal', $validated['tanggal'])
            ->first();

        $dispen = Dispen::create($validated + [
            'token_verifikasi' => Str::random(64),
        ]);

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil ditambahkan.');
    }

    public function whatsapp(Dispen $dispen)
    {
        $dispen->load(['siswa.kelas', 'jamMulai', 'jamSelesai']);

        $waka = JadwalKesiswaan::with('user')
            ->whereDate('tanggal', $dispen->tanggal)
            ->first()?->user;

        abort_unless($waka?->no_wa, 422, 'Nomor WhatsApp Waka untuk tanggal tersebut belum tersedia.');

        $message = "Permohonan Dispensasi Siswa\n\n"
            .'Nama Siswa: '.$dispen->siswa->nama_siswa."\n"
            .'Kelas: '.($dispen->siswa->kelas->nama_kelas ?? '-')."\n"
            .'Tanggal: '.$dispen->tanggal->format('d-m-Y')."\n"
            .'Jam: '.($dispen->jamMulai->jam_mulai ?? '-').' - '.($dispen->jamSelesai->jam_selesai ?? '-')."\n"
            .'Alasan: '.$dispen->alasan."\n\n"
            .'Silakan lakukan verifikasi melalui link berikut:\n'
            .route('dispen.verifikasi', $dispen->token_verifikasi);

        $phone = preg_replace('/\D+/', '', $waka->no_wa);
        $phone = str_starts_with($phone, '0') ? '62'.substr($phone, 1) : $phone;

        return redirect('https://wa.me/'.$phone.'?text='.rawurlencode($message));
    }

    public function edit(Dispen $dispen)
    {
        $siswa = Siswa::orderBy('nama_siswa')->get();

        $jamPels = JamPel::where('jenis', 'pelajaran')
            ->orderBy('jam_ke')
            ->get();

        return view('piket.dispen.edit', compact(
            'dispen',
            'siswa',
            'jamPels'
        ));
    }

    public function update(Request $request, Dispen $dispen)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ]);

        $dispen->update($validated);

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil diperbarui.');
    }

    public function destroy(Dispen $dispen)
    {
        $dispen->delete();

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil dihapus.');
    }
}
