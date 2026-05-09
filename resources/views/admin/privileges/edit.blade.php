@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Privileges</h1>

    <form action="{{ route('admin.privileges.update', $privilege) }}" method="POST">
        @csrf @method('POST')

        <div class="mb-3">
            <label class="form-label">Class Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $privilege->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="slug" class="form-control">{{ old('slug', $privilege->slug) }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" @checked($privilege->active)>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Update Class</button>
    </form>
</div>
@endsection
