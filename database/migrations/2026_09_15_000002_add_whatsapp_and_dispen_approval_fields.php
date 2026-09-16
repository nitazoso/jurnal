<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_wa', 20)->nullable()->after('role');
        });

        Schema::table('dispens', function (Blueprint $table) {
            $table->string('status', 20)->default('menunggu')->after('alasan');
            $table->foreignId('disetujui_oleh')->nullable()->after('status')
                ->constrained('users', 'id_user')->nullOnDelete();
            $table->timestamp('disetujui_pada')->nullable()->after('disetujui_oleh');
            $table->string('catatan_persetujuan', 255)->nullable()->after('disetujui_pada');
        });

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('dispens', function (Blueprint $table) {
            $table->dropForeign(['disetujui_oleh']);
            $table->dropColumn(['status', 'disetujui_oleh', 'disetujui_pada', 'catatan_persetujuan']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_wa');
        });

        Schema::dropIfExists('notifications');
    }
};
