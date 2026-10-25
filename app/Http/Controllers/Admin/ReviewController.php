<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user:id,name', 'house:id,title,deleted_at'])
            ->when($request->rating, fn ($qb, $v) => $qb->where('rating', (int) $v))
            ->when($request->q, function ($qb, $s) {
                $qb->where(fn ($w) => $w->where('comment', 'like', "%{$s}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$s}%"))
                    ->orWhereHas('house', fn ($h) => $h->withTrashed()->where('title', 'like', "%{$s}%")));
            })
            ->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'avg' => round((float) Review::avg('rating'), 1),
            'total' => Review::count(),
        ]);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        ActivityLog::record('deleted', "Memadam ulasan #{$review->id} (moderasi)");

        return back()->with('success', 'Ulasan dipadam.');
    }
}
