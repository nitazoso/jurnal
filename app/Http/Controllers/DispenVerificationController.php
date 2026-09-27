<?php

namespace App\Http\Controllers;

use App\Models\Dispen;
use App\Models\PiketJadwal;
use App\Models\User;
use Illuminate\Http\Request;

class DispenVerificationController extends Controller
{
    /**
     * Tampilkan halaman verifikasi dispensasi berdasarkan token unik.
     * Tidak memerlukan login.
     */
    public function show(string $token)
    {
        $dispen = $this->findDispen($token);
        $dispen->load(['siswa.kelas', 'jamMulai', 'jamSelesai', 'approver', 'petugasKesiswaan']);

        return view('dispen.verifikasi', compact('dispen'));
    }

    /**
     * Menyetujui permohonan dispensasi.
     */
    public function approve(Request $request, string $token)
    {
        $dispen = $this->findDispen($token);

        // Pencegahan proses ulang jika sudah disetujui atau ditolak
        if ($dispen->status !== 'menunggu') {
            return redirect()->route('dispen.verifikasi', $dispen->token_verifikasi)
                ->with('info', 'Dispensasi ini sudah diverifikasi sebelumnya dengan status: ' . ucfirst($dispen->status) . '.');
        }

        $approverId = $this->scheduledApproverId($dispen);
        $updated = Dispen::whereKey($dispen->getKey())
            ->where("status", "menunggu")
            ->update([
                "status" => "disetujui",
                "disetujui_oleh" => $approverId,
                "disetujui_pada" => now(),
                "updated_at" => now(),
            ]);

        if (! $updated) {
            return $this->alreadyProcessed($dispen);
        }

        return redirect()->route('dispen.verifikasi', $dispen->token_verifikasi)
            ->with('success', 'Dispensasi siswa berhasil disetujui.');
    }

    /**
     * Menolak permohonan dispensasi. Alasan penolakan WAJIB diisi.
     */
    public function reject(Request $request, string $token)
    {
        $dispen = $this->findDispen($token);

        // Pencegahan proses ulang jika sudah disetujui atau ditolak
        if ($dispen->status !== 'menunggu') {
            return redirect()->route('dispen.verifikasi', $dispen->token_verifikasi)
                ->with('info', 'Dispensasi ini sudah diverifikasi sebelumnya dengan status: ' . ucfirst($dispen->status) . '.');
        }

        $validated = $request->validate([
            'catatan_persetujuan' => 'required|string|max:255',
        ], [
            'catatan_persetujuan.required' => 'Alasan penolakan wajib diisi.',
            'catatan_persetujuan.max' => 'Alasan penolakan maksimal 255 karakter.',
        ]);

        $approverId = $this->scheduledApproverId($dispen);
        $updated = Dispen::whereKey($dispen->getKey())
            ->where("status", "menunggu")
            ->update([
                "status" => "ditolak",
                "disetujui_oleh" => $approverId,
                "disetujui_pada" => now(),
                "catatan_persetujuan" => $validated["catatan_persetujuan"],
                "updated_at" => now(),
            ]);

        if (! $updated) {
            return $this->alreadyProcessed($dispen);
        }

        return redirect()->route('dispen.verifikasi', $dispen->token_verifikasi)
            ->with('success', 'Dispensasi siswa telah ditolak.');
    }

    /**
     * Cari dispen berdasarkan token verifikasi.
     */
    private function findDispen(string $token): Dispen
    {
        return Dispen::where('token_verifikasi', $token)->firstOrFail();
    }

    private function scheduledApproverId(Dispen $dispen): ?int
    {
        if ($dispen->id_kesiswaan) {
            return $dispen->id_kesiswaan;
        }

        $wakaGuruId = PiketJadwal::whereDate('tanggal', $dispen->tanggal)
            ->where('jenis_tugas', 'Piket Waka')
            ->value('id_guru');

        return $wakaGuruId
            ? User::where('id_guru', $wakaGuruId)->value('id_user')
            : null;
    }

    private function alreadyProcessed(Dispen $dispen)
    {
        $dispen->refresh();

        return redirect()->route('dispen.verifikasi', $dispen->token_verifikasi)
            ->with('info', 'Dispensasi ini sudah diverifikasi sebelumnya dengan status: ' . ucfirst($dispen->status) . '.');
    }
}