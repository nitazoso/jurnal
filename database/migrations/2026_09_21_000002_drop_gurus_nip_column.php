<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('gurus', 'nip')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('DROP INDEX IF EXISTS gurus_nip_unique');
            DB::statement('CREATE TABLE gurus_new (id_guru INTEGER PRIMARY KEY AUTOINCREMENT, nama_guru VARCHAR(100) NOT NULL, no_hp VARCHAR(15) NULL, created_at DATETIME NULL, updated_at DATETIME NULL, deleted_at DATETIME NULL)');
            DB::statement('INSERT INTO gurus_new (id_guru, nama_guru, no_hp, created_at, updated_at, deleted_at) SELECT id_guru, nama_guru, no_hp, created_at, updated_at, deleted_at FROM gurus');
            DB::statement('DROP TABLE gurus');
            DB::statement('ALTER TABLE gurus_new RENAME TO gurus');

            return;
        }

        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn('nip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('DROP TABLE IF EXISTS gurus');
            DB::statement('CREATE TABLE gurus (id_guru INTEGER PRIMARY KEY AUTOINCREMENT, nip VARCHAR(20) NULL UNIQUE, nama_guru VARCHAR(100) NOT NULL, no_hp VARCHAR(15) NULL, created_at DATETIME NULL, updated_at DATETIME NULL, deleted_at DATETIME NULL)');

            return;
        }

        Schema::table('gurus', function (Blueprint $table) {
            $table->string('nip', 20)->nullable()->unique()->after('id_guru');
        });
    }
};
