<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Menggantikan GET /profile: setiap peranan nampak profil dalam layout masing-masing.
 * Student kekal menggunakan ProfileController::edit() sedia ada (tiada perubahan).
 */
class ProfileEntryController extends Controller
{
    public function __invoke(Request $request)
    {
        return match ($request->user()->role) {
            'admin' => view('admin.profile'),
            'landlord' => view('landlord.profile'),
            default => app()->call([\App\Http\Controllers\ProfileController::class, 'edit']),
        };
    }
}
