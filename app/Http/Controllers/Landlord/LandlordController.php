<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\House;

abstract class LandlordController extends Controller
{
    protected function isAdmin(): bool
    {
        return auth()->user()->role === 'admin';
    }

    /** ID rumah milik tuan rumah semasa (admin nampak semua, untuk semakan). */
    protected function myHouseIds()
    {
        return House::when(! $this->isAdmin(), fn ($q) => $q->where('landlord_id', auth()->id()))->pluck('id');
    }

    protected function authorizeHouse(?House $house): void
    {
        abort_unless($house && ($this->isAdmin() || $house->landlord_id === auth()->id()), 403, 'Ini bukan rumah anda.');
    }
}
