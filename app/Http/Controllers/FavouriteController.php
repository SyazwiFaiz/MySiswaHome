<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    /**
     * Toggle favourite
     */
    public function toggle(Request $request, House $house)
    {
        $user = $request->user();

        $exists = $user->favouriteHouses()
            ->where('houses.id', $house->id)
            ->exists();

        if ($exists) {

            $user->favouriteHouses()->detach($house->id);

            return response()->json([
                'success' => true,
                'favourite' => false,
                'message' => 'Rumah dibuang daripada Favourite.',
            ]);

        }

        $user->favouriteHouses()->attach($house->id);

        return response()->json([
            'success' => true,
            'favourite' => true,
            'message' => 'Rumah ditambah ke Favourite.',
        ]);
    }


    /**
     * Check favourite status
     */
    public function check(Request $request, House $house)
    {
        $favourite = $request->user()
            ->favouriteHouses()
            ->where('houses.id', $house->id)
            ->exists();

        return response()->json([
            'favourite' => $favourite,
        ]);
    }


    /**
     * Display favourite houses
     */
    public function index(Request $request)
    {
        $houses = $request->user()
            ->favouriteHouses()
            ->with('landlord')
            ->latest('favourites.created_at')
            ->get();

        return view('favourite', compact('houses'));
    }
}