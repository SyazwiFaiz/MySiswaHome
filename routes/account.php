<?php

use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\ProfileEntryController;
use Illuminate\Support\Facades\Route;

// Pastikan fail ini di-require PALING BAWAH dalam routes/web.php supaya GET /profile menimpa yang lama.
Route::middleware('auth')->group(function () {
    Route::get('/profile', ProfileEntryController::class)->name('profile.edit');

    Route::patch('/account/profile', [ProfileController::class, 'update'])->name('profile.account.update');
    Route::put('/account/password', [ProfileController::class, 'password'])->name('profile.account.password');
    Route::delete('/account/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.account.avatar.destroy');
});
