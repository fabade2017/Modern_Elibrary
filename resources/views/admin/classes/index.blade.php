@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Classes</h1>
@if(Auth::user()->role_id === 1)
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary mb-3">+ Add Class</a>
@endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
           @if(Auth::user()->role_id === 1)     <th>Active</th>
                <th>Actions</th>@endif
            </tr>
        </thead>
        <tbody>
        @forelse($classes as $class)
            <tr>
                <td>{{ $class->name }}</td>
                <td>{{ $class->description }}</td>
          @if(Auth::user()->role_id === 1)     <td>
                 <form action="{{ route('classes.toggle-active',$class->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $class->active ? 'btn-success':'btn-secondary' }}">
                                {{ $class->active ? 'Active':'Inactive' }}
                            </button>
                        </form>
</td>
               <td>
                      <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-warning">Edit</a>
                    
                    <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this class?')">Delete</button>
                    </form>
                </td>@endif
            </tr>
        @empty
            <tr><td colspan="4">No classes found.</td></tr>
        @endforelse
        </tbody>
    </table>{{ $classes->links() }}
</div>
@endsection
