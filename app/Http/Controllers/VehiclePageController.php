<?php

namespace App\Http\Controllers;

use App\Enums\VehicleType;
use App\Models\Vehicles;
use Illuminate\View\View;

class VehiclePageController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicles::with('vendor:id,name')
            ->orderBy('id')
            ->get();

        $types = $vehicles->pluck('type')
            ->filter()
            ->unique()
            ->map(fn ($type) => $type instanceof VehicleType ? $type->value : (string) $type)
            ->values();

        $categories = $vehicles->pluck('category')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $minPrice = $vehicles->min('price_per_day') ?? 0;
        $maxPrice = $vehicles->max('price_per_day') ?? 0;

        return view('vehicles.index', [
            'vehicles' => $vehicles,
            'types' => $types,
            'categories' => $categories,
            'priceRange' => [
                'min' => $minPrice,
                'max' => $maxPrice,
            ],
            'isLoading' => false,
            'errorMessage' => null,
        ]);
    }

    public function show(Vehicles $vehicle): View
    {
        $vehicle->load('vendor:id,name');

        $vehicleImages = $vehicle->images ?? [];
        if (empty($vehicleImages) && $vehicle->image) {
            $vehicleImages = [$vehicle->image];
        }

        $availabilityLabel = $vehicle->available ? 'Available Now' : 'Currently Unavailable';

        return view('vehicles.show', [
            'vehicle' => $vehicle,
            'vehicleImages' => $vehicleImages,
            'availabilityLabel' => $availabilityLabel,
            'isLoading' => false,
            'errorMessage' => null,
        ]);
    }
}
