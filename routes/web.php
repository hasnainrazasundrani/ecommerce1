<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthsController;
use App\Http\Controllers\RatingController;


Auth::routes();

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index']); // Admin Dashboard
    // Products
    Route::get('products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');

    // Categories
    Route::get('categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('categories/create', [AdminController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('categories/{id}/edit', [AdminController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('categories/{id}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
});


Route::middleware('auth')->group(function () {
    // Add product to cart
    Route::post('/cart/add', [CartController::class, 'addToCart']);

    // Add rating to product
    Route::post('/product/{product}/rate', [CartController::class, 'addRating']);

    // View cart
    Route::get('/cart', [CartController::class, 'viewCart']);

    Route::post('/rating/add', [RatingController::class, 'addRating']);
    Route::post('/review-product', [RatingController::class, 'addReview']);
    Route::get('/rating/{productId}', [RatingController::class, 'getRatings']);
    Route::get('/review/{productId}', [RatingController::class, 'getReview']);

});

Route::get('/product/{productId}/reviews', [RatingController::class, 'getCustomersReviews']);

Route::controller(AuthsController::class)->group(function(){
    // Route::post('registerUser','register');
    Route::post('loginUser','login');
    Route::get('usetdetail','userDetails');
});

Route::post('/registerUser', [AuthsController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logoutUser', [AuthsController::class, 'logout']);

Route::get('/getproducts', [ProductController::class, 'index']);
Route::get('/getproduct/{id}', [ProductController::class, 'getProduct']);

Route::get('/check-auth', function () {
    return response()->json(['loggedIn' => auth()->check()]);
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');