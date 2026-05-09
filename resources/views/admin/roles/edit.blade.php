@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Role</h1>
<a href="{{ route('admin.roles.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf @method('POST')

        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $role->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $role->description) }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" @checked($role->active)>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Update Class</button>
    </form>
</div>
@endsection
