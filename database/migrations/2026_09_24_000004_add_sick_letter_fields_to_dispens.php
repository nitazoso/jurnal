<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispens', function (Blueprint $table) {
            $table->string('jenis', 20)->default('dispen')->after('id_siswa');
            $table->string('surat_path')->nullable()->after('alasan');
            $table->foreignId('submitted_by')->nullable()->after('id_kesiswaan')
                ->constrained('users', 'id_user')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dispens', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropColumn(['jenis', 'surat_path', 'submitted_by']);
        });
    }
};
