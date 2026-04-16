<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSetting extends Model
{
    protected $fillable = [
        'service_fee',
        'default_estimated_days',
    ];

    protected function casts(): array
    {
        return [
            'service_fee' => 'decimal:2',
            'default_estimated_days' => 'integer',
        ];
    }
}
