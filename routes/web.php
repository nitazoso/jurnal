<?php

use Illuminate\Support\Facades\Route;

// Import Controller Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\JamPelController;
use App\Http\Controllers\Admin\JurnalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\UserController;

// Import Controller Guru
use App\Http\Controllers\Guru\JurnalGuruController;
use App\Http\Controllers\Guru\ProfilGuruController;
use App\Http\Controllers\Guru\RiwayatJurnalGuruController;

// Import Controller Sekretaris
use App\Http\Controllers\Sekretaris\ProfilSekreController;
use App\Http\Controllers\Sekretaris\RiwayatSekreController;
use App\Http\Controllers\Sekretaris\ValidasiSekreController;

// Import Controller Staff Piket
use App\Http\Controllers\Piket\DashboardPiketController;
use App\Http\Controllers\Piket\DispenController;
use App\Http\Controllers\Piket\LaporanPiketController;

// =========================================================
// ROUTE DASHBOARD UTAMA (REDIRECTION BERDASARKAN ROLE)
// =========================================================
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('Guru')) {
        return redirect()->route('guru.dashboard');
    }

    if ($user->hasRole('Sekretaris')) {
        return redirect()->route('sekretaris.dashboard');
    }

    if ($user->hasRole('Staff Piket')) {
        return redirect()->route('piket.dashboard');
    }

    return redirect()->route('login');
})->name('dashboard');

// =========================================================
// 1. GROUP ROLE ADMIN
// =========================================================
Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
    
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
    Route::resource('user', UserController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('jam', JamPelController::class);
    Route::resource('jadwal', JadwalController::class);
});

// =========================================================
// 2. GROUP ROLE SEKRETARIS
// =========================================================
Route::middleware(['role:Sekretaris'])->prefix('sekretaris')->name('sekretaris.')->group(function () {
    Route::get('/dashboard', function () {
        return view('sekretaris.dashboard');
    })->name('dashboard');

    Route::get('validasi', [ValidasiSekreController::class, 'index'])->name('validasi.index');
    Route::post('validasi/{id}/acc', [ValidasiSekreController::class, 'approve'])->name('validasi.approve');
    Route::post('validasi/{id}/tolak', [ValidasiSekreController::class, 'reject'])->name('validasi.reject');

    Route::get('riwayat', [RiwayatSekreController::class, 'index'])->name('riwayat.index');

    Route::get('profil', [ProfilSekreController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [ProfilSekreController::class, 'update'])->name('profil.update');
});

// =========================================================
// 3. GROUP ROLE STAFF PIKET
// =========================================================
Route::middleware(['role:Staff Piket'])->prefix('piket')->name('piket.')->group(function () {
    Route::get('/dashboard', [DashboardPiketController::class, 'index'])->name('dashboard');
    Route::get('monitoring', [DashboardPiketController::class, 'monitoring'])->name('monitoring');

    // Dispensasi Routes
    Route::get('dispen', [DispenController::class, 'index'])->name('dispen.index');
    Route::get('dispen/create', [DispenController::class, 'create'])->name('dispen.create');
    Route::post('dispen', [DispenController::class, 'store'])->name('dispen.store');
    Route::get('dispen/{dispen}/edit', [DispenController::class, 'edit'])->name('dispen.edit');
    Route::put('dispen/{dispen}', [DispenController::class, 'update'])->name('dispen.update');
    Route::delete('dispen/{dispen}', [DispenController::class, 'destroy'])->name('dispen.destroy');

    // Laporan
    Route::get('laporan', [LaporanPiketController::class, 'index'])->name('laporan.index');
    Route::get('laporan/cetak', [LaporanPiketController::class, 'cetak'])->name('laporan.cetak');
});

// Load file settings bawaan
require __DIR__.'/settings.php';