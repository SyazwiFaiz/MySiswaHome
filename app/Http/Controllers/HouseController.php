<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseController extends Controller
{
    public function index()
    {
        $houses = House::where('landlord_id', Auth::id())
            ->latest()
            ->get();

        return view('landlord.manage', compact('houses'));
    }


    public function create()
    {
        return view('landlord.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'area' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'monthly_rent' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'property_type' => 'nullable|string|max:100',
            'furnished' => 'nullable|string|max:100',

            'image' => 'nullable|array',
            'image.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);


        // Get logged-in landlord ID
        $validated['landlord_id'] = Auth::id();


        // Upload multiple images
        $imagePaths = [];

        if ($request->hasFile('image')) {

            foreach ($request->file('image') as $image) {

                $imagePaths[] = $image->store('houses', 'public');

            }
        }


        // Store image paths as JSON
        $validated['image'] = json_encode($imagePaths);


        // Create house
        House::create($validated);


        return redirect()
            ->route('landlord.create')
            ->with('success', 'House added successfully.');
    }


    public function edit(House $house)
    {
        // Make sure the house belongs to the logged-in landlord
        abort_unless($house->landlord_id === Auth::id(), 403);

        return view('landlord.edit', compact('house'));
    }


    public function update(Request $request, House $house)
    {
        // Make sure the house belongs to the logged-in landlord
        abort_unless($house->landlord_id === Auth::id(), 403);


        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'area' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'monthly_rent' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'property_type' => 'nullable|string|max:100',
            'furnished' => 'nullable|string|max:100',

            'image' => 'nullable|array',
            'image.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',

            'status' => 'required|string|max:255',
        ]);


        // Upload new images if provided
        if ($request->hasFile('image')) {

            $imagePaths = [];

            foreach ($request->file('image') as $image) {

                $imagePaths[] = $image->store('houses', 'public');

            }

            $validated['image'] = json_encode($imagePaths);
        }


        // Update house
        $house->update($validated);


        return redirect()
            ->route('landlord.manage')
            ->with('success', 'House updated successfully.');
    }


    public function destroy(House $house)
    {
        // Make sure the house belongs to the logged-in landlord
        abort_unless($house->landlord_id === Auth::id(), 403);


        // Delete the house
        $house->delete();


        return redirect()
            ->route('landlord.manage')
            ->with('success', 'House deleted successfully.');
    }
}
