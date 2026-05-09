@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create Resource</h1>
<a href="{{ route('admin.resources.index') }}" class="btn btn-primary mb-3">Return</a>
    <form action="{{ route('admin.resources.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="resource_type_id" class="form-label">Resource Type</label>
            <select name="resource_type_id" id="resource_type_id" class="form-control @error('resource_type_id') is-invalid @enderror">
                <option value="">Select Resource Type</option>
                @foreach($resourceType as $type)
                    <option value="{{ $type->id }}" {{ old('resource_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
            @error('resource_type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">File</label>
            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror">
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="downloadable" id="downloadable" class="form-check-input" {{ old('downloadable') ? 'checked' : '' }}>
            <label for="downloadable" class="form-check-label">Downloadable</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="view_online" id="view_online" class="form-check-input" {{ old('view_online') ? 'checked' : '' }}>
            <label for="view_online" class="form-check-label">Viewable Online</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="active" id="active" class="form-check-input" {{ old('active', true) ? 'checked' : '' }}>
            <label for="active" class="form-check-label">Active</label>
        </div>
         <div class="mb-3 form-check">
            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" {{ old('is_featured', true) ? 'checked' : '' }}>
            <label for="is_featured" class="form-check-label">Featured</label>
        </div>
        <button type="submit" class="btn btn-primary">Create Resource</button>
    </form>
</div>
@endsection
@section('script')
<script>
  //  alert();
document.getElementById('file').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const maxSizeMB = 32; // Example: 2 MB limit
        const maxSizeBytes = maxSizeMB * 1024 * 1024;

        if (file.size > maxSizeBytes) {
            alert(`File too large! Max allowed size is ${maxSizeMB} MB.`);
            this.value = ""; // reset input
        }
    }
});
</script>
@endsection