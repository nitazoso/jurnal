<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kelases', 'qr_token')) {
            Schema::table('kelases', function (Blueprint $table) {
                $table->string('qr_token', 64)->nullable()->unique()->after('nama_kelas');
            });
        }

        DB::table('kelases')->whereNull('qr_token')->orderBy('id_kelas')->get()->each(function ($kelas) {
            DB::table('kelases')
                ->where('id_kelas', $kelas->id_kelas)
                ->update(['qr_token' => Str::random(48)]);
        });
    }

    public function down(): void
    {
        Schema::table('kelases', function (Blueprint $table) {
            $table->dropUnique(['qr_token']);
            $table->dropColumn('qr_token');
        });
    }
};