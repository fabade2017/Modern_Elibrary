@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Departments</h1>
@if(Auth::user()->role_id === 1)
    <a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">+ Add Class</a>
@endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
              @if(Auth::user()->role_id === 1)         <th>Active</th>
         <th>Actions</th> @endif
            </tr>
        </thead>
        <tbody>
        @forelse($departments as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
             @if(Auth::user()->role_id === 1)       <td>
                 <form action="{{ route('departments.toggle-active',$category->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $category->active ? 'btn-success':'btn-secondary' }}">
                                {{ $category->active ? 'Active':'Inactive' }}
                            </button>
                        </form>
</td>
            <td>
                    <a href="{{ route('departments.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
            
               <form action="{ { route('departments.destroy', $category) } }" method="DELETE" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                    </form> @endif
                    <!-- No need for method spoofing inside the form if using AJAX -->
     <!-- <button class="btn btn-sm btn-danger delete-category" 
        data-id="{{ $category->id }}" 
        data-url="{{ route('departments.destroy', $category) }}">
    Delete
</button> -->
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No departments found.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $departments->links() }}
</div>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('click', '.delete-category', function (e) {
    e.preventDefault();

    let button = $(this);
    let url = button.data('url');
    let categoryId = button.data('id');

    if (confirm("Delete this category?")) {
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                alert(response.message || 'Department deleted successfully!');
                // Optionally remove the row from table/list without reload
                $("#category-row-" + categoryId).fadeOut();
            },
            error: function (xhr) {
                alert('Something went wrong. Could not delete.');
            }
        });
    }
});
</script>

@endsection