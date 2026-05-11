<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class VendorReviewController extends Controller
{
    public function index(Request $request)
    {
        $vendorId = auth()->id();
        $ratingFilter = $request->query('rating');

        $baseQuery = Review::with(['customer', 'vehicle'])
            ->where('vendor_id', $vendorId);

        $reviewsQuery = clone $baseQuery;

        if (in_array($ratingFilter, ['3', '4', '5'])) {
            $reviewsQuery->where('rating', $ratingFilter);
        }

        $reviews = $reviewsQuery
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $totalReviews = (clone $baseQuery)->count();

        $averageRating = $totalReviews > 0
            ? round((clone $baseQuery)->avg('rating'), 1)
            : 0;

        $fiveStarReviews = (clone $baseQuery)
            ->where('rating', 5)
            ->count();

        $thisMonthReviews = (clone $baseQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $ratingDistribution = [
            5 => (clone $baseQuery)->where('rating', 5)->count(),
            4 => (clone $baseQuery)->where('rating', 4)->count(),
            3 => (clone $baseQuery)->where('rating', 3)->count(),
            2 => (clone $baseQuery)->where('rating', 2)->count(),
            1 => (clone $baseQuery)->where('rating', 1)->count(),
        ];

        return view('vendor.reviews.index', compact(
            'reviews',
            'totalReviews',
            'averageRating',
            'fiveStarReviews',
            'thisMonthReviews',
            'ratingDistribution',
            'ratingFilter'
        ));
    }

    public function reply(Request $request, Review $review)
    {
        if ($review->vendor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'vendor_reply' => ['required', 'string', 'max:1000'],
        ]);

        $review->update([
            'vendor_reply' => $request->vendor_reply,
        ]);

        return back()->with('success', 'Reply saved successfully.');
    }
}