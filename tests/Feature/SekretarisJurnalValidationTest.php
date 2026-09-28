<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPel;
use App\Models\Jurnal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SekretarisJurnalValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretary_history_keeps_validated_journals_grouped_by_day(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-09-27 18:30:00', 'UTC'));

        try {
            $guru = Guru::create(['nama_guru' => 'Nita']);
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
            $userGuru = User::create([
                'username' => 'guru_riwayat_test',
                'password' => bcrypt('password'),
                'nama_user' => 'Nita Guru',
                'role' => 'Guru',
                'id_guru' => $guru->id_guru,
            ]);
            $sekretaris = User::create([
                'username' => 'sekretaris_riwayat_test',
                'password' => bcrypt('password'),
                'nama_user' => 'Nita Sekretaris',
                'role' => 'Sekretaris',
                'id_kelas' => $kelas->id_kelas,
            ]);

            $journalCases = [
                ['2026-09-28', 'Jurnal hari ini', 'Disetujui', $sekretaris->id_user, false],
                ['2026-09-27', 'Jurnal kemarin', 'Disetujui', $sekretaris->id_user, false],
                ['2026-09-28', 'Jurnal menunggu', 'Menunggu', null, false],
                ['2026-09-28', 'Jurnal otomatis piket', 'Disetujui', null, true],
            ];

            foreach ($journalCases as [$tanggal, $materi, $status, $validatorId, $diisiOlehPiket]) {
                Jurnal::create([
                    'id_jadwal' => $jadwal->id_jadwal,
                    'id_kelas' => $kelas->id_kelas,
                    'id_guru' => $guru->id_guru,
                    'id_user' => $userGuru->id_user,
                    'id_jam_mulai' => $jamMulai->id_jam,
                    'id_jam_selesai' => $jamSelesai->id_jam,
                    'tanggal' => $tanggal,
                    'materi' => $materi,
                    'status_guru' => 'Hadir',
                    'ada_tugas' => 'Tidak',
                    'jml_hadir' => 30,
                    'jml_tidak_hadir' => 0,
                    'status_validasi_guru' => $status,
                    'validated_by' => $validatorId,
                    'validated_at' => $validatorId ? now() : null,
                    'diisi_oleh_piket' => $diisiOlehPiket,
                ]);
            }

            $this->actingAs($sekretaris)
                ->get(route('sekretaris.validasi-jurnal'))
                ->assertOk()
                ->assertSee('Jurnal hari ini')
                ->assertSee('Jurnal menunggu')
                ->assertSee('Jurnal otomatis piket')
                ->assertDontSee('Jurnal kemarin');

            $jadwal->delete();

            $this->actingAs($sekretaris)
                ->get(route('sekretaris.riwayat-jurnal'))
                ->assertOk()
                ->assertSee('Jurnal Tervalidasi')
                ->assertSee('Jurnal hari ini')
                ->assertSee('Jurnal kemarin')
                ->assertSee('Senin, 28 September 2026')
                ->assertSee('Minggu, 27 September 2026')
                ->assertSee('Nita Sekretaris')
                ->assertDontSee('Jurnal menunggu')
                ->assertDontSee('Jurnal otomatis piket');
        } finally {
            Carbon::setTestNow();
        }
    }

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

        $jurnalKemarin = $jurnal->replicate();
        $jurnalKemarin->tanggal = now('Asia/Jakarta')->subDay()->toDateString();
        $jurnalKemarin->materi = 'Jurnal kemarin';
        $jurnalKemarin->status_validasi_guru = 'Disetujui';
        $jurnalKemarin->validated_by = $sekretaris->id_user;
        $jurnalKemarin->validated_at = now();
        $jurnalKemarin->save();

        $this->actingAs($sekretaris)
            ->patch(route('sekretaris.validasi-jurnal.update', $jurnal), [
                'status_kehadiran_validasi' => 'Tidak Hadir',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Kehadiran guru berhasil divalidasi.');

        $jurnal->refresh();

        $this->assertSame('Disetujui', $jurnal->status_validasi_guru);
        $this->assertSame('Tidak Hadir', $jurnal->status_kehadiran_validasi);
        $this->assertSame($sekretaris->id_user, $jurnal->validated_by);
        $this->assertNotNull($jurnal->validated_at);
        $this->assertSame('Siswa berlatih soal pecahan', $jurnal->keterangan);

        $this->actingAs($sekretaris)
            ->get(route('sekretaris.validasi-jurnal'))
            ->assertOk()
            ->assertSee('Pecahan')
            ->assertDontSee('Jurnal kemarin');

        $this->get(route('sekretaris.riwayat-jurnal'))
            ->assertOk()
            ->assertSee('Pecahan')
            ->assertSee('Jurnal kemarin');

        $this->withSession(['success' => 'Kehadiran guru berhasil divalidasi.'])
            ->actingAs($sekretaris)
            ->get(route('sekretaris.validasi-jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('Keterangan Pembelajaran')
            ->assertSee('Siswa berlatih soal pecahan')
            ->assertSee('Ada Tugas?')
            ->assertSee('Latihan halaman 10')
            ->assertSee('VALIDASI OLEH')
            ->assertSee('Kehadiran guru berhasil divalidasi.')
            ->assertSee('data-success-toast', false)
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