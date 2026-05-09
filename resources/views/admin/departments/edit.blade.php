@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Department</h1>
<a href="{{ route('departments.index') }}" class="btn btn-primary mb-3">Return</a>
<form action="{{ route('departments.update', $department) }}" method="POST">
    @csrf
    @method('PUT')

        <div class="mb-3">
            <label class="form-label">Department Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $department->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $department->description) }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" @checked($department->active)>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Update Department</button>
    </form>
</div>
@endsection
