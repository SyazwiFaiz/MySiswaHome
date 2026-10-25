<?php

use App\Http\Controllers\Student\HouseExtrasController;
use App\Http\Controllers\Student\InquiryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('student')->name('student.x.')->group(function () {
    Route::get('houses/{house}/extras', [HouseExtrasController::class, 'show'])->name('extras');
    Route::post('houses/{house}/review', [HouseExtrasController::class, 'storeReview'])->name('review.store');
    Route::delete('houses/{house}/review', [HouseExtrasController::class, 'destroyReview'])->name('review.destroy');
    Route::post('houses/{house}/inquiry', [HouseExtrasController::class, 'storeInquiry'])->name('inquiry.store');
    Route::get('ratings', [HouseExtrasController::class, 'ratings'])->name('ratings');

    Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries');
    Route::delete('inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
});
