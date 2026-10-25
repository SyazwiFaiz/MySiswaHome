<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\Inquiry;
use App\Models\Review;
use Illuminate\Http\Request;

class HouseExtrasController extends Controller
{
    /** Data tambahan untuk panel butiran rumah (ulasan, permintaan, rumah serupa). */
    public function show(House $house)
    {
        $user = auth()->user();

        $reviews = Review::with('user:id,name')->where('house_id', $house->id)->latest()->limit(20)->get()
            ->map(fn (Review $r) => [
                'id' => $r->id,
                'rating' => $r->rating,
                'comment' => $r->comment,
                'reply' => $r->landlord_reply,
                'name' => explode(' ', trim($r->user?->name ?? 'Pengguna'))[0],
                'mine' => $r->user_id === $user->id,
                'date' => $r->created_at->diffForHumans(),
            ]);

        $mine = Review::where('house_id', $house->id)->where('user_id', $user->id)->first();
        $inquiry = Inquiry::where('house_id', $house->id)->where('user_id', $user->id)->latest()->first();

        $similar = House::with('landlord:id,name,email,phone')
            ->where('id', '!=', $house->id)
            ->where('status', 'available')
            ->where(fn ($q) => $q->where('area', $house->area)
                ->orWhereBetween('monthly_rent', [$house->monthly_rent * 0.75, $house->monthly_rent * 1.25]))
            ->orderByRaw('(area = ?) desc', [$house->area])
            ->limit(3)->get()->map(fn (House $h) => $this->payload($h));

        return response()->json([
            'avg' => round((float) Review::where('house_id', $house->id)->avg('rating'), 1),
            'count' => Review::where('house_id', $house->id)->count(),
            'reviews' => $reviews,
            'mine' => $mine ? ['rating' => $mine->rating, 'comment' => $mine->comment] : null,
            'is_owner' => $house->landlord_id === $user->id,
            'inquiry' => $inquiry ? [
                'id' => $inquiry->id,
                'status' => $inquiry->status,
                'preferred_date' => $inquiry->preferred_date?->format('d/m/Y'),
            ] : null,
            'me_phone' => $user->phone,
            'similar' => $similar,
        ]);
    }

    /** Purata penilaian untuk banyak rumah sekali gus (lencana pada kad). */
    public function ratings(Request $request)
    {
        $ids = collect($request->input('ids', []))->map(fn ($i) => (int) $i)->filter()->take(60);

        $rows = Review::whereIn('house_id', $ids)
            ->selectRaw('house_id, avg(rating) as avg_rating, count(*) as total')
            ->groupBy('house_id')->get();

        return response()->json($rows->mapWithKeys(fn ($r) => [
            $r->house_id => ['avg' => round((float) $r->avg_rating, 1), 'count' => (int) $r->total],
        ]));
    }

    public function storeReview(Request $request, House $house)
    {
        abort_if($house->landlord_id === auth()->id(), 403, 'Anda tidak boleh menilai rumah sendiri.');

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Pilih bilangan bintang dahulu.',
            'rating.between' => 'Penilaian mesti antara 1 hingga 5 bintang.',
            'comment.max' => 'Ulasan terlalu panjang (maksimum 1000 aksara).',
        ]);

        Review::updateOrCreate(['house_id' => $house->id, 'user_id' => auth()->id()], $data);

        return response()->json(['ok' => true]);
    }

    public function destroyReview(House $house)
    {
        Review::where('house_id', $house->id)->where('user_id', auth()->id())->delete();

        return response()->json(['ok' => true]);
    }

    public function storeInquiry(Request $request, House $house)
    {
        abort_if($house->landlord_id === auth()->id(), 403, 'Ini rumah anda sendiri.');

        if ($house->status !== 'available') {
            return response()->json(['message' => 'Rumah ini sudah disewa.'], 422);
        }

        $pending = Inquiry::where('house_id', $house->id)->where('user_id', auth()->id())->where('status', 'pending')->exists();
        if ($pending) {
            return response()->json(['message' => 'Anda sudah menghantar permintaan untuk rumah ini. Tunggu tuan rumah menghubungi anda.'], 422);
        }

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
        ], [
            'preferred_date.after_or_equal' => 'Tarikh lawatan tidak boleh sebelum hari ini.',
            'preferred_date.date' => 'Tarikh lawatan tidak sah.',
        ]);

        Inquiry::create($data + ['house_id' => $house->id, 'user_id' => auth()->id(), 'status' => 'pending']);

        return response()->json(['ok' => true]);
    }

    /** Bentuk data sama seperti yang dipakai oleh halaman dashboard student. */
    private function payload(House $h): array
    {
        return [
            'id' => $h->id,
            'title' => $h->title,
            'description' => $h->description,
            'address' => $h->address,
            'area' => $h->area,
            'phone' => $h->phone,
            'monthly_rent' => $h->monthly_rent,
            'bedrooms' => $h->bedrooms,
            'bathrooms' => $h->bathrooms,
            'property_type' => $h->property_type,
            'furnished' => $h->furnished,
            'status' => $h->status,
            'latitude' => $h->latitude,
            'longitude' => $h->longitude,
            'image_urls' => $h->image_urls,
            'landlord' => $h->landlord ? ['name' => $h->landlord->name, 'email' => $h->landlord->email, 'phone' => $h->landlord->phone] : null,
        ];
    }
}
