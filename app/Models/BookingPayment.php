<?php

namespace App\Models;

use App\Enums\BookingPaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'vendor_id',
        'purchase_order_id',
        'purchase_order_name',
        'pidx',
        'transaction_id',
        'amount',
        'service_fee',
        'net_amount',
        'payment_url',
        'return_url',
        'website_url',
        'status',
        'request_payload',
        'response_payload',
        'lookup_payload',
        'initiated_at',
        'verified_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'request_payload' => 'array',
            'response_payload' => 'array',
            'lookup_payload' => 'array',
            'initiated_at' => 'datetime',
            'verified_at' => 'datetime',
            'expired_at' => 'datetime',
            'status' => BookingPaymentStatus::class,
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Bookings::class, 'booking_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function settlement(): HasOne
    {
        return $this->hasOne(BookingSettlement::class, 'booking_payment_id');
    }
}
