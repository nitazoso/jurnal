<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('jurnals', 'diisi_oleh_piket')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $table->boolean('diisi_oleh_piket')->default(false)->after('status_kehadiran_validasi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('jurnals', 'diisi_oleh_piket')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $table->dropColumn('diisi_oleh_piket');
            });
        }
    }
};