<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorContactController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->isVendor(), 403);

        return view('vendor.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->isVendor(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create([
            'vendor_id' => $request->user()->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'Unread',
        ]);

        return back()->with('success', 'Message sent successfully. Our support team will contact you soon.');
    }
}