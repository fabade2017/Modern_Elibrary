@extends('layouts.admin') {{-- or layouts.admin depending on your template --}}

@section('title', 'Account Not Activated')

@section('content')
<div class="container text-center mt-5">
    <div class="card shadow-lg p-4 rounded-3">
        <h1 class="text-danger mb-3">🚫 Access Denied</h1>
        <p class="lead">
            You are <strong>not activated</strong> for this operation.
        </p>
        <p class="mb-4">
            Please consult your <strong>Administrator</strong> or <strong>Admin</strong> to activate your account.
        </p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">
            ⬅ Back
        </a>
    </div>
</div>
@endsection
