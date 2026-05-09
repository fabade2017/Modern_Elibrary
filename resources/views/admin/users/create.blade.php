@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Add User</h1>
<a href="{{ route('admin.users.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-control">
                <option value="">— Select Role —</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Class</label>
            <select name="class_id" class="form-control">
                <option value="">— None —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Groups</label>
            <select name="groups[]" class="form-control" multiple>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" @selected(collect(old('groups'))->contains($group->id))>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" checked>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-success">Save User</button>
    </form>
</div>
@endsection
