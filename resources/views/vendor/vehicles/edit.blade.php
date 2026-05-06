@extends('layouts.vendor')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-5">
        <a href="{{ route('vendor.vehicles.index') }}" class="text-sm text-blue-600 hover:underline">
            ← Back to vehicles
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-3">Edit Vehicle</h1>
        <p class="text-sm text-gray-500 mt-1">Update vehicle information and availability.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="font-semibold text-red-700 mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('vendor.vehicles.update', $vehicle->id) }}"
          enctype="multipart/form-data"
          novalidate
          class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Vehicle Name</label>
                <input type="text" name="name" value="{{ old('name', $vehicle->name) }}"
                       class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Vehicle Type</label>
                <select name="type"
                        class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select type</option>
                    @foreach(['Car', 'Bike', 'Scooter', 'Bus'] as $type)
                        <option value="{{ $type }}" {{ old('type', $vehicle->type->value ?? $vehicle->type) === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
                @error('type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                <input type="text" name="category" value="{{ old('category', $vehicle->category) }}"
                       class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                @error('category') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Price Per Day</label>
                <input type="number" name="price_per_day" value="{{ old('price_per_day', $vehicle->price_per_day) }}"
                       class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                @error('price_per_day') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                <input type="text" name="location" value="{{ old('location', $vehicle->location) }}"
                       class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Seats</label>
                <input type="number" name="seats" value="{{ old('seats', $vehicle->seats) }}"
                       class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                @error('seats') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Transmission</label>
                <input type="text" name="transmission" value="{{ old('transmission', $vehicle->transmission) }}"
                       class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fuel</label>
                <input type="text" name="fuel" value="{{ old('fuel', $vehicle->fuel) }}"
                       class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Image</label>
            <div class="w-48 h-28 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden mb-3">
                <img src="{{ asset($vehicle->image ?? 'images/vehicles/car1.jpg') }}"
                     alt="{{ $vehicle->name }}"
                     class="max-h-full max-w-full object-contain">
            </div>

            <label class="block text-sm font-semibold text-gray-700 mb-1">Replace Image</label>
            <input type="file" name="image"
                   class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-white">
            @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="4"
                      class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">{{ old('description', $vehicle->description) }}</textarea>
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="available" value="1"
                   {{ old('available', $vehicle->available) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-blue-600">
            <span class="text-sm font-medium text-gray-700">Available for booking</span>
        </label>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('vendor.vehicles.index') }}"
               class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50">
                Cancel
            </a>

            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-green-600 text-white font-medium hover:bg-green-700">
                Update Vehicle
            </button>
        </div>
    </form>
</div>

@endsection