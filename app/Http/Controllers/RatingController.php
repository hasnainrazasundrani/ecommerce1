<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Product;

class RatingController extends Controller
{
    /**
     * Add or update a rating for a product.
     */
    public function addRating(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rating = Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        return response()->json([
            'message' => 'Rating submitted successfully',
            'rating' => $rating,
        ]);
    }

    public function addReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'review' => 'required',
        ]);

        $review = Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ],
            [
                'ratingtext' => $request->review,
            ]
        );

        return response()->json([
            'message' => 'Review submitted successfully',
            'rating' => $review,
        ]);
    }

    /**
     * Fetch average rating and user rating for a product.
     */
    public function getRatings($productId)
    {
        $userRating = Rating::with('product')->where('product_id', $productId)->where('user_id', auth()->id())->first();
        return response()->json([
            // 'average_rating' => $averageRating,
            'user_rating' => $userRating ? $userRating->rating : null,
            'user_review' => $userRating ? $userRating->ratingtext : null,
        ]);
    }

    public function getCustomersReviews($productId)
    {
        // Fetch all reviews for the product
        $reviews = Rating::with('user')->where('product_id', $productId)->get();
        // dd($reviews);

        // Calculate average rating
        $averageRating = $reviews->avg('rating');

        return response()->json([
            'averageRating' => round($averageRating, 1), // Rounded to 1 decimal place
            'reviews' => $reviews // Return all reviews
        ]);
    }
}
