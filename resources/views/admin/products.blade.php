@extends('admin.dashboard')

@section('content')
<div class="container">
    <h1>Manage Products</h1>
    
    <!-- Add Product Button -->
    <a href="{{ url('admin/products/create') }}" class="btn btn-primary">Add New Product</a>

    <!-- Product Table -->
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>${{ $product->price }}</td>
                    <td>
                        <a href="{{ url('admin/products/' . $product->id . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ url('admin/products/' . $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection