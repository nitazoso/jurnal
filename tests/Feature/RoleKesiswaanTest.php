<?php

namespace Tests\Feature;

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

    public function test_kesiswaan_role_is_supported_for_user_creation(): void
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
                'role' => 'Kesiswaan',
                'id_guru' => null,
                'id_kelas' => null,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'username' => 'kesiswaan_test',
            'role' => 'Kesiswaan',
            'nama_user' => 'Kesiswaan Test',
        ]);
    }
}
