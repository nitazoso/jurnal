<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kelases')->insert(array (
  0 => 
  array (
    'id_kelas' => 1,
    'nama_kelas' => 'XII RPL 1',
    'qr_token' => 'y9bdUVBEG5UzA4WcKzrToe5x5qrg658kBT5frDETDsBxGalp',
    'wali_kelas' => 1,
    'jumlah_siswa' => 35,
    'created_at' => '2026-09-17 07:44:09',
    'updated_at' => '2026-09-23 01:19:12',
    'deleted_at' => '2026-09-23 01:19:12',
  ),
  1 => 
  array (
    'id_kelas' => 2,
    'nama_kelas' => 'XII RPL 2',
    'qr_token' => '7dAIX4X61Q1cy9rvB6eXxPMAaOYduAjDUYBaS0cSa1NHUmbP',
    'wali_kelas' => 2,
    'jumlah_siswa' => 32,
    'created_at' => '2026-09-17 07:44:09',
    'updated_at' => '2026-09-23 01:19:17',
    'deleted_at' => '2026-09-23 01:19:17',
  ),
  2 => 
  array (
    'id_kelas' => 3,
    'nama_kelas' => 'XI ATR 2',
    'qr_token' => 'cfx8iXTlUsJfMq4utnV3ShnVeXtBBOhmgcLFpZQTZ3eDD5Pm',
    'wali_kelas' => 3,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-18 06:51:43',
    'updated_at' => '2026-09-23 01:19:22',
    'deleted_at' => '2026-09-23 01:19:22',
  ),
  3 => 
  array (
    'id_kelas' => 4,
    'nama_kelas' => 'XI APAT 2',
    'qr_token' => '3viq9gYSN3qa42WOJFvEDOwfgaS9QnKjaBCd7rRDMqusZu1i',
    'wali_kelas' => 6,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-21 16:17:30',
    'updated_at' => '2026-09-23 01:19:26',
    'deleted_at' => '2026-09-23 01:19:26',
  ),
  4 => 
  array (
    'id_kelas' => 5,
    'nama_kelas' => 'XI RPL 2',
    'qr_token' => '63MlGMEPogmmduA0BUfBmPRDkbEZa1V8UHv1Hr83s7kaESxn',
    'wali_kelas' => 9,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 02:02:07',
    'updated_at' => '2026-09-23 02:02:07',
    'deleted_at' => NULL,
  ),
  5 => 
  array (
    'id_kelas' => 6,
    'nama_kelas' => 'XI RPL 1',
    'qr_token' => 'KcpHIfKljD5iuD6vEqwyF8eXCFfmWJFasAw2S8EF5JwM2Pmd',
    'wali_kelas' => 71,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 09:57:42',
    'updated_at' => '2026-09-23 09:57:42',
    'deleted_at' => NULL,
  ),
  6 => 
  array (
    'id_kelas' => 7,
    'nama_kelas' => 'XI TKI 1',
    'qr_token' => 'JI1nHnaPTdGFL2bVrrhFc88U9FMUN9R4A7vK6rJovKfJSipC',
    'wali_kelas' => 54,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 09:58:17',
    'updated_at' => '2026-09-23 09:58:17',
    'deleted_at' => NULL,
  ),
  7 => 
  array (
    'id_kelas' => 8,
    'nama_kelas' => 'XI TKI 2',
    'qr_token' => '4Hucau6ZjZcSoMwg8xx1tWvltiQfCPxqHfxao9KcLN4TO287',
    'wali_kelas' => 55,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 09:58:38',
    'updated_at' => '2026-09-23 09:58:38',
    'deleted_at' => NULL,
  ),
  8 => 
  array (
    'id_kelas' => 9,
    'nama_kelas' => 'XI TKJ 1',
    'qr_token' => 'hUrarEkqAteCmsjMBTVDhy5ZTCCFewJzqeZ0CgWXFGwednHy',
    'wali_kelas' => 41,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 09:59:08',
    'updated_at' => '2026-09-23 09:59:08',
    'deleted_at' => NULL,
  ),
  9 => 
  array (
    'id_kelas' => 10,
    'nama_kelas' => 'XI TKJ 2',
    'qr_token' => 'FXF3LOzNwQuijEtNu1sN0bpbKealJ6osocylBCQgEsiothuh',
    'wali_kelas' => 56,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 09:59:42',
    'updated_at' => '2026-09-23 09:59:42',
    'deleted_at' => NULL,
  ),
  10 => 
  array (
    'id_kelas' => 11,
    'nama_kelas' => 'XI BD 1',
    'qr_token' => 'WXxWC6gOJzDyQrVh6ZTzyNQiUg2d1ahvzXtGqdI4W5sEhHEx',
    'wali_kelas' => 57,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:00:10',
    'updated_at' => '2026-09-23 10:00:10',
    'deleted_at' => NULL,
  ),
  11 => 
  array (
    'id_kelas' => 12,
    'nama_kelas' => 'XI BD 2',
    'qr_token' => '2ZlllA2A2Z5V4COBsurRtJukHFG0fzzG01u4zhyae3c0CELw',
    'wali_kelas' => 58,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:00:35',
    'updated_at' => '2026-09-23 10:00:35',
    'deleted_at' => NULL,
  ),
  12 => 
  array (
    'id_kelas' => 13,
    'nama_kelas' => 'XI BD 3',
    'qr_token' => 'AEeodrgO9SCPZRbKcp5zObOHdpM1HhLxqIfMowMk3PUE4CmU',
    'wali_kelas' => 32,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:01:23',
    'updated_at' => '2026-09-23 10:01:23',
    'deleted_at' => NULL,
  ),
  13 => 
  array (
    'id_kelas' => 14,
    'nama_kelas' => 'XI MP 1',
    'qr_token' => 'Zlu0buOZIrz1liLmM9PhegeXq3UCRncgc7vYvRgGFrazX0I4',
    'wali_kelas' => 59,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:01:44',
    'updated_at' => '2026-09-23 10:01:44',
    'deleted_at' => NULL,
  ),
  14 => 
  array (
    'id_kelas' => 15,
    'nama_kelas' => 'XI MP 2',
    'qr_token' => 'AB0G56PBgrT8tnvtvMjB3rS7WprUhzUCE99X8b4h3V3Fb69X',
    'wali_kelas' => 60,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:02:06',
    'updated_at' => '2026-09-23 10:02:06',
    'deleted_at' => NULL,
  ),
  15 => 
  array (
    'id_kelas' => 16,
    'nama_kelas' => 'XI MP 3',
    'qr_token' => 'fdJE8kXmjrMQrEF7XERgZ1L2beIaHjnUcESMgq07RmLvCiRY',
    'wali_kelas' => 61,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:02:56',
    'updated_at' => '2026-09-23 10:02:56',
    'deleted_at' => NULL,
  ),
  16 => 
  array (
    'id_kelas' => 17,
    'nama_kelas' => 'XI MP 4',
    'qr_token' => 'a4VZDJyIAQSWr7gH1VycXJRkga2USzho8AMlZ0eB2i33BvuG',
    'wali_kelas' => 62,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:03:57',
    'updated_at' => '2026-09-23 10:03:57',
    'deleted_at' => NULL,
  ),
  17 => 
  array (
    'id_kelas' => 18,
    'nama_kelas' => 'XI AK 1',
    'qr_token' => 'z7dcWXiu15o61J56xSwd2UfUieJEUQgzayZyaii8n37gHNCX',
    'wali_kelas' => 63,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:04:50',
    'updated_at' => '2026-09-23 10:04:50',
    'deleted_at' => NULL,
  ),
  18 => 
  array (
    'id_kelas' => 19,
    'nama_kelas' => 'XI AK 2',
    'qr_token' => 'JDjTbjTBE3G7nAtFWPAGNWTOWNXvT4sV9n7Na2miwN77O9Q1',
    'wali_kelas' => 64,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:05:11',
    'updated_at' => '2026-09-23 10:05:11',
    'deleted_at' => NULL,
  ),
  19 => 
  array (
    'id_kelas' => 20,
    'nama_kelas' => 'XI AK 3',
    'qr_token' => 'ZQPhyRJNxgz3K81Ezl5QmOZi1kDq6KPqfwo6CCLuRlCKxbvK',
    'wali_kelas' => 65,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:05:44',
    'updated_at' => '2026-09-23 10:05:44',
    'deleted_at' => NULL,
  ),
  20 => 
  array (
    'id_kelas' => 21,
    'nama_kelas' => 'XI AK 4',
    'qr_token' => '65NVipkCeDyn27R6D0dQzeHE3hKohW53ryKZPYG19K7wItE6',
    'wali_kelas' => 45,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:06:07',
    'updated_at' => '2026-09-23 10:06:07',
    'deleted_at' => NULL,
  ),
  21 => 
  array (
    'id_kelas' => 22,
    'nama_kelas' => 'XI ULW',
    'qr_token' => 'r9MDZD9yd486APtcQKOCgBjezz8LHzZVtZXGogeFBhmsvwd6',
    'wali_kelas' => 66,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:06:37',
    'updated_at' => '2026-09-23 10:06:37',
    'deleted_at' => NULL,
  ),
  22 => 
  array (
    'id_kelas' => 23,
    'nama_kelas' => 'XI DKV 1',
    'qr_token' => '3ZrAUVSuKwrODECoGHn4XmQ5JSsrXih68wB5hxZvVr9x8jMy',
    'wali_kelas' => 67,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:06:58',
    'updated_at' => '2026-09-23 10:06:58',
    'deleted_at' => NULL,
  ),
  23 => 
  array (
    'id_kelas' => 24,
    'nama_kelas' => 'XI DKV 2',
    'qr_token' => '428TaR4aV3zo1rY5sKgSLRWFslVOlEzhQlyBdfYqzY2Dn0De',
    'wali_kelas' => 46,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:07:24',
    'updated_at' => '2026-09-23 10:07:24',
    'deleted_at' => NULL,
  ),
  24 => 
  array (
    'id_kelas' => 25,
    'nama_kelas' => 'XI PSPT 1',
    'qr_token' => 'Pj6GA925tefOQDELI6HZ3zUFWyBHtfsq9qf3gvOmgWOCrj6T',
    'wali_kelas' => 68,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:07:48',
    'updated_at' => '2026-09-23 10:07:48',
    'deleted_at' => NULL,
  ),
  25 => 
  array (
    'id_kelas' => 26,
    'nama_kelas' => 'XI PSPT 2',
    'qr_token' => '9GLWysih3v1Z9IfSBmOUw1GS7I5sre50gLlyQ2uyRijlTE8d',
    'wali_kelas' => 33,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:08:11',
    'updated_at' => '2026-09-23 10:08:11',
    'deleted_at' => NULL,
  ),
  26 => 
  array (
    'id_kelas' => 27,
    'nama_kelas' => 'XI AN 1',
    'qr_token' => 'Rzm3U4WnkHgHTBE44wm47T1ffjbRRHnaszS0sWOdrqt61LER',
    'wali_kelas' => 69,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:08:45',
    'updated_at' => '2026-09-23 10:08:45',
    'deleted_at' => NULL,
  ),
  27 => 
  array (
    'id_kelas' => 28,
    'nama_kelas' => 'XI AN 2',
    'qr_token' => '3CFLceR3hCJEAuYW31pWZTlWoGfO1hgwfRHturDS3SmPNdTc',
    'wali_kelas' => 70,
    'jumlah_siswa' => 0,
    'created_at' => '2026-09-23 10:09:08',
    'updated_at' => '2026-09-23 10:09:08',
    'deleted_at' => NULL,
  ),
));
    }
}
