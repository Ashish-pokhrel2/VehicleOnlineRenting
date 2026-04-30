<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PickupTimeSlot extends Model
{
    protected $fillable = [
        'label',
        'sort_order',
    ];

    public static function orderedWithDefaults(): Collection
    {
        $slots = self::query()
            ->orderBy('sort_order')
            ->get();

        if ($slots->isNotEmpty()) {
            return $slots;
        }

        self::query()->upsert(
            collect(self::defaultSlots())
                ->map(fn (string $label, int $index): array => [
                    'label' => $label,
                    'sort_order' => $index + 1,
                ])
                ->all(),
            ['label'],
            ['sort_order']
        );

        return self::query()
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return array<int, string>
     */
    public static function defaultSlots(): array
    {
        return [
            '9:00 AM',
            '11:00 AM',
            '1:00 PM',
            '3:00 PM',
            '5:00 PM',
        ];
    }
}
