<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispens', function (Blueprint $table) {
            $table->id('id_dispen');

            $table->unsignedBigInteger('id_siswa');

            $table->date('tanggal');

            $table->unsignedBigInteger('id_jam_mulai');
            $table->unsignedBigInteger('id_jam_selesai');

            $table->string('alasan', 255);

            $table->timestamps();

            $table->foreign('id_siswa')
                ->references('id_siswa')
                ->on('siswas')
                ->onDelete('cascade');

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

    public function down(): void
    {
        Schema::dropIfExists('dispens');
    }
};