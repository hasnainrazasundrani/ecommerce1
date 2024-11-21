@extends('admin.dashboard')

@section('content')
<div class="container">
    <h1>Edit Product</h1>

    <!-- Form to edit product with image upload -->
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div>
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required>
        </div>

        <div>
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        @if ($product->image)
            <div>
                <p>Current Image:</p>
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 200px;">
            </div>
        @endif

        <div>
            <button type="submit">Update Product</button>
        </div>
    </form>
</div>
@endsection