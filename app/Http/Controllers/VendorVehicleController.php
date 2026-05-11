<?php

namespace App\Http\Controllers;

use App\Enums\VehicleType;
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
        $allVehicles = Vehicles::orderBy('name')->get();

        return view('vendor.vehicles.create', compact('allVehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Car,Bike,Scooter,Bus',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'template_image' => 'nullable|string',
            'description' => 'required|string',
            'seats' => 'required|integer|min:1',
            'transmission' => 'required|string|max:255',
            'fuel' => 'required|string|max:255',
            'available' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $storedImage = $request->file('image')->store('images/vehicles', 'public');
            $imagePath = 'storage/' . $storedImage;
        } elseif (!empty($validated['template_image'])) {
            $imagePath = $validated['template_image'];
        } else {
            return back()
                ->withErrors(['image' => 'The vehicle image field is required.'])
                ->withInput();
        }

        Vehicles::create([
            'vendor_id' => Auth::id(),
            'name' => $validated['name'],
            'type' => VehicleType::from($validated['type']),
            'category' => $validated['category'],
            'location' => $validated['location'],
            'price_per_day' => $validated['price_per_day'],
            'image' => $imagePath,
            'images' => [$imagePath],
            'description' => $validated['description'],
            'seats' => $validated['seats'],
            'transmission' => $validated['transmission'],
            'fuel' => $validated['fuel'],
            'rating' => 0,
            'reviews' => 0,
            'available' => $request->boolean('available'),
            'features' => [],
        ]);

        return redirect()
            ->route('vendor.vehicles.index')
            ->with('success', 'Vehicle added successfully.');
    }

    public function edit(Vehicles $vehicle)
    {
        abort_unless($vehicle->vendor_id === Auth::id(), 403);

        $allVehicles = Vehicles::orderBy('name')->get();

        return view('vendor.vehicles.edit', compact('vehicle', 'allVehicles'));
    }

    public function update(Request $request, Vehicles $vehicle)
    {
        abort_unless($vehicle->vendor_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Car,Bike,Scooter,Bus',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'required|string',
            'seats' => 'required|integer|min:1',
            'transmission' => 'required|string|max:255',
            'fuel' => 'required|string|max:255',
            'available' => 'nullable|boolean',
        ]);

        $imagePath = $vehicle->image;

        if ($request->hasFile('image')) {
            $storedImage = $request->file('image')->store('images/vehicles', 'public');
            $imagePath = 'storage/' . $storedImage;
        }

        $vehicle->update([
            'name' => $validated['name'],
            'type' => VehicleType::from($validated['type']),
            'category' => $validated['category'],
            'location' => $validated['location'],
            'price_per_day' => $validated['price_per_day'],
            'image' => $imagePath,
            'images' => [$imagePath],
            'description' => $validated['description'],
            'seats' => $validated['seats'],
            'transmission' => $validated['transmission'],
            'fuel' => $validated['fuel'],
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