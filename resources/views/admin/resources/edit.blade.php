@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Resource</h1>
<a href="{{ route('admin.resources.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('admin.resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('POST')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required value="{{ old('title', $resource->title) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $resource->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Resource Type</label>
            <select name="resource_type_id" class="form-control" required>
                @foreach($resourceType as $resourcetype)

  <option value="{{ $resourcetype->id }}" {{ old('resourcetype') == $resourcetype->id ? 'selected' : '' }}>{{ $resourcetype->name }}</option>
                    @endforeach
             
            </select>
        </div>
 <div class="mb-3">
            <label for="category_id" class="form-label">Category  </label>
            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
              
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $resource->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
         
        </div>
        <div class="mb-3">
            <label class="form-label">Upload File (leave blank to keep existing)</label>
            <input type="file" name="file" class="form-control">
            @if($resource->file_path)
                <small>Current File: <a href="{{ asset('storage/'.$resource->file_path) }}" target="_blank">View</a></small>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Or External URL</label>
            <input type="url" name="url" class="form-control" value="{{ old('url', $resource->url) }}">
        </div>

        <div class="form-check mb-2">
            <input type="checkbox" name="view_online" value="1" class="form-check-input" id="view_online" @checked($resource->view_online)>
            <label for="view_online" class="form-check-label">Viewable Online</label>
        </div>

        <div class="form-check mb-2">
            <input type="checkbox" name="downloadable" value="1" class="form-check-input" id="downloadable" @checked($resource->downloadable)>
            <label for="downloadable" class="form-check-label">Downloadable</label>
        </div>

        <div class="form-check mb-2">
            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" @checked($resource->active)>
            <label for="active" class="form-check-label">Active</label>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured" @checked($resource->is_featured)>
            <label for="is_featured" class="form-check-label">Mark as Featured</label>
        </div>

        <button type="submit" class="btn btn-success">Update Resource</button>
    </form>
</div>
@endsection
