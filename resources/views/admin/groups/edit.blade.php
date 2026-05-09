@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Arm</h1>
<a href="{{ route('admin.groups.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('admin.groups.update', $group) }}" method="POST">
        @csrf @method('POST')

        <div class="mb-3">
            <label class="form-label">Arm Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $group->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $group->description) }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" @checked($group->active)>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Update Class</button>
    </form>
</div>
@endsection
