<?php

use Illuminate\Support\Facades\Route;

// Import Controller Admin (Porsi Nita & Marvel)
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\JamPelController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\UserController;

// Import Controller Guru (Porsi Mapeng, Wildan, Adip)
use App\Http\Controllers\Guru\JurnalGuruController;
use App\Http\Controllers\Guru\ProfilGuruController;
use App\Http\Controllers\Guru\RiwayatJurnalGuruController;

// Import Controller Sekretaris (Porsi Mapeng, Wildan, Adip)
use App\Http\Controllers\Sekretaris\ProfilSekreController;
use App\Http\Controllers\Sekretaris\RiwayatSekreController;
use App\Http\Controllers\Sekretaris\ValidasiSekreController;

// Import Controller Staff Piket (Porsi Wildan & Mapeng)
use App\Http\Controllers\Piket\DashboardPiketController;
use App\Http\Controllers\Piket\LaporanPiketController;

// -------------------------------------------------------------
// ROUTE BAWAAN STARTER KIT
// -------------------------------------------------------------
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// -------------------------------------------------------------
// ROUTE SETELAH LOGIN (AUTH & VERIFIED)
// -------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // Redirection pintar berdasarkan Role pengguna setelah Login
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
    // 1. GROUP ROLE ADMIN (Nita & Marvel)
    // =========================================================
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- PORSI NITA ---
        Route::resource('guru', GuruController::class);
        Route::resource('mapel', MapelController::class);

        // --- PORSI MARVEL ---
        Route::resource('kelas', KelasController::class)->parameters([
            'kelas' => 'kelas',
        ]);
        Route::resource('user', UserController::class);
        Route::resource('siswa', SiswaController::class);
        Route::resource('jam', JamPelController::class);
        Route::resource('jadwal', JadwalController::class);
    });

    // =========================================================
    // 2. GROUP ROLE GURU (Mapeng, Wildan, Adip)
    // =========================================================
    Route::middleware(['role:Guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', function () {
            return view('guru.dashboard');
        })->name('dashboard');

        // --- PORSI MAPENG ---
        Route::resource('jurnal', JurnalGuruController::class);

        // --- PORSI WILDAN ---
        Route::get('riwayat', [RiwayatJurnalGuruController::class, 'index'])->name('riwayat.index');
        Route::get('riwayat/{id}', [RiwayatJurnalGuruController::class, 'show'])->name('riwayat.show');

        // --- PORSI ADIP ---
        Route::get('profil', [ProfilGuruController::class, 'edit'])->name('profil.edit');
        Route::put('profil', [ProfilGuruController::class, 'update'])->name('profil.update');
    });

    // =========================================================
    // 3. GROUP ROLE SEKRETARIS (Mapeng, Wildan, Adip)
    // =========================================================
    Route::middleware(['role:Sekretaris'])->prefix('sekretaris')->name('sekretaris.')->group(function () {
        Route::get('/dashboard', function () {
            return view('sekretaris.dashboard');
        })->name('dashboard');

        // --- PORSI MAPENG ---
        Route::get('validasi', [ValidasiSekreController::class, 'index'])->name('validasi.index');
        Route::post('validasi/{id}/acc', [ValidasiSekreController::class, 'approve'])->name('validasi.approve');
        Route::post('validasi/{id}/tolak', [ValidasiSekreController::class, 'reject'])->name('validasi.reject');

        // --- PORSI WILDAN ---
        Route::get('riwayat', [RiwayatSekreController::class, 'index'])->name('riwayat.index');

        // --- PORSI ADIP ---
        Route::get('profil', [ProfilSekreController::class, 'edit'])->name('profil.edit');
        Route::put('profil', [ProfilSekreController::class, 'update'])->name('profil.update');
    });

    // =========================================================
    // 4. GROUP ROLE STAFF PIKET (Wildan & Mapeng)
    // =========================================================
    Route::middleware(['role:Staff Piket'])->prefix('piket')->name('piket.')->group(function () {
        Route::get('/dashboard', [DashboardPiketController::class, 'index'])->name('dashboard');

        // --- PORSI WILDAN ---
        Route::get('monitoring', [DashboardPiketController::class, 'monitoring'])->name('monitoring');

        // --- PORSI MAPENG ---
        Route::get('laporan', [LaporanPiketController::class, 'index'])->name('laporan.index');
        Route::get('laporan/cetak', [LaporanPiketController::class, 'cetak'])->name('laporan.cetak');
    });

});

// Load file settings bawaan
require __DIR__.'/settings.php';