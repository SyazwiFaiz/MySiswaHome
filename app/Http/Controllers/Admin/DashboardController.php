<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Favourite;
use App\Models\House;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total = House::count();
        $rented = House::where('status', 'rented')->count();

        $stats = [
            'houses' => $total,
            'available' => $total - $rented,
            'rented' => $rented,
            'users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'landlords' => User::where('role', 'landlord')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'favourites' => Favourite::count(),
            'avg_rent' => (float) House::avg('monthly_rent'),
        ];

        // Perkara yang perlu admin semak
        $attention = [
            ['label' => 'Permintaan lawatan menunggu', 'count' => Inquiry::where('status', 'pending')->count(), 'url' => route('admin.inquiries.index', ['status' => 'pending'])],
            ['label' => 'Rumah belum ada koordinat peta', 'count' => House::where(fn ($q) => $q->whereNull('latitude')->orWhereNull('longitude'))->count(), 'url' => route('admin.houses.index', ['missing' => 'coords'])],
            ['label' => 'Rumah belum ada gambar', 'count' => House::where(fn ($q) => $q->whereNull('image')->orWhere('image', ''))->count(), 'url' => route('admin.houses.index', ['missing' => 'image'])],
            ['label' => 'Tuan rumah tiada iklan', 'count' => User::where('role', 'landlord')->doesntHave('houses')->count(), 'url' => route('admin.users.index', ['role' => 'landlord', 'no_listing' => 1])],
            ['label' => 'Akaun digantung', 'count' => User::where('is_active', false)->count(), 'url' => route('admin.users.index', ['active' => '0'])],
            ['label' => 'Rumah dalam tong sampah', 'count' => House::onlyTrashed()->count(), 'url' => route('admin.houses.index', ['trashed' => 'only'])],
        ];

        // Trend 6 bulan: pengguna baharu vs iklan baharu
        $trend = ['labels' => [], 'users' => [], 'houses' => []];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $trend['labels'][] = $start->format('M');
            $trend['users'][] = User::whereBetween('created_at', [$start, $end])->count();
            $trend['houses'][] = House::whereBetween('created_at', [$start, $end])->count();
        }

        $areas = House::select('area', DB::raw('count(*) as total'), DB::raw('avg(monthly_rent) as avg_rent'))
            ->groupBy('area')->orderByDesc('total')->limit(8)->get();

        $topFavourites = House::withCount('favourites')->whereHas('favourites')
            ->orderByDesc('favourites_count')->limit(5)->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'attention' => $attention,
            'trend' => $trend,
            'areas' => $areas,
            'topFavourites' => $topFavourites,
            'recentLogs' => ActivityLog::with('user')->latest()->limit(6)->get(),
            'newUsers' => User::latest()->limit(5)->get(),
        ]);
    }
}
