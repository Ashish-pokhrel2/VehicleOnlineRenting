<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookings extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'customer_id',
        'vendor_id',
        'start_date',
        'end_date',
        'pickup_time',
        'full_name',
        'phone',
        'email',
        'citizenship_id',
        'special_request',
        'total_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_price' => 'decimal:2',
            'status' => BookingStatus::class,
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicles::class, 'vehicle_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', BookingStatus::PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', BookingStatus::CONFIRMED);
    }

    public function scopeForVehicle($query, int $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }
}
