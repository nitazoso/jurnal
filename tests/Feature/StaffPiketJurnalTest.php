<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPel;
use App\Models\Kelas;
use App\Models\Jurnal;
use App\Models\Mapel;
use App\Models\PiketJadwal;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffPiketJurnalTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_piket_profile_renders_account_details_with_profile_heading(): void
    {
        $staff = $this->createStaffPiket();

        $this->actingAs($staff)
            ->get(route('piket.profil'))
            ->assertOk()
            ->assertSee('Profil')
            ->assertSee('Informasi Akun')
            ->assertSee('Petugas Piket')
            ->assertSee($staff->username)
            ->assertSee('Staff Piket')
            ->assertSee('piket-profile-page');
    }

    public function test_staff_piket_sees_all_current_teacher_schedules_for_the_selected_class(): void
    {
        $kelas = Kelas::create(['nama_kelas' => 'X-Test']);
        $this->createSchedule($kelas, 'Guru Satu', 'Matematika');
        $jadwalSelasa = $this->createSchedule($kelas, 'Guru Dua', 'Bahasa Indonesia');
        $jadwalSelasa->update(['hari' => 'Selasa']);
        $kelasLain = Kelas::create(['nama_kelas' => 'XI-Lain']);
        $this->createSchedule($kelasLain, 'Guru Lain', 'Fisika');
        $staff = $this->createStaffPiket();

        $this->actingAs($staff)
            ->get(route('piket.jurnal.create', ['id_kelas' => $kelas->id_kelas]))
            ->assertOk()
            ->assertSee('Guru Satu')
            ->assertSee('Matematika')
            ->assertSee('Guru Dua')
            ->assertSee('Bahasa Indonesia')
            ->assertSeeInOrder(['Senin', 'Selasa'])
            ->assertDontSee('Guru Lain')
            ->assertDontSee('Fisika');
    }

    public function test_staff_piket_journal_form_shows_five_students_then_offers_all(): void
    {
        $kelas = Kelas::create(['nama_kelas' => 'X-Lima']);
        $jadwal = $this->createSchedule($kelas, 'Guru Lima', 'Matematika');

        foreach (range(1, 6) as $number) {
            Siswa::create([
                'id_kelas' => $kelas->id_kelas,
                'nis' => '500'.$number,
                'no_presensi' => $number,
                'nama_siswa' => 'Siswa '.$number,
                'jenis_kelamin' => 'L',
            ]);
        }

        $response = $this->actingAs($this->createStaffPiket())
            ->get(route('piket.jurnal.form', $jadwal))
            ->assertOk()
            ->assertSee('Lihat Semua (6 siswa)');

        $this->assertSame(5, substr_count($response->getContent(), 'class="student-row visible-row"'));
    }

    public function test_guru_on_duty_sees_and_can_access_the_piket_journal_menu(): void
    {
        $kelas = Kelas::create(['nama_kelas' => 'X-Piket']);
        $jadwal = $this->createSchedule($kelas, 'Guru Bertugas', 'Bahasa');
        $guruPiket = User::create([
            'username' => 'guru_piket_hari_ini',
            'password' => bcrypt('password'),
            'nama_user' => 'Guru Piket',
            'role' => 'Guru',
            'id_guru' => $jadwal->id_guru,
        ]);
        PiketJadwal::create([
            'id_guru' => $jadwal->id_guru,
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'shift' => 'Pagi',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
            'jenis_tugas' => 'Piket KBM Pagi',
            'created_by' => $guruPiket->id_user,
        ]);

        $this->actingAs($guruPiket)
            ->get(route('piket.dashboard'))
            ->assertOk()
            ->assertSee('Isi Jurnal Guru');

        $this->get(route('piket.jurnal.create'))
            ->assertOk();

        $guruTanpaPiket = User::create([
            'username' => 'guru_tanpa_piket',
            'password' => bcrypt('password'),
            'nama_user' => 'Guru Bukan Piket',
            'role' => 'Guru',
        ]);

        $this->actingAs($guruTanpaPiket)
            ->get(route('piket.jurnal.create'))
            ->assertForbidden();
    }

    public function test_staff_piket_picker_reflects_admin_schedule_edits(): void
    {
        $kelas = Kelas::create(['nama_kelas' => 'X-Sinkron']);
        $jadwal = $this->createSchedule($kelas, 'Guru Lama', 'Mapel Lama');
        $guruBaru = Guru::create(['nama_guru' => 'Guru Baru']);
        $mapelBaru = Mapel::create(['nama_mapel' => 'Mapel Baru']);
        $jamSelesai = $jadwal->id_jam_selesai;
        $admin = User::create([
            'username' => 'admin_sinkron_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Admin Jadwal',
            'role' => 'Admin',
        ]);

        $this->actingAs($admin)->put(route('admin.jadwal.update', $jadwal), [
            'id_guru' => $guruBaru->id_guru,
            'id_mapel' => $mapelBaru->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jadwal->id_jam_mulai,
            'id_jam_selesai' => $jamSelesai,
            'hari' => 'Senin',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ])->assertRedirect();

        $this->actingAs($this->createStaffPiket())
            ->get(route('piket.jurnal.create', ['id_kelas' => $kelas->id_kelas]))
            ->assertOk()
            ->assertSee('Guru Baru')
            ->assertSee('Mapel Baru')
            ->assertDontSee('Guru Lama')
            ->assertDontSee('Mapel Lama');
    }

    public function test_staff_piket_can_fill_teacher_journal_and_journal_shows_filler_identity(): void
    {
        $kelas = Kelas::create(['nama_kelas' => 'X-Isi']);
        $jadwal = $this->createSchedule($kelas, 'Guru Sakit', 'IPA');
        $siswa = Siswa::create([
            'id_kelas' => $kelas->id_kelas,
            'nis' => '90123',
            'no_presensi' => 1,
            'nama_siswa' => 'Siswa Contoh',
            'jenis_kelamin' => 'L',
        ]);
        $staff = $this->createStaffPiket();

        $this->actingAs($staff)
            ->get(route('piket.jurnal.form', $jadwal))
            ->assertOk()
            ->assertSee('Kehadiran Guru')
            ->assertSee('Data Pengisi Jurnal')
            ->assertSee('Guru Sakit');

        $this->post(route('piket.jurnal.store'), [
            'id_jadwal' => $jadwal->id_jadwal,
            'tanggal' => today()->toDateString(),
            'materi' => 'Pengenalan materi IPA',
            'keterangan' => 'Guru berhalangan hadir.',
            'status_guru' => 'Sakit',
            'ada_tugas' => 'Ya',
            'deskripsi_tugas' => 'Latihan tambahan halaman 12',
            'absensi' => [$siswa->id_siswa => 'Hadir'],
        ])->assertRedirect(route('piket.jurnal.create', ['id_kelas' => $kelas->id_kelas]));

        $jurnal = Jurnal::where('id_jadwal', $jadwal->id_jadwal)->firstOrFail();
        $this->assertSame($staff->id_user, $jurnal->id_user);
        $this->assertSame('Sakit', $jurnal->status_guru);
        $this->assertSame('Tidak Hadir', $jurnal->status_kehadiran_validasi);
        $this->assertSame('Disetujui', $jurnal->status_validasi_guru);
        $this->assertTrue($jurnal->diisi_oleh_piket);
        $this->assertNotNull($jurnal->validated_at);
        $this->assertNull($jurnal->validated_by);
        $this->assertSame(1, $jurnal->jml_hadir);
        $this->assertSame(0, $jurnal->jml_tidak_hadir);

        $this->get(route('piket.jurnal.create', ['id_kelas' => $kelas->id_kelas]))
            ->assertOk()
            ->assertSee('Jurnal hari ini sudah diisi')
            ->assertSee('disabled');

        $this->get(route('piket.jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('Diisi Oleh')
            ->assertSee('Petugas Piket')
            ->assertSee($staff->username)
            ->assertSee('Staff Piket')
            ->assertSee('081234567890');

        $userGuruPengajar = User::create([
            'username' => 'guru_detail_piket',
            'password' => bcrypt('password'),
            'nama_user' => 'Guru Pengajar',
            'role' => 'Guru',
            'id_guru' => $jadwal->id_guru,
        ]);

        $this->actingAs($userGuruPengajar)
            ->get(route('guru.jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('DIISI OLEH PETUGAS PIKET')
            ->assertSee('Nama Pengisi')
            ->assertSee('Petugas Piket')
            ->assertSee('Status Guru pada Jurnal')
            ->assertSee('Sakit')
            ->assertSee('Hasil Validasi Kehadiran')
            ->assertSee('Tidak Hadir')
            ->assertSee('Keterangan Pembelajaran')
            ->assertSee('Guru berhalangan hadir.')
            ->assertSee('Latihan tambahan halaman 12')
            ->assertDontSee('Validator Sekretaris');

        $sekretaris = User::create([
            'username' => 'sekretaris_tes_piket',
            'password' => bcrypt('password'),
            'nama_user' => 'Sekretaris Test',
            'role' => 'Sekretaris',
            'id_kelas' => $kelas->id_kelas,
        ]);

        $this->actingAs($sekretaris)
            ->get(route('sekretaris.validasi-jurnal'))
            ->assertOk()
            ->assertSee('Diisi petugas piket')
            ->assertSee('validasi otomatis')
            ->assertSee('Lihat Isi Jurnal')
            ->assertDontSee('Validasi Guru Hadir');

        $this->get(route('sekretaris.validasi-jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('Otomatis oleh sistem')
            ->assertSee('Diisi petugas piket: Petugas Piket')
            ->assertSee('Latihan tambahan halaman 12');
    }

    private function createSchedule(Kelas $kelas, string $namaGuru, string $namaMapel): Jadwal
    {
        $guru = Guru::create(['nama_guru' => $namaGuru]);
        $mapel = Mapel::create(['nama_mapel' => $namaMapel]);
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

        return Jadwal::create([
            'id_guru' => $guru->id_guru,
            'id_mapel' => $mapel->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'hari' => 'Senin',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);
    }

    private function createStaffPiket(): User
    {
        return User::create([
            'username' => 'piket_'.uniqid(),
            'password' => bcrypt('password'),
            'nama_user' => 'Petugas Piket',
            'role' => 'Staff Piket',
            'no_wa' => '081234567890',
        ]);
    }
}