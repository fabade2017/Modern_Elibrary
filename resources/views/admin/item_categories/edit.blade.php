@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Item Category</h1>
<a href="{{ route('item_categories.index') }}" class="btn btn-primary mb-3">Return</a>
<form action="{{ route('item_categories.update', $item_category) }}" method="POST">
    @csrf
    @method('PUT')

        <div class="mb-3">
            <label class="form-label">Item Category Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $item_category->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $item_category->description) }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" @checked($item_category->active)>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Update Category</button>
    </form>
</div>
@endsection
