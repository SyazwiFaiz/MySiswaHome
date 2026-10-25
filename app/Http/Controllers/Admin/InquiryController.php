<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $inquiries = Inquiry::with(['user:id,name,email,phone', 'house:id,title,area,deleted_at'])
            ->when($request->status, fn ($qb, $v) => $qb->where('status', $v))
            ->when($request->q, function ($qb, $s) {
                $qb->where(fn ($w) => $w
                    ->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
                    ->orWhereHas('house', fn ($h) => $h->withTrashed()->where('title', 'like', "%{$s}%")));
            })
            ->latest()->paginate(15)->withQueryString();

        return view('admin.inquiries.index', [
            'inquiries' => $inquiries,
            'counts' => Inquiry::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $data = $request->validate(['status' => ['required', Rule::in(array_keys(Inquiry::STATUSES))]]);
        $inquiry->update($data);

        ActivityLog::record('updated', "Menukar status permintaan lawatan #{$inquiry->id} kepada {$data['status']}", $inquiry);

        return back()->with('success', 'Status permintaan dikemas kini.');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();
        ActivityLog::record('deleted', "Memadam permintaan lawatan #{$inquiry->id}");

        return back()->with('success', 'Permintaan dipadam.');
    }
}
