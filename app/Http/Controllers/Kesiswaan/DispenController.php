<?php

namespace App\Http\Controllers\Kesiswaan;

use App\Http\Controllers\Controller;
use App\Models\Dispen;
use Illuminate\Http\Request;

class DispenController extends Controller
{
    public function index()
    {
        $dispens = Dispen::with(['siswa', 'jamMulai', 'jamSelesai'])
            ->where('status', 'menunggu')
            ->latest()
            ->paginate(15);

        return view('kesiswaan.dispen.index', compact('dispens'));
    }

    public function history()
    {
        $dispens = Dispen::with(['siswa', 'approver', 'jamMulai', 'jamSelesai'])
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->latest('disetujui_pada')
            ->paginate(15);

        return view('kesiswaan.dispen.history', compact('dispens'));
    }

    public function show(Dispen $dispen)
    {
        $this->ensureScheduled($dispen);

        $dispen->load(['siswa.kelas', 'jamMulai', 'jamSelesai']);

        auth()->user()->unreadNotifications()
            ->where('data->dispen_id', $dispen->id_dispen)
            ->update(['read_at' => now()]);

        return view('kesiswaan.dispen.show', compact('dispen'));
    }

    public function approve(Request $request, Dispen $dispen)
    {
        $this->ensureScheduled($dispen);

        $validated = $request->validate([
            'catatan_persetujuan' => 'nullable|string|max:255',
        ]);

        $dispen->update([
            'status' => 'disetujui',
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => now(),
            'catatan_persetujuan' => $validated['catatan_persetujuan'] ?? null,
        ]);

        return redirect()->route('kesiswaan.dispen.index')
            ->with('success', 'Pengajuan dispen disetujui.');
    }

    public function reject(Request $request, Dispen $dispen)
    {
        $this->ensureScheduled($dispen);

        $validated = $request->validate([
            'catatan_persetujuan' => 'required|string|max:255',
        ]);

        $dispen->update([
            'status' => 'ditolak',
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => now(),
            'catatan_persetujuan' => $validated['catatan_persetujuan'],
        ]);

        return redirect()->route('kesiswaan.dispen.index')
            ->with('success', 'Pengajuan dispen ditolak.');
    }

    private function ensureScheduled(Dispen $dispen): void
    {
        if ($dispen->id_kesiswaan) {
            abort_unless(
                $dispen->id_kesiswaan === auth()->id(),
                403,
                'Dispen ini ditujukan untuk petugas kesiswaan lain.'
            );

            return;
        }

        // Pengajuan lama belum memiliki penerima Kesiswaan. Karena halaman ini
        // sudah dilindungi role:Kesiswaan, semua petugas Kesiswaan dapat
        // memeriksa dan memvalidasi pengajuan tersebut.
    }
}
