@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Roles</h1>

    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mb-3">+ Add Role</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>{{ $role->description }}</td>
                         <td>
                 <form action="{{ route('roles.toggle-active',$role->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $role->active ? 'btn-success':'btn-secondary' }}">
                                {{ $role->active ? 'Active':'Inactive' }}
                            </button>
                        </form>
</td>
                <td>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this role?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No roles found.</td></tr>
        @endforelse
        </tbody>
    </table>
       {{ $roles->links() }}
</div>
@endsection
