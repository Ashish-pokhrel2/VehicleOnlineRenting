<?php

namespace App\Notifications;

use App\Models\Bookings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingUpdatedNotification extends Notification
{
    use Queueable;

    protected Bookings $booking;

    public function __construct(Bookings $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'vehicle_name' => $this->booking->vehicle->name ?? 'Vehicle',
            'customer_name' => $this->booking->customer->name ?? 'Customer',
            'message' => 'Booking details have been modified by customer.',
            'type' => 'booking_updated',
        ];
    }
}