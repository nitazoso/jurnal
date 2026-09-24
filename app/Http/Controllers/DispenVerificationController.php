<?php

namespace App\Http\Controllers;

use App\Models\Dispen;
use App\Models\JadwalKesiswaan;
use Illuminate\Http\Request;

class DispenVerificationController extends Controller
{
    public function show(string $token)
    {
        $dispen = $this->findDispen($token);
        $dispen->load(['siswa.kelas', 'jamMulai', 'jamSelesai']);

        return view('dispen.verifikasi', compact('dispen'));
    }

    public function approve(Request $request, string $token)
    {
        $dispen = $this->findDispen($token);

        if ($dispen->status === 'menunggu') {
            $dispen->update($this->approvalData('disetujui'));
        }

        return redirect()->route('dispen.verifikasi', $dispen->token_verifikasi);
    }

    public function reject(Request $request, string $token)
    {
        $validated = $request->validate([
            'catatan_persetujuan' => 'required|string|max:255',
        ]);

        $dispen = $this->findDispen($token);

        if ($dispen->status === 'menunggu') {
            $dispen->update($this->approvalData('ditolak') + [
                'catatan_persetujuan' => $validated['catatan_persetujuan'],
            ]);
        }

        return redirect()->route('dispen.verifikasi', $dispen->token_verifikasi);
    }

    private function findDispen(string $token): Dispen
    {
        return Dispen::where('token_verifikasi', $token)->firstOrFail();
    }

    private function approvalData(string $status): array
    {
        $userId = auth()->id();

        return [
            'status' => $status,
            'disetujui_oleh' => $userId && JadwalKesiswaan::where('tanggal', now()->toDateString())
                ->where('id_user', $userId)->exists() ? $userId : null,
            'disetujui_pada' => now(),
        ];
    }
}