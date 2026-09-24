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
        // 1. Data guru utama
        $guruSendang = Guru::create([
            'nama_guru' => 'Sendang',
        ]);

        $guruBudi = Guru::create([
            'nama_guru' => 'Budi Santoso, S.Pd',
        ]);

        // 2. Data kelas
        $kelas12RPL1 = Kelas::create([
            'nama_kelas' => 'XII RPL 1',
            'wali_kelas' => $guruSendang->id_guru,
            'jumlah_siswa' => 35,
        ]);

        $kelas12RPL2 = Kelas::create([
            'nama_kelas' => 'XII RPL 2',
            'wali_kelas' => $guruBudi->id_guru,
            'jumlah_siswa' => 32,
        ]);

        // 3. User role admin dan guru
        $admin = User::withTrashed()->firstOrNew(['username' => 'admin']);
        $admin->password = Hash::make('password123');
        $admin->nama_user = 'Admin Utama';
        $admin->role = 'Admin';
        $admin->id_guru = null;
        $admin->id_kelas = null;
        $admin->deleted_at = null;
        $admin->save();

        User::create([
            'username' => 'sendang',
            'password' => Hash::make('password123'),
            'nama_user' => 'Sendang',
            'role' => 'Guru',
            'id_guru' => $guruSendang->id_guru,
            'id_kelas' => null,
        ]);

        User::create([
            'username' => 'budi_guru',
            'password' => Hash::make('password123'),
            'nama_user' => 'Budi Santoso, S.Pd',
            'role' => 'Guru',
            'id_guru' => $guruBudi->id_guru,
            'id_kelas' => null,
        ]);

        User::create([
            'username' => 'kesiswaan',
            'password' => Hash::make('password123'),
            'nama_user' => 'Kesiswaan SMK',
            'role' => 'Kesiswaan',
            'id_guru' => null,
            'id_kelas' => null,
        ]);

        User::create([
            'username' => 'sekre_12rpl1',
            'password' => Hash::make('password123'),
            'nama_user' => 'Nazwa Sekretaris',
            'role' => 'Sekretaris',
            'id_guru' => null,
            'id_kelas' => $kelas12RPL1->id_kelas,
        ]);

        User::create([
            'username' => 'wildan_piket',
            'password' => Hash::make('password123'),
            'nama_user' => 'Wildan Piket',
            'role' => 'Staff Piket',
            'id_guru' => null,
            'id_kelas' => null,
        ]);

        // 4. Mata pelajaran
        $mapelMatematika = Mapel::create(['nama_mapel' => 'Matematika Wajib']);
        $mapelBahasa = Mapel::create(['nama_mapel' => 'Bahasa Indonesia']);
        $mapelPkn = Mapel::create(['nama_mapel' => 'Pendidikan Pancasila']);

        // 5. Jam pelajaran
        $jam1 = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
        ]);

        $jam2 = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 2,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:45:00',
            'jam_selesai' => '08:30:00',
        ]);

        $jam3 = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 3,
            'jenis' => 'pelajaran',
            'jam_mulai' => '08:30:00',
            'jam_selesai' => '09:15:00',
        ]);

        $jam4 = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 4,
            'jenis' => 'pelajaran',
            'jam_mulai' => '09:30:00',
            'jam_selesai' => '10:15:00',
        ]);

        // 6. Siswa kelas
        $siswaData = [
            ['nis' => '222310001', 'nama_siswa' => 'Ahmad Rizky', 'jenis_kelamin' => 'L', 'id_kelas' => $kelas12RPL1->id_kelas],
            ['nis' => '222310002', 'nama_siswa' => 'Bunga Lestari', 'jenis_kelamin' => 'P', 'id_kelas' => $kelas12RPL1->id_kelas],
            ['nis' => '222310003', 'nama_siswa' => 'Candra Wijaya', 'jenis_kelamin' => 'L', 'id_kelas' => $kelas12RPL1->id_kelas],
            ['nis' => '222310004', 'nama_siswa' => 'Dewi Anggraini', 'jenis_kelamin' => 'P', 'id_kelas' => $kelas12RPL1->id_kelas],
            ['nis' => '222310005', 'nama_siswa' => 'Eko Prasetyo', 'jenis_kelamin' => 'L', 'id_kelas' => $kelas12RPL2->id_kelas],
            ['nis' => '222310006', 'nama_siswa' => 'Fira Amelia', 'jenis_kelamin' => 'P', 'id_kelas' => $kelas12RPL2->id_kelas],
            ['nis' => '222310007', 'nama_siswa' => 'Gilang Ramadhan', 'jenis_kelamin' => 'L', 'id_kelas' => $kelas12RPL2->id_kelas],
        ];

        foreach ($siswaData as $index => $siswa) {
            Siswa::create([
                'nis' => $siswa['nis'],
                'id_kelas' => $siswa['id_kelas'],
                'no_presensi' => $index + 1,
                'nama_siswa' => $siswa['nama_siswa'],
                'jenis_kelamin' => $siswa['jenis_kelamin'],
            ]);
        }

        // 7. Jadwal untuk guru Sendang
        $jadwalSendang = \App\Models\Jadwal::create([
            'id_guru' => $guruSendang->id_guru,
            'id_mapel' => $mapelMatematika->id_mapel,
            'id_kelas' => $kelas12RPL1->id_kelas,
            'id_jam_mulai' => $jam1->id_jam,
            'id_jam_selesai' => $jam2->id_jam,
            'hari' => 'Senin',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);

        \App\Models\Jadwal::create([
            'id_guru' => $guruSendang->id_guru,
            'id_mapel' => $mapelBahasa->id_mapel,
            'id_kelas' => $kelas12RPL1->id_kelas,
            'id_jam_mulai' => $jam3->id_jam,
            'id_jam_selesai' => $jam4->id_jam,
            'hari' => 'Rabu',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);

        // 8. Contoh jurnal guru Sendang agar dashboard guru terlihat ada data
        \App\Models\Jurnal::create([
            'id_jadwal' => $jadwalSendang->id_jadwal,
            'id_kelas' => $kelas12RPL1->id_kelas,
            'id_guru' => $guruSendang->id_guru,
            'id_user' => User::where('username', 'sendang')->first()->id_user,
            'id_jam_mulai' => $jam1->id_jam,
            'id_jam_selesai' => $jam2->id_jam,
            'tanggal' => now()->toDateString(),
            'materi' => 'Pengenalan materi persamaan linear dan latihan soal dasar',
            'status_guru' => 'Hadir',
            'ada_tugas' => 'Ya',
            'deskripsi_tugas' => 'Kerjakan 10 soal latihan pada buku paket halaman 15-18',
            'jml_hadir' => 4,
            'jml_tidak_hadir' => 1,
            'status_validasi_guru' => 'Menunggu',
            'catatan_umum' => 'Kelas kondusif dan antusiasme baik',
        ]);
    }
}
