@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Resource Types</h2>
    @if(Auth::user()->role_id === 1)
    <a href="{{ route('admin.resource_types.create') }}" class="btn btn-primary mb-3">Add New Resource Type</a>
@endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
           @if(Auth::user()->role_id === 1)         <th>Active</th>
            <th>Actions</th> @endif
            </tr>
        </thead>
        <tbody>
            @forelse($resourceTypes as $resourceType)
                <tr>
                    <td>{{ $resourceType->name }}</td>
            @if(Auth::user()->role_id === 1)        <td>
                        <form action="{{ route('resource_types.toggle-active',$resourceType->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $resourceType->active ? 'btn-success':'btn-secondary' }}">
                                {{ $resourceType->active ? 'Active':'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.resource_types.edit',$resourceType) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.resource_types.destroy',$resourceType->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td> @endif
                </tr>
            @empty
                <tr><td colspan="3">No resource types found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $resourceTypes->links() }}
</div>
@endsection
