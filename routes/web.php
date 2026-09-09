<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JurnalController;
use App\Http\Controllers\Piket\DispenController;

// ====================
// ADMIN
// ====================

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

Route::get('/admin/jurnal', [JurnalController::class, 'index'])
    ->name('admin.jurnal.index');


// ====================
// STAFF PIKET
// ====================

Route::get('/piket/dispen', [DispenController::class, 'index'])
    ->name('piket.dispen.index');

Route::get('/piket/dispen/create', [DispenController::class, 'create'])
    ->name('piket.dispen.create');

Route::post('/piket/dispen', [DispenController::class, 'store'])
    ->name('piket.dispen.store');

    Route::get('/piket/dispen/{dispen}/edit', [DispenController::class, 'edit'])
    ->name('piket.dispen.edit');

Route::put('/piket/dispen/{dispen}', [DispenController::class, 'update'])
    ->name('piket.dispen.update');

Route::delete('/piket/dispen/{dispen}', [DispenController::class, 'destroy'])
    ->name('piket.dispen.destroy');