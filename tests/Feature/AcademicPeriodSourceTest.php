<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\AcademicPeriod;
use App\Models\Jadwal;
use App\Models\JamPel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicPeriodSourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_headers_and_schedule_modal_use_the_latest_academic_period_saved_in_database(): void
    {
        $guru = Guru::create(['nama_guru' => 'Guru Periode']);
        $kelas = Kelas::create([
            'nama_kelas' => 'X-Periode',
            'wali_kelas' => $guru->id_guru,
        ]);
        $mapel = Mapel::create(['nama_mapel' => 'Matematika']);
        $jam = JamPel::create([
            'klp_hari' => 'Senin-Kamis',
            'jam_ke' => 1,
            'jenis' => 'pelajaran',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '07:45:00',
        ]);
        $userGuru = User::create([
            'username' => 'guru_periode',
            'password' => bcrypt('password123'),
            'nama_user' => 'Guru Periode',
            'role' => 'Guru',
            'id_guru' => $guru->id_guru,
        ]);
        $admin = User::create([
            'username' => 'admin_periode',
            'password' => bcrypt('password123'),
            'nama_user' => 'Admin Periode',
            'role' => 'Admin',
        ]);

        Jadwal::create([
            'id_guru' => $guru->id_guru,
            'id_mapel' => $mapel->id_mapel,
            'id_kelas' => $kelas->id_kelas,
            'id_jam_mulai' => $jam->id_jam,
            'id_jam_selesai' => $jam->id_jam,
            'hari' => 'Senin',
            'semester' => 'Genap',
            'tahun_ajaran' => '2027/2028',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.jadwal.index', ['id_kelas' => $kelas->id_kelas]))
            ->assertOk()
            ->assertSee(route('admin.jadwal.academic-period'))
            ->assertSee('Guru Periode')
            ->assertSee('X-Periode')
            ->assertSee('Matematika')
            ->assertSee('tahun_ajaran: "2027\/2028"', false)
            ->assertSee('semester: "Genap"', false);

        $this->get(route('admin.jadwal.academic-period'))
            ->assertOk()
            ->assertSee('Tahun Ajaran Aktif')
            ->assertSee('value="2027/2028"', false);

        $this->put(route('admin.jadwal.academic-period.update'), [
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2028/2029',
        ])->assertRedirect(route('admin.jadwal.index'));

        $this->assertDatabaseHas('academic_periods', [
            'id' => 1,
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2028/2029',
        ]);

        $this->actingAs($userGuru)
            ->get(route('guru.profil'))
            ->assertOk()
            ->assertSee('Ganjil 2028/2029');
    }
}