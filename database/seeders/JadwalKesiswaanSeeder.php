<?php

namespace Database\Seeders;

use App\Models\JadwalKesiswaan;
use App\Models\User;
use Illuminate\Database\Seeder;

class JadwalKesiswaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kesiswaanUsers = User::where('role', 'Kesiswaan')
            ->orderBy('id_user')
            ->get();

        if ($kesiswaanUsers->isEmpty()) {
            return;
        }

        $wakaA = $kesiswaanUsers->first();
        $wakaB = $kesiswaanUsers->count() > 1 ? $kesiswaanUsers->get(1) : $wakaA;

        $jadwalData = [
            '2026-09-21' => $wakaA,
            '2026-09-22' => $wakaB,
            '2026-09-23' => $wakaA,
            '2026-09-24' => $wakaB,
            '2026-09-25' => $wakaA,
            '2026-09-26' => $wakaB,
            '2026-09-27' => $wakaA,
            '2026-09-28' => $wakaB,
        ];

        foreach ($jadwalData as $tanggal => $petugas) {
            JadwalKesiswaan::updateOrCreate(
                ['tanggal' => $tanggal],
                ['id_user' => $petugas->id_user]
            );
        }
    }
}
