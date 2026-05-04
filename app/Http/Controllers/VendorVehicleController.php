<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorVehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicles::where('vendor_id', Auth::id())
            ->latest()
            ->get();

        return view('vendor.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vendor.vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Car,Bike,Scooter,Bus',
            'category' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'price_per_day' => 'required|numeric|min:1',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'nullable|string',
            'seats' => 'nullable|integer|min:1',
            'transmission' => 'nullable|string|max:255',
            'fuel' => 'nullable|string|max:255',
            'available' => 'nullable|boolean',
        ], [], [
            'name' => 'vehicle name',
            'type' => 'vehicle type',
            'category' => 'category',
            'price_per_day' => 'price per day',
            'image' => 'vehicle image',
        ]);

        $imagePath = $request->file('image')->store('images/vehicles', 'public');

        Vehicles::create([
            'vendor_id' => Auth::id(),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'location' => $validated['location'] ?? null,
            'price_per_day' => $validated['price_per_day'],
            'image' => 'storage/' . $imagePath,
            'description' => $validated['description'] ?? 'No description provided.',
            'seats' => $validated['seats'] ?? 4,
            'transmission' => $validated['transmission'] ?? 'Automatic',
            'fuel' => $validated['fuel'] ?? 'Petrol',
            'rating' => 0,
            'reviews' => 0,
            'available' => $request->boolean('available'),
            'features' => [],
            'images' => [],
        ]);

        return redirect()
            ->route('vendor.vehicles.index')
            ->with('success', 'Vehicle added successfully.');
    }

    public function edit(Vehicles $vehicle)
    {
        abort_unless($vehicle->vendor_id === Auth::id(), 403);

        return view('vendor.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicles $vehicle)
    {
        abort_unless($vehicle->vendor_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Car,Bike,Scooter,Bus',
            'category' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'price_per_day' => 'required|numeric|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'nullable|string',
            'seats' => 'nullable|integer|min:1',
            'transmission' => 'nullable|string|max:255',
            'fuel' => 'nullable|string|max:255',
            'available' => 'nullable|boolean',
        ], [], [
            'name' => 'vehicle name',
            'type' => 'vehicle type',
            'category' => 'category',
            'price_per_day' => 'price per day',
            'image' => 'vehicle image',
        ]);

        $imagePath = $vehicle->image;

        if ($request->hasFile('image')) {
            $storedImage = $request->file('image')->store('images/vehicles', 'public');
            $imagePath = 'storage/' . $storedImage;
        }

        $vehicle->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'location' => $validated['location'] ?? null,
            'price_per_day' => $validated['price_per_day'],
            'image' => $imagePath,
            'description' => $validated['description'] ?? $vehicle->description,
            'seats' => $validated['seats'] ?? $vehicle->seats,
            'transmission' => $validated['transmission'] ?? $vehicle->transmission,
            'fuel' => $validated['fuel'] ?? $vehicle->fuel,
            'available' => $request->boolean('available'),
        ]);

        return redirect()
            ->route('vendor.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicles $vehicle)
    {
        abort_unless($vehicle->vendor_id === Auth::id(), 403);

        $vehicle->delete();

        return redirect()
            ->route('vendor.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}