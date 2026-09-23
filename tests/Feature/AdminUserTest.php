<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_last_admin_cannot_be_deleted(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'password' => 'password123',
            'nama_user' => 'Admin Utama',
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.user.destroy', $admin));

        $response->assertRedirect(route('admin.user.index'));
        $response->assertSessionHas('error', 'Admin terakhir tidak dapat dihapus. Buat akun Admin lain terlebih dahulu.');
        $this->assertDatabaseHas('users', ['id_user' => $admin->id_user]);
    }

    public function test_last_admin_cannot_be_demoted(): void
    {
        $guru = Guru::create([
            'nama_guru' => 'Guru Pengganti',
        ]);

        $admin = User::create([
            'username' => 'admin',
            'password' => 'password123',
            'nama_user' => 'Admin Utama',
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.user.update', $admin), [
            'username' => 'admin',
            'nama_user' => 'Admin Utama',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Admin terakhir tidak dapat diganti rolenya. Buat akun Admin lain terlebih dahulu.');
        $this->assertDatabaseHas('users', [
            'id_user' => $admin->id_user,
            'role' => 'Admin',
        ]);
    }
}