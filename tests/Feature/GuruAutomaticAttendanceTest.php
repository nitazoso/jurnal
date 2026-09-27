<?php

namespace Tests\Feature;

use App\Models\DetailAbsensi;
use App\Models\Dispen;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPel;
use App\Models\Jurnal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GuruAutomaticAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_piket_sick_letter_and_approved_dispen_are_applied_to_teacher_attendance(): void
    {
        config(['app.jurnal_bebas_testing' => true]);
        Storage::fake('public');

        $guru = Guru::create(['nama_guru' => 'Guru Pengampu']);
        $kelas = Kelas::create(['nama_kelas' => 'X-IPA', 'wali_kelas' => $guru->id_guru]);
        $mapel = Mapel::create(['nama_mapel' => 'Biologi']);
        $jamMulai = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '00:00:00',
            'jam_selesai' => '23:59:59',
        ]);
        $jamSelesai = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 2,
            'jenis' => 'pelajaran',
            'jam_mulai' => '00:00:00',
            'jam_selesai' => '23:59:59',
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
        $siswaSakit = Siswa::create([
            'id_kelas' => $kelas->id_kelas,
            'nis' => '1001',
            'no_presensi' => 1,
            'nama_siswa' => 'Siswa Sakit',
            'jenis_kelamin' => 'P',
        ]);
        $siswaDispen = Siswa::create([
            'id_kelas' => $kelas->id_kelas,
            'nis' => '1002',
            'no_presensi' => 2,
            'nama_siswa' => 'Siswa Dispen',
            'jenis_kelamin' => 'L',
        ]);
        $siswaHadir = Siswa::create([
            'id_kelas' => $kelas->id_kelas,
            'nis' => '1003',
            'no_presensi' => 3,
            'nama_siswa' => 'Siswa Hadir',
            'jenis_kelamin' => 'L',
        ]);

        $piket = User::create([
            'username' => 'piket_surat_sakit_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Petugas Piket',
            'role' => 'Staff Piket',
        ]);
        $userGuru = User::create([
            'username' => 'guru_absensi_otomatis_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Guru Pengampu',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
        ]);

        $this->actingAs($piket)
            ->post(route('piket.dispen.sakit.store'), [
                'id_kelas' => $kelas->id_kelas,
                'id_siswa' => $siswaSakit->id_siswa,
                'tanggal' => today()->toDateString(),
                'alasan' => 'Sakit demam',
                'surat' => UploadedFile::fake()->image('surat-sakit.jpg'),
            ])
            ->assertRedirect(route('piket.dispen.sakit.create'));

        $laporanSakit = Dispen::query()
            ->where('id_siswa', $siswaSakit->id_siswa)
            ->where('jenis', 'sakit')
            ->firstOrFail();
        Storage::disk('public')->assertExists($laporanSakit->surat_path);

        $laporanDispen = Dispen::create([
            'id_siswa' => $siswaDispen->id_siswa,
            'jenis' => 'dispen',
            'tanggal' => today()->toDateString(),
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'alasan' => 'Kegiatan sekolah',
            'status' => 'disetujui',
        ]);

        $this->actingAs($userGuru)
            ->get(route('guru.jurnal.form', $jadwal))
            ->assertOk()
            ->assertSee('Sakit · Terkonfirmasi')
            ->assertSee('Dispen · Terkonfirmasi')
            ->assertSee('Lihat foto surat')
            ->assertSee($laporanSakit->surat_path);

        $this->post(route('guru.jurnal.store'), [
            'id_jadwal' => $jadwal->id_jadwal,
            'tanggal' => today()->toDateString(),
            'materi' => 'Ekosistem',
            'keterangan' => 'Pembelajaran berjalan lancar',
            'ada_tugas' => 'Tidak',
            'absensi' => [
                $siswaSakit->id_siswa => 'Hadir',
                $siswaDispen->id_siswa => 'Hadir',
                $siswaHadir->id_siswa => 'Hadir',
            ],
        ])->assertRedirect(route('guru.jurnal.index'));

        $jurnal = Jurnal::where('id_jadwal', $jadwal->id_jadwal)->firstOrFail();

        $this->assertSame('Menunggu', $jurnal->status_validasi_guru);
        $this->assertFalse($jurnal->diisi_oleh_piket);
        $this->assertSame(1, $jurnal->jml_hadir);
        $this->assertSame(2, $jurnal->jml_tidak_hadir);
        $this->assertDatabaseHas('detail_absensi', [
            'id_jurnal' => $jurnal->id_jurnal,
            'id_siswa' => $siswaSakit->id_siswa,
            'id_dispen' => $laporanSakit->id_dispen,
            'status' => 'Sakit',
        ]);
        $this->assertDatabaseHas('detail_absensi', [
            'id_jurnal' => $jurnal->id_jurnal,
            'id_siswa' => $siswaDispen->id_siswa,
            'id_dispen' => $laporanDispen->id_dispen,
            'status' => 'Dispen',
        ]);

        $this->actingAs($userGuru)
            ->get(route('guru.jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('attendance-detail-table-wrap')
            ->assertSee('attendance-detail-status sakit')
            ->assertSee('Daftar Kehadiran Semua Siswa · 3 siswa')
            ->assertSee('Siswa Sakit')
            ->assertSee('Siswa Dispen')
            ->assertSee('Siswa Hadir')
            ->assertSee('attendance-detail-status dispen')
            ->assertSee('attendance-detail-status hadir')
            ->assertSee('Lihat foto surat')
            ->assertSee($laporanSakit->surat_path);

        $this->get(route('piket.jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('Lihat foto surat')
            ->assertSee($laporanSakit->surat_path);
    }
}