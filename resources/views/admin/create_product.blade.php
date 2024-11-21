@extends('admin.dashboard')

@section('content')
<div class="container">
    <h1>Create Product</h1>

    <!-- Form to create product with image upload -->
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required>{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}" required>
        </div>

        <div>
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>
        </div>

        <div>
            <button type="submit">Create Product</button>
        </div>
    </form>
</div>
@endsection