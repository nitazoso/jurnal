<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('dispens', 'token_verifikasi')) {
            Schema::table('dispens', function (Blueprint $table) {
                $table->string('token_verifikasi', 64)->nullable()->unique()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('dispens', 'token_verifikasi')) {
            Schema::table('dispens', function (Blueprint $table) {
                $table->dropUnique(['token_verifikasi']);
                $table->dropColumn('token_verifikasi');
            });
        }
    }
};