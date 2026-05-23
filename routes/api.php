<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletLinkController;
use Illuminate\Support\Facades\Route;

// Route pengguna
Route::prefix('pengguna')->group(function () {
    Route::get('/', [UserController::class, 'getUsers']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/sinkronisasi', [UserController::class, 'userSync']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
});

Route::prefix('tautan-outlet')->group(function () {
    // Route outlet
    Route::get('/', [OutletLinkController::class, 'getOutlets']);

    // Route tautan outlet
    Route::post('/', [OutletLinkController::class, 'store']);
    Route::get('/sinkronisasi', [OutletLinkController::class, 'getOutlets'])->name('tautan-outlet.sync');
    Route::get('/{uuid_outlet}', [OutletLinkController::class, 'getOutletLinks']);
    Route::put('/{outlet_link}', [OutletLinkController::class, 'update']);
    Route::delete('/{outlet_link}', [OutletLinkController::class, 'destroy']);
});