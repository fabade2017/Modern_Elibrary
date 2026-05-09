@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Arms</h1>
@if(Auth::user()->role_id === 1)
    <a href="{{ route('admin.groups.create') }}" class="btn btn-primary mb-3">+ Add Arm</a>
@endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
         @if(Auth::user()->role_id === 1)       <th>Active</th>
                <th>Actions</th>@endif
            </tr>
        </thead>
        <tbody>
        @forelse($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td>{{ $group->description }}</td>
          @if(Auth::user()->role_id === 1)       <td>
                 <form action="{{ route('groups.toggle-active',$group->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $group->active ? 'btn-success':'btn-secondary' }}">
                                {{ $group->active ? 'Active':'Inactive' }}
                            </button>
                        </form>
</td>
                <td>
                    <a href="{{ route('admin.groups.edit', $group) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.groups.destroy', $group) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this group?')">Delete</button>
                    </form>
                </td>@endif
            </tr>
        @empty
            <tr><td colspan="4">No groups found.</td></tr>
        @endforelse
        </tbody>
    </table>{{ $groups->links() }}
</div>
@endsection
