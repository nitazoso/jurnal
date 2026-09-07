<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\JamPel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Master Data Guru
        $guru1 = Guru::create([
            'nip' => '198501012010011001',
            'nama_guru' => 'Budi Santoso, S.Pd',
            'no_hp' => '081234567890',
        ]);

        // 2. Seed Master Data Kelas (wali_kelas mengacu ke id_guru)
        $kelas12RPL1 = Kelas::create([
            'nama_kelas' => 'XII RPL 1',
            'wali_kelas' => $guru1->id_guru,
            'jumlah_siswa' => 36,
        ]);

        // 3. Seed Users (Sesuai Enum Role: Admin, Guru, Sekretaris, Staff Piket)
        $admin = User::create([
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'nama_user' => 'Admin Utama',
            'role' => 'Admin',
            'id_guru' => null,
            'id_kelas' => null,
        ]);

        $guruUser = User::create([
            'username' => 'budi_guru',
            'password' => Hash::make('password123'),
            'nama_user' => 'Budi Santoso, S.Pd',
            'role' => 'Guru',
            'id_guru' => $guru1->id_guru,
            'id_kelas' => null,
        ]);

        $sekreUser = User::create([
            'username' => 'sekre_12rpl1',
            'password' => Hash::make('password123'),
            'nama_user' => 'Nazwa Sekretaris',
            'role' => 'Sekretaris',
            'id_guru' => null,
            'id_kelas' => $kelas12RPL1->id_kelas,
        ]);

        $piketUser = User::create([
            'username' => 'wildan_piket',
            'password' => Hash::make('password123'),
            'nama_user' => 'Wildan Piket',
            'role' => 'Staff Piket',
            'id_guru' => null,
            'id_kelas' => null,
        ]);

        // 4. Seed Master Data Mapel
        Mapel::create([
            'nama_mapel' => 'Matematika Wajib',
        ]);

        // 5. Seed Master Data Jam Pelajaran
        JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
        ]);

        JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 2,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:45:00',
            'jam_selesai' => '08:30:00',
        ]);

        // 6. Seed Siswa Sampel
        Siswa::create([
            'nis' => '222310001',
            'id_kelas' => $kelas12RPL1->id_kelas,
            'nama_siswa' => 'Ahmad Rizky',
            'jenis_kelamin' => 'L',
        ]);
    }
}
