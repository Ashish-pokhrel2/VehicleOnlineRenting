@extends('layouts.vendor')

@section('content')

<div class="max-w-[980px] mx-auto">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Vehicles</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $vehicles->count() }} vehicles in your fleet
            </p>
        </div>

        <a href="{{ route('vendor.vehicles.create') }}"
           class="bg-gray-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-800">
            + Add Vehicle
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($vehicles as $vehicle)

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                <div class="relative h-32 bg-gray-100 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset($vehicle->image ?? 'images/vehicles/car1.jpg') }}"
                         alt="{{ $vehicle->name }}"
                         class="max-h-full max-w-full object-contain">

                    <span class="absolute top-3 right-3 text-xs px-2 py-1 rounded-full
                        {{ $vehicle->available ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                        {{ $vehicle->available ? 'Available' : 'Unavailable' }}
                    </span>
                </div>

                <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="font-bold text-gray-900 text-sm truncate">
                                {{ $vehicle->name }}
                            </h2>

                            <span class="inline-block mt-1 text-[11px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                {{ $vehicle->category }}
                            </span>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <div class="flex items-center justify-end gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                     class="w-3.5 h-3.5 text-yellow-400" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447 1.287 3.966c.3.921-.755 1.688-1.538 1.118L10 13.348l-3.368 2.886c-.783.57-1.838-.197-1.538-1.118l1.287-3.966-3.368-2.447c-.783-.57-.38-1.81.588-1.81h4.162l1.286-3.966z"/>
                                </svg>
                                <span class="text-xs font-semibold">
                                    {{ $vehicle->rating }}
                                </span>
                            </div>

                            <p class="text-[11px] text-gray-500">
                                ({{ $vehicle->reviews }})
                            </p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="text-lg font-bold text-blue-600">
                            Rs. {{ number_format($vehicle->price_per_day) }}
                        </span>
                        <span class="text-xs text-gray-500">/day</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <a href="{{ route('vendor.vehicles.edit', $vehicle->id) }}"
                           class="text-center border border-gray-200 rounded-lg py-1.5 text-sm hover:bg-gray-50">
                            Edit
                        </a>

                        <button type="button"
                                onclick="openDeleteModal('{{ route('vendor.vehicles.destroy', $vehicle->id) }}', '{{ $vehicle->name }}')"
                                class="w-full border border-gray-200 rounded-lg py-1.5 text-sm text-red-600 hover:bg-red-50">
                            Delete
                        </button>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-span-3 bg-white border border-gray-200 rounded-xl p-10 text-center">
                <p class="font-semibold text-gray-700">No vehicles found</p>
                <p class="text-sm text-gray-500 mt-1">
                    Add your first vehicle to start accepting bookings.
                </p>
            </div>
        @endforelse
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <h2 class="text-xl font-bold text-gray-900">Delete Vehicle?</h2>
        <p class="mt-2 text-sm text-gray-600">
            Are you sure you want to delete <span id="deleteVehicleName" class="font-semibold"></span>? This action cannot be undone.
        </p>

        <form id="deleteForm" method="POST" class="mt-6">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="rounded-xl border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>

                <button type="submit"
                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(action, vehicleName) {
        document.getElementById('deleteForm').setAttribute('action', action);
        document.getElementById('deleteVehicleName').textContent = vehicleName;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>

@endsection