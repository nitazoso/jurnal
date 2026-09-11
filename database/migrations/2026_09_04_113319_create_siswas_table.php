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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->string('nis', 20)->unique();
            $table->unsignedBigInteger('id_kelas');
            $table->integer('no_presensi');
            $table->string('nama_siswa', 225);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
