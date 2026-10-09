<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_periods', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->string('semester', 10);
            $table->string('tahun_ajaran', 9);
            $table->timestamps();
        });

        $period = DB::table('jadwals')
            ->whereNotNull('tahun_ajaran')
            ->where('tahun_ajaran', '!=', '')
            ->orderByDesc('tahun_ajaran')
            ->orderByDesc('updated_at')
            ->first(['semester', 'tahun_ajaran']);

        if ($period) {
            DB::table('academic_periods')->insert([
                'id' => 1,
                'semester' => $period->semester,
                'tahun_ajaran' => $period->tahun_ajaran,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_periods');
    }
};