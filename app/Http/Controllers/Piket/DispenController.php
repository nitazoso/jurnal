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
    /**
     * Menampilkan daftar dispen.
     */
    public function index()
    {
        $dispens = Dispen::with([
            'siswa',
            'jamMulai',
            'jamSelesai'
        ])
            ->latest('tanggal')
            ->paginate(10);

        return view('piket.dispen.index', compact('dispens'));
    }

    /**
     * Form tambah dispen.
     */
    public function create()
    {
        $siswa = Siswa::orderBy('nama_siswa')->get();

        $jamPels = JamPel::where('jenis', 'pelajaran')
            ->orderBy('jam_ke')
            ->get();

        return view('piket.dispen.create', compact(
            'siswa',
            'jamPels'
        ));
    }

    /**
     * Simpan dispen baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'tanggal' => 'required|date',
            'id_jam_mulai' => 'required|exists:jam_pels,id_jam',
            'id_jam_selesai' => 'required|exists:jam_pels,id_jam',
            'alasan' => 'required|string|max:255',
        ]);

        $dispen = Dispen::create([
            'id_siswa' => $validated['id_siswa'],
            'tanggal' => $validated['tanggal'],
            'id_jam_mulai' => $validated['id_jam_mulai'],
            'id_jam_selesai' => $validated['id_jam_selesai'],
            'alasan' => $validated['alasan'],
            'token_verifikasi' => Str::random(64),
        ]);

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil ditambahkan.');
    }

    /**
     * Membuka WhatsApp untuk mengirim permohonan verifikasi ke Waka.
     */
    public function whatsapp(Dispen $dispen)
    {
        $dispen->load([
            'siswa.kelas',
            'jamMulai',
            'jamSelesai'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cari Waka berdasarkan jadwal Kesiswaan pada tanggal dispen
        |--------------------------------------------------------------------------
        */

        $waka = JadwalKesiswaan::with('user')
            ->whereDate('tanggal', $dispen->tanggal)
            ->first()?->user;

        abort_unless(
            $waka,
            422,
            'Waka untuk tanggal tersebut belum memiliki jadwal.'
        );

        /*
        |--------------------------------------------------------------------------
        | Cek nomor WhatsApp
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $waka->no_wa,
            422,
            'Nomor WhatsApp Waka untuk tanggal tersebut belum tersedia.'
        );

        /*
        |--------------------------------------------------------------------------
        | Buat link verifikasi
        |--------------------------------------------------------------------------
        */

        $linkVerifikasi = route(
            'dispen.verifikasi',
            $dispen->token_verifikasi
        );

        /*
        |--------------------------------------------------------------------------
        | Buat pesan WhatsApp
        |--------------------------------------------------------------------------
        */

        $message =
            "Permohonan Dispensasi Siswa\n\n"
            . "Nama Siswa: " . $dispen->siswa->nama_siswa . "\n"
            . "Kelas: " . ($dispen->siswa->kelas->nama_kelas ?? '-') . "\n"
            . "Tanggal: " . $dispen->tanggal->format('d-m-Y') . "\n"
            . "Jam: "
            . ($dispen->jamMulai->jam_mulai ?? '-')
            . " - "
            . ($dispen->jamSelesai->jam_selesai ?? '-')
            . "\n"
            . "Alasan: " . $dispen->alasan . "\n\n"
            . "Silakan lakukan verifikasi melalui link berikut:\n"
            . $linkVerifikasi;

        /*
        |--------------------------------------------------------------------------
        | Bersihkan nomor WhatsApp
        |--------------------------------------------------------------------------
        |
        | Contoh:
        | 081234567890 -> 6281234567890
        |
        */

        $phone = preg_replace('/\D+/', '', $waka->no_wa);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        /*
        |--------------------------------------------------------------------------
        | Buka WhatsApp
        |--------------------------------------------------------------------------
        */

        $whatsappUrl =
            'https://wa.me/'
            . $phone
            . '?text='
            . rawurlencode($message);

        return redirect()->away($whatsappUrl);
    }

    /**
     * Form edit dispen.
     */
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

    /**
     * Update dispen.
     */
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

    /**
     * Hapus dispen.
     */
    public function destroy(Dispen $dispen)
    {
        $dispen->delete();

        return redirect()
            ->route('piket.dispen.index')
            ->with('success', 'Data dispen berhasil dihapus.');
    }
}