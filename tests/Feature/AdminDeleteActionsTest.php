<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
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
