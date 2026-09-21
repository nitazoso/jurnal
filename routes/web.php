<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JurnalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\JamPelController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\JadwalPiketController as AdminJadwalPiketController;
use App\Http\Controllers\Admin\JadwalKesiswaanController;
use App\Http\Controllers\Admin\SiswaController;

use App\Http\Controllers\Guru\JurnalController as GuruJurnalController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\PiketController as GuruPiketController;
use App\Http\Controllers\Kesiswaan\DashboardController as KesiswaanDashboardController;
use App\Http\Controllers\Kesiswaan\DispenController as KesiswaanDispenController;
use App\Http\Controllers\Piket\DispenController as PiketDispenController;
use App\Http\Controllers\Piket\DashboardController as PiketDashboardController;
use App\Http\Controllers\Piket\JurnalController as PiketJurnalController;
use App\Http\Controllers\Piket\JadwalPiketController;
use App\Http\Controllers\DispenVerificationController;
use App\Http\Controllers\Sekretaris\JurnalController as SekretarisJurnalController;

use App\Http\Controllers\StaffPiket\DashboardController as StaffPiketDashboardController;

// LOGIN

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Admin'])->group(function () {
    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // ====================
    // JURNAL ADMIN
    // ====================

    Route::get('/admin/jurnal', [JurnalController::class, 'index'])
        ->name('admin.jurnal.index');

    Route::get('/admin/jurnal/{jurnal}', [JurnalController::class, 'show'])
        ->name('admin.jurnal.show');

    // ====================
    // USER ADMIN
    // ====================

    Route::get('/admin/user', [UserController::class, 'index'])
        ->name('admin.user.index');

    Route::get('/admin/user/create', [UserController::class, 'create'])
        ->name('admin.user.create');

    Route::post('/admin/user', [UserController::class, 'store'])
        ->name('admin.user.store');

    Route::get('/admin/user/{user}/edit', [UserController::class, 'edit'])
        ->name('admin.user.edit');

    Route::get('/admin/user/{user}/receipt', [UserController::class, 'receipt'])
        ->name('admin.user.receipt');

    Route::put('/admin/user/{user}', [UserController::class, 'update'])
        ->name('admin.user.update');

    Route::delete('/admin/user/{user}', [UserController::class, 'destroy'])
        ->name('admin.user.destroy');

    // ====================
    // GURU ADMIN
    // ====================

    Route::get('/admin/guru', [GuruController::class, 'index'])
        ->name('admin.guru.index');

    Route::get('/admin/guru/create', [GuruController::class, 'create'])
        ->name('admin.guru.create');

    Route::post('/admin/guru', [GuruController::class, 'store'])
        ->name('admin.guru.store');

    Route::get('/admin/guru/{guru}/edit', [GuruController::class, 'edit'])
        ->name('admin.guru.edit');

    Route::put('/admin/guru/{guru}', [GuruController::class, 'update'])
        ->name('admin.guru.update');

    Route::delete('/admin/guru/{guru}', [GuruController::class, 'destroy'])
        ->name('admin.guru.destroy');

    // ====================
    // KELAS ADMIN
    // ====================

    Route::get('/admin/kelas', [KelasController::class, 'index'])
        ->name('admin.kelas.index');

    Route::get('/admin/kelas/create', [KelasController::class, 'create'])
        ->name('admin.kelas.create');

    Route::post('/admin/kelas', [KelasController::class, 'store'])
        ->name('admin.kelas.store');

    Route::get('/admin/kelas/{kelas}/edit', [KelasController::class, 'edit'])
        ->name('admin.kelas.edit');

    Route::put('/admin/kelas/{kelas}', [KelasController::class, 'update'])
        ->name('admin.kelas.update');

    Route::delete('/admin/kelas/{kelas}', [KelasController::class, 'destroy'])
        ->name('admin.kelas.destroy');

    // ====================
    // SISWA PER KELAS
    // ====================
    Route::get('/admin/kelas/siswa', [SiswaController::class, 'index'])
        ->name('admin.kelas.siswa');

    Route::get('/admin/siswa/create', [SiswaController::class, 'create'])
        ->name('admin.siswa.create');

    Route::post('/admin/siswa', [SiswaController::class, 'store'])
        ->name('admin.siswa.store');

    Route::get('/admin/siswa/{siswa}/edit', [SiswaController::class, 'edit'])
        ->name('admin.siswa.edit');

    Route::put('/admin/siswa/{siswa}', [SiswaController::class, 'update'])
        ->name('admin.siswa.update');

    Route::delete('/admin/siswa/{siswa}', [SiswaController::class, 'destroy'])
        ->name('admin.siswa.destroy');

    // ====================
    // MAPEL ADMIN
    // ====================

    Route::get('/admin/mapel', [MapelController::class, 'index'])
        ->name('admin.mapel.index');

    Route::get('/admin/mapel/create', [MapelController::class, 'create'])
        ->name('admin.mapel.create');

    Route::post('/admin/mapel', [MapelController::class, 'store'])
        ->name('admin.mapel.store');

    Route::get('/admin/mapel/{mapel}/edit', [MapelController::class, 'edit'])
        ->name('admin.mapel.edit');

    Route::put('/admin/mapel/{mapel}', [MapelController::class, 'update'])
        ->name('admin.mapel.update');

    Route::delete('/admin/mapel/{mapel}', [MapelController::class, 'destroy'])
        ->name('admin.mapel.destroy');

    // ====================
    // JAM PELAJARAN ADMIN
    // ====================

    Route::get('/admin/jam', [JamPelController::class, 'index'])
        ->name('admin.jam.index');

    Route::get('/admin/jam/create', [JamPelController::class, 'create'])
        ->name('admin.jam.create');

    Route::post('/admin/jam', [JamPelController::class, 'store'])
        ->name('admin.jam.store');
