<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Add product to the cart
    public function addToCart(Request $request)
    {
        $user = Auth::user(); // Get the logged-in user
        $product = Product::findOrFail($request->product_id);

        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            // If the product is already in the cart, just update the quantity
            $cartItem->increment('quantity', $request->quantity);
        } else {
            // If the product is not in the cart, create a new cart item
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Product added to cart successfully!']);
    }

    // Add rating to a product
    public function addRating(Request $request)
    {
        $user = Auth::user(); // Get the logged-in user
        $product = Product::findOrFail($request->product_id);

        // Check if the user has already rated the product
        $rating = $product->ratings()->where('user_id', $user->id)->first();

        if ($rating) {
            // If the user has already rated, update the rating
            $rating->update(['rating' => $request->rating]);
        } else {
            // If the user has not rated, create a new rating
            $product->ratings()->create([
                'user_id' => $user->id,
                'rating' => $request->rating,
            ]);
        }

        return response()->json(['message' => 'Rating added successfully!']);
    }

    // View the cart for a user
    public function viewCart()
    {
        $user = Auth::user(); // Get the logged-in user
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        return response()->json($cartItems);
    }
}