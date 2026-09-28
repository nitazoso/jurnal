<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => '12345678',
                'nama_user' => 'Administrator',
                'role' => 'Admin',
                'id_guru' => null,
                'id_kelas' => null,
            ],
        );

        User::updateOrCreate(
            ['username' => 'sekretaris'],
            [
                'password' => '12345678',
                'nama_user' => 'Sekretaris',
                'role' => 'Sekretaris',
                'id_guru' => null,
                'id_kelas' => null,
            ],
        );

        Guru::query()
            ->orderBy('id_guru')
            ->limit(8)
            ->get()
            ->each(function (Guru $guru): void {
                User::updateOrCreate(
                    ['username' => 'guru_'.$guru->id_guru],
                    [
                        'password' => '12345678',
                        'nama_user' => $guru->nama_guru,
                        'role' => 'Guru',
                        'id_guru' => $guru->id_guru,
                        'id_kelas' => null,
                    ],
                );
            });
    }
}
