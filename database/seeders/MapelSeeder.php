<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mapels')->insert(array (
  0 => 
  array (
    'id_mapel' => 1,
    'nama_mapel' => 'Matematika Wajib',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-23 01:19:53',
    'deleted_at' => '2026-09-23 01:19:53',
  ),
  1 => 
  array (
    'id_mapel' => 2,
    'nama_mapel' => 'Bahasa Indonesia',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-23 01:19:49',
    'deleted_at' => '2026-09-23 01:19:49',
  ),
  2 => 
  array (
    'id_mapel' => 3,
    'nama_mapel' => 'Pendidikan Pancasila',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-23 01:19:46',
    'deleted_at' => '2026-09-23 01:19:46',
  ),
  3 => 
  array (
    'id_mapel' => 4,
    'nama_mapel' => 'olah raga',
    'created_at' => '2026-09-18 06:52:08',
    'updated_at' => '2026-09-23 01:19:42',
    'deleted_at' => '2026-09-23 01:19:42',
  ),
  4 => 
  array (
    'id_mapel' => 5,
    'nama_mapel' => 'dasar jurusan atr',
    'created_at' => '2026-09-18 06:52:33',
    'updated_at' => '2026-09-23 01:19:40',
    'deleted_at' => '2026-09-23 01:19:40',
  ),
  5 => 
  array (
    'id_mapel' => 6,
    'nama_mapel' => 'b.inggris',
    'created_at' => '2026-09-18 07:01:32',
    'updated_at' => '2026-09-23 01:19:37',
    'deleted_at' => '2026-09-23 01:19:37',
  ),
  6 => 
  array (
    'id_mapel' => 7,
    'nama_mapel' => 'kik',
    'created_at' => '2026-09-18 07:02:03',
    'updated_at' => '2026-09-23 01:19:31',
    'deleted_at' => '2026-09-23 01:19:31',
  ),
  7 => 
  array (
    'id_mapel' => 8,
    'nama_mapel' => 'ppkn',
    'created_at' => '2026-09-23 02:19:22',
    'updated_at' => '2026-09-23 02:19:22',
    'deleted_at' => NULL,
  ),
  8 => 
  array (
    'id_mapel' => 9,
    'nama_mapel' => 'Matematika',
    'created_at' => '2026-09-23 09:17:05',
    'updated_at' => '2026-09-23 09:17:05',
    'deleted_at' => NULL,
  ),
  9 => 
  array (
    'id_mapel' => 10,
    'nama_mapel' => 'Bahasa Jawa',
    'created_at' => '2026-09-23 09:17:17',
    'updated_at' => '2026-09-23 09:17:17',
    'deleted_at' => NULL,
  ),
  10 => 
  array (
    'id_mapel' => 11,
    'nama_mapel' => 'Bahasa Jepang',
    'created_at' => '2026-09-23 09:18:15',
    'updated_at' => '2026-09-23 09:18:15',
    'deleted_at' => NULL,
  ),
  11 => 
  array (
    'id_mapel' => 12,
    'nama_mapel' => 'Bahasa Inggris',
    'created_at' => '2026-09-23 09:18:27',
    'updated_at' => '2026-09-23 09:18:27',
    'deleted_at' => NULL,
  ),
  12 => 
  array (
    'id_mapel' => 13,
    'nama_mapel' => 'Kreativitas Iovasi Kewirausahaan',
    'created_at' => '2026-09-23 09:20:36',
    'updated_at' => '2026-09-23 09:20:36',
    'deleted_at' => NULL,
  ),
  13 => 
  array (
    'id_mapel' => 14,
    'nama_mapel' => 'PAIBP',
    'created_at' => '2026-09-23 09:20:49',
    'updated_at' => '2026-09-23 09:20:49',
    'deleted_at' => NULL,
  ),
  14 => 
  array (
    'id_mapel' => 15,
    'nama_mapel' => 'Mapel Pilihan RPL',
    'created_at' => '2026-09-23 09:21:02',
    'updated_at' => '2026-09-23 09:21:02',
    'deleted_at' => NULL,
  ),
  15 => 
  array (
    'id_mapel' => 16,
    'nama_mapel' => 'Konsentrasi RPL',
    'created_at' => '2026-09-23 09:21:35',
    'updated_at' => '2026-09-23 09:21:35',
    'deleted_at' => NULL,
  ),
  16 => 
  array (
    'id_mapel' => 17,
    'nama_mapel' => 'IPAS',
    'created_at' => '2026-09-23 09:22:43',
    'updated_at' => '2026-09-23 09:22:43',
    'deleted_at' => NULL,
  ),
  17 => 
  array (
    'id_mapel' => 18,
    'nama_mapel' => 'Bhs Indonesia',
    'created_at' => '2026-09-23 09:23:59',
    'updated_at' => '2026-09-23 09:23:59',
    'deleted_at' => NULL,
  ),
  18 => 
  array (
    'id_mapel' => 19,
    'nama_mapel' => 'PJOK',
    'created_at' => '2026-09-23 09:24:13',
    'updated_at' => '2026-09-23 10:17:00',
    'deleted_at' => NULL,
  ),
  19 => 
  array (
    'id_mapel' => 20,
    'nama_mapel' => 'Sejarah',
    'created_at' => '2026-09-23 10:19:16',
    'updated_at' => '2026-09-23 10:19:16',
    'deleted_at' => NULL,
  ),
  20 => 
  array (
    'id_mapel' => 21,
    'nama_mapel' => 'BK',
    'created_at' => '2026-09-23 10:21:05',
    'updated_at' => '2026-09-23 10:21:05',
    'deleted_at' => NULL,
  ),
));
    }
}
