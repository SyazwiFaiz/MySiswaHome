<?php

namespace App\Http\Controllers\Landlord;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends LandlordController
{
    public function index(Request $request)
    {
        $ids = $this->myHouseIds();

        $reviews = Review::with(['user:id,name', 'house:id,title'])
            ->whereIn('house_id', $ids)
            ->when($request->filter === 'unreplied', fn ($q) => $q->whereNull('landlord_reply'))
            ->latest()->paginate(10)->withQueryString();

        return view('landlord.reviews', [
            'reviews' => $reviews,
            'avg' => round((float) Review::whereIn('house_id', $ids)->avg('rating'), 1),
            'total' => Review::whereIn('house_id', $ids)->count(),
            'unreplied' => Review::whereIn('house_id', $ids)->whereNull('landlord_reply')->count(),
        ]);
    }

    public function reply(Request $request, Review $review)
    {
        $this->authorizeHouse($review->house);

        $data = $request->validate(['reply' => ['required', 'string', 'max:500']], [
            'reply.required' => 'Tulis balasan dahulu.',
            'reply.max' => 'Balasan terlalu panjang (maksimum 500 aksara).',
        ]);

        $review->update(['landlord_reply' => $data['reply'], 'replied_at' => now()]);

        return back()->with('success', 'Balasan disimpan dan akan dipaparkan kepada pelajar.');
    }

    public function destroyReply(Review $review)
    {
        $this->authorizeHouse($review->house);
        $review->update(['landlord_reply' => null, 'replied_at' => null]);

        return back()->with('success', 'Balasan dipadam.');
    }
}
