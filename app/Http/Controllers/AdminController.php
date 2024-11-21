<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');  // Return the view for the admin dashboard
    }

    public function products()
    {
        $products = Product::with('category')->get();
        return view('admin.products', compact('products'));
    }

    // Show product creation form
    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.create_product', compact('categories'));
    }

    // Store a new product
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validate image
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Create new product
        $product = new Product();
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->category_id = $validated['category_id'];
        $product->image = $imagePath ?? null; // Store the image path if exists
        $product->save();

        // return redirect()->route('admin.products.index')->with('success', 'Product created successfully');

        return redirect('admin/products');
    }

    // Show product edit form
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.edit_product', compact('product', 'categories'));
    }

    // Update product
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validate image
        ]);

        // Handle image upload (if any)
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        // Update product details
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->category_id = $validated['category_id'];
        $product->save();

        // return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');

        return redirect('admin/products');
    }

    // Delete product
    public function destroyProduct($id)
    {
        Product::destroy($id);
        return redirect('admin/products');
    }

    // Show list of categories
    public function categories()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    // Show category creation form
    public function createCategory()
    {
        return view('admin.create_category');
    }

    // Store a new category
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect('admin/categories');
    }

    // Show category edit form
    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.edit_category', compact('category'));
    }

    // Update category
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect('admin/categories');
    }

    // Delete category
    public function destroyCategory($id)
    {
        Category::destroy($id);
        return redirect('admin/categories');
    }
}
