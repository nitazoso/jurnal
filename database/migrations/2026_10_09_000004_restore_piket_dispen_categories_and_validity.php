<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasJenisDispen = Schema::hasColumn('dispens', 'jenis_dispen');
        $hasJenisSuratSakit = Schema::hasColumn('dispens', 'jenis_surat_sakit');
        $hasTanggalSelesai = Schema::hasColumn('dispens', 'tanggal_selesai');

        if (! $hasJenisDispen || ! $hasJenisSuratSakit || ! $hasTanggalSelesai) {
            Schema::table('dispens', function (Blueprint $table) use ($hasJenisDispen, $hasJenisSuratSakit, $hasTanggalSelesai) {
                if (! $hasJenisDispen) {
                    $table->string('jenis_dispen', 20)->default('kegiatan')->after('jenis');
                }
                if (! $hasJenisSuratSakit) {
                    $table->string('jenis_surat_sakit', 20)->nullable()->after('jenis_dispen');
                }
                if (! $hasTanggalSelesai) {
                    $table->date('tanggal_selesai')->nullable()->after('tanggal');
                }
            });
        }
    }

    public function down(): void
    {
        $columns = array_filter([
            Schema::hasColumn('dispens', 'jenis_dispen') ? 'jenis_dispen' : null,
            Schema::hasColumn('dispens', 'jenis_surat_sakit') ? 'jenis_surat_sakit' : null,
            Schema::hasColumn('dispens', 'tanggal_selesai') ? 'tanggal_selesai' : null,
        ]);

        if ($columns) {
            Schema::table('dispens', fn (Blueprint $table) => $table->dropColumn($columns));
        }
    }
};