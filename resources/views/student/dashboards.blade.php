@extends('layouts.student')

@section('content')
<div class="container">
    <h1 class="mb-4">My Library</h1>

    <!-- Featured Section -->
    @if($featured->count())
    <div class="mb-5">
        <h3>🌟 Featured Resources</h3>
        <div class="row">
            @foreach($featured as $resource)
            <div class="col-md-4 mb-4">
                <div class="card border-primary shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $resource->title }}</h5>
                        <p class="card-text">{{ Str::limit($resource->description, 80) }}</p>
                        <span class="badge bg-warning text-dark">{{ ucfirst($resource->resource_type) }}</span>
                        <div class="mt-3">
                            @if($resource->is_viewable)
                                <a href="{{ asset('storage/'.$resource->file_path) }}" target="_blank"
                                   class="btn btn-sm btn-outline-primary">Open</a>
                            @endif
                            @if($resource->is_downloadable && $resource->file_path)
                                <a href="{{ asset('storage/'.$resource->file_path) }}" download
                                   class="btn btn-sm btn-outline-success">Download</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Search and Filter -->
    <form method="POST" action="{{ route('student.dashboard') }}" class="row mb-4">
        <div class="col-md-6">{{$point}}
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                   placeholder="Search resources...">
        </div>
        <!-- <div class="col-md-4">
            <select name="type" class="form-control">
                <option value="">All Types</option>
                <option value="video" @selected(request('type') == 'video')>Video</option>
                <option value="audio" @selected(request('type') == 'audio')>Audio</option>
                <option value="pdf" @selected(request('type') == 'pdf')>PDF</option>
                <option value="book" @selected(request('type') == 'book')>Book</option>
            </select>
        </div> -->
    
         <div class="col-md-4">
            <!-- <label for="type" class="form-label">Resource Type</label> -->
            <select name="type" id="category_id" class="form-control">
                <option value="">Select Category</option>
                @foreach($categories as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Resources Grid -->
    <div class="row">
        @forelse($resources as $resAccess)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $resAccess->resource->title }}</h5>
                    <p class="card-text">{{ Str::limit($resAccess->resource->description, 100) }}</p>
                    <span class="badge bg-info text-dark">{{ ucfirst($resAccess->resource->resource_type_id) }}</span>

                    <div class="mt-3">
                        @if($resAccess->resource->view_online)
                            <a href="{{ asset('storage/'.$resAccess->resource->file_path) }}" target="_blank"
                               class="btn btn-sm btn-outline-primary">Open</a>
                        @endif
                        @if($resAccess->resource->downloadable && $resAccess->resource->file_path)
                            <a href="{{ asset('storage/'.$resAccess->resource->file_path) }}" download
                               class="btn btn-sm btn-outline-success">Download</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p>No resources found.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $resources->withQueryString()->links() }}
    </div>
</div>
@endsection
