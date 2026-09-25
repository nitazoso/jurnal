<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleKesiswaanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_route_requires_authentication(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_logout_redirects_to_login_page(): void
    {
        $user = User::create([
            'username' => 'logout_test',
            'password' => bcrypt('password123'),
            'nama_user' => 'Logout Test',
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($user)
            ->post(route('logout'));

        $response->assertRedirect(route('login'));
    }

    public function test_staff_piket_role_is_rejected_for_user_creation(): void
    {
        $admin = User::create([
            'username' => 'admin_test',
            'password' => bcrypt('password123'),
            'nama_user' => 'Admin Test',
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.user.store'), [
                'username' => 'kesiswaan_test',
                'nama_user' => 'Kesiswaan Test',
                'password' => 'password123',
                'role' => 'Staff Piket',
                'id_guru' => null,
                'id_kelas' => null,
            ]);

        $response->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', [
            'username' => 'kesiswaan_test',
        ]);
    }

    public function test_admin_can_store_guru_without_nip(): void
    {
        $admin = User::create([
            'username' => 'admin_guru_test',
            'password' => bcrypt('password123'),
            'nama_user' => 'Admin Guru Test',
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.guru.store'), [
                'nama_guru' => 'Guru Tanpa NIP',
            ]);

        $response->assertRedirect(route('admin.guru.index'));

        $this->assertDatabaseHas('gurus', [
            'nama_guru' => 'Guru Tanpa NIP',
        ]);
    }

    public function test_guru_can_update_username_and_password_from_profile(): void
    {
        $guru = User::create([
            'username' => 'guru_lama',
            'password' => bcrypt('password123'),
            'nama_user' => 'Guru Lama',
            'role' => 'Guru',
            'id_guru' => null,
            'id_kelas' => null,
        ]);

        $response = $this->actingAs($guru)
            ->put(route('guru.profil.update'), [
                'username' => 'guru_baru',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('guru.profil'));

        $guru->refresh();

        $this->assertSame('guru_baru', $guru->username);
        $this->assertTrue(password_verify('newpassword123', $guru->password));
    }

    public function test_public_dispen_verification_page_can_be_opened_without_login(): void
    {
        $siswa = \App\Models\Siswa::create([
            'id_kelas' => 1,
            'nis' => '2001',
            'no_presensi' => 1,
            'nama_siswa' => 'Candra',
            'jenis_kelamin' => 'L',
        ]);

        $jamMulai = \App\Models\JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
            'durasi_menit' => 45,
        ]);

        $jamSelesai = \App\Models\JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 2,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:45:00',
            'jam_selesai' => '08:30:00',
            'durasi_menit' => 45,
        ]);

        $dispen = \App\Models\Dispen::create([
            'id_siswa' => $siswa->id_siswa,
            'jenis' => 'izin',
            'id_kesiswaan' => null,
            'submitted_by' => null,
            'tanggal' => now()->toDateString(),
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'alasan' => 'Kepentingan keluarga',
            'status' => 'menunggu',
            'token_verifikasi' => 'token-public-123',
        ]);

        $response = $this->get(route('dispen.verifikasi', $dispen->token_verifikasi));

        $response->assertOk();
        $response->assertSee('Verifikasi Dispensasi');
    }

    public function test_staff_piket_can_store_dispen_and_route_it_to_selected_recipient(): void
    {
        $piket = User::create([
            'username' => 'piket_dispen',
            'password' => bcrypt('password123'),
            'nama_user' => 'Staff Piket',
            'role' => 'Staff Piket',
        ]);

        $guru = Guru::create([
            'nama_guru' => 'Guru Wali Kelas',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'XI IPA 1',
            'wali_kelas' => $guru->id_guru,
            'jumlah_siswa' => 30,
        ]);

        $siswa = \App\Models\Siswa::create([
            'id_kelas' => $kelas->id_kelas,
            'nis' => '1001',
            'no_presensi' => 1,
            'nama_siswa' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
        ]);

        $petugas = User::create([
            'username' => 'recipient_target',
            'password' => bcrypt('password123'),
            'nama_user' => 'Petugas Target',
            'role' => 'Sekretaris',
        ]);

        $jamMulai = \App\Models\JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
            'durasi_menit' => 45,
        ]);

        $jamSelesai = \App\Models\JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 2,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:45:00',
            'jam_selesai' => '08:30:00',
            'durasi_menit' => 45,
        ]);

        $response = $this->actingAs($piket)
            ->post(route('piket.dispen.store'), [
                'id_kelas' => $kelas->id_kelas,
                'id_siswa' => $siswa->id_siswa,
                'id_kesiswaan' => $petugas->id_user,
                'tanggal' => now()->toDateString(),
                'id_jam_mulai' => $jamMulai->id_jam,
                'id_jam_selesai' => $jamSelesai->id_jam,
                'alasan' => 'Mengikuti lomba',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('dispens', [
            'id_siswa' => $siswa->id_siswa,
            'id_kesiswaan' => $petugas->id_user,
            'status' => 'menunggu',
            'alasan' => 'Mengikuti lomba',
        ]);
    }

    public function test_staff_piket_can_see_newly_submitted_journal(): void
    {
        $guru = Guru::create([
            'nama_guru' => 'Guru Baru',
        ]);

        $guruUser = User::create([
            'username' => 'guru_pengampu',
            'password' => bcrypt('password123'),
            'nama_user' => 'Guru Pengampu',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
            'id_kelas' => null,
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'X IPA 1',
            'wali_kelas' => $guru->id_guru,
            'jumlah_siswa' => 30,
        ]);

        $mapel = Mapel::create([
            'nama_mapel' => 'Biologi',
        ]);

        $jamMulai = \App\Models\JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
            'durasi_menit' => 45,
        ]);

        $jamSelesai = \App\Models\JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 2,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:45:00',
            'jam_selesai' => '08:30:00',
            'durasi_menit' => 45,
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

        Jurnal::create([
            'id_jadwal' => $jadwal->id_jadwal,
            'id_kelas' => $kelas->id_kelas,
            'id_guru' => $guru->id_guru,
            'id_user' => $guruUser->id_user,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'tanggal' => now()->toDateString(),
            'materi' => 'Ekosistem',
            'status_guru' => 'Hadir',
            'ada_tugas' => 'Ya',
            'deskripsi_tugas' => 'Membuat ringkasan',
            'jml_hadir' => 30,
            'jml_tidak_hadir' => 0,
            'status_validasi_guru' => 'Menunggu',
            'catatan_umum' => 'Baru masuk',
        ]);

        $piket = User::create([
            'username' => 'piket_test',
            'password' => bcrypt('password123'),
            'nama_user' => 'Staff Piket',
            'role' => 'Staff Piket',
            'id_guru' => $guru->id_guru,
            'id_kelas' => $kelas->id_kelas,
        ]);

        $response = $this->actingAs($piket)
            ->get(route('piket.jurnal.index', ['id_kelas' => $kelas->id_kelas]));

        $response->assertOk();
        $response->assertSee('Jurnal Kelas X IPA 1');
        $response->assertSee('Ekosistem');
    }
}
