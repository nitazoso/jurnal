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

class GuruWaliKelasRekapTest extends TestCase
{
    use RefreshDatabase;

    public function test_wali_kelas_sees_journals_for_the_whole_class_but_not_other_classes(): void
    {
        $waliGuru = Guru::create(['nama_guru' => 'Guru Wali']);
        $pengajar = Guru::create(['nama_guru' => 'Guru Pengajar']);
        $guruLuar = Guru::create(['nama_guru' => 'Guru Kelas Lain']);
        $waliUser = $this->createGuruUser('wali_kelas_user', $waliGuru);
        $pengajarUser = $this->createGuruUser('pengajar_user', $pengajar);
        $guruLuarUser = $this->createGuruUser('guru_luar_user', $guruLuar);
        $kelasWali = Kelas::create(['nama_kelas' => 'X-Wali', 'wali_kelas' => $waliGuru->id_guru]);
        $kelasLain = Kelas::create(['nama_kelas' => 'XI-Lain', 'wali_kelas' => $guruLuar->id_guru]);

        $jurnalKelasWali = $this->createJurnal($kelasWali, $pengajar, $pengajarUser, 'Materi Kelas Wali');
        $this->createJurnal($kelasLain, $guruLuar, $guruLuarUser, 'Materi Kelas Lain');

        $this->actingAs($waliUser)
            ->get(route('guru.jurnal.wali-kelas-rekap'))
            ->assertOk()
            ->assertSee('Rekap Jurnal Wali Kelas')
            ->assertSee('X-Wali')
            ->assertSee('Guru Pengajar')
            ->assertSee('Materi Kelas Wali')
            ->assertDontSee('Materi Kelas Lain')
            ->assertSee(route('guru.jurnal.show', [
                'jurnal' => $jurnalKelasWali->id_jurnal,
                'from' => 'wali-kelas-rekap',
            ]))
            ->assertSee(route('guru.jurnal.wali-kelas-rekap'));

        $detailResponse = $this->get(route('guru.jurnal.show', [
            'jurnal' => $jurnalKelasWali->id_jurnal,
            'from' => 'wali-kelas-rekap',
            'bulan' => '2026-09',
            'status' => 'Menunggu',
        ]))
            ->assertOk()
            ->assertSee('Informasi Pembelajaran')
            ->assertSee('Materi Kelas Wali')
            ->assertSee('Kembali')
            ->assertDontSee('Kembali ke Daftar Jurnal')
            ->assertSee(route('guru.jurnal.wali-kelas-rekap', [
                'id_kelas' => $kelasWali->id_kelas,
                'bulan' => '2026-09',
                'status' => 'Menunggu',
            ]))
            ->assertSee('Rekap Jurnal Wali Kelas');

        $document = new \DOMDocument();
        @$document->loadHTML($detailResponse->getContent());
        $menuLinks = new \DOMXPath($document);
        $rekapMenuLink = $menuLinks->query('//a[.//span[normalize-space()="Rekap Jurnal Wali Kelas"]]')->item(0);
        $daftarJurnalMenuLink = $menuLinks->query('//a[.//span[normalize-space()="Daftar Jurnal"]]')->item(0);

        $this->assertSame('active', $rekapMenuLink?->getAttribute('class'));
        $this->assertSame('', $daftarJurnalMenuLink?->getAttribute('class'));

        $this->actingAs($guruLuarUser)
            ->get(route('guru.jurnal.show', $jurnalKelasWali->id_jurnal))
            ->assertForbidden();

        $this->actingAs($waliUser)
            ->get(route('guru.jurnal.wali-kelas-rekap', ['id_kelas' => $kelasLain->id_kelas]))
            ->assertNotFound();

        $this->assertDatabaseHas('jurnals', ['id_jurnal' => $jurnalKelasWali->id_jurnal]);
    }

    public function test_teacher_without_a_homeroom_class_cannot_open_the_class_recap(): void
    {
        $guru = Guru::create(['nama_guru' => 'Guru Bukan Wali']);
        $user = $this->createGuruUser('guru_bukan_wali', $guru);

        $this->actingAs($user)
            ->get(route('guru.jurnal.wali-kelas-rekap'))
            ->assertForbidden();

        $this->get(route('guru.jurnal.index'))
            ->assertOk()
            ->assertDontSee('Rekap Jurnal Wali Kelas');
    }

    private function createGuruUser(string $username, Guru $guru): User
    {
        return User::create([
            'username' => $username,
            'password' => bcrypt('password123'),
            'nama_user' => $guru->nama_guru,
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
        ]);
    }

    private function createJurnal(Kelas $kelas, Guru $guru, User $user, string $materi): Jurnal
    {
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

        return Jurnal::create([
            'id_jadwal' => $jadwal->id_jadwal,
            'id_kelas' => $kelas->id_kelas,
            'id_guru' => $guru->id_guru,
            'id_user' => $user->id_user,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'tanggal' => now()->toDateString(),
            'materi' => $materi,
            'status_guru' => 'Hadir',
            'ada_tugas' => 'Tidak',
            'jml_hadir' => 25,
            'jml_tidak_hadir' => 0,
            'status_validasi_guru' => 'Menunggu',
        ]);
    }
}