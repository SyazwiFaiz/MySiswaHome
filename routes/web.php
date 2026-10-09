
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\FavouriteController;
use App\Models\House;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('landingpage');
});

// Main Dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'student' => redirect()->route('student.dashboard'),
        'landlord' => redirect()->route('landlord.create'),
        'admin' => redirect()->route('admin.dashboard'),
        default => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Student Dashboard + Search
Route::get('/student/dashboard', function () {
    $query = House::with('landlord')
        ->where('status', 'available');

    // Search Lokasi
    if (request('location')) {
        $location = request('location');

        $query->where(function ($q) use ($location) {
            $q->where('area', 'like', '%' . $location . '%')
                ->orWhere('address', 'like', '%' . $location . '%');
        });
    }

    // Search Bajet Maksimum
    if (request('price')) {
        $query->where(
            'monthly_rent',
            '<=',
            request('price')
        );
    }

    // Search Jenis Rumah
    if (request('type')) {
        $query->where(
            'property_type',
            request('type')
        );
    }

    // Get Houses
    $houses = $query
        ->latest()
        ->paginate(6)
        ->withQueryString();

    return view('dashboard', compact('houses'));
})->middleware(['auth', 'verified'])->name('student.dashboard');

// Landlord Dashboard
Route::get('/landlord/create', function () {
    return view('landlord.create');
})->middleware(['auth', 'verified'])->name('landlord.create');

// House Management for Landlords
Route::middleware(['auth', 'verified'])->group(function () {

    // Manage House
    Route::get('/landlord/manage', [
        HouseController::class,
        'index'
    ])->name('landlord.manage');

    // Edit House
    Route::get('/landlord/edit/{house}', [
        HouseController::class,
        'edit'
    ])->name('landlord.edit');

    // Update House
    Route::put('/landlord/update/{house}', [
        HouseController::class,
        'update'
    ])->name('landlord.update');

    // Delete House
    Route::delete('/landlord/delete/{house}', [
        HouseController::class,
        'destroy'
    ])->name('landlord.delete');

    // Create House Page
    Route::get('/landlord/houses/create', [
        HouseController::class,
        'create'
    ])->name('landlord.houses.create');

    // Store House
    Route::post('/landlord/houses', [
        HouseController::class,
        'store'
    ])->name('landlord.houses.store');
});

// Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

// Profile
Route::middleware('auth')->group(function () {

    // Profile Edit
    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');

    // Profile Update
    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');

    // Profile Delete
    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])->name('profile.destroy');

    // Upload Avatar
    Route::post('/profile/avatar', [
        ProfileController::class,
        'updateAvatar'
    ])->name('profile.avatar');
});

// Favourite
Route::middleware('auth')->group(function () {

    // Favourite Page
    Route::get('/favourite', [
        FavouriteController::class,
        'index'
    ])->name('favourite');

    // Toggle Favourite
    Route::post('/favourite/{house}/toggle', [
        FavouriteController::class,
        'toggle'
    ])->name('favourite.toggle');

    // Check Favourite
    Route::get('/favourite/{house}/check', [
        FavouriteController::class,
        'check'
    ])->name('favourite.check');
});

// Permohonan - Map View
Route::get('/MapSearch', function () {
    $houses = House::where('status', 'available')
        ->latest()
        ->get([
            'id',
            'title',
            'description',
            'address',
            'area',
            'monthly_rent',
            'property_type',
            'latitude',
            'longitude',
        ]);

    return view('MapSearch', compact('houses'));
})->middleware(['auth', 'verified'])->name('MapSearch');

// Authentication Routes
require __DIR__ . '/auth.php';
