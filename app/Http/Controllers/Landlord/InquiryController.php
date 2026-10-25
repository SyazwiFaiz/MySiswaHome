<?php

namespace App\Http\Controllers\Landlord;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InquiryController extends LandlordController
{
    public function index(Request $request)
    {
        $ids = $this->myHouseIds();

        $inquiries = Inquiry::with(['user:id,name,email,phone', 'house:id,title,area,monthly_rent'])
            ->whereIn('house_id', $ids)
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->latest()->paginate(10)->withQueryString();

        $counts = Inquiry::whereIn('house_id', $ids)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('landlord.inquiries', compact('inquiries', 'counts'));
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $this->authorizeHouse($inquiry->house);
        $inquiry->update($request->validate(['status' => ['required', Rule::in(array_keys(Inquiry::STATUSES))]]));

        return back()->with('success', 'Status permintaan dikemas kini. Pelajar boleh nampak perubahan ini.');
    }
}
