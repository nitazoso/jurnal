<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $jamLama = DB::table('jam_pels')
            ->where('jenis', 'pelajaran')
            ->whereNotNull('deleted_at')
            ->get();

        $jamMap = [];

        foreach ($jamLama as $jam) {
            $jamBaru = DB::table('jam_pels')
                ->where('klp_hari', $jam->klp_hari)
                ->where('jam_ke', $jam->jam_ke)
                ->where('jenis', 'pelajaran')
                ->whereNull('deleted_at')
                ->orderByDesc('id_jam')
                ->first();

            if ($jamBaru) {
                $jamMap[$jam->id_jam] = $jamBaru->id_jam;
            }
        }

        foreach ($jamMap as $jamLamaId => $jamBaruId) {
            DB::table('jadwals')
                ->where('id_jam_mulai', $jamLamaId)
                ->update(['id_jam_mulai' => $jamBaruId]);

            DB::table('jadwals')
                ->where('id_jam_selesai', $jamLamaId)
                ->update(['id_jam_selesai' => $jamBaruId]);

            DB::table('jurnals')
                ->where('id_jam_mulai', $jamLamaId)
                ->update(['id_jam_mulai' => $jamBaruId]);

            DB::table('jurnals')
                ->where('id_jam_selesai', $jamLamaId)
                ->update(['id_jam_selesai' => $jamBaruId]);
        }
    }

    public function down(): void
    {
    }
};
