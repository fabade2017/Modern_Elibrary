@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Add Privileges</h1>
<a href="{{ route('admin.privileges.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('admin.privileges.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label"> Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Module</label>
            <textarea name="slug" class="form-control">{{ old('slug') }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" checked>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Save Class</button>
    </form>
</div>
@endsection
