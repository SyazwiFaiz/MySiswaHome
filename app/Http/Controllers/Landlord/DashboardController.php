<?php

namespace App\Http\Controllers\Landlord;

use App\Models\House;
use App\Models\Inquiry;
use App\Models\Review;
use App\Models\Tenancy;
use App\Support\ListingQuality;

class DashboardController extends LandlordController
{
    /** Pengganti halaman lama /landlord/manage. */
    public function legacyManage()
    {
        return redirect()->route('landlord.x.overview');
    }

    public function index()
    {
        $user = auth()->user();

        $houses = House::when(! $this->isAdmin(), fn ($q) => $q->where('landlord_id', $user->id))
            ->withCount('favourites')->latest()->get();
        $ids = $houses->pluck('id');

        $ratings = Review::whereIn('house_id', $ids)
            ->selectRaw('house_id, avg(rating) as avg_rating, count(*) as total')
            ->groupBy('house_id')->get()->keyBy('house_id');

        $inq = Inquiry::whereIn('house_id', $ids)
            ->selectRaw("house_id, count(*) as total, sum(case when status = 'pending' then 1 else 0 end) as pending")
            ->groupBy('house_id')->get()->keyBy('house_id');

        $houses->each(function (House $h) use ($ratings, $inq) {
            $h->rating_avg = $ratings->has($h->id) ? round((float) $ratings[$h->id]->avg_rating, 1) : null;
            $h->rating_count = (int) ($ratings[$h->id]->total ?? 0);
            $h->inq_total = (int) ($inq[$h->id]->total ?? 0);
            $h->inq_pending = (int) ($inq[$h->id]->pending ?? 0);
            $h->quality = ListingQuality::evaluate($h);
        });

        $tenancies = Tenancy::with(['house:id,title', 'payments'])->whereIn('house_id', $ids)->where('status', 'active')->get();
        $overdue = $tenancies->filter(fn (Tenancy $t) => $t->overdueCount() > 0);
        $ending = $tenancies->filter(fn (Tenancy $t) => $t->daysLeft() !== null && $t->daysLeft() <= 30)
            ->sortBy(fn (Tenancy $t) => $t->daysLeft());

        $stats = [
            'houses' => $houses->count(),
            'available' => $houses->where('status', 'available')->count(),
            'rented' => $houses->where('status', 'rented')->count(),
            'income' => (float) $tenancies->sum('monthly_rent'),
            'favourites' => (int) $houses->sum('favourites_count'),
            'pending' => (int) $houses->sum('inq_pending'),
            'rating' => round((float) Review::whereIn('house_id', $ids)->avg('rating'), 1),
            'reviews' => Review::whereIn('house_id', $ids)->count(),
            'unreplied' => Review::whereIn('house_id', $ids)->whereNull('landlord_reply')->count(),
        ];

        $weak = $houses->filter(fn ($h) => $h->quality['score'] < 60)->count();

        $attention = array_values(array_filter([
            ['label' => 'Permintaan lawatan menunggu balasan', 'count' => $stats['pending'], 'url' => route('landlord.x.inquiries', ['status' => 'pending'])],
            ['label' => 'Penyewa lewat bayar sewa', 'count' => $overdue->count(), 'url' => route('landlord.x.tenancies.index')],
            ['label' => 'Sewaan tamat dalam 30 hari', 'count' => $ending->count(), 'url' => route('landlord.x.tenancies.index')],
            ['label' => 'Ulasan belum dibalas', 'count' => $stats['unreplied'], 'url' => route('landlord.x.reviews', ['filter' => 'unreplied'])],
            ['label' => 'Iklan perlu dipertingkat (skor bawah 60)', 'count' => $weak, 'url' => '#prestasi'],
        ], fn ($a) => $a['count'] > 0));

        return view('landlord.overview', compact('houses', 'stats', 'attention', 'overdue', 'ending'));
    }
}
