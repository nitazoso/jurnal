<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Jurnal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\PiketJadwal;
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

    public function test_all_role_profiles_render_the_same_account_information_fields(): void
    {
        $profiles = [
            ['Admin', 'admin.profil', 'Nomor Telepon'],
            ['Guru', 'guru.profil', null],
            ['Staff Piket', 'piket.profil', 'Nomor Telepon'],
            ['Kesiswaan', 'kesiswaan.profil', 'Nomor WhatsApp'],
            ['Sekretaris', 'sekretaris.profil', 'Kelas'],
        ];

        foreach ($profiles as $index => [$role, $routeName, $roleSpecificField]) {
            $user = User::create([
                'username' => 'profile_'.$index,
                'password' => bcrypt('password123'),
                'nama_user' => 'Pengguna '.$role,
                'role' => $role,
                'no_wa' => in_array($role, ['Admin', 'Staff Piket', 'Kesiswaan'], true) ? '081234567890' : null,
            ]);

            $response = $this->actingAs($user)
                ->get(route($routeName))
                ->assertOk()
                ->assertSee('Informasi Akun')
                ->assertSee('Nama Lengkap')
                ->assertSee('Username')
                ->assertSee('Role');

            if ($roleSpecificField) {
                $response->assertSee($roleSpecificField);
            }
        }
    }

    public function test_kesiswaan_can_update_username_and_password_from_profile(): void
    {
        $user = User::create([
            'username' => 'kesiswaan_lama',
            'password' => bcrypt('password123'),
            'nama_user' => 'Petugas Kesiswaan',
            'role' => 'Kesiswaan',
        ]);

        $this->actingAs($user)
            ->put(route('kesiswaan.profil.update'), [
                'username' => 'kesiswaan_baru',
                'password' => 'password-baru-123',
                'password_confirmation' => 'password-baru-123',
            ])
            ->assertRedirect(route('kesiswaan.profil'));

        $this->assertDatabaseHas('users', [
            'id_user' => $user->id_user,
            'username' => 'kesiswaan_baru',
        ]);
        $this->assertTrue(password_verify('password-baru-123', $user->fresh()->password));
    }

    public function test_teacher_on_piket_duty_is_displayed_as_guru_piket_without_changing_primary_role(): void
    {
        $guru = Guru::create([
            'nama_guru' => 'Guru Bertugas',
            'no_hp' => '081234567890',
        ]);
        $user = User::create([
            'username' => 'guru_bertugas',
            'password' => bcrypt('password123'),
            'nama_user' => 'Guru Bertugas',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
        ]);

        PiketJadwal::create([
            'id_guru' => $guru->id_guru,
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'shift' => 'Pagi',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
            'jenis_tugas' => 'Piket KBM Pagi',
            'created_by' => $user->id_user,
        ]);

        $this->actingAs($user)
            ->get(route('guru.profil'))
            ->assertOk()
            ->assertSee('GURU PIKET')
            ->assertSee('Guru Piket');

        $this->get(route('piket.profil'))
            ->assertOk()
            ->assertSee('Guru Piket');

        $this->assertSame('Guru', $user->fresh()->role);
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

    public function test_kesiswaan_user_requires_a_whatsapp_number(): void
    {
        $admin = User::create([
            'username' => 'admin_kesiswaan_test',
            'password' => bcrypt('password123'),
            'nama_user' => 'Admin Kesiswaan Test',
            'role' => 'Admin',
        ]);

        $missingPhone = $this->actingAs($admin)->post(route('admin.user.store'), [
            'username' => 'kesiswaan_tanpa_nomor',
            'nama_user' => 'Kesiswaan Tanpa Nomor',
            'password' => 'password123',
            'role' => 'Kesiswaan',
        ]);
        $missingPhone->assertSessionHasErrors('no_wa');

        $this->post(route('admin.user.store'), [
            'username' => 'kesiswaan_dengan_nomor',
            'nama_user' => 'Kesiswaan Dengan Nomor',
            'password' => 'password123',
            'role' => 'Kesiswaan',
            'no_wa' => '081234567890',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'username' => 'kesiswaan_dengan_nomor',
            'role' => 'Kesiswaan',
            'no_wa' => '081234567890',
        ]);
    }

    public function test_secretary_user_requires_and_saves_assigned_class(): void
    {
        $admin = User::create([
            'username' => 'admin_secretary_test',
            'password' => bcrypt('password123'),
            'nama_user' => 'Admin Secretary Test',
            'role' => 'Admin',
        ]);
        $kelas = Kelas::create(['nama_kelas' => 'VIII-B']);

        $form = $this->actingAs($admin)->get(route('admin.user.create'));
        $form->assertOk();
        $form->assertSee('Sekretaris untuk Kelas');
        $form->assertSee('VIII-B');

        $missingClass = $this->post(route('admin.user.store'), [
            'username' => 'sekretaris_tanpa_kelas',
            'password' => 'password123',
            'role' => 'Sekretaris',
        ]);
        $missingClass->assertSessionHasErrors('id_kelas');

        $response = $this->post(route('admin.user.store'), [
            'username' => 'sekretaris_viii_b',
            'password' => 'password123',
            'role' => 'Sekretaris',
            'id_kelas' => $kelas->id_kelas,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'username' => 'sekretaris_viii_b',
            'role' => 'Sekretaris',
            'id_kelas' => $kelas->id_kelas,
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

    public function test_admin_cannot_add_a_duplicate_teacher_name(): void
    {
        $admin = User::create([
            'username' => 'admin_duplicate_guru',
            'password' => bcrypt('password123'),
            'nama_user' => 'Admin Duplicate Guru',
            'role' => 'Admin',
        ]);
        Guru::create(['nama_guru' => 'Guru Contoh']);

        $response = $this->actingAs($admin)
            ->from(route('admin.guru.create'))
            ->followingRedirects()
            ->post(route('admin.guru.store'), [
                'nama_guru' => '  guru contoh  ',
            ]);

        $response->assertOk();
        $response->assertSee('Nama guru sudah terdaftar.');
        $this->assertSame(1, Guru::count());
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

    public function test_secretary_can_update_username_and_password_from_profile(): void
    {
        $sekretaris = User::create([
            'username' => 'sekretaris_lama',
            'password' => bcrypt('password123'),
            'nama_user' => 'Sekretaris Kelas',
            'role' => 'Sekretaris',
        ]);

        $response = $this->actingAs($sekretaris)
            ->put(route('sekretaris.profil.update'), [
                'username' => 'sekretaris_baru',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('sekretaris.profil'));

        $sekretaris->refresh();

        $this->assertSame('sekretaris_baru', $sekretaris->username);
        $this->assertTrue(password_verify('newpassword123', $sekretaris->password));
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

    public function test_staff_piket_store_dispen_determines_waka_from_schedule(): void
    {
        $piket = User::create([
            'username' => 'piket_dispen',
            'password' => bcrypt('password123'),
            'nama_user' => 'Staff Piket',
            'role' => 'Guru',
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
            'username' => 'waka_tugas',
            'password' => bcrypt('password123'),
            'nama_user' => 'Waka Tugas',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
            'no_wa' => '081234567890',
        ]);

        $tanggal = '2026-09-25';
        PiketJadwal::create([
            'id_guru' => $guru->id_guru,
            'tanggal' => $tanggal,
            'shift' => 'Waka',
            'jenis_tugas' => 'Piket Waka',
            'posisi' => 'Petugas',
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

        // Staff piket membuat dispen tanpa memilih Waka manual
        $response = $this->actingAs($piket)->post(route('piket.dispen.store'), [
            'id_kelas' => $kelas->id_kelas,
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => $tanggal,
            'id_jam_mulai' => $jamMulai->id_jam,
            'id_jam_selesai' => $jamSelesai->id_jam,
            'alasan' => 'Mengikuti lomba sains',
        ]);

        $response->assertRedirect(route('piket.dispen.index'));

        $this->assertDatabaseHas('dispens', [
            'id_siswa' => $siswa->id_siswa,
            'id_kesiswaan' => $petugas->id_user,
            'status' => 'menunggu',
            'alasan' => 'Mengikuti lomba sains',
        ]);

        $dispen = \App\Models\Dispen::firstOrFail();
        $this->assertNotEmpty($dispen->token_verifikasi);

        // WhatsApp redirect check
        $whatsappResponse = $this->actingAs($piket)->get(route('piket.dispen.whatsapp', $dispen));
        $whatsappResponse->assertRedirect();
        parse_str(parse_url($whatsappResponse->headers->get('Location'), PHP_URL_QUERY), $whatsappQuery);

        $this->assertSame('https://wa.me/6281234567890', strtok($whatsappResponse->headers->get('Location'), '?'));
        $this->assertStringContainsString(
            route('dispen.verifikasi', $dispen->token_verifikasi),
            $whatsappQuery['text']
        );
    }

    public function test_staff_piket_store_dispen_fails_gracefully_when_no_waka_scheduled(): void
    {
        $piket = User::create([
            'username' => 'piket_test_fail',
            'password' => bcrypt('password123'),
            'nama_user' => 'Staff Piket',
            'role' => 'Guru',
        ]);

        $guru = Guru::create(['nama_guru' => 'Guru']);
        $kelas = Kelas::create(['nama_kelas' => 'X-1', 'wali_kelas' => $guru->id_guru]);
        $siswa = \App\Models\Siswa::create([
            'id_kelas' => $kelas->id_kelas,
            'nis' => '1002',
            'no_presensi' => 2,
            'nama_siswa' => 'Siti',
            'jenis_kelamin' => 'P',
        ]);

        $jam = \App\Models\JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
        ]);

        $response = $this->actingAs($piket)->from(route('piket.dispen.create'))->post(route('piket.dispen.store'), [
            'id_kelas' => $kelas->id_kelas,
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => '2026-10-10', // Tanggal belum ada jadwal
            'id_jam_mulai' => $jam->id_jam,
            'id_jam_selesai' => $jam->id_jam,
            'alasan' => 'Test tanpa jadwal',
        ]);

        $response->assertRedirect(route('piket.dispen.create'));
        $response->assertSessionHasErrors('tanggal');
        $this->assertDatabaseMissing('dispens', ['alasan' => 'Test tanpa jadwal']);
    }

    public function test_waka_can_approve_dispen_via_verification_link_without_login(): void
    {
        $siswa = \App\Models\Siswa::create([
            'id_kelas' => 1,
            'nis' => '2002',
            'no_presensi' => 2,
            'nama_siswa' => 'Rian',
            'jenis_kelamin' => 'L',
        ]);

        $jam = \App\Models\JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
        ]);

        $waka = User::create([
            'username' => 'waka_acc',
            'password' => bcrypt('password123'),
            'nama_user' => 'Waka ACC',
            'role' => 'Kesiswaan',
            'no_wa' => '62811111111',
        ]);

        $dispen = \App\Models\Dispen::create([
            'id_siswa' => $siswa->id_siswa,
            'jenis' => 'dispen',
            'id_kesiswaan' => $waka->id_user,
            'submitted_by' => null,
            'tanggal' => '2026-09-25',
            'id_jam_mulai' => $jam->id_jam,
            'id_jam_selesai' => $jam->id_jam,
            'alasan' => 'Lomba PMR',
            'status' => 'menunggu',
            'token_verifikasi' => 'token-acc-12345',
        ]);

        $response = $this->post(route('dispen.verifikasi.approve', $dispen->token_verifikasi));
        $response->assertRedirect(route('dispen.verifikasi', $dispen->token_verifikasi));

        $dispen->refresh();
        $this->assertSame('disetujui', $dispen->status);
        $this->assertEquals($waka->id_user, $dispen->disetujui_oleh);
        $this->assertNotNull($dispen->disetujui_pada);

        // Link dibuka kembali: tombol sudah tidak ada dan menampilkan status sudah diverifikasi
        $showResponse = $this->get(route('dispen.verifikasi', $dispen->token_verifikasi));
        $showResponse->assertOk();
        $showResponse->assertSee('Dispensasi ini sudah diverifikasi');
        $showResponse->assertDontSee('SETUJUI DISPENSASI');
    }

    public function test_waka_rejection_requires_reason_and_updates_status(): void
    {
        $siswa = \App\Models\Siswa::create([
            'id_kelas' => 1,
            'nis' => '2003',
            'no_presensi' => 3,
            'nama_siswa' => 'Rini',
            'jenis_kelamin' => 'P',
        ]);

        $jam = \App\Models\JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
        ]);

        $waka = User::create([
            'username' => 'waka_tolak',
            'password' => bcrypt('password123'),
            'nama_user' => 'Waka Tolak',
            'role' => 'Kesiswaan',
            'no_wa' => '62822222222',
        ]);

        $dispen = \App\Models\Dispen::create([
            'id_siswa' => $siswa->id_siswa,
            'jenis' => 'dispen',
            'id_kesiswaan' => $waka->id_user,
            'submitted_by' => null,
            'tanggal' => '2026-09-25',
            'id_jam_mulai' => $jam->id_jam,
            'id_jam_selesai' => $jam->id_jam,
            'alasan' => 'Urusan pribadi',
            'status' => 'menunggu',
            'token_verifikasi' => 'token-tolak-12345',
        ]);

        // Tolak tanpa alasan -> gagal validasi
        $failResponse = $this->post(route('dispen.verifikasi.reject', $dispen->token_verifikasi), [
            'catatan_persetujuan' => '',
        ]);
        $failResponse->assertSessionHasErrors('catatan_persetujuan');

        $dispen->refresh();
        $this->assertSame('menunggu', $dispen->status);

        // Tolak dengan alasan -> berhasil
        $successResponse = $this->post(route('dispen.verifikasi.reject', $dispen->token_verifikasi), [
            'catatan_persetujuan' => 'Alasan tidak memenuhi kriteria dispensasi sekolah.',
        ]);
        $successResponse->assertRedirect(route('dispen.verifikasi', $dispen->token_verifikasi));

        $dispen->refresh();
        $this->assertSame('ditolak', $dispen->status);
        $this->assertSame('Alasan tidak memenuhi kriteria dispensasi sekolah.', $dispen->catatan_persetujuan);
        $this->assertEquals($waka->id_user, $dispen->disetujui_oleh);
        $this->assertNotNull($dispen->disetujui_pada);

        // Cegah proses ulang setelah ditolak
        $reApprove = $this->post(route('dispen.verifikasi.approve', $dispen->token_verifikasi));
        $reApprove->assertRedirect(route('dispen.verifikasi', $dispen->token_verifikasi));
        $dispen->refresh();
        $this->assertSame('ditolak', $dispen->status); // Tetap ditolak
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

        $jurnal = Jurnal::create([
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
            ->get(route('piket.jurnal.rekap'));

        $response->assertOk();
        $response->assertSee('Rekap Aktivitas Jurnal');
        $response->assertSee('Ekosistem');
        $response->assertSee(route('piket.jurnal.show', $jurnal), false);

        $this->get(route('piket.jurnal.show', $jurnal))
            ->assertOk()
            ->assertSee('Detail Jurnal')
            ->assertSee('Ekosistem')
            ->assertSee('Membuat ringkasan');

        $this->get('/piket/jurnal')->assertNotFound();
    }
}
