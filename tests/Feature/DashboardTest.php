<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPel;
use App\Models\Jurnal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\PiketJadwal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_guru_with_today_picket_is_redirected_to_the_piket_dashboard_after_login(): void
    {
        $guru = Guru::create([
            'nama_guru' => 'Budi',
        ]);

        $user = User::create([
            'username' => 'budi.guru',
            'password' => bcrypt('password'),
            'nama_user' => 'Budi Guru',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
        ]);

        PiketJadwal::create([
            'id_guru' => $guru->id_guru,
            'tanggal' => now()->toDateString(),
            'shift' => 'Pagi',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
            'jenis_tugas' => 'Piket KBM Pagi',
            'created_by' => $user->id_user,
        ]);

        $response = $this->post(route('login'), [
            'username' => 'budi.guru',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('piket.dashboard'));
    }

    public function test_guru_cannot_access_journal_form_outside_their_schedule_window(): void
    {
        $guru = Guru::create([
            'nama_guru' => 'Siti',
        ]);

        $mapel = Mapel::create([
            'nama_mapel' => 'Matematika',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'VII-A',
            'wali_kelas' => $guru->id_guru,
        ]);

        $user = User::create([
            'username' => 'siti.guru',
            'password' => bcrypt('password'),
            'nama_user' => 'Siti Guru',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
        ]);

        $jamMulai = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
        ]);

        $jamSelesai = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
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

        $this->actingAs($user);

        $response = $this->get(route('guru.jurnal.form', $jadwal));

        $response->assertRedirect(route('guru.jurnal.create'));
        $response->assertSessionHas('error', 'Jurnal hanya dapat diisi saat jadwal mengajar sedang berlangsung.');
    }

    public function test_admin_jadwal_changes_remove_stale_journal_entries(): void
    {
        $admin = User::create([
            'username' => 'admin.1',
            'password' => bcrypt('password'),
            'nama_user' => 'Admin Sistem',
            'role' => 'Admin',
        ]);
        $this->actingAs($admin);

        $guruLama = Guru::create(['nama_guru' => 'Guru Matematika']);
        $guruBaru = Guru::create(['nama_guru' => 'Guru Bahasa Inggris']);
        $mapelLama = Mapel::create(['nama_mapel' => 'Matematika']);
        $mapelBaru = Mapel::create(['nama_mapel' => 'Bahasa Inggris']);
        $kelas = Kelas::create(['nama_kelas' => 'XI-A']);

        $jamMulai = JamPel::create([
            'klp_hari' => 'Jumat',
            'jam_ke' => 5,
            'jenis' => 'pelajaran',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '13:45:00',
        ]);
        $jamSelesai = JamPel::create([
            'klp_hari' => 'Jumat',
            'jam_ke' => 5,
            'jenis' => 'pelajaran',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '13:45:00',
        ]);

        $jadwal = Jadwal::create([
            'id_guru' => $guruLama->id_guru,
            'id_mapel' => $mapelLama->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'hari' => 'Jumat',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);

        $jurnal = Jurnal::create([
            'id_jadwal' => $jadwal->id_jadwal,
            'id_kelas' => $kelas->id_kelas,
            'id_guru' => $guruLama->id_guru,
            'id_user' => $admin->id_user,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'tanggal' => today()->toDateString(),
            'materi' => 'Aljabar',
            'keterangan' => 'Materi awal',
            'status_guru' => 'Hadir',
            'ada_tugas' => 'Tidak',
            'jml_hadir' => 0,
            'jml_tidak_hadir' => 0,
            'status_validasi_guru' => 'Menunggu',
        ]);

        $this->put(route('admin.jadwal.update', $jadwal), [
            'id_guru' => $guruBaru->id_guru,
            'id_mapel' => $mapelBaru->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'hari' => 'Jumat',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);

        $this->assertSoftDeleted('jurnals', ['id_jurnal' => $jurnal->id_jurnal]);

        $this->delete(route('admin.jadwal.destroy', $jadwal));
        $this->assertSoftDeleted('jurnals', ['id_jurnal' => $jurnal->id_jurnal]);
    }

    public function test_teacher_index_hides_journals_without_active_schedule(): void
    {
        $guruAwal = Guru::create(['nama_guru' => 'Badru']);
        $guruBaru = Guru::create(['nama_guru' => 'Guru Lain']);
        $mapel = Mapel::create(['nama_mapel' => 'Matematika']);
        $kelas = Kelas::create(['nama_kelas' => 'XI-A']);

        $user = User::create([
            'username' => 'badru.guru',
            'password' => bcrypt('password'),
            'nama_user' => 'Badru',
            'role' => 'Guru',
            'id_guru' => $guruAwal->id_guru,
        ]);

        $jamMulai = JamPel::create([
            'klp_hari' => 'Jumat',
            'jam_ke' => 4,
            'jenis' => 'pelajaran',
            'jam_mulai' => '12:00:00',
            'jam_selesai' => '12:45:00',
        ]);
        $jamSelesai = JamPel::create([
            'klp_hari' => 'Jumat',
            'jam_ke' => 4,
            'jenis' => 'pelajaran',
            'jam_mulai' => '12:00:00',
            'jam_selesai' => '12:45:00',
        ]);

        $jadwal = Jadwal::create([
            'id_guru' => $guruAwal->id_guru,
            'id_mapel' => $mapel->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'hari' => 'Jumat',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);

        Jurnal::create([
            'id_jadwal' => $jadwal->id_jadwal,
            'id_kelas' => $kelas->id_kelas,
            'id_guru' => $guruAwal->id_guru,
            'id_user' => $user->id_user,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'tanggal' => today()->toDateString(),
            'materi' => 'Aljabar',
            'keterangan' => 'Materi lama',
            'status_guru' => 'Hadir',
            'ada_tugas' => 'Tidak',
            'jml_hadir' => 1,
            'jml_tidak_hadir' => 0,
            'status_validasi_guru' => 'Menunggu',
        ]);

        $jadwal->update([
            'id_guru' => $guruBaru->id_guru,
            'id_mapel' => $mapel->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'hari' => 'Jumat',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('guru.jurnal.index'));

        $response->assertDontSee('Aljabar');
    }

    public function test_teacher_create_hides_stale_schedule_without_jam(): void
    {
        $guru = Guru::create(['nama_guru' => 'Badru']);
        $mapel = Mapel::create(['nama_mapel' => 'Matematika']);
        $kelas = Kelas::create(['nama_kelas' => 'XI-A']);

        $jamMulai = JamPel::create([
            'klp_hari' => 'Jumat',
            'jam_ke' => 4,
            'jenis' => 'pelajaran',
            'jam_mulai' => '12:00:00',
            'jam_selesai' => '12:45:00',
        ]);
        $jamSelesai = JamPel::create([
            'klp_hari' => 'Jumat',
            'jam_ke' => 4,
            'jenis' => 'pelajaran',
            'jam_mulai' => '12:00:00',
            'jam_selesai' => '12:45:00',
        ]);

        $user = User::create([
            'username' => 'badru.guru.2',
            'password' => bcrypt('password'),
            'nama_user' => 'Badru',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
        ]);

        Jadwal::create([
            'id_guru' => $guru->id_guru,
            'id_mapel' => $mapel->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'hari' => 'Jumat',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);

        $jamMulai->delete();
        $jamSelesai->delete();

        $this->actingAs($user);

        $response = $this->get(route('guru.jurnal.create'));

        $response->assertDontSee('Jam Ke');
    }

    public function test_admin_can_edit_kbm_piket_hours_for_morning_and_afternoon(): void
    {
        $admin = User::create([
            'username' => 'admin_piket_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Admin Piket',
            'role' => 'Admin',
        ]);
        $guruPagi = Guru::create(['nama_guru' => 'Guru Pagi']);
        $guruSiang = Guru::create(['nama_guru' => 'Guru Siang']);
        $tanggal = now()->addDay()->toDateString();

        PiketJadwal::create([
            'id_guru' => $guruPagi->id_guru,
            'tanggal' => $tanggal,
            'shift' => 'Pagi',
            'jam_mulai' => '07:00',
            'jam_selesai' => '11:00',
            'jenis_tugas' => 'Piket KBM Pagi',
        ]);
        PiketJadwal::create([
            'id_guru' => $guruSiang->id_guru,
            'tanggal' => $tanggal,
            'shift' => 'Siang',
            'jam_mulai' => '11:00',
            'jam_selesai' => '15:00',
            'jenis_tugas' => 'Piket KBM Siang',
        ]);

        $response = $this->actingAs($admin)->put(
            route('admin.jadwal-piket.update', $tanggal),
            [
                'tanggal' => $tanggal,
                'jam_mulai_pagi' => '06:30',
                'jam_selesai_pagi' => '10:30',
                'jam_mulai_siang' => '12:30',
                'jam_selesai_siang' => '16:30',
                'pagi_petugas' => [$guruPagi->id_guru],
                'siang_petugas' => [$guruSiang->id_guru],
                'keterangan' => 'Jam diperbarui',
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('piket_jadwals', [
            'id_guru' => $guruPagi->id_guru,
            'jam_mulai' => '06:30',
            'jam_selesai' => '10:30',
        ]);
        $this->assertDatabaseHas('piket_jadwals', [
            'id_guru' => $guruSiang->id_guru,
            'jam_mulai' => '12:30',
            'jam_selesai' => '16:30',
        ]);
    }

    public function test_admin_can_create_kbm_piket_with_custom_hours(): void
    {
        $admin = User::create([
            'username' => 'admin_piket_create_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Admin Piket Create',
            'role' => 'Admin',
        ]);
        $guruPagi = Guru::create(['nama_guru' => 'Guru Pagi Baru']);
        $guruSiang = Guru::create(['nama_guru' => 'Guru Siang Baru']);
        $tanggal = now()->addDays(2)->toDateString();

        $response = $this->actingAs($admin)->post(
            route('admin.jadwal-piket.store'),
            [
                'tanggal' => $tanggal,
                'jam_mulai_pagi' => '06:45',
                'jam_selesai_pagi' => '10:45',
                'jam_mulai_siang' => '12:15',
                'jam_selesai_siang' => '16:15',
                'pagi_petugas' => [$guruPagi->id_guru],
                'siang_petugas' => [$guruSiang->id_guru],
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('piket_jadwals', [
            'id_guru' => $guruPagi->id_guru,
            'jam_mulai' => '06:45',
            'jam_selesai' => '10:45',
        ]);
        $this->assertDatabaseHas('piket_jadwals', [
            'id_guru' => $guruSiang->id_guru,
            'jam_mulai' => '12:15',
            'jam_selesai' => '16:15',
        ]);
    }

    public function test_admin_can_save_global_kbm_piket_hours_without_a_date(): void
    {
        $admin = User::create([
            'username' => 'admin_global_piket_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Admin Global Piket',
            'role' => 'Admin',
        ]);
        $guruPagi = Guru::create(['nama_guru' => 'Guru Pagi Global']);
        $guruSiang = Guru::create(['nama_guru' => 'Guru Siang Global']);

        PiketJadwal::create([
            'id_guru' => $guruPagi->id_guru,
            'tanggal' => now()->toDateString(),
            'shift' => 'Pagi',
            'jam_mulai' => '07:00',
            'jam_selesai' => '11:00',
            'jenis_tugas' => 'Piket KBM Pagi',
        ]);
        PiketJadwal::create([
            'id_guru' => $guruSiang->id_guru,
            'tanggal' => now()->toDateString(),
            'shift' => 'Siang',
            'jam_mulai' => '11:00',
            'jam_selesai' => '15:00',
            'jenis_tugas' => 'Piket KBM Siang',
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.jadwal-piket.hours.update'),
            [
                'jam_mulai_pagi' => '06:30',
                'jam_selesai_pagi' => '10:30',
                'jam_mulai_siang' => '12:30',
                'jam_selesai_siang' => '16:30',
            ]
        );

        $response->assertRedirect();
        $this->get(route('admin.jadwal-piket.index'))
            ->assertSee('Jam jadwal piket berhasil disimpan untuk setiap hari.');
        $this->assertDatabaseHas('app_settings', [
            'key' => 'piket.jam_mulai_pagi',
            'value' => '06:30',
        ]);
        $this->assertDatabaseHas('piket_jadwals', [
            'id_guru' => $guruPagi->id_guru,
            'jam_mulai' => '06:30',
            'jam_selesai' => '10:30',
        ]);
        $this->assertDatabaseHas('piket_jadwals', [
            'id_guru' => $guruSiang->id_guru,
            'jam_mulai' => '12:30',
            'jam_selesai' => '16:30',
        ]);
    }

    public function test_editing_jam_pelajaran_updates_existing_jadwal_time_references(): void
    {
        $admin = User::create([
            'username' => 'admin_jam_sync_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Admin Jam Sync',
            'role' => 'Admin',
        ]);
        $guru = Guru::create(['nama_guru' => 'Guru Jam Sync']);
        $mapel = Mapel::create(['nama_mapel' => 'Mapel Jam Sync']);
        $kelas = Kelas::create(['nama_kelas' => 'Kelas Jam Sync']);
        $jamMulaiLama = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '08:00:00',
        ]);
        $jamSelesaiLama = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 2,
            'jenis' => 'pelajaran',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:00:00',
        ]);
        $jadwal = Jadwal::create([
            'id_guru' => $guru->id_guru,
            'id_mapel' => $mapel->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jamMulaiLama->id_jam,
            'id_jam_selesai' => $jamSelesaiLama->id_jam,
            'hari' => 'Senin',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ]);

        $response = $this->actingAs($admin)->put(
            route('admin.jam.update', 'Senin-Kamis'),
            [
                'jam_masuk' => '06:30',
                'jam_pulang' => '08:30',
                'mode_durasi' => 'seragam',
                'durasi_jp' => 60,
                'durasi_khusus' => [],
                'istirahat' => [],
            ]
        );

        $response->assertRedirect(route('admin.jam.index'));
        $jadwal->refresh()->load(['jamMulai', 'jamSelesai']);

        $this->assertNotSame($jamMulaiLama->id_jam, $jadwal->id_jam_mulai);
        $this->assertNotSame($jamSelesaiLama->id_jam, $jadwal->id_jam_selesai);
        $this->assertSame('06:30:00', $jadwal->jamMulai->jam_mulai);
        $this->assertSame('08:30:00', $jadwal->jamSelesai->jam_selesai);
    }

    public function test_secretary_must_record_teacher_absence_with_a_note(): void
    {
        $sekretaris = User::create([
            'username' => 'sekretaris_absensi_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Sekretaris Absensi',
            'role' => 'Sekretaris',
        ]);
        $guru = Guru::create(['nama_guru' => 'Guru Absensi']);
        $mapel = Mapel::create(['nama_mapel' => 'Mapel Absensi']);
        $kelas = Kelas::create(['nama_kelas' => 'Kelas Absensi']);
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

        $payload = [
            'id_jadwal' => $jadwal->id_jadwal,
            'tanggal' => now()->toDateString(),
            'materi' => 'Materi Absensi',
            'status_guru' => 'Hadir',
            'ada_tugas' => 'Tidak',
            'jml_hadir' => 0,
            'jml_tidak_hadir' => 0,
        ];

        $this->actingAs($sekretaris);

        $this->post(route('sekretaris.isi-jurnal.store'), $payload)
            ->assertSessionHasErrors('status_guru');

        $payload['status_guru'] = 'Izin';
        $this->post(route('sekretaris.isi-jurnal.store'), $payload)
            ->assertSessionHasErrors('catatan_umum');

        $payload['catatan_umum'] = 'Guru sedang izin.';
        $this->post(route('sekretaris.isi-jurnal.store'), $payload)
            ->assertRedirect(route('sekretaris.validasi-jurnal'));

        $this->assertDatabaseHas('jurnals', [
            'id_jadwal' => $jadwal->id_jadwal,
            'status_guru' => 'Izin',
            'catatan_umum' => 'Guru sedang izin.',
        ]);
    }

}
