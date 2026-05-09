@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Assign Users to Resources</h1>

    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('admin.resource-access.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4 mb-3">
                <input type="text" name="search" class="form-control" placeholder="Search by title, category, or type" value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-3 mb-3">
                <select name="category_id" class="form-control">
                    <option value="">All Item Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <select name="resource_type_id" class="form-control">
                    <option value="">All Resource Types</option>
                    @foreach($resource_types as $resource_type)
                        <option value="{{ $resource_type->id }}" {{ $resource_type_id == $resource_type->id ? 'selected' : '' }}>{{ $resource_type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Assignment Form -->
    <form action="{{ route('admin.resource-access.store') }}" method="POST">
        @csrf
        <div class="mb-3">
                  <!-- <label for="user_id">User</label>
      <select name="user_id" class="form-control selectpicker" data-live-search="true" data-sort="true">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role->name ?? $user->role }})</option>
                @endforeach
            </select> -->
        </div>
        <div class="mb-3">
            <label>Resources (select or leave blank for all)</label>
            <div class="mb-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="select-all-resources">
                    <label class="form-check-label" for="select-all-resources">Select All Resources</label>
                </div>
            </div>
            <div class="row">
                @forelse($resources as $resource)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $resource->file_path) }}" alt="{{ $resource->title }}" class="card-img-top" style="width: 100px; height: 100px; object-fit: cover; margin: 10px auto;">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <input type="checkbox" name="resource_ids[]" class="form-check-input resource-checkbox" value="{{ $resource->id }}" id="resource_{{ $resource->id }}">
                                    <label class="form-check-label" for="resource_{{ $resource->id }}">{{ $resource->title }}</label>
                                </h5>
                                <p class="card-text">
                                    Category: {{ $resource->itemCategory->name }}<br>
                                    Type: {{ $resource->type->name }}<br>
                                    <small>{{ Str::limit($resource->description, 50) }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p>No resources found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="row mb-3">
   

        <div class="col-md-2">
            <label for="role_id">Role</label>
            <select name="role_id" class="form-control">
                <option value="">None</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="class_id">Class</label>
            <select name="class_id" class="form-control">
                <option value="">None</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

         <div class="col-md-2">
            <label for="category_id">Category</label>
            <select name="category_id" class="form-control">
                <option value="">None</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="arm_id">Arm</label>
            <select name="arm_id" class="form-control">
                <option value="">None</option>
                @foreach($arms as $arm)
                    <option value="{{ $arm->id }}">{{ $arm->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="group_id">Group</label>
            <select name="group_id" class="form-control">
                <option value="">None</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary col-md-2">Assign Access</button>
</div>
        
    </form>

    <!-- Current Assignments -->
    <h2 class="mt-5">Current Assignments</h2>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Resource</th>
                <th>Role</th>
                <th>Class</th>
                  <th>Category</th>
                    <th>Arm</th>
                <th>Group</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resourceAccesses as $access)
                <tr>
                    <td>{{ $access->user->name }}</td>
                    <td>{{ $access->resource ? $access->resource->title : 'All Resources' }}</td>
                    <td>{{ ucfirst(\App\Models\Role::find($access->role_id)->name ?? 'N/A') }}</td>
                    <td>{{ ucfirst(\App\Models\Classes::find($access->class_id)->name ?? 'N/A') }}</td>
                     <td>{{ ucfirst(\App\Models\ItemCategory::find($access->category_id)->name ?? 'N/A') }}</td>
                      <td>{{ ucfirst(\App\Models\Group::find($access->arm_id)->name ?? 'N/A') }}</td>
                    <td>{{ ucfirst(\App\Models\Group::find($access->groups_id)->name ?? 'N/A') }}</td>
                    <td>
                        <form action="{{ route('admin.resource-access.destroy', $access) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
       {{ $resourceAccesses->links() }}
</div>

<script>
    // Select All Checkbox Logic
    document.getElementById('select-all-resources').addEventListener('change', function() {
        document.querySelectorAll('.resource-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Deselect "Select All" if any individual checkbox is unchecked
    document.querySelectorAll('.resource-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                document.getElementById('select-all-resources').checked = false;
            }
        });
    });
</script>
@endsection
