<?php

namespace App\Http\Controllers\Landlord;

use App\Models\House;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HouseQuickController extends LandlordController
{
    public function toggleStatus(House $house)
    {
        $this->authorizeHouse($house);
        $house->update(['status' => $house->status === 'available' ? 'rented' : 'available']);

        return back()->with('success', "\"{$house->title}\" ditandakan " . ($house->status === 'available' ? 'kosong' : 'disewa') . '.');
    }

    /** Gandakan iklan (termasuk gambar) untuk unit serupa dalam bangunan yang sama. */
    public function duplicate(House $house)
    {
        $this->authorizeHouse($house);

        $copy = $house->replicate(['image']);
        $copy->title = Str::limit($house->title, 240, '') . ' (Salinan)';
        $copy->status = 'available';

        $paths = [];
        foreach ($house->image_paths as $p) {
            if (preg_match('#^https?://#i', $p)) {
                $paths[] = $p;
            } elseif (Storage::disk('public')->exists($p)) {
                $ext = pathinfo($p, PATHINFO_EXTENSION);
                $dest = 'houses/' . Str::random(40) . ($ext ? ".{$ext}" : '');
                Storage::disk('public')->copy($p, $dest);
                $paths[] = $dest;
            }
        }
        $copy->image = count($paths) > 1 ? json_encode($paths) : ($paths[0] ?? null);
        $copy->save();

        return back()->with('success', 'Iklan digandakan. Kemas kini tajuk dan harga unit baharu di halaman urus rumah.');
    }
}
