@extends('layouts.vendor')

@section('content')

@php
    $vehicleData = $allVehicles->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'type' => $item->type->value ?? $item->type,
            'category' => $item->category,
            'location' => $item->location,
            'price_per_day' => $item->price_per_day,
            'seats' => $item->seats,
            'transmission' => $item->transmission,
            'fuel' => $item->fuel,
            'description' => $item->description,
            'image' => asset($item->image ?? 'images/vehicles/car1.jpg'),
            'update_url' => route('vendor.vehicles.update', $item->id),
        ];
    })->values();
@endphp

<div class="max-w-3xl mx-auto">

    <div class="mb-5">
        <a href="{{ route('vendor.vehicles.index') }}" class="text-sm text-blue-600 hover:underline">
            ← Back to vehicles
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-3">Edit Vehicle</h1>
        <p class="text-sm text-gray-500 mt-1">Choose a vehicle and update its information.</p>
    </div>

    <form id="vehicleEditForm"
          method="POST"
          action="{{ route('vendor.vehicles.update', $vehicle->id) }}"
          enctype="multipart/form-data"
          novalidate
          class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Vehicle Name</label>
                <input list="vehicleNameList"
                       id="vehicle_name"
                       name="name"
                       value="{{ old('name', $vehicle->name) }}"
                       class="live-field w-full rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">

                <datalist id="vehicleNameList">
                    @foreach($allVehicles as $item)
                        <option value="{{ $item->name }}"></option>
                    @endforeach
                </datalist>

                @error('name')
                    <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Vehicle Type</label>
                <select id="vehicle_type"
                        name="type"
                        class="live-field w-full rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('type') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Select type</option>
                    @foreach(['Car', 'Bike', 'Scooter', 'Bus'] as $type)
                        <option value="{{ $type }}" {{ old('type', $vehicle->type->value ?? $vehicle->type) === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>

                @error('type')
                    <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                <input type="text"
                       id="vehicle_category"
                       name="category"
                       value="{{ old('category', $vehicle->category) }}"
                       class="live-field w-full rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('category') ? 'border-red-500' : 'border-gray-300' }}">

                @error('category')
                    <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Price Per Day</label>
                <input type="number"
                       id="vehicle_price"
                       name="price_per_day"
                       value="{{ old('price_per_day', $vehicle->price_per_day) }}"
                       class="live-field w-full rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('price_per_day') ? 'border-red-500' : 'border-gray-300' }}">

                @error('price_per_day')
                    <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                <input type="text"
                       id="vehicle_location"
                       name="location"
                       value="{{ old('location', $vehicle->location) }}"
                       class="live-field w-full rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('location') ? 'border-red-500' : 'border-gray-300' }}">

                @error('location')
                    <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Seats</label>
                <input type="number"
                       id="vehicle_seats"
                       name="seats"
                       value="{{ old('seats', $vehicle->seats) }}"
                       class="live-field w-full rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('seats') ? 'border-red-500' : 'border-gray-300' }}">

                @error('seats')
                    <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Transmission</label>
                <input type="text"
                       id="vehicle_transmission"
                       name="transmission"
                       value="{{ old('transmission', $vehicle->transmission) }}"
                       class="live-field w-full rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('transmission') ? 'border-red-500' : 'border-gray-300' }}">

                @error('transmission')
                    <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fuel</label>
                <input type="text"
                       id="vehicle_fuel"
                       name="fuel"
                       value="{{ old('fuel', $vehicle->fuel) }}"
                       class="live-field w-full rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('fuel') ? 'border-red-500' : 'border-gray-300' }}">

                @error('fuel')
                    <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Image</label>
            <div class="w-48 h-28 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden mb-3">
                <img id="current_vehicle_image"
                     src="{{ asset($vehicle->image ?? 'images/vehicles/car1.jpg') }}"
                     alt="{{ $vehicle->name }}"
                     class="max-h-full max-w-full object-contain">
            </div>

            <label class="block text-sm font-semibold text-gray-700 mb-1">Replace Image</label>
            <input type="file"
                   name="image"
                   class="live-field w-full rounded-xl border px-4 py-3 bg-white {{ $errors->has('image') ? 'border-red-500' : 'border-gray-300' }}">

            @error('image')
                <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
            <textarea id="vehicle_description"
                      name="description"
                      rows="4"
                      class="live-field w-full rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }}">{{ old('description', $vehicle->description) }}</textarea>

            @error('description')
                <p class="field-error text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox"
                   name="available"
                   value="1"
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

<script>
    const vehicleData = @json($vehicleData);
    const nameInput = document.getElementById('vehicle_name');
    const editForm = document.getElementById('vehicleEditForm');

    nameInput.addEventListener('change', function () {
        const selectedVehicle = vehicleData.find(vehicle => vehicle.name === this.value);

        if (!selectedVehicle) {
            return;
        }

        editForm.setAttribute('action', selectedVehicle.update_url);

        document.getElementById('vehicle_type').value = selectedVehicle.type ?? '';
        document.getElementById('vehicle_category').value = selectedVehicle.category ?? '';
        document.getElementById('vehicle_location').value = selectedVehicle.location ?? '';
        document.getElementById('vehicle_price').value = selectedVehicle.price_per_day ?? '';
        document.getElementById('vehicle_seats').value = selectedVehicle.seats ?? '';
        document.getElementById('vehicle_transmission').value = selectedVehicle.transmission ?? '';
        document.getElementById('vehicle_fuel').value = selectedVehicle.fuel ?? '';
        document.getElementById('vehicle_description').value = selectedVehicle.description ?? '';
        document.getElementById('current_vehicle_image').src = selectedVehicle.image;

        clearAllErrors();
    });

    document.querySelectorAll('.live-field').forEach(function (field) {
        field.addEventListener('input', function () {
            clearFieldError(field);
        });

        field.addEventListener('change', function () {
            clearFieldError(field);
        });
    });

    function clearFieldError(field) {
        const wrapper = field.closest('div');
        const error = wrapper ? wrapper.querySelector('.field-error') : null;

        if (error) {
            error.remove();
        }

        field.classList.remove('border-red-500');
        field.classList.add('border-gray-300');
    }

    function clearAllErrors() {
        document.querySelectorAll('.field-error').forEach(function (error) {
            error.remove();
        });

        document.querySelectorAll('.live-field').forEach(function (field) {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        });
    }
</script>

@endsection