<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FavouriteController;
use App\Http\Controllers\Admin\HouseController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rumah
    Route::get('houses/export', [HouseController::class, 'export'])->name('houses.export');
    Route::post('houses/bulk', [HouseController::class, 'bulk'])->name('houses.bulk');
    Route::post('houses/{house}/toggle-status', [HouseController::class, 'toggleStatus'])->name('houses.toggle')->withTrashed();
    Route::post('houses/{house}/restore', [HouseController::class, 'restore'])->name('houses.restore')->withTrashed();
    Route::resource('houses', HouseController::class)->withTrashed(['show', 'edit', 'update', 'destroy']);

    // Pengguna
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::post('users/bulk', [UserController::class, 'bulk'])->name('users.bulk');
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle');
    Route::resource('users', UserController::class);

    // Kegemaran
    Route::get('favourites', [FavouriteController::class, 'index'])->name('favourites.index');
    Route::post('favourites/bulk', [FavouriteController::class, 'bulk'])->name('favourites.bulk');
    Route::delete('favourites/{favourite}', [FavouriteController::class, 'destroy'])->name('favourites.destroy');

    // Log aktiviti
    Route::get('activity', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::post('activity/prune', [ActivityLogController::class, 'prune'])->name('activity.prune');
});
