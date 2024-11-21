@extends('admin.dashboard')

@section('content')
<div class="container">
    <h1>Add New Category</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Category</button>
    </form>
</div>
@endsection