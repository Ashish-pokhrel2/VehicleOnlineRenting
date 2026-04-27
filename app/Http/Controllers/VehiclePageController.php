<?php

namespace App\Http\Controllers;

use App\Enums\VehicleType;
use App\Models\Vehicles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehiclePageController extends Controller
{
    /**
     * Display the vehicle listing page.
     *
     * This method loads vehicles for the customer-facing listing page.
     * It also supports category/type filtering from the homepage category cards,
     * for example: /vehicles?type=cars, /vehicles?type=bikes, /vehicles?type=scooters.
     */
    public function index(Request $request): View
    {
        // Start the vehicle query and eager load vendor data to avoid repeated database queries.
        $query = Vehicles::with('vendor:id,name');

        // Apply category/type filtering when the user clicks a homepage category card.
        if ($request->filled('type')) {
            $selectedType = strtolower(trim($request->type));
            $singularType = rtrim($selectedType, 's');

            $query->where(function ($q) use ($selectedType, $singularType) {
                $q->whereRaw('LOWER(type) = ?', [$selectedType])
                    ->orWhereRaw('LOWER(type) = ?', [$singularType])
                    ->orWhereRaw('LOWER(category) = ?', [$selectedType])
                    ->orWhereRaw('LOWER(category) = ?', [$singularType]);
            });
        }

        // Retrieve the filtered vehicles in a consistent order.
        $vehicles = $query->orderBy('id')->get();

        // Load all vehicles separately so the sidebar filters still show all available options.
        $allVehicles = Vehicles::all();

        // Prepare vehicle type options for the listing page filters.
        $types = $allVehicles->pluck('type')
            ->filter()
            ->unique()
            ->map(fn ($type) => $type instanceof VehicleType ? $type->value : (string) $type)
            ->values();

        // Prepare category options for the listing page filters.
        $categories = $allVehicles->pluck('category')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Calculate price range values for the filter sidebar.
        $minPrice = $allVehicles->min('price_per_day') ?? 0;
        $maxPrice = $allVehicles->max('price_per_day') ?? 0;

        // Send all required data to the vehicle listing Blade view.
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

    /**
     * Display the detailed page for a selected vehicle.
     *
     * This method loads the selected vehicle, its vendor information,
     * image gallery data, and availability status for the customer.
     */
    public function show(Vehicles $vehicle): View
    {
        // Load vendor details for display on the vehicle detail page.
        $vehicle->load('vendor:id,name');

        // Use the vehicle image gallery if available; otherwise fall back to the main image.
        $vehicleImages = $vehicle->images ?? [];

        if (empty($vehicleImages) && $vehicle->image) {
            $vehicleImages = [$vehicle->image];
        }

        // Create a simple customer-facing availability label.
        $availabilityLabel = $vehicle->available ? 'Available Now' : 'Currently Unavailable';

        // Send selected vehicle data to the detail page.
        return view('vehicles.show', [
            'vehicle' => $vehicle,
            'vehicleImages' => $vehicleImages,
            'availabilityLabel' => $availabilityLabel,
            'isLoading' => false,
            'errorMessage' => null,
        ]);
    }

    /**
     * Handle AJAX vehicle search from the homepage search bar.
     *
     * This method returns filtered vehicles as JSON without reloading the page.
     * Customers can search by location and vehicle type/category.
     */
    public function ajaxSearch(Request $request): JsonResponse
    {
        // Only show vehicles that are currently available for booking.
        $query = Vehicles::query()->where('available', true);

        // Filter by location if the customer enters a location.
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . trim($request->location) . '%');
        }

        // Filter by vehicle type/category entered in the search box.
        if ($request->filled('vehicle_type')) {
            $vehicleType = strtolower(trim($request->vehicle_type));
            $singularType = rtrim($vehicleType, 's');

            $query->where(function ($q) use ($vehicleType, $singularType) {
                $q->whereRaw('LOWER(category) LIKE ?', ['%' . $vehicleType . '%'])
                    ->orWhereRaw('LOWER(category) LIKE ?', ['%' . $singularType . '%'])
                    ->orWhereRaw('LOWER(type) LIKE ?', ['%' . $vehicleType . '%'])
                    ->orWhereRaw('LOWER(type) LIKE ?', ['%' . $singularType . '%']);
            });
        }

        // Fetch matching vehicles with vendor details.
        $vehicles = $query->with('vendor:id,name')->orderBy('id')->get();

        // Format the results into a clean JSON structure for JavaScript rendering.
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

        // Return the filtered vehicle list to the AJAX request.
        return response()->json([
            'vehicles' => $formattedVehicles,
        ]);
    }
}