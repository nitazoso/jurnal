<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_username_and_password_from_profile(): void
    {
        $admin = User::create([
            'username' => 'admin-lama',
            'password' => 'password-lama',
            'nama_user' => 'Administrator',
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.profil.update'), [
            'username' => 'admin-baru',
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ]);

        $response->assertRedirect(route('admin.profil'));
        $this->assertSame('admin-baru', $admin->fresh()->username);
        $this->assertTrue(Hash::check('password-baru-123', $admin->fresh()->password));
    }

    public function test_admin_can_leave_password_unchanged(): void
    {
        $admin = User::create([
            'username' => 'admin-lama',
            'password' => 'password-lama',
            'nama_user' => 'Administrator',
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.profil.update'), [
            'username' => 'admin-baru',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('admin.profil'));
        $this->assertSame('admin-baru', $admin->fresh()->username);
        $this->assertTrue(Hash::check('password-lama', $admin->fresh()->password));
    }
}