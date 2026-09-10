<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JurnalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\JamPelController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\SiswaController;

use App\Http\Controllers\Guru\JurnalController as GuruJurnalController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');


// ====================
// JURNAL
// ====================

Route::get('/admin/jurnal', [JurnalController::class, 'index'])
    ->name('admin.jurnal.index');


// ====================
// USER
// ====================

Route::get('/admin/user', [UserController::class, 'index'])
    ->name('admin.user.index');

Route::get('/admin/user/create', [UserController::class, 'create'])
    ->name('admin.user.create');

Route::post('/admin/user', [UserController::class, 'store'])
    ->name('admin.user.store');

Route::get('/admin/user/{user}/edit', [UserController::class, 'edit'])
    ->name('admin.user.edit');

Route::put('/admin/user/{user}', [UserController::class, 'update'])
    ->name('admin.user.update');

Route::delete('/admin/user/{user}', [UserController::class, 'destroy'])
    ->name('admin.user.destroy');


// ====================
// GURU
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
// KELAS
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

Route::get('/admin/kelas/{kelas}/siswa', [SiswaController::class, 'index'])
    ->name('admin.kelas.siswa.index');

Route::get('/admin/kelas/{kelas}/siswa/create', [SiswaController::class, 'create'])
    ->name('admin.kelas.siswa.create');

Route::post('/admin/kelas/{kelas}/siswa', [SiswaController::class, 'store'])
    ->name('admin.kelas.siswa.store');

Route::get('/admin/kelas/{kelas}/siswa/{siswa}/edit', [SiswaController::class, 'edit'])
    ->name('admin.kelas.siswa.edit');

Route::put('/admin/kelas/{kelas}/siswa/{siswa}', [SiswaController::class, 'update'])
    ->name('admin.kelas.siswa.update');

Route::delete('/admin/kelas/{kelas}/siswa/{siswa}', [SiswaController::class, 'destroy'])
    ->name('admin.kelas.siswa.destroy');


// ====================
// MAPEL
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
// JAM PELAJARAN
// ====================

Route::get('/admin/jam', [JamPelController::class, 'index'])
    ->name('admin.jam.index');

Route::get('/admin/jam/create', [JamPelController::class, 'create'])
    ->name('admin.jam.create');

Route::post('/admin/jam', [JamPelController::class, 'store'])
    ->name('admin.jam.store');

Route::get('/admin/jam/{jam}/edit', [JamPelController::class, 'edit'])
    ->name('admin.jam.edit');

Route::put('/admin/jam/{jam}', [JamPelController::class, 'update'])
    ->name('admin.jam.update');

Route::delete('/admin/jam/{jam}', [JamPelController::class, 'destroy'])
    ->name('admin.jam.destroy');


// ====================
// JADWAL
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


// ====================
// PROFIL
// ====================

Route::get('/admin/profil', function () {
    return view('admin.profil');
})->name('admin.profil');


/*
|--------------------------------------------------------------------------
| GURU
|--------------------------------------------------------------------------
*/

// Dashboard Guru
Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])
    ->name('guru.dashboard');

// Jurnal Guru
Route::get('/guru/jurnal', [GuruJurnalController::class, 'index'])
    ->name('guru.jurnal.index');

Route::get('/guru/jurnal/create/{jadwal}', [GuruJurnalController::class, 'create'])
    ->name('guru.jurnal.create');

Route::post('/guru/jurnal', [GuruJurnalController::class, 'store'])
    ->name('guru.jurnal.store');

// Profil Guru
Route::get('/guru/profil', function () {
    return view('guru.profil');
})->name('guru.profil');