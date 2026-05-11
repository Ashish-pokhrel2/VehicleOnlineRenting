@extends('layouts.vendor')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-900">Customer Reviews</h1>
        <p class="text-sm text-gray-500 mt-1">See what customers are saying about your service</p>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-4 gap-4 mb-5">
        <div class="bg-white rounded-xl border border-gray-200 p-3 shadow-sm">
            <p class="text-xs text-gray-500">Average Rating</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $averageRating }}</h2>
            <p class="text-xs text-gray-400">out of 5.0</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-3 shadow-sm">
            <p class="text-xs text-gray-500">Total Reviews</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalReviews }}</h2>
            <p class="text-xs text-gray-400">all time</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-3 shadow-sm">
            <p class="text-xs text-gray-500">5-Star Reviews</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $fiveStarReviews }}</h2>
            <p class="text-xs text-gray-400">
                {{ $totalReviews > 0 ? round(($fiveStarReviews / $totalReviews) * 100) : 0 }}%
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-3 shadow-sm">
            <p class="text-xs text-gray-500">This Month</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $thisMonthReviews }}</h2>
            <p class="text-xs text-green-600">+12% from last month</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm h-fit">
            <h2 class="text-sm font-bold text-gray-900 mb-4">Rating Distribution</h2>

            @foreach([5,4,3,2,1] as $star)
                @php
                    $count = $ratingDistribution[$star] ?? 0;
                    $percent = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                @endphp

                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 flex items-center gap-1 text-xs text-gray-700">
                        <span>{{ $star }}</span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-3 h-3 text-yellow-400"
                             viewBox="0 0 20 20"
                             fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.956a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.365 2.445a1 1 0 00-.364 1.118l1.286 3.956c.3.921-.755 1.688-1.539 1.118l-3.365-2.445a1 1 0 00-1.176 0L5.046 18.02c-.784.57-1.838-.197-1.539-1.118l1.286-3.956a1 1 0 00-.364-1.118L1.064 9.383c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.288-3.956z"/>
                        </svg>
                    </div>

                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>

                    <div class="w-5 text-right text-xs text-gray-500">
                        {{ $count }}
                    </div>
                </div>
            @endforeach

            <div class="mt-5 pt-4 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Filter Reviews</h3>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('vendor.reviews.index') }}"
                       class="px-3 py-1.5 rounded-lg border text-xs font-medium
                       {{ !$ratingFilter ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200' }}">
                        All
                    </a>

                    @foreach([5,4,3] as $filter)
                        <a href="{{ route('vendor.reviews.index', ['rating' => $filter]) }}"
                           class="px-3 py-1.5 rounded-lg border text-xs font-medium
                           {{ $ratingFilter == $filter ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200' }}">
                            {{ $filter }} Star
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <h2 class="text-sm font-bold text-gray-900 mb-4">
                All Reviews ({{ $totalReviews }})
            </h2>

            <div class="space-y-4">
                @forelse($reviews as $review)
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($review->customer->name ?? 'U', 0, 1)) }}
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">
                                        {{ $review->customer->name ?? 'Customer' }}
                                    </h3>

                                    <p class="text-xs text-gray-400">
                                        {{ $review->created_at->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                         viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.956a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.365 2.445a1 1 0 00-.364 1.118l1.286 3.956c.3.921-.755 1.688-1.539 1.118l-3.365-2.445a1 1 0 00-1.176 0L5.046 18.02c-.784.57-1.838-.197-1.539-1.118l1.286-3.956a1 1 0 00-.364-1.118L1.064 9.383c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.288-3.956z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>

                        <p class="text-sm text-gray-700 leading-relaxed mt-3">
                            {{ $review->comment }}
                        </p>

                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500">
                                Rented:
                                <span class="font-medium text-gray-700">
                                    {{ $review->vehicle->name ?? 'Vehicle' }}
                                </span>
                            </p>
                        </div>

                        @if($review->vendor_reply)
                            <div class="mt-3 bg-gray-50 border border-gray-100 rounded-lg p-3">
                                <p class="text-xs font-bold text-gray-700 mb-1">Your Reply</p>
                                <p class="text-sm text-gray-700">{{ $review->vendor_reply }}</p>
                            </div>
                        @else
                            <form method="POST" action="{{ route('vendor.reviews.reply', $review) }}" class="mt-3">
                                @csrf

                                <textarea
                                    name="vendor_reply"
                                    rows="1"
                                    required
                                    placeholder="Write your reply..."
                                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

                                <button type="submit"
                                        class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-semibold hover:bg-blue-700 transition">
                                    Save Reply
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-sm text-gray-500">No reviews found.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5">
                {{ $reviews->links() }}
            </div>
        </div>

    </div>
</div>
@endsection