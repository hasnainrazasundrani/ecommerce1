<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->get();
    }

    public function getProduct($id)
    {
        $product = Product::find($id);

        // Check if product exists
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Return the product details as JSON
        return response()->json($product);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);
        return Product::create($request->all());
    }

    public function show(Product $product)
    {
        return $product->load('category');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);
        $product->update($request->all());
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
