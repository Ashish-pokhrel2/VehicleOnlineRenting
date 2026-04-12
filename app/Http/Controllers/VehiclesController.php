<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehicles\StoreVehiclesRequest;
use App\Http\Requests\Vehicles\UpdateVehiclesRequest;
use App\Models\Vehicles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehiclesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Vehicles::with('vendor:id,name')
            ->when($request->type, fn ($q) => $q->byType($request->type))
            ->when($request->available !== null, fn ($q) => $q->where('available', $request->available))
            ->when($request->vendor_id, fn ($q) => $q->where('vendor_id', $request->vendor_id));

        $vehicles = $request->paginate
            ? $query->paginate($request->per_page ?? 15)
            : $query->get();

        return response()->json([
            'success' => true,
            'data' => $vehicles,
        ]);
    }

    public function store(StoreVehiclesRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle = Vehicles::create([
            ...$validated,
            'vendor_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle created successfully',
            'data' => $vehicle->load('vendor:id,name'),
        ], 201);
    }

    public function show(Vehicles $vehicle): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $vehicle->load(['vendor:id,name', 'vehicleReviews.customer:id,name']),
        ]);
    }

    public function update(UpdateVehiclesRequest $request, Vehicles $vehicle): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($vehicle->image && Storage::disk('public')->exists($vehicle->image)) {
                Storage::disk('public')->delete($vehicle->image);
            }

            $validated['image'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully',
            'data' => $vehicle->load('vendor:id,name'),
        ]);
    }

    public function destroy(Vehicles $vehicle): JsonResponse
    {
        if ($vehicle->vendor_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this vehicle',
            ], 403);
        }

        if ($vehicle->image && Storage::disk('public')->exists($vehicle->image)) {
            Storage::disk('public')->delete($vehicle->image);
        }

        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully',
        ]);
    }
}
