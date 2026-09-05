<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_guru')
                  ->references('id_guru')
                  ->on('gurus')
                  ->onDelete('set null');

            $table->foreign('id_kelas')
                  ->references('id_kelas')
                  ->on('kelases')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_guru']);
            $table->dropForeign(['id_kelas']);
        });
    }
};