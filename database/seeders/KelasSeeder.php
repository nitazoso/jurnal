<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            // Pertahankan data awal beserta wali kelas yang sudah ditetapkan.
            $kelasLama = [
                ['id_kelas' => 1, 'nama_kelas' => 'XII RPL 1', 'qr_token' => 'Bh97w0xLEjwZSYfKVbjvCQKZJRt5cpsu6PDj26IcxZWyhJHG', 'wali_kelas' => 87, 'jumlah_siswa' => 35],
                ['id_kelas' => 2, 'nama_kelas' => 'XII RPL 2', 'qr_token' => 'IlPXPe2HMljRxUTPBKeG9UOuNZDssO975G2F040snmHqOts0', 'wali_kelas' => 88, 'jumlah_siswa' => 32],
                ['id_kelas' => 3, 'nama_kelas' => 'XI DPIB 1', 'qr_token' => 'D0QXYGiZKSAL4HacLDrCFjfBHVY1i16lpeknd6hwroS1xWxe', 'wali_kelas' => 35, 'jumlah_siswa' => 0],
                ['id_kelas' => 4, 'nama_kelas' => 'XII AK 2', 'qr_token' => '3T8g6N7ziyn7WG7UJINpOWMgqP0UNSfN0qt2J1cO7w3r2csw', 'wali_kelas' => 15, 'jumlah_siswa' => 0],
                ['id_kelas' => 5, 'nama_kelas' => 'XII PPLG 8', 'qr_token' => 'DrQTFqzERRpRne6DoXPNdSbahsNGByACYzWtOZ8Dn3fAYTxW', 'wali_kelas' => 24, 'jumlah_siswa' => 0],
                ['id_kelas' => 6, 'nama_kelas' => 'XI DPIB 3', 'qr_token' => 'xVdSjlGEnzSVQY3xDaYcXguLSWohn996', 'wali_kelas' => 43, 'jumlah_siswa' => 0],
                ['id_kelas' => 7, 'nama_kelas' => 'XI RPL 2', 'qr_token' => 'YpmKH40Cf4sZP6K6BFzeAowyvPL471QvPxxbcstL81QaRG1t', 'wali_kelas' => 9, 'jumlah_siswa' => 0],
            ];

            foreach ($kelasLama as $data) {
                Kelas::firstOrCreate(['nama_kelas' => $data['nama_kelas']], $data);
            }

            // PDF jadwal berisi kelas X dan XI. Karena tidak memuat nama wali kelas,
            // wali_kelas dibiarkan null agar dapat diisi dengan penugasan yang benar.
            $namaKelas = [
                'X TKI 1', 'X TKI 2', 'X RPL 1', 'X RPL 2', 'X TKJ 1', 'X TKJ 2',
                'X BD 1', 'X BD 2', 'X BD 3', 'X MP 1', 'X MP 2', 'X MP 3', 'X MP 4',
                'X AK 1', 'X AK 2', 'X AK 3', 'X AK 4', 'X ULW', 'X DKV 1', 'X DKV 2',
                'X PSPT 1', 'X PSPT 2', 'X AN 1', 'X AN 2',
                'XI TKI 1', 'XI TKI 2', 'XI RPL 1', 'XI RPL 2', 'XI TKJ 1', 'XI TKJ 2',
                'XI BD 1', 'XI BD 2', 'XI BD 3', 'XI MP 1', 'XI MP 2', 'XI MP 3', 'XI MP 4',
                'XI AK 1', 'XI AK 2', 'XI AK 3', 'XI AK 4', 'XI ULW', 'XI DKV 1', 'XI DKV 2',
                'XI PSPT 1', 'XI PSPT 2', 'XI AN 1', 'XI AN 2',
            ];

            foreach ($namaKelas as $nama) {
                Kelas::firstOrCreate(
                    ['nama_kelas' => $nama],
                    ['wali_kelas' => null, 'jumlah_siswa' => 0],
                );
            }

            // Penugasan wali yang sudah ada di database tetapi belum tercatat
            // pada data seeder kelas. Nama guru dipakai agar tidak bergantung ID.
            $waliKelasDiketahui = [
                'X AK 3' => 'Anisa Kusumawati, S.Pd',
                'X RPL 2' => 'Lutfia Marsalina, S.Pd.I,M.Pd.',
                'XI DPIB 1' => 'Andri Krisdianto, SE.,M.Pd',
                'XI DPIB 3' => 'Ratih Dian Irawati, SE',
            ];

            foreach ($waliKelasDiketahui as $namaKelas => $namaGuru) {
                $kelas = Kelas::where('nama_kelas', $namaKelas)->first();
                $guruId = Guru::where('nama_guru', $namaGuru)->value('id_guru');

                if ($kelas && $guruId && ! $kelas->wali_kelas) {
                    $kelas->update(['wali_kelas' => $guruId]);
                }
            }

            $kelasTanpaWali = Kelas::whereNull('wali_kelas')->get()->shuffle();
            $waliYangBelumDitugaskan = Guru::whereNotIn(
                'id_guru',
                Kelas::whereNotNull('wali_kelas')->select('wali_kelas'),
            )->get()->shuffle();

            foreach ($kelasTanpaWali as $index => $kelas) {
                $guru = $waliYangBelumDitugaskan->get($index);
                if (! $guru) break;

                $kelas->update(['wali_kelas' => $guru->id_guru]);
            }
        });
    }
}
