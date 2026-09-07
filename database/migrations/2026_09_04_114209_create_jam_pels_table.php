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
        Schema::create('jam_pels', function (Blueprint $table) {
            $table->id('id_jam');
            $table->enum('klp_hari', ['Senin-Kamis', 'Jumat']);
            $table->integer('jam_ke')->nullable();
            $table->enum('jenis', ['pelajaran', 'istirahat']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_pels');
    }
};
