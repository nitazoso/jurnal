<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPel;
use App\Models\Jurnal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SekretarisJurnalValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretary_validates_teacher_attendance_and_persists_validator_metadata(): void
    {
        $guru = Guru::create(['nama_guru' => 'Nita']);
        $userGuru = User::create([
            'username' => 'guru_validasi_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Guru Test',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
        ]);
        $kelas = Kelas::create(['nama_kelas' => 'X-Test', 'wali_kelas' => $guru->id_guru]);
        $mapel = Mapel::create(['nama_mapel' => 'Matematika']);
        $jamMulai = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
        ]);
        $jamSelesai = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 2,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:45:00',
            'jam_selesai' => '08:30:00',
        ]);
        $jadwal = Jadwal::create([
            'id_guru' => $guru->id_guru,
            'id_mapel' => $mapel->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'hari' => 'Senin',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);
        $sekretaris = User::create([
            'username' => 'sekretaris_validasi_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Nita Sekretaris',
            'role' => 'Sekretaris',
            'id_kelas' => $kelas->id_kelas,
        ]);
        $jurnal = Jurnal::create([
            'id_jadwal' => $jadwal->id_jadwal,
            'id_kelas' => $kelas->id_kelas,
            'id_guru' => $guru->id_guru,
            'id_user' => $userGuru->id_user,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'tanggal' => today(),
            'materi' => 'Pecahan',
            'keterangan' => 'Siswa berlatih soal pecahan',
            'status_guru' => 'Hadir',
            'ada_tugas' => 'Ya',
            'deskripsi_tugas' => 'Latihan halaman 10',
            'jml_hadir' => 28,
            'jml_tidak_hadir' => 2,
            'status_validasi_guru' => 'Menunggu',
        ]);

        $this->actingAs($sekretaris)
            ->patch(route('sekretaris.validasi-jurnal.update', $jurnal), [
                'status_kehadiran_validasi' => 'Tidak Hadir',
            ])
            ->assertRedirect();

        $jurnal->refresh();

        $this->assertSame('Disetujui', $jurnal->status_validasi_guru);
        $this->assertSame('Tidak Hadir', $jurnal->status_kehadiran_validasi);
        $this->assertSame($sekretaris->id_user, $jurnal->validated_by);
        $this->assertNotNull($jurnal->validated_at);
        $this->assertSame('Siswa berlatih soal pecahan', $jurnal->keterangan);

        $this->actingAs($sekretaris)
            ->get(route('sekretaris.validasi-jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('Keterangan Pembelajaran')
            ->assertSee('Siswa berlatih soal pecahan')
            ->assertSee('Ada Tugas?')
            ->assertSee('Latihan halaman 10')
            ->assertSee('VALIDASI OLEH')
            ->assertSee('Nita Sekretaris')
            ->assertSee('Tidak Hadir')
            ->assertDontSee('Catatan Umum');

        $this->actingAs($userGuru)
            ->get(route('guru.jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('VALIDASI OLEH SEKRETARIS')
            ->assertSee('Nita Sekretaris')
            ->assertSee('Keterangan Pembelajaran')
            ->assertSee('Siswa berlatih soal pecahan')
            ->assertSee('Tidak Hadir');

        $this->actingAs($userGuru)
            ->get(route('piket.jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('VALIDASI OLEH')
            ->assertSee('Nita Sekretaris')
            ->assertSee('Tidak Hadir');
    }
}