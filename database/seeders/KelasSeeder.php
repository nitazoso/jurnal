<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kelases')->insert(
array (
  0 => 
  array (
    'id_kelas' => 1,
    'nama_kelas' => 'XII RPL 1',
    'qr_token' => 'Bh97w0xLEjwZSYfKVbjvCQKZJRt5cpsu6PDj26IcxZWyhJHG',
    'wali_kelas' => 87,
    'jumlah_siswa' => 35,
    'created_at' => '2026-09-27 04:02:34',
    'updated_at' => '2026-09-27 04:02:34',
    'deleted_at' => NULL,
  ),
  1 => 
  array (
    'id_kelas' => 2,
    'nama_kelas' => 'XII RPL 2',
    'qr_token' => 'IlPXPe2HMljRxUTPBKeG9UOuNZDssO975G2F040snmHqOts0',
    'wali_kelas' => 88,
    'jumlah_siswa' => 32,
    'created_at' => '2026-09-27 04:02:34',
    'updated_at' => '2026-09-27 04:02:34',
    'deleted_at' => NULL,
  ),
  2 => 
  array (
    'id_kelas' => 3,
    'nama_kelas' => 'XI DPIB 1',
    'qr_token' => 'D0QXYGiZKSAL4HacLDrCFjfBHVY1i16lpeknd6hwroS1xWxe',
    'wali_kelas' => 35,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-28 01:28:56',
    'updated_at' => '2026-09-28 01:28:56',
    'deleted_at' => NULL,
  ),
  3 => 
  array (
    'id_kelas' => 4,
    'nama_kelas' => 'XII AK 2',
    'qr_token' => '3T8g6N7ziyn7WG7UJINpOWMgqP0UNSfN0qt2J1cO7w3r2csw',
    'wali_kelas' => 15,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-28 01:29:13',
    'updated_at' => '2026-09-28 01:29:13',
    'deleted_at' => NULL,
  ),
  4 => 
  array (
    'id_kelas' => 5,
    'nama_kelas' => 'XII PPLG 8',
    'qr_token' => 'DrQTFqzERRpRne6DoXPNdSbahsNGByACYzWtOZ8Dn3fAYTxW',
    'wali_kelas' => 24,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-28 01:29:26',
    'updated_at' => '2026-09-28 01:29:26',
    'deleted_at' => NULL,
  ),
  5 => 
  array (
    'id_kelas' => 6,
    'nama_kelas' => 'XI DPIB 3',
    'qr_token' => 'xVdSjlGEnzDrkzheD0AxlIgbEzSVQY3xDaYcXguLSWohn996',
    'wali_kelas' => 43,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-28 01:29:43',
    'updated_at' => '2026-09-28 01:29:43',
    'deleted_at' => NULL,
  ),
  6 => 
  array (
    'id_kelas' => 7,
    'nama_kelas' => 'XI RPL 2',
    'qr_token' => 'YpmKH40Cf4sZP6K6BFzeAowyvPL471QvPxxbcstL81QaRG1t',
    'wali_kelas' => 9,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-28 03:33:11',
    'updated_at' => '2026-09-28 03:33:11',
    'deleted_at' => NULL,
  ),
)
        );
    }
}
