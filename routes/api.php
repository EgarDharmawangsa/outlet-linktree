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

// Route outlet
Route::prefix('tautan-outlet')->group(function () {
    Route::get('/', [OutletLinkController::class, 'getOutlets']);
    Route::post('/', [OutletLinkController::class, 'store']);
    Route::get('/sinkronisasi', [OutletLinkController::class, 'getOutlets'])->name('tautan-outlet.sync');
    Route::get('/{uuid_outlet}', [OutletLinkController::class, 'getOutletLinks']);
    Route::put('/{outlet_link}', [OutletLinkController::class, 'update']);
    Route::delete('/{outlet_link}', [OutletLinkController::class, 'destroy']);
});

// Route tautan outlet