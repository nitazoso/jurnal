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
        Schema::create('kelases', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 20);

            $table->unsignedBigInteger('wali_kelas');
            $table->integer('jumlah_siswa')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('wali_kelas')
                ->references('id_guru')
                ->on('gurus')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelases');
    }
};
