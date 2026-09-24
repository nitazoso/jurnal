<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('gurus', 'no_hp')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('CREATE TABLE gurus_without_no_hp (id_guru INTEGER PRIMARY KEY AUTOINCREMENT, nama_guru VARCHAR(100) NOT NULL, created_at DATETIME NULL, updated_at DATETIME NULL, deleted_at DATETIME NULL)');
            DB::statement('INSERT INTO gurus_without_no_hp (id_guru, nama_guru, created_at, updated_at, deleted_at) SELECT id_guru, nama_guru, created_at, updated_at, deleted_at FROM gurus');
            DB::statement('DROP TABLE gurus');
            DB::statement('ALTER TABLE gurus_without_no_hp RENAME TO gurus');

            return;
        }

        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->string('no_hp', 15)->nullable();
        });
    }
};