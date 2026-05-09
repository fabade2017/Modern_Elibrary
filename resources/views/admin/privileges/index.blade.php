@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Privileges</h2>
    @if(Auth::user()->role_id === 1)
    <a href="{{ route('admin.privileges.create') }}" class="btn btn-primary mb-3">Add New Privilege</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Module</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($privileges as $privilege)
                <tr>
                    <td>{{ $privilege->name }}</td>
                    <td>{{ $privilege->slug }}</td>
                    <td>
                        <a href="{{ route('admin.privileges.edit',$privilege->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.privileges.destroy',$privilege->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No privileges found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $privileges->links() }}
    @endif
</div>
@endsection
