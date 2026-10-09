<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('piket_jadwals', function (Blueprint $table) {
            $table->unsignedBigInteger('petugas_kbm_pagi_id')->nullable()->after('id_guru');
            $table->unsignedBigInteger('koordinator_kbm_pagi_id')->nullable()->after('petugas_kbm_pagi_id');
            $table->unsignedBigInteger('petugas_kbm_siang_id')->nullable()->after('koordinator_kbm_pagi_id');
            $table->unsignedBigInteger('koordinator_kbm_siang_id')->nullable()->after('petugas_kbm_siang_id');
            $table->unsignedBigInteger('piket_waka_id')->nullable()->after('koordinator_kbm_siang_id');
            $table->string('jam_mulai_kbm_pagi', 20)->nullable()->after('jam_selesai');
            $table->string('jam_selesai_kbm_pagi', 20)->nullable()->after('jam_mulai_kbm_pagi');
            $table->string('jam_mulai_koordinator_pagi', 20)->nullable()->after('jam_selesai_kbm_pagi');
            $table->string('jam_selesai_koordinator_pagi', 20)->nullable()->after('jam_mulai_koordinator_pagi');
            $table->string('jam_mulai_kbm_siang', 20)->nullable()->after('jam_selesai_koordinator_pagi');
            $table->string('jam_selesai_kbm_siang', 20)->nullable()->after('jam_mulai_kbm_siang');
            $table->string('jam_mulai_koordinator_siang', 20)->nullable()->after('jam_selesai_kbm_siang');
            $table->string('jam_selesai_koordinator_siang', 20)->nullable()->after('jam_mulai_koordinator_siang');

            foreach ([
                'petugas_kbm_pagi_id',
                'koordinator_kbm_pagi_id',
                'petugas_kbm_siang_id',
                'koordinator_kbm_siang_id',
                'piket_waka_id',
            ] as $column) {
                $table->foreign($column)->references('id_guru')->on('gurus')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('piket_jadwals', function (Blueprint $table) {
            foreach ([
                'petugas_kbm_pagi_id',
                'koordinator_kbm_pagi_id',
                'petugas_kbm_siang_id',
                'koordinator_kbm_siang_id',
                'piket_waka_id',
            ] as $column) {
                $table->dropForeign([$column]);
            }

            $table->dropColumn([
                'petugas_kbm_pagi_id',
                'koordinator_kbm_pagi_id',
                'petugas_kbm_siang_id',
                'koordinator_kbm_siang_id',
                'piket_waka_id',
                'jam_mulai_kbm_pagi',
                'jam_selesai_kbm_pagi',
                'jam_mulai_koordinator_pagi',
                'jam_selesai_koordinator_pagi',
                'jam_mulai_kbm_siang',
                'jam_selesai_kbm_siang',
                'jam_mulai_koordinator_siang',
                'jam_selesai_koordinator_siang',
            ]);
        });
    }
};
