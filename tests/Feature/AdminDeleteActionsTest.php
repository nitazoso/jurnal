<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PiketJadwal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDeleteActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_jadwal_piket_page_renders_delete_modal_handler(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'nama_user' => 'Admin Utama',
            'role' => 'Admin',
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.jadwal-piket.index'));

        $response->assertOk();
        $response->assertSee('function openDeleteModal(url, date)');
    }

    public function test_admin_jadwal_piket_calendar_displays_selected_waka(): void
    {
        $admin = User::create([
            'username' => 'admin_waka_test',
            'password' => bcrypt('password'),
            'nama_user' => 'Admin Waka Test',
            'role' => 'Admin',
        ]);
        $waka = Guru::create(['nama_guru' => 'Waka Kesiswaan Terpilih']);

        PiketJadwal::create([
            'id_guru' => $waka->id_guru,
            'tanggal' => today()->toDateString(),
            'shift' => 'Waka',
            'jenis_tugas' => 'Piket Waka',
            'posisi' => 'Petugas',
            'created_by' => $admin->id_user,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.jadwal-piket.index', [
            'month' => now()->format('Y-m'),
        ]));

        $response->assertOk();
        $this->assertMatchesRegularExpression(
            '/<div class="schedule-mini-name">\s*Waka Kesiswaan Terpilih\s*<\/div>/',
            $response->getContent()
        );
    }

    public function test_admin_kelas_page_renders_delete_action(): void
    {
        $admin = User::create([
            'username' => 'admin2',
            'password' => bcrypt('password'),
            'nama_user' => 'Admin Kedua',
            'role' => 'Admin',
        ]);

        $guru = Guru::create([
            'nama_guru' => 'Guru Wali',
        ]);

        Kelas::create([
            'nama_kelas' => 'XII RPL 1',
            'wali_kelas' => $guru->id_guru,
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.kelas.index'));

        $response->assertOk();
        $response->assertSee('name="_method"');
        $response->assertSee('value="DELETE"');
        $response->assertSee('method="POST"');
    }
}
