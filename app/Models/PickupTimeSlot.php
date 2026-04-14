<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupTimeSlot extends Model
{
    protected $fillable = [
        'label',
        'sort_order',
    ];
}
