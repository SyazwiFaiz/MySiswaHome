<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function index(Request $request)
    {
        $favourites = Favourite::with(['user:id,name,email', 'house:id,title,area,status,deleted_at'])
            ->when($request->q, function ($qb, $s) {
                $qb->where(fn ($w) => $w
                    ->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
                    ->orWhereHas('house', fn ($h) => $h->withTrashed()->where('title', 'like', "%{$s}%")));
            })
            ->latest()->paginate(15)->withQueryString();

        return view('admin.favourites.index', compact('favourites'));
    }

    public function destroy(Favourite $favourite)
    {
        $favourite->delete();
        ActivityLog::record('deleted', 'Membuang satu rekod kegemaran');

        return back()->with('success', 'Kegemaran dibuang.');
    }

    public function bulk(Request $request)
    {
        $request->validate(['ids' => ['required', 'array', 'min:1'], 'ids.*' => ['integer']], ['ids.required' => 'Pilih sekurang-kurangnya satu rekod.']);

        $count = Favourite::whereIn('id', $request->ids)->delete();
        ActivityLog::record('bulk', "Membuang {$count} rekod kegemaran");

        return back()->with('success', "{$count} kegemaran dibuang.");
    }
}
