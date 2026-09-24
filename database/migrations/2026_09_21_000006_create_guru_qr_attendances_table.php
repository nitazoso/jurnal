<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_qr_attendances', function (Blueprint $table) {
            $table->id('id_kehadiran_qr');
            $table->foreignId('id_jadwal')->constrained('jadwals', 'id_jadwal')->cascadeOnDelete();
            $table->foreignId('id_guru')->constrained('gurus', 'id_guru')->cascadeOnDelete();
            $table->foreignId('id_kelas')->constrained('kelases', 'id_kelas')->cascadeOnDelete();
            $table->date('tanggal');
            $table->dateTime('discan_pada');
            $table->timestamps();
            $table->unique(['id_jadwal', 'id_guru', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_qr_attendances');
    }
};