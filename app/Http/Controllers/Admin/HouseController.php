<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\House;
use App\Models\User;
use App\Support\Csv;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HouseController extends Controller
{
    public function index(Request $request)
    {
        $houses = $this->filtered($request)
            ->paginate(in_array((int) $request->per_page, [10, 25, 50]) ? (int) $request->per_page : 10)
            ->withQueryString();

        return view('admin.houses.index', [
            'houses' => $houses,
            'areas' => House::select('area')->distinct()->orderBy('area')->pluck('area'),
            'types' => House::whereNotNull('property_type')->distinct()->orderBy('property_type')->pluck('property_type'),
            'landlords' => $this->landlords(),
        ]);
    }

    public function create()
    {
        return view('admin.houses.create', $this->formData(new House(['status' => 'available', 'bedrooms' => 1, 'bathrooms' => 1])));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $house = House::create($data);

        ActivityLog::record('created', "Menambah rumah \"{$house->title}\"", $house);

        return redirect()->route('admin.houses.show', $house)->with('success', 'Rumah berjaya ditambah.');
    }

    public function show(House $house)
    {
        $house->load('landlord')->loadCount('favourites');
        $favouritedBy = $house->favouritedBy()->latest('favourites.created_at')->limit(10)->get();

        return view('admin.houses.show', compact('house', 'favouritedBy'));
    }

    public function edit(House $house)
    {
        return view('admin.houses.edit', $this->formData($house));
    }

    public function update(Request $request, House $house)
    {
        $data = $this->validated($request, $house);
        $house->update($data);

        ActivityLog::record('updated', "Mengemas kini rumah \"{$house->title}\"", $house);

        return redirect()->route('admin.houses.show', $house)->with('success', 'Perubahan disimpan.');
    }

    /** Pertama kali = tong sampah (soft delete). Kali kedua (sudah dalam tong sampah) = padam kekal. */
    public function destroy(House $house)
    {
        $title = $house->title;

        if ($house->trashed()) {
            foreach ($house->image_paths as $path) {
                $this->deleteImage($path);
            }
            $house->forceDelete();
            ActivityLog::record('force_deleted', "Memadam kekal rumah \"{$title}\"");

            return redirect()->route('admin.houses.index', ['trashed' => 'only'])->with('success', 'Rumah dipadam kekal.');
        }

        $house->delete();
        ActivityLog::record('deleted', "Memadam rumah \"{$title}\" (boleh dipulihkan)", $house);

        return redirect()->route('admin.houses.index')->with('success', 'Rumah dialih ke tong sampah. Anda boleh pulihkannya.');
    }

    public function restore(House $house)
    {
        $house->restore();
        ActivityLog::record('restored', "Memulihkan rumah \"{$house->title}\"", $house);

        return back()->with('success', 'Rumah dipulihkan.');
    }

    public function toggleStatus(House $house)
    {
        $house->update(['status' => $house->status === 'available' ? 'rented' : 'available']);
        ActivityLog::record('updated', "Menukar status \"{$house->title}\" kepada {$house->status}", $house);

        return back()->with('success', 'Status dikemas kini.');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => ['required', 'in:available,rented,delete,restore'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ], ['ids.required' => 'Pilih sekurang-kurangnya satu rumah.']);

        $ids = $request->ids;
        $count = count($ids);

        match ($request->action) {
            'available', 'rented' => House::whereIn('id', $ids)->update(['status' => $request->action]),
            'delete' => House::whereIn('id', $ids)->delete(),
            'restore' => House::onlyTrashed()->whereIn('id', $ids)->restore(),
        };

        ActivityLog::record('bulk', "Tindakan pukal '{$request->action}' pada {$count} rumah");

        return back()->with('success', "Tindakan siap untuk {$count} rumah.");
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request, paginate: false)->cursor()->map(fn (House $h) => [
            $h->id, $h->title, $h->landlord?->name, $h->area, $h->address, $h->phone,
            $h->monthly_rent, $h->bedrooms, $h->bathrooms, $h->property_type, $h->furnished,
            $h->status, $h->latitude, $h->longitude, $h->created_at,
        ]);

        ActivityLog::record('export', 'Eksport senarai rumah (CSV)');

        return Csv::download('rumah-' . now()->format('Ymd-His') . '.csv', [
            'ID', 'Tajuk', 'Tuan rumah', 'Kawasan', 'Alamat', 'Telefon', 'Sewa (RM)', 'Bilik', 'Bilik air',
            'Jenis', 'Perabot', 'Status', 'Latitud', 'Longitud', 'Dicipta',
        ], $rows);
    }

    /* ---------------------------------------------------------------- */

    private function filtered(Request $r, bool $paginate = true)
    {
        $query = House::query()->with('landlord:id,name')->withCount('favourites');

        if ($r->trashed === 'only') {
            $query->onlyTrashed();
        } elseif ($r->trashed === 'with') {
            $query->withTrashed();
        }

        $query
            ->when($r->q, function ($qb, $s) {
                $qb->where(fn ($w) => $w->where('title', 'like', "%{$s}%")
                    ->orWhere('address', 'like', "%{$s}%")
                    ->orWhere('area', 'like', "%{$s}%")
                    ->orWhereHas('landlord', fn ($l) => $l->where('name', 'like', "%{$s}%")));
            })
            ->when($r->status, fn ($qb, $v) => $qb->where('status', $v))
            ->when($r->area, fn ($qb, $v) => $qb->where('area', $v))
            ->when($r->type, fn ($qb, $v) => $qb->where('property_type', $v))
            ->when($r->landlord, fn ($qb, $v) => $qb->where('landlord_id', $v))
            ->when($r->min_rent !== null && $r->min_rent !== '', fn ($qb) => $qb->where('monthly_rent', '>=', $r->min_rent))
            ->when($r->max_rent !== null && $r->max_rent !== '', fn ($qb) => $qb->where('monthly_rent', '<=', $r->max_rent))
            ->when($r->missing === 'coords', fn ($qb) => $qb->where(fn ($w) => $w->whereNull('latitude')->orWhereNull('longitude')))
            ->when($r->missing === 'image', fn ($qb) => $qb->where(fn ($w) => $w->whereNull('image')->orWhere('image', '')));

        $sort = in_array($r->sort, ['created_at', 'monthly_rent', 'title', 'favourites_count']) ? $r->sort : 'created_at';
        $dir = $r->dir === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $dir)->orderByDesc('id');
    }

    private function validated(Request $request, ?House $house = null): array
    {
        $data = $request->validate([
            'landlord_id' => ['required', 'exists:users,id'],
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
        ]);

        // Gambar sedia ada - buang yang ditanda, tambah yang baharu
        $paths = $house ? $house->image_paths : [];
        $remove = $request->input('remove_images', []);

        foreach ($paths as $path) {
            if (in_array($path, $remove, true)) {
                $this->deleteImage($path);
            }
        }
        $paths = array_values(array_diff($paths, $remove));

        foreach ($request->file('photos', []) as $file) {
            $paths[] = $file->store('houses', 'public');
        }

        if ($house || $paths) {
            // Satu gambar = simpan sebagai teks biasa (serasi data lama), lebih = JSON
            $data['image'] = count($paths) > 1 ? json_encode($paths) : ($paths[0] ?? null);
        }

        return Arr::except($data, ['photos', 'remove_images']);
    }

    private function deleteImage(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://', '/'])) {
            Storage::disk('public')->delete($path);
        }
    }

    private function landlords()
    {
        return User::whereIn('role', ['landlord', 'admin'])->orderBy('name')->get(['id', 'name']);
    }

    private function formData(House $house): array
    {
        return [
            'house' => $house,
            'landlords' => $this->landlords(),
            'types' => House::whereNotNull('property_type')->distinct()->pluck('property_type'),
            'furnishings' => House::whereNotNull('furnished')->distinct()->pluck('furnished'),
        ];
    }
}
