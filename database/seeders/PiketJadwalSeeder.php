<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PiketJadwalSeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('seeders/PiketJadwalData.php');

        DB::table('piket_jadwals')->insert($data);
    }
}