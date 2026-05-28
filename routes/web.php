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


    // Ini untuk API
    Route::prefix('api')->group(function () {
        Route::prefix('pengguna')->group(function () {
            Route::get('/', [UserController::class, 'getUsers']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/sinkronisasi', [UserController::class, 'userSync']);
            Route::put('/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
        });
        
        Route::prefix('tautan-outlet')->group(function () {
            // Route outlet
            Route::get('/', [OutletLinkController::class, 'getOutlets'])->name('tautan-outlet.index');
        
            // Route tautan outlet
            Route::post('/', [OutletLinkController::class, 'store']);
            Route::get('/sinkronisasi', [OutletLinkController::class, 'getOutlets'])->name('tautan-outlet.sync');

            // Route Diagram
            Route::get('/distribute-device/{uuid_outlet}', [OutletLinkController::class, 'getDistributeDevice']);
            Route::get('/top-click/{uuid_outlet}', [OutletLinkController::class, 'getTopClick']);
            Route::get('/daily-click/{uuid_outlet}', [OutletLinkController::class, 'getDailyClick']);

            Route::get('/{uuid_outlet}', [OutletLinkController::class, 'getOutletLinks']);
            Route::put('/{outlet_link}', [OutletLinkController::class, 'update']);
            Route::delete('/{outlet_link}', [OutletLinkController::class, 'destroy']);
        });
    });
});

Route::post('/api/tautan-outlet/store-click', [OutletLinkController::class, 'storeClick']);
   
// Route untuk halaman publi
Route::get('/{outlet_slug}', [OutletLinkController::class, 'showPublic'])->name('show-public');

// Fallback/redirect
Route::fallback([AuthController::class, 'redirect']);



