@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Item Category</h1>
@if(Auth::user()->role_id === 1)
    <a href="{{ route('item_categories.create') }}" class="btn btn-primary mb-3">+ Add Class</a>
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
        @forelse($item_categories as $arm)
            <tr>
                <td>{{ $arm->name }}</td>
                <td>{{ $arm->description }}</td>
             @if(Auth::user()->role_id === 1)       <td>
                 <form action="{{ route('item_category.toggle-active',$arm->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $arm->active ? 'btn-success':'btn-secondary' }}">
                                {{ $arm->active ? 'Active':'Inactive' }}
                            </button>
                        </form>
</td>
            <td>
                    <a href="{{ route('item_categories.edit', $arm) }}" class="btn btn-sm btn-warning">Edit</a>
            
               <form action="{ { route('item_categories.destroy', $arm) } }" method="DELETE" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this arm?')">Delete</button>
                    </form> @endif
      
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No Item Category found.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $item_categories->links() }}
</div>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('click', '.delete-arm', function (e) {
    e.preventDefault();

    let button = $(this);
    let url = button.data('url');
    let armId = button.data('id');

    if (confirm("Delete this arm?")) {
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                alert(response.message || 'Arm deleted successfully!');
                // Optionally remove the row from table/list without reload
                $("#arm-row-" + armId).fadeOut();
            },
            error: function (xhr) {
                alert('Something went wrong. Could not delete.');
            }
        });
    }
});
</script>

@endsection