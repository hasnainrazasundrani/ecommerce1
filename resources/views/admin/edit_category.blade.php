@extends('admin.dashboard')

@section('content')
<div class="container">
    <h1>Edit Category</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- This is to specify the PUT request -->
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Category</button>
    </form>
</div>
@endsection