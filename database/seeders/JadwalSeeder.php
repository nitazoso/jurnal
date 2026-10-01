<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwals')->insert(
array (
  2 => 
  array (
    'id_jadwal' => 3,
    'id_guru' => 35,
    'id_mapel' => 6,
    'id_kelas' => 3,
    'id_jam_mulai' => 21,
    'id_jam_selesai' => 24,
    'hari' => 'Senin',
    'semester' => 'Ganjil',
    'tahun_ajaran' => '2026/2027',
    'created_at' => '2026-09-28 01:30:57',
    'updated_at' => '2026-09-28 01:31:48',
    'deleted_at' => '2026-09-28 01:31:48',
  ),
  3 => 
  array (
    'id_jadwal' => 4,
    'id_guru' => 35,
    'id_mapel' => 1,
    'id_kelas' => 3,
    'id_jam_mulai' => 25,
    'id_jam_selesai' => 27,
    'hari' => 'Senin',
    'semester' => 'Ganjil',
    'tahun_ajaran' => '2026/2027',
    'created_at' => '2026-09-28 01:31:10',
    'updated_at' => '2026-09-28 01:31:10',
    'deleted_at' => NULL,
  ),
  4 => 
  array (
    'id_jadwal' => 5,
    'id_guru' => 23,
    'id_mapel' => 1,
    'id_kelas' => 1,
    'id_jam_mulai' => 21,
    'id_jam_selesai' => 24,
    'hari' => 'Senin',
    'semester' => 'Ganjil',
    'tahun_ajaran' => '2026/2027',
    'created_at' => '2026-09-28 01:31:32',
    'updated_at' => '2026-09-28 01:40:14',
    'deleted_at' => NULL,
  ),
  5 => 
  array (
    'id_jadwal' => 6,
    'id_guru' => 35,
    'id_mapel' => 3,
    'id_kelas' => 1,
    'id_jam_mulai' => 23,
    'id_jam_selesai' => 23,
    'hari' => 'Selasa',
    'semester' => 'Ganjil',
    'tahun_ajaran' => '2026/2027',
    'created_at' => '2026-09-28 01:32:19',
    'updated_at' => '2026-09-28 01:32:19',
    'deleted_at' => NULL,
  ),
  6 => 
  array (
    'id_jadwal' => 7,
    'id_guru' => 23,
    'id_mapel' => 6,
    'id_kelas' => 5,
    'id_jam_mulai' => 25,
    'id_jam_selesai' => 25,
    'hari' => 'Senin',
    'semester' => 'Ganjil',
    'tahun_ajaran' => '2026/2027',
    'created_at' => '2026-09-28 01:41:20',
    'updated_at' => '2026-09-28 01:41:20',
    'deleted_at' => NULL,
  ),
  7 => 
  array (
    'id_jadwal' => 8,
    'id_guru' => 23,
    'id_mapel' => 6,
    'id_kelas' => 5,
    'id_jam_mulai' => 27,
    'id_jam_selesai' => 27,
    'hari' => 'Senin',
    'semester' => 'Ganjil',
    'tahun_ajaran' => '2026/2027',
    'created_at' => '2026-09-28 01:41:53',
    'updated_at' => '2026-09-28 01:41:53',
    'deleted_at' => NULL,
  ),
  8 => 
  array (
    'id_jadwal' => 9,
    'id_guru' => 23,
    'id_mapel' => 6,
    'id_kelas' => 4,
    'id_jam_mulai' => 30,
    'id_jam_selesai' => 30,
    'hari' => 'Senin',
    'semester' => 'Ganjil',
    'tahun_ajaran' => '2026/2027',
    'created_at' => '2026-09-28 01:42:19',
    'updated_at' => '2026-09-28 01:42:19',
    'deleted_at' => NULL,
  ),
)
        );
    }
}
