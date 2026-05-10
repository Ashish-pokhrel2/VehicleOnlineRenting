<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'vendor_id',
        'vehicle_id',
        'customer_id',
        'rating',
        'comment',
        'vendor_reply',
        'created_at',
        'updated_at',
    ];

    public function booking()
    {
        return $this->belongsTo(Bookings::class, 'booking_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicle_id');
    }
}