<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'Staff Piket')
            ->update(['role' => 'Guru']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('Admin', 'Guru', 'Kesiswaan', 'Sekretaris') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('Admin', 'Guru', 'Kesiswaan', 'Sekretaris', 'Staff Piket') NOT NULL");
        }
    }
};