Route::get('/admin/jam/{klp_hari}/edit', [JamPelController::class, 'edit'])
    ->name('admin.jam.edit');

Route::put('/admin/jam/{klp_hari}', [JamPelController::class, 'update'])
    ->name('admin.jam.update');

Route::delete('/admin/jam/{klp_hari}', [JamPelController::class, 'destroy'])
    ->name('admin.jam.destroy');

    // ====================
    // JADWAL ADMIN
    // ====================

    Route::get('/admin/jadwal', [JadwalController::class, 'index'])
        ->name('admin.jadwal.index');

    Route::get('/admin/jadwal/create', [JadwalController::class, 'create'])
        ->name('admin.jadwal.create');


    Route::post('/admin/jadwal', [JadwalController::class, 'store'])
        ->name('admin.jadwal.store');

    Route::get('/admin/jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])
        ->name('admin.jadwal.edit');

    Route::put('/admin/jadwal/{jadwal}', [JadwalController::class, 'update'])
        ->name('admin.jadwal.update');


    Route::delete('/admin/jadwal/{jadwal}', [JadwalController::class, 'destroy'])
        ->name('admin.jadwal.destroy');

    Route::get('/admin/jadwal-piket', [AdminJadwalPiketController::class, 'index'])
        ->name('admin.jadwal-piket.index');
    Route::get('/admin/jadwal-piket/create', [AdminJadwalPiketController::class, 'create'])
        ->name('admin.jadwal-piket.create');
    Route::post('/admin/jadwal-piket', [AdminJadwalPiketController::class, 'store'])
        ->name('admin.jadwal-piket.store');
    Route::get('/admin/jadwal-piket/{jadwal}/edit', [AdminJadwalPiketController::class, 'edit'])
        ->name('admin.jadwal-piket.edit');
    Route::put('/admin/jadwal-piket/{jadwal}', [AdminJadwalPiketController::class, 'update'])
        ->name('admin.jadwal-piket.update');
    Route::delete('/admin/jadwal-piket/{jadwal}', [AdminJadwalPiketController::class, 'destroy'])
        ->name('admin.jadwal-piket.destroy');

    Route::get('/admin/jadwal-kesiswaan', [JadwalKesiswaanController::class, 'index'])
        ->name('admin.jadwal-kesiswaan.index');
    Route::get('/admin/jadwal-kesiswaan/create', [JadwalKesiswaanController::class, 'create'])
        ->name('admin.jadwal-kesiswaan.create');
    Route::post('/admin/jadwal-kesiswaan', [JadwalKesiswaanController::class, 'store'])
        ->name('admin.jadwal-kesiswaan.store');
    Route::get('/admin/jadwal-kesiswaan/{jadwalKesiswaan}/edit', [JadwalKesiswaanController::class, 'edit'])
        ->name('admin.jadwal-kesiswaan.edit');
    Route::put('/admin/jadwal-kesiswaan/{jadwalKesiswaan}', [JadwalKesiswaanController::class, 'update'])
        ->name('admin.jadwal-kesiswaan.update');
    Route::delete('/admin/jadwal-kesiswaan/{jadwalKesiswaan}', [JadwalKesiswaanController::class, 'destroy'])
        ->name('admin.jadwal-kesiswaan.destroy');

    // ====================
    // PROFIL ADMIN
    // ====================

    Route::get('/admin/profil', function () {
        return view('admin.profil');
    })->name('admin.profil');
});


