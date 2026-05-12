<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - Vehicles</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="home-body">

@include('partials.navbar')

<main class="vehicles-page-wrapper">

    <section class="vehicles-page-header">
        <h1>Available Vehicles</h1>
        <p>{{ $vehicles->count() }} vehicles found</p>
    </section>

    @php
        $visibleTypes = $vehicles->map(function ($vehicle) {
            return $vehicle->type->value ?? $vehicle->type;
        })->unique()->values();

        $categoryTypes = $vehicles->groupBy('category')->map(function ($items) {
            return $items->map(function ($vehicle) {
                return strtolower($vehicle->type->value ?? $vehicle->type);
            })->unique()->values();
        })->sortKeys();
    @endphp

    <section class="vehicles-layout">

        <aside class="vehicles-filter-card">
            <h3>Filters</h3>

            <div class="filter-group">
                <h4>Price Range</h4>
                <input type="range" id="priceRange" min="{{ $priceRange['min'] }}" max="{{ $priceRange['max'] }}" value="{{ $priceRange['max'] }}" style="width:100%">
                <div class="price-labels">
                    <span>RS {{ number_format($priceRange['min'], 0) }}</span>
                    <span id="priceDisplay">RS {{ number_format($priceRange['max'], 0) }}</span>
                </div>
            </div>

            <div class="filter-group">
                <h4>Vehicle Type</h4>
                @forelse ($visibleTypes as $type)
                    <label class="filter-check">
                        <input type="checkbox" class="type-filter" value="{{ strtolower($type) }}"> {{ $type }}
                    </label>
                @empty
                    <p>No types available</p>
                @endforelse
            </div>

            <div class="filter-group">
                <h4>Category</h4>
                @forelse ($categoryTypes as $category => $categoryTypesForCategory)
                    <label class="filter-check category-filter-row" data-category-types="{{ $categoryTypesForCategory->implode(',') }}">
                        <input type="checkbox" class="category-filter" value="{{ strtolower($category) }}"> {{ $category }}
                    </label>
                @empty
                    <p>No categories available</p>
                @endforelse
            </div>

            <button class="reset-filter-btn" id="resetFilters">Reset Filters</button>
        </aside>

        <section class="vehicles-grid-page">
            @if ($isLoading)
                <div class="vehicle-card page-card">
                    <div class="vehicle-content">
                        <p class="text-gray-500">Loading vehicles...</p>
                    </div>
                </div>
            @elseif ($errorMessage)
                <div class="vehicle-card page-card">
                    <div class="vehicle-content">
                        <p class="text-red-600">{{ $errorMessage }}</p>
                    </div>
                </div>
            @else
                @forelse ($vehicles as $vehicle)
                    <div
                        class="vehicle-card page-card"
                        data-type="{{ strtolower($vehicle->type->value ?? $vehicle->type) }}"
                        data-category="{{ strtolower($vehicle->category) }}"
                        data-price="{{ (float) $vehicle->price_per_day }}"
                        onclick="window.location='{{ route('vehicles.show', $vehicle) }}'"
                    >
                        <div class="vehicle-image-wrapper">
                            <img src="{{ asset($vehicle->image) }}" alt="{{ $vehicle->name }}">
                            <span class="vehicle-tag">{{ $vehicle->category }}</span>
                        </div>

                        <div class="vehicle-content">
                            <div class="vehicle-header">
                                <div>
                                    <h3>{{ $vehicle->name }}</h3>
                                    <p>{{ $vehicle->vendor?->name ?? 'Unknown Vendor' }}</p>
                                </div>
                                <span class="rating">{{ number_format($vehicle->rating, 1) }}</span>
                            </div>

                            <small>{{ $vehicle->reviews }} reviews</small>

                            <div class="vehicle-meta">
                                <span>{{ $vehicle->seats }}</span>
                                <span>{{ $vehicle->transmission }}</span>
                                <span>{{ $vehicle->fuel }}</span>
                            </div>

                            <div class="vehicle-footer">
                                <div class="price">RS {{ number_format($vehicle->price_per_day, 0) }}<span>/day</span></div>
                                <a href="{{ route('vehicles.show', $vehicle) }}" onclick="event.stopPropagation();">View Details →</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="vehicle-card page-card">
                        <div class="vehicle-content">
                            <p class="text-gray-500">No vehicles available right now.</p>
                        </div>
                    </div>
                @endforelse
            @endif
        </section>

    </section>
</main>

<script>
const allCards = Array.from(document.querySelectorAll('.vehicle-card.page-card'));
const priceRange = document.getElementById('priceRange');
const priceDisplay = document.getElementById('priceDisplay');
const resetBtn = document.getElementById('resetFilters');

function formatRs(value) {
    return 'RS ' + Number(value).toLocaleString('en-US');
}

function updateCategoryFilters() {
    const checkedTypes = Array.from(document.querySelectorAll('.type-filter:checked')).map(el => el.value.toLowerCase());

    document.querySelectorAll('.category-filter-row').forEach(row => {
        const allowedTypes = (row.dataset.categoryTypes || '').split(',');

        if (checkedTypes.length === 0 || checkedTypes.some(type => allowedTypes.includes(type))) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
            const checkbox = row.querySelector('.category-filter');
            if (checkbox) checkbox.checked = false;
        }
    });
}

function applyFilters() {
    const maxPrice = parseInt(priceRange.value);
    const checkedTypes = Array.from(document.querySelectorAll('.type-filter:checked')).map(el => el.value.toLowerCase());
    const checkedCats = Array.from(document.querySelectorAll('.category-filter:checked')).map(el => el.value.toLowerCase());

    allCards.forEach(card => {
        const cardPrice = Number(card.dataset.price || 0);
        const cardType = card.dataset.type || '';
        const cardCat = card.dataset.category || '';

        const priceOk = cardPrice <= maxPrice;
        const typeOk = checkedTypes.length === 0 || checkedTypes.includes(cardType);
        const catOk = checkedCats.length === 0 || checkedCats.includes(cardCat);

        card.style.display = (priceOk && typeOk && catOk) ? '' : 'none';
    });
}

priceRange.addEventListener('input', function() {
    priceDisplay.textContent = formatRs(this.value);
    applyFilters();
});

document.querySelectorAll('.type-filter').forEach(cb => {
    cb.addEventListener('change', function() {
        updateCategoryFilters();
        applyFilters();
    });
});

document.querySelectorAll('.category-filter').forEach(cb => {
    cb.addEventListener('change', applyFilters);
});

resetBtn.addEventListener('click', function() {
    priceRange.value = priceRange.max;
    priceDisplay.textContent = formatRs(priceRange.max);
    document.querySelectorAll('.type-filter, .category-filter').forEach(cb => cb.checked = false);
    updateCategoryFilters();
    applyFilters();
});

updateCategoryFilters();
</script>

</body>
</html>