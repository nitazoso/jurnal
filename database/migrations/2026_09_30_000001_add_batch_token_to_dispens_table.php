<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('dispens', 'batch_token')) {
            Schema::table('dispens', function (Blueprint $table) {
                $table->string('batch_token', 36)->nullable()->index()->after('token_verifikasi');
            });
        }

        // A grouped request shares one verification token across its student records.
        try {
            Schema::table('dispens', function (Blueprint $table) {
                $table->dropUnique('dispens_token_verifikasi_unique');
            });
        } catch (Throwable $exception) {
            // The unique index may already have been removed in an existing database.
        }
        Schema::table('dispens', function (Blueprint $table) {
            $table->index('token_verifikasi', 'dispens_token_verifikasi_index');
        });
    }

    public function down(): void
    {
        Schema::table('dispens', function (Blueprint $table) {
            $table->dropIndex('dispens_token_verifikasi_index');
            $table->unique('token_verifikasi');
        });

        if (Schema::hasColumn('dispens', 'batch_token')) {
            Schema::table('dispens', function (Blueprint $table) {
                $table->dropIndex(['batch_token']);
                $table->dropColumn('batch_token');
            });
        }
    }
};
