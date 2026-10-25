<?php

namespace App\Http\Controllers\Landlord;

use App\Models\House;
use App\Models\Tenancy;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class HouseController extends LandlordController
{
    public function create()
    {
        return view('landlord.houses.create', [
            'house' => new House(['status' => 'available', 'bedrooms' => 1, 'bathrooms' => 1, 'phone' => auth()->user()->phone]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['landlord_id'] = auth()->id();

        $house = House::create($data);

        return redirect()->route('landlord.x.overview')->with('success', "Iklan \"{$house->title}\" berjaya ditambah.");
    }

    public function edit(House $house)
    {
        $this->authorizeHouse($house);

        return view('landlord.houses.edit', compact('house'));
    }

    public function update(Request $request, House $house)
    {
        $this->authorizeHouse($house);
        $house->update($this->validated($request, $house));

        return redirect()->route('landlord.x.overview')->with('success', "Iklan \"{$house->title}\" dikemas kini.");
    }

    public function destroy(House $house)
    {
        $this->authorizeHouse($house);

        if (Tenancy::where('house_id', $house->id)->where('status', 'active')->exists()) {
            return back()->with('error', 'Rumah ini ada sewaan aktif. Tamatkan sewaan dahulu sebelum memadam iklan.');
        }

        $title = $house->title;
        $house->delete(); // soft delete: admin masih boleh memulihkan

        return redirect()->route('landlord.x.overview')->with('success', "Iklan \"{$title}\" dipadam. Hubungi admin jika perlu dipulihkan.");
    }

    /* ---------------------------------------------------------------- */

    private function validated(Request $request, ?House $house = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'address' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'monthly_rent' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'bedrooms' => ['required', 'integer', 'min:0', 'max:50'],
            'bathrooms' => ['required', 'integer', 'min:0', 'max:50'],
            'property_type' => ['nullable', 'string', 'max:100'],
            'furnished' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:available,rented'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'photos' => ['nullable', 'array', 'max:10'],
            'photos.*' => ['image', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string'],
        ], [
            'title.required' => 'Tajuk iklan diperlukan.',
            'address.required' => 'Alamat diperlukan.',
            'area.required' => 'Kawasan diperlukan.',
            'phone.required' => 'Nombor telefon diperlukan.',
            'monthly_rent.required' => 'Sewa bulanan diperlukan.',
            'photos.*.image' => 'Fail mesti berupa gambar (JPG, PNG atau WebP).',
            'photos.*.max' => 'Setiap gambar maksimum 4 MB.',
            'photos.max' => 'Maksimum 10 gambar sekali muat naik.',
        ]);

        // Gambar: buang yang ditanda, tambah yang baharu.
        // Satu gambar = teks biasa, lebih daripada satu = JSON (serasi dengan halaman student).
        $paths = $house ? $house->image_paths : [];
        $remove = $request->input('remove_images', []);

        foreach ($paths as $p) {
            if (in_array($p, $remove, true) && ! preg_match('#^https?://#i', $p)) {
                Storage::disk('public')->delete($p);
            }
        }
        $paths = array_values(array_diff($paths, $remove));

        foreach ($request->file('photos', []) as $file) {
            $paths[] = $file->store('houses', 'public');
        }

        if ($house || $paths) {
            $data['image'] = count($paths) > 1 ? json_encode($paths) : ($paths[0] ?? null);
        }

        return Arr::except($data, ['photos', 'remove_images']);
    }
}
