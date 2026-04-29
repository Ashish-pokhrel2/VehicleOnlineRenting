<?php

namespace App\Models;

use App\Enums\BookingSettlementStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingSettlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'booking_payment_id',
        'vendor_id',
        'admin_id',
        'gross_amount',
        'service_fee',
        'net_amount',
        'status',
        'settled_at',
        'released_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'settled_at' => 'datetime',
            'released_at' => 'datetime',
            'status' => BookingSettlementStatus::class,
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Bookings::class, 'booking_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(BookingPayment::class, 'booking_payment_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
