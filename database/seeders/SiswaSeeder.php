<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('siswas')->insert(array (
  0 => 
  array (
    'id_siswa' => 1,
    'nis' => '222310001',
    'id_kelas' => 1,
    'no_presensi' => 1,
    'nama_siswa' => 'Ahmad Rizky',
    'jenis_kelamin' => 'L',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-17 07:44:11',
    'deleted_at' => NULL,
  ),
  1 => 
  array (
    'id_siswa' => 2,
    'nis' => '222310002',
    'id_kelas' => 1,
    'no_presensi' => 2,
    'nama_siswa' => 'Bunga Lestari',
    'jenis_kelamin' => 'P',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-17 07:44:11',
    'deleted_at' => NULL,
  ),
  2 => 
  array (
    'id_siswa' => 3,
    'nis' => '222310003',
    'id_kelas' => 1,
    'no_presensi' => 3,
    'nama_siswa' => 'Candra Wijaya',
    'jenis_kelamin' => 'L',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-17 07:44:11',
    'deleted_at' => NULL,
  ),
  3 => 
  array (
    'id_siswa' => 4,
    'nis' => '222310004',
    'id_kelas' => 1,
    'no_presensi' => 4,
    'nama_siswa' => 'Dewi Anggraini',
    'jenis_kelamin' => 'P',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-17 07:44:11',
    'deleted_at' => NULL,
  ),
  4 => 
  array (
    'id_siswa' => 5,
    'nis' => '222310005',
    'id_kelas' => 2,
    'no_presensi' => 5,
    'nama_siswa' => 'Eko Prasetyo',
    'jenis_kelamin' => 'L',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-17 07:44:11',
    'deleted_at' => NULL,
  ),
  5 => 
  array (
    'id_siswa' => 6,
    'nis' => '222310006',
    'id_kelas' => 2,
    'no_presensi' => 6,
    'nama_siswa' => 'Fira Amelia',
    'jenis_kelamin' => 'P',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-17 07:44:11',
    'deleted_at' => NULL,
  ),
  6 => 
  array (
    'id_siswa' => 7,
    'nis' => '222310007',
    'id_kelas' => 2,
    'no_presensi' => 7,
    'nama_siswa' => 'Gilang Ramadhan',
    'jenis_kelamin' => 'L',
    'created_at' => '2026-09-17 07:44:11',
    'updated_at' => '2026-09-17 07:44:11',
    'deleted_at' => NULL,
  ),
  7 => 
  array (
    'id_siswa' => 8,
    'nis' => '1234567891',
    'id_kelas' => 5,
    'no_presensi' => 1,
    'nama_siswa' => 'Marvel Maulana Saputra',
    'jenis_kelamin' => 'L',
    'created_at' => '2026-09-24 07:07:38',
    'updated_at' => '2026-09-24 07:07:52',
    'deleted_at' => NULL,
  ),
  8 => 
  array (
    'id_siswa' => 9,
    'nis' => '876543212',
    'id_kelas' => 5,
    'no_presensi' => 2,
    'nama_siswa' => 'Maulana Qubro Alghozali',
    'jenis_kelamin' => 'L',
    'created_at' => '2026-09-24 07:09:13',
    'updated_at' => '2026-09-24 07:09:13',
    'deleted_at' => NULL,
  ),
  9 => 
  array (
    'id_siswa' => 10,
    'nis' => '0987654321',
    'id_kelas' => 5,
    'no_presensi' => 3,
    'nama_siswa' => 'Muhammad Raffi Nur Alfan',
    'jenis_kelamin' => 'L',
    'created_at' => '2026-09-24 07:10:02',
    'updated_at' => '2026-09-24 07:10:02',
    'deleted_at' => NULL,
  ),
));
    }
}
