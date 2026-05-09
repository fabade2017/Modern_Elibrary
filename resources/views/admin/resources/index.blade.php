@extends('layouts.admin')

@section('content')
<div class="container">
<h1>Resources</h1>
@if(Auth::user()->role_id === 1)
<a href="{{ route('admin.resources.create') }}" class="btn btn-primary mb-3">+ Add Resource</a>
@endif
<table class="table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
              <th>Item Category</th>
        @if(Auth::user()->role_id === 1)    <th>Active</th>@endif
            <th>Downloadable</th>
            <th>Viewable</th>
      @if(Auth::user()->role_id === 1)      <th>Featured</th>
            <th>Actions</th>@endif
        </tr>
    </thead>
    <tbody>
        @foreach($resources as $resource)
        <tr>
            <td>{{ $resource->title }}</td>
           <td>{{ ucfirst(\App\Models\ResourceType::find($resource->resource_type_id)->name ?? 'N/A') }}</td>
            <td>{{ ucfirst(\App\Models\ItemCategory::find($resource->category_id)->name ?? 'N/A') }}</td>
      @if(Auth::user()->role_id === 1)     <td>
                 <form action="{{ route('resources.toggle-active',$resource) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $resource->active ? 'btn-success':'btn-secondary' }}">
                                {{ $resource->active ? 'Active':'Inactive' }}
                            </button>
                        </form>
        </td>@endif
                    <td>{{ $resource->downloadable ? 'Yes' : 'No' }}</td>
                    <td>{{ $resource->view_online ? 'Yes' : 'No' }}</td>
                    
       @if(Auth::user()->role_id === 1)                   <td>
                 <form action="{{ route('resources.toggle-feature',$resource) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $resource->is_featured ? 'btn-success':'btn-secondary' }}">
                                {{ $resource->is_featured ? 'Active':'Inactive' }}
                            </button>
                        </form>
        @if($resource->is_featured)
                <span class="badge bg-warning text-dark">🌟 Featured</span>
            @endif </td>
           
   
            <td>
                <a href="{{ route('admin.resources.edit', $resource) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.resources.destroy', $resource) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete resource?')">Delete</button>
                </form>
            </td>@endif
        </tr>
        @endforeach
    </tbody>
</table>   {{ $resources->links() }}
</div>

@endsection
