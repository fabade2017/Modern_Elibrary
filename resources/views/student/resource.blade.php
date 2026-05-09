@extends('layouts.student')

@section('content')
<div class="container">
    <h2>{{ $resource->title }}</h2>
    <p>{{ $resource->description }}</p>

    <div class="mb-3">
        <span class="badge bg-info">{{ $resource->title }}</span>
    </div>

    {{-- Resource actions --}}
    @if($resource->view_enabled)
        @if(Str::endsWith($resource->file_path, ['.pdf']))
            <iframe src="{{ asset('storage/'.$resource->file_path) }}" 
                    width="100%" height="600px"></iframe>
        @elseif(Str::endsWith($resource->file_path, ['.mp4','.webm']))
            <video controls width="100%">
                <source src="{{ asset('storage/'.$resource->file_path) }}">
                Your browser does not support video playback.
            </video>
        @elseif(Str::endsWith($resource->file_path, ['.mp3']))
            <audio controls>
                <source src="{{ asset('storage/'.$resource->file_path) }}">
                Your browser does not support audio playback.
            </audio>
        @else
            <p>Preview not available.</p>
        @endif
    @endif

    @if($resource->downloadable)
        <a href="{{ asset('storage/'.$resource->file_path) }}" download 
           class="btn btn-success mt-3">Download</a>
    @endif
</div>
@endsection
