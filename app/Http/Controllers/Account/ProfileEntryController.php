<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;

/**
 * Menggantikan GET /profile supaya setiap peranan
 * melihat halaman profil menggunakan layout masing-masing.
 *
 * Admin    : admin.profile
 * Landlord : landlord.profile
 * Student  : ProfileController sedia ada
 */
class ProfileEntryController extends Controller
{
    public function __invoke(Request $request)
    {
        return match ($request->user()->role) {
            'admin' => view('admin.profile'),

            'landlord' => view('landlord.profile'),

            default => app(ProfileController::class)->edit($request),
        };
    }
}
