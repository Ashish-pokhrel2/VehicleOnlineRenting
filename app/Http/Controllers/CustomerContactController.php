<?php

namespace App\Http\Controllers;

use App\Models\CustomerContactMessage;
use Illuminate\Http\Request;

class CustomerContactController extends Controller
{
    public function index()
    {
        return view('customer.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:500'],
        ]);

        CustomerContactMessage::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => 'Customer Support Message',
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Your message has been sent successfully.');
    }
}