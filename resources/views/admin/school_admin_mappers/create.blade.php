
@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create School Admin Mapping</h1>
<a href="{{ route('school_admin_mappers.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('school_admin_mappers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="adminemail" class="form-label">Admin Email</label>
            <select name="adminemail" class="form-control selectpicker" data-live-search="true" data-sort="true">
                @foreach($users as $user)
                    <option value="{{ $user->email }}">{{ $user->email }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="school" class="form-label">School</label>
            <select name="school" class="form-control selectpicker" data-live-search="true" data-sort="true"">
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="studentcount" class="form-label">Student Count</label>
            <input type="number" name="studentcount" class="form-control">
        </div>

        <div class="mb-3">
            <label for="others" class="form-label">Upload Picture (Optional)</label>
            <input type="file" name="others" class="form-control">
            @error('others')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="active" class="form-label">Active</label>
            <select name="active" class="form-control">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
