<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispens', function (Blueprint $table) {
            $table->foreignId('id_kesiswaan')
                ->nullable()
                ->after('id_siswa')
                ->constrained('users', 'id_user')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dispens', function (Blueprint $table) {
            $table->dropForeign(['id_kesiswaan']);
            $table->dropColumn('id_kesiswaan');
        });
    }
};
