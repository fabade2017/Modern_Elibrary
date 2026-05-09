@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Add Resource Type</h1>
<a href="{{ route('admin.resource_types.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('admin.resource_types.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label"> Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" checked>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Save Category</button>
    </form>
</div>
@endsection
