<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mapels')->insert(
array (
  0 => 
  array (
    'id_mapel' => 1,
    'nama_mapel' => 'Matematika Wajib',
    'created_at' => '2026-09-27 04:02:36',
    'updated_at' => '2026-09-27 04:02:36',
    'deleted_at' => NULL,
  ),
  1 => 
  array (
    'id_mapel' => 2,
    'nama_mapel' => 'Bahasa Indonesia',
    'created_at' => '2026-09-27 04:02:36',
    'updated_at' => '2026-09-27 04:02:36',
    'deleted_at' => NULL,
  ),
  2 => 
  array (
    'id_mapel' => 3,
    'nama_mapel' => 'Pendidikan Pancasila',
    'created_at' => '2026-09-27 04:02:36',
    'updated_at' => '2026-09-27 04:02:36',
    'deleted_at' => NULL,
  ),
  3 => 
  array (
    'id_mapel' => 4,
    'nama_mapel' => 'Sejarah',
    'created_at' => '2026-09-28 01:30:12',
    'updated_at' => '2026-09-28 01:30:12',
    'deleted_at' => NULL,
  ),
  4 => 
  array (
    'id_mapel' => 5,
    'nama_mapel' => 'PAI',
    'created_at' => '2026-09-28 01:30:23',
    'updated_at' => '2026-09-28 01:30:23',
    'deleted_at' => NULL,
  ),
  5 => 
  array (
    'id_mapel' => 6,
    'nama_mapel' => 'iPA',
    'created_at' => '2026-09-28 01:30:35',
    'updated_at' => '2026-09-28 01:30:35',
    'deleted_at' => NULL,
  ),
)
        );
    }
}
