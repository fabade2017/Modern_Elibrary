@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Permissions</h1>
    @if(Auth::user()->role_id === 1)
    <a href="{{ route('permissions.create') }}" class="btn btn-primary">Create Permission</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissions as $permission)
            <tr>
                <td>{{ $permission->name }}</td>
                <td>{{ $permission->slug }}</td>
                 <td>
                 <form action="{{ route('permissions.toggle-active',$permission->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $permission->active ? 'btn-success':'btn-secondary' }}">
                                {{ $permission->active ? 'Active':'Inactive' }}
                            </button>
                        </form>
</td>
                <td>
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this permission?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $permissions->links() }}
    @endif
</div>
@endsection
