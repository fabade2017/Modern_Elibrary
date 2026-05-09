@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Permission</h1>
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
        @csrf
        @method('POST')
        
        <div class="mb-3">
            <label for="name" class="form-label">Permission Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $permission->slug) }}" required>
            @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', $permission->active) ? 'checked' : '' }}>
            <label for="active" class="form-check-label">Active</label>
            @error('active') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Permission</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
