@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Classes</h1>
<a href="{{ route('classes.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('admin.classes.update', $class) }}" method="POST">
        @csrf @method('POST')

        <div class="mb-3">
            <label class="form-label">Class Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $class->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $class->description) }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" @checked($class->active)>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Update Class</button>
    </form>
</div>
@endsection
