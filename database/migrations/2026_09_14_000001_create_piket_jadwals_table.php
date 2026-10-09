<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piket_jadwals', function (Blueprint $table) {
            $table->id('id_piket_jadwal');
            $table->unsignedBigInteger('id_guru');
            $table->date('tanggal');
            $table->enum('shift', ['Pagi', 'Siang', 'Waka'])->default('Pagi');
            $table->string('jam_mulai', 20)->nullable();
            $table->string('jam_selesai', 20)->nullable();
            $table->enum('jenis_tugas', [
                'Piket KBM Pagi',
                'Koordinator Piket KBM Pagi',
                'Piket KBM Siang',
                'Koordinator Piket KBM Siang',
                'Piket Waka',
            ]);
            $table->string('posisi', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_guru')->references('id_guru')->on('gurus')->onDelete('cascade');
            $table->foreign('created_by')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piket_jadwals');
    }
};
