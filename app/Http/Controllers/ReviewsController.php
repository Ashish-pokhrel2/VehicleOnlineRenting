<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reviews\StoreReviewsRequest;
use App\Models\Reviews;
use App\Models\Vehicles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Reviews::with(['vehicle:id,name', 'customer:id,name'])
            ->when($request->vehicle_id, fn ($q) => $q->forVehicle($request->vehicle_id))
            ->latest();

        $reviews = $request->paginate
            ? $query->paginate($request->per_page ?? 15)
            : $query->get();

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }

    public function store(StoreReviewsRequest $request): JsonResponse
    {
        $vehicle = Vehicles::findOrFail($request->vehicle_id);

        $review = Reviews::create([
            'vehicle_id' => $request->vehicle_id,
            'customer_id' => auth()->id(),
            'vendor_id' => $vehicle->vendor_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $this->updateVehicleRating($vehicle);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'data' => $review->load(['vehicle:id,name', 'customer:id,name']),
        ], 201);
    }

    public function show(Reviews $review): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $review->load(['vehicle:id,name', 'customer:id,name', 'vendor:id,name']),
        ]);
    }

    public function destroy(Reviews $review): JsonResponse
    {
        if ($review->customer_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this review',
            ], 403);
        }

        $vehicle = $review->vehicle;
        $review->delete();

        $this->updateVehicleRating($vehicle);

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully',
        ]);
    }

    private function updateVehicleRating(Vehicles $vehicle): void
    {
        $reviews = $vehicle->vehicleReviews;

        $vehicle->update([
            'rating' => $reviews->avg('rating') ?? 0,
            'reviews' => $reviews->count(),
        ]);
    }
}
