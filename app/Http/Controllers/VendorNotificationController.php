<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class VendorNotificationController extends Controller
{
    public function read(string $notification): RedirectResponse
    {
        abort_unless(auth()->user()?->isVendor(), Response::HTTP_FORBIDDEN);

        $notificationRecord = auth()->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $notificationRecord->markAsRead();

        return redirect()->route('vendor.bookings.index');
    }

    public function readAll(): RedirectResponse
    {
        abort_unless(auth()->user()?->isVendor(), Response::HTTP_FORBIDDEN);

        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}