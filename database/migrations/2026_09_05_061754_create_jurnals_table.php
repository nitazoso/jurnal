<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id('id_jurnal');

            // Relasi Entitas
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_guru');  // Guru pengajar di kelas/jam tersebut
            $table->unsignedBigInteger('id_user');  // User pembuat/pengisi (Sekretaris / Piket)

            // Jam Pelaksanaan & Waktu
            $table->unsignedBigInteger('id_jam_mulai');
            $table->unsignedBigInteger('id_jam_selesai');
            $table->date('tanggal');

            // Isi Jurnal & Tugas
            $table->string('materi', 255);
            $table->enum('status_guru', ['Hadir', 'Izin', 'Sakit', 'Tanpa Keterangan']);
            $table->enum('ada_tugas', ['Ya', 'Tidak'])->default('Tidak');
            $table->text('deskripsi_tugas')->nullable();

            // Rekap Total Kehadiran
            $table->integer('jml_hadir');
            $table->integer('jml_tidak_hadir');

            // Status validasi dari Sekre
            $table->enum('status_validasi_guru', ['Menunggu', 'Disetujui', 'Ditolak', 'Perlu Diperbaiki'])
                ->default('Menunggu');

            // Catatan perbaikan (Diisi oleh Sekre jika jurnal DITOLAK / butuh revisi)
            $table->text('catatan_revisi')->nullable();
            $table->string('catatan_umum', 255)->nullable(); // Catatan tambahan biasa

            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwals')->onDelete('cascade');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelases')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('gurus')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_jam_mulai')->references('id_jam')->on('jam_pels')->onDelete('cascade');
            $table->foreign('id_jam_selesai')->references('id_jam')->on('jam_pels')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
