<?php

namespace App\Models;

use App\Enums\VehicleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicles extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'category',
        'location',
        'price_per_day',
        'rating',
        'reviews',
        'image',
        'images',
        'features',
        'description',
        'seats',
        'transmission',
        'fuel',
        'vendor_id',
        'available',
    ];

    protected function casts(): array
    {
        return [
            'type' => VehicleType::class,
            'price_per_day' => 'decimal:2',
            'rating' => 'float',
            'reviews' => 'integer',
            'features' => 'array',
            'images' => 'array',
            'seats' => 'integer',
            'available' => 'boolean',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Bookings::class, 'vehicle_id');
    }

    public function vehicleReviews(): HasMany
    {
        return $this->hasMany(Reviews::class, 'vehicle_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
