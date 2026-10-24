<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HouseController extends Controller
{
    public function index()
    {
        $houses = House::where('landlord_id', Auth::id())
            ->latest()
            ->get();

        return view('landlord.manage', compact('houses'));
    }

    public function show(House $house)
    {
        return view('student.house-detail', compact('house'));
    }

    public function studentDashboard(Request $request)
    {
        $houses = House::with('landlord')
            ->where('status', 'available')
            ->when($request->filled('location'), function ($query) use ($request) {
                $location = $request->string('location')->trim();

                $query->where(function ($q) use ($location) {
                    $q->where('area', 'like', "%{$location}%")
                        ->orWhere('address', 'like', "%{$location}%")
                        ->orWhere('title', 'like', "%{$location}%");
                });
            })
            ->when(
                $request->filled('price'),
                fn($query) =>
                $query->where('monthly_rent', '<=', (float) $request->input('price'))
            )
            ->when(
                $request->filled('type'),
                fn($query) =>
                $query->where('property_type', 'like', '%' . $request->input('type') . '%')
            )
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $selectedHouse = $request->filled('house_id')
            ? House::with('landlord')
            ->where('status', 'available')
            ->find($request->integer('house_id'))
            : null;

        return view('student.dashboard', compact('houses', 'selectedHouse'));
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
            'image' => 'nullable|array|max:5',
            'image.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['landlord_id'] = Auth::id();

        $imagePaths = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imagePaths[] = $image->store('houses', 'public');
            }
        }

        $validated['image'] = json_encode($imagePaths);

        House::create($validated);

        return redirect()
            ->route('landlord.create')
            ->with('success', 'House added successfully.');
    }

    public function edit(House $house)
    {
        abort_unless(
            $house->landlord_id == Auth::id(),
            403
        );

        return view('landlord.edit', compact('house'));
    }

    public function update(Request $request, House $house)
    {
        abort_unless(
            $house->landlord_id == Auth::id(),
            403
        );

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
            'image' => 'nullable|array|max:5',
            'image.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'status' => 'required|in:available,rented',
        ]);

        if ($request->hasFile('image')) {
            $oldImages = json_decode($house->image, true);

            if (is_array($oldImages)) {
                foreach ($oldImages as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            } elseif ($house->image) {
                Storage::disk('public')->delete($house->image);
            }

            $imagePaths = [];

            foreach ($request->file('image') as $image) {
                $imagePaths[] = $image->store('houses', 'public');
            }

            $validated['image'] = json_encode($imagePaths);
        }

        $house->update($validated);

        return redirect()
            ->route('landlord.manage')
            ->with('success', 'House updated successfully.');
    }

    public function destroy(House $house)
    {
        abort_unless(
            $house->landlord_id == Auth::id(),
            403
        );

        $images = json_decode($house->image, true);

        if (is_array($images)) {
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        } elseif ($house->image) {
            Storage::disk('public')->delete($house->image);
        }

        $house->delete();

        return redirect()
            ->route('landlord.manage')
            ->with('success', 'House deleted successfully.');
    }
}
