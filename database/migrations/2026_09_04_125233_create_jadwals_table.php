<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id('id_jadwal');

            // Relasi ke Guru, Mapel, dan Kelas
            $table->unsignedBigInteger('id_guru');
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_kelas');

            // Relasi ke Tabel jam_pel
            $table->unsignedBigInteger('id_jam_mulai');
            $table->unsignedBigInteger('id_jam_selesai');

            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);
            $table->enum('semester', ['Ganjil', 'Genap'])->default('Ganjil');
            $table->string('tahun_ajaran', 9); // Diisi otomatis via Controller (contoh: "2026/2027")

            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraints Utama
            $table->foreign('id_guru')
                ->references('id_guru')
                ->on('gurus')
                ->onDelete('cascade');

            $table->foreign('id_mapel')
                ->references('id_mapel')
                ->on('mapels')
                ->onDelete('cascade');

            $table->foreign('id_kelas')
                ->references('id_kelas')
                ->on('kelases')
                ->onDelete('cascade');

            // Foreign Key Constraints ke Tabel jam_pel
            $table->foreign('id_jam_mulai')
                ->references('id_jam')
                ->on('jam_pels')
                ->onDelete('cascade');

            $table->foreign('id_jam_selesai')
                ->references('id_jam')
                ->on('jam_pels')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
