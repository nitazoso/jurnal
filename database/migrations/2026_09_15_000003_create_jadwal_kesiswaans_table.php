<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kesiswaans', function (Blueprint $table) {
            $table->id('id_jadwal_kesiswaan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->unique('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kesiswaans');
    }
};
