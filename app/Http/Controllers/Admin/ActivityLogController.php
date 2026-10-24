<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user:id,name')
            ->when($request->action, fn ($qb, $v) => $qb->where('action', $v))
            ->when($request->q, fn ($qb, $s) => $qb->where(fn ($w) => $w->where('description', 'like', "%{$s}%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$s}%"))))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.activity.index', [
            'logs' => $logs,
            'actions' => ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action'),
        ]);
    }

    public function prune(Request $request)
    {
        $days = (int) $request->validate(['days' => ['required', 'in:30,90,180']])['days'];
        $count = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', "{$count} log lama dibersihkan.");
    }
}
