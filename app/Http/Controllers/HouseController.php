<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseController extends Controller
{
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

            // Contact number
            'phone' => 'required|string|max:30',

            'monthly_rent' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'property_type' => 'nullable|string|max:100',
            'furnished' => 'nullable|string|max:100',

            // Multiple images
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

        // Store image paths as JSON in houses.image
        $validated['image'] = json_encode($imagePaths);

        // Create house
        House::create($validated);

        return redirect()
            ->route('landlord.create')
            ->with('success', 'House added successfully.');
    }
}