<?php

namespace App\Http\Controllers;

use App\Enums\VehicleType;
use App\Models\Vehicles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function ajaxSearch(Request $request): JsonResponse
    {
        $query = Vehicles::query()->where('available', true);

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . trim($request->location) . '%');
        }

        if ($request->filled('vehicle_type')) {
            $vehicleType = strtolower(trim($request->vehicle_type));

            $query->where(function ($q) use ($vehicleType) {
                $q->whereRaw('LOWER(category) LIKE ?', ['%' . $vehicleType . '%'])
                  ->orWhereRaw('LOWER(type) LIKE ?', ['%' . $vehicleType . '%']);
            });
        }

        $vehicles = $query->with('vendor:id,name')->orderBy('id')->get();

        $formattedVehicles = $vehicles->map(function ($vehicle) {
            $typeValue = $vehicle->type instanceof VehicleType
                ? $vehicle->type->value
                : (string) $vehicle->type;

            return [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'type' => $typeValue,
                'category' => $vehicle->category,
                'location' => $vehicle->location,
                'price_per_day' => $vehicle->price_per_day,
                'image_url' => $vehicle->image ? asset($vehicle->image) : asset('images/logo/logo.png'),
                'vendor_name' => $vehicle->vendor?->name ?? 'Unknown Vendor',
            ];
        });

        return response()->json([
            'vehicles' => $formattedVehicles,
        ]);
    }
}