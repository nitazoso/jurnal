<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPel;
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

        $mapel = \App\Models\Mapel::create([
            'nama_mapel' => 'Matematika',
        ]);

        $kelas = \App\Models\Kelas::create([
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
}