/*
|--------------------------------------------------------------------------
| GURU
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Guru'])->group(function () {
    // Dashboard Guru
    Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])
        ->name('guru.dashboard');

    // ====================
    // JURNAL GURU
    // ====================
    Route::get('/guru/jurnal', [GuruJurnalController::class, 'index'])
        ->name('guru.jurnal.index');    

    Route::get('/guru/jurnal/create', [GuruJurnalController::class, 'create'])
        ->name('guru.jurnal.create');

    Route::get('/guru/jurnal/form/{jadwal}', [GuruJurnalController::class, 'form'])
        ->name('guru.jurnal.form');

    Route::post('/guru/jurnal', [GuruJurnalController::class, 'store'])
        ->name('guru.jurnal.store');

    Route::get('/guru/jurnal/{jurnal}', [GuruJurnalController::class, 'show'])
        ->name('guru.jurnal.show');

    // ====================
    // JADWAL PIKET GURU
    // ====================
    Route::get('/guru/piket', [GuruPiketController::class, 'index'])
        ->name('guru.piket.index');

    // ====================
    // PROFIL GURU
    // ====================
    Route::get('/guru/profil', function () {
        return view('guru.profil');
    })->name('guru.profil');

    Route::put('/guru/profil', function (Request $request) {
        $user = auth()->user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.($user->id_user ?? 0).',id_user'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->username = $validated['username'];

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('guru.profil')->with('success', 'Profil berhasil diperbarui.');
    })->name('guru.profil.update');
});

Route::middleware(['auth', 'role:Staff Piket'])->group(function () {
    Route::get('/piket/dashboard', [PiketDashboardController::class, 'index'])
        ->name('piket.dashboard');

    Route::get('/piket/jurnal', [PiketJurnalController::class, 'index'])
        ->name('piket.jurnal.index');

    Route::get('/piket/profil', function () {
        return view('piket.profil');
    })->name('piket.profil');

    Route::get('/piket/jadwal', [JadwalPiketController::class, 'index'])
        ->name('piket.jadwal.index');


    Route::get('/piket/dispen', [PiketDispenController::class, 'index'])->name('piket.dispen.index');
    Route::get('/piket/dispen/create', [PiketDispenController::class, 'create'])->name('piket.dispen.create');
    Route::post('/piket/dispen', [PiketDispenController::class, 'store'])->name('piket.dispen.store');
    Route::get('/piket/dispen/{dispen}/whatsapp', [PiketDispenController::class, 'whatsapp'])->name('piket.dispen.whatsapp');
    Route::get('/piket/dispen/{dispen}/edit', [PiketDispenController::class, 'edit'])->name('piket.dispen.edit');
    Route::put('/piket/dispen/{dispen}', [PiketDispenController::class, 'update'])->name('piket.dispen.update');
    Route::delete('/piket/dispen/{dispen}', [PiketDispenController::class, 'destroy'])->name('piket.dispen.destroy');
});

Route::get('/dispen/verifikasi/{token}', [DispenVerificationController::class, 'show'])
    ->name('dispen.verifikasi');
Route::post('/dispen/verifikasi/{token}/approve', [DispenVerificationController::class, 'approve'])
    ->name('dispen.verifikasi.approve');
Route::post('/dispen/verifikasi/{token}/reject', [DispenVerificationController::class, 'reject'])
    ->name('dispen.verifikasi.reject');

Route::middleware(['auth', 'role:Kesiswaan'])->group(function () {
    Route::get('/kesiswaan/dashboard', [KesiswaanDashboardController::class, 'index'])
        ->name('kesiswaan.dashboard');

    Route::get('/kesiswaan/profil', function () {
        return view('kesiswaan.profil');
    })->name('kesiswaan.profil');

    Route::get('/kesiswaan/dispen', [KesiswaanDispenController::class, 'index'])->name('kesiswaan.dispen.index');
    Route::get('/kesiswaan/dispen/{dispen}', [KesiswaanDispenController::class, 'show'])->name('kesiswaan.dispen.show');
    Route::post('/kesiswaan/dispen/{dispen}/approve', [KesiswaanDispenController::class, 'approve'])->name('kesiswaan.dispen.approve');
    Route::post('/kesiswaan/dispen/{dispen}/reject', [KesiswaanDispenController::class, 'reject'])->name('kesiswaan.dispen.reject');

});

/*
|--------------------------------------------------------------------------
| SEKRETARIS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Sekretaris'])->prefix('sekretaris')->name('sekretaris.')->group(function () {
    Route::get('/dashboard', [SekretarisJurnalController::class, 'dashboard'])->name('dashboard');
    Route::get('/validasi-jurnal', [SekretarisJurnalController::class, 'index'])->name('validasi-jurnal');
    Route::get('/validasi-jurnal/{jurnal}', [SekretarisJurnalController::class, 'show'])->name('validasi-jurnal.show');
    Route::patch('/validasi-jurnal/{jurnal}', [SekretarisJurnalController::class, 'validateJurnal'])->name('validasi-jurnal.update');
    Route::get('/isi-jurnal', [SekretarisJurnalController::class, 'create'])->name('isi-jurnal');
    Route::post('/isi-jurnal', [SekretarisJurnalController::class, 'store'])->name('isi-jurnal.store');
    Route::view('/profil', 'sekretaris.profil')->name('profil');
});

