<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_absensi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dispen')->nullable()->after('id_siswa');
            $table->foreign('id_dispen')->references('id_dispen')->on('dispens')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detail_absensi', function (Blueprint $table) {
            $table->dropForeign(['id_dispen']);
            $table->dropColumn('id_dispen');
        });
    }
};
