<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurnals', function (Blueprint $table) {
            if (! Schema::hasColumn('jurnals', 'piket_approved_by')) {
                $table->foreignId('piket_approved_by')->nullable()
                    ->constrained('users', 'id_user')->nullOnDelete();
            }

            if (! Schema::hasColumn('jurnals', 'piket_approved_at')) {
                $table->timestamp('piket_approved_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('jurnals', 'piket_approved_by')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $table->dropForeign(['piket_approved_by']);
                $table->dropColumn('piket_approved_by');
            });
        }

        if (Schema::hasColumn('jurnals', 'piket_approved_at')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $table->dropColumn('piket_approved_at');
            });
        }
    }
};
