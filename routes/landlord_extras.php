<?php

use App\Http\Controllers\Landlord\DashboardController;
use App\Http\Controllers\Landlord\HouseQuickController;
use App\Http\Controllers\Landlord\InquiryController;
use App\Http\Controllers\Landlord\ReviewController;
use App\Http\Controllers\Landlord\TenancyController;
use App\Http\Middleware\EnsureLandlord;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', EnsureLandlord::class])->prefix('landlord')->name('landlord.x.')->group(function () {
    Route::get('overview', [DashboardController::class, 'index'])->name('overview');

    Route::post('houses/{house}/toggle-status', [HouseQuickController::class, 'toggleStatus'])->name('houses.toggle');
    Route::post('houses/{house}/duplicate', [HouseQuickController::class, 'duplicate'])->name('houses.duplicate');

    Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries');
    Route::patch('inquiries/{inquiry}', [InquiryController::class, 'update'])->name('inquiries.update');

    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews');
    Route::post('reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    Route::delete('reviews/{review}/reply', [ReviewController::class, 'destroyReply'])->name('reviews.reply.destroy');

    Route::get('tenancies', [TenancyController::class, 'index'])->name('tenancies.index');
    Route::get('tenancies/create', [TenancyController::class, 'create'])->name('tenancies.create');
    Route::post('tenancies', [TenancyController::class, 'store'])->name('tenancies.store');
    Route::get('tenancies/{tenancy}', [TenancyController::class, 'show'])->name('tenancies.show');
    Route::get('tenancies/{tenancy}/edit', [TenancyController::class, 'edit'])->name('tenancies.edit');
    Route::put('tenancies/{tenancy}', [TenancyController::class, 'update'])->name('tenancies.update');
    Route::post('tenancies/{tenancy}/end', [TenancyController::class, 'end'])->name('tenancies.end');
    Route::delete('tenancies/{tenancy}', [TenancyController::class, 'destroy'])->name('tenancies.destroy');
    Route::post('tenancies/{tenancy}/pay/{period}', [TenancyController::class, 'pay'])->where('period', '\d{4}-\d{2}')->name('tenancies.pay');
    Route::delete('tenancies/{tenancy}/pay/{period}', [TenancyController::class, 'unpay'])->where('period', '\d{4}-\d{2}')->name('tenancies.unpay');
});
