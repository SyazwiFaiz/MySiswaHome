<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::with('house')->where('user_id', auth()->id())->latest()->paginate(10);

        return view('student.inquiries', compact('inquiries'));
    }

    public function destroy(Inquiry $inquiry)
    {
        abort_unless($inquiry->user_id === auth()->id(), 403);
        abort_unless($inquiry->status === 'pending', 403, 'Hanya permintaan yang masih menunggu boleh dibatalkan.');

        $inquiry->delete();

        return back()->with('success', 'Permintaan dibatalkan.');
    }
}
