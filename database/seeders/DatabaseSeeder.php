<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GuruSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            MapelSeeder::class,
            JamPelSeeder::class,
            JadwalSeeder::class,
            PiketJadwalSeeder::class,
        ]);
    }
}