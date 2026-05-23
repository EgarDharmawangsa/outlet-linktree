<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletLinkController;
use App\Http\Controllers\ProfileController;

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/masuk', [AuthController::class, 'index'])->name('masuk');
    Route::post('/masuk', [AuthController::class, 'authenticate'])->name('autentikasi');
});
    
Route::middleware('auth')->group(function () {
    // Beranda
    Route::get('/beranda', [DashboardController::class, 'index'])->name('beranda');

    // Pengguna
    Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');

    // Profil
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');
    Route::put('/profil/{user}', [ProfileController::class, 'update'])->name('profil.update');

    // Tautan outlet
    Route::prefix('tautan-outlet')->group(function () {
        Route::get('/', [OutletLinkController::class, 'index'])->name('tautan-outlet.index');
        Route::get('/{uuid_outlet}', [OutletLinkController::class, 'show'])->name('tautan-outlet.show');
    });

    // Logout
    Route::post('/keluar', [AuthController::class, 'logout'])->name('keluar');
});
   
// Route untuk halaman publi
Route::get('/{outlet_slug}', [OutletLinkController::class, 'showPublic'])->name('show-public');

// Fallback/redirect
Route::fallback([AuthController::class, 'redirect']);
