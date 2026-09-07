<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_absensi', function (Blueprint $table) {
            $table->id('id_absensi');

            $table->unsignedBigInteger('id_jurnal'); // Terhubung ke jurnal di atas
            $table->unsignedBigInteger('id_siswa');  // Siswa yang tidak masuk

            $table->enum('status', ['Sakit', 'Izin', 'Alpha', 'Dispen']);
            $table->string('keterangan', 255)->nullable(); // Misal: "Surat Dokter", "Izin Acara Keluarga"

            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_jurnal')->references('id_jurnal')->on('jurnals')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_absensi');
    }
};
