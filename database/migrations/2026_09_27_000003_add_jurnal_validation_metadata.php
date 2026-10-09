<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('jurnals', 'keterangan')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $table->text('keterangan')->nullable()->after('materi');
            });
        }

        if (! Schema::hasColumn('jurnals', 'status_kehadiran_validasi')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $table->string('status_kehadiran_validasi', 20)->nullable()->after('status_validasi_guru');
            });
        }

        if (! Schema::hasColumn('jurnals', 'validated_by')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $table->foreignId('validated_by')->nullable()->after('status_kehadiran_validasi')
                    ->constrained('users', 'id_user')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('jurnals', 'validated_at')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $table->timestamp('validated_at')->nullable()->after('validated_by');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('jurnals', 'validated_by')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $table->dropForeign(['validated_by']);
                $table->dropColumn('validated_by');
            });
        }

        Schema::table('jurnals', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('jurnals', 'keterangan') ? 'keterangan' : null,
                Schema::hasColumn('jurnals', 'status_kehadiran_validasi') ? 'status_kehadiran_validasi' : null,
                Schema::hasColumn('jurnals', 'validated_at') ? 'validated_at' : null,
            ]);

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};