@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-5 bg-primary text-white text-center">
        <h1>My First Bootstrap Page</h1>
        <p>Resize this responsive page to see the effect!</p> 
    </div>
    <div class="container" id="bucketContainer">
        @forelse($bucket as $val)
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$val->name}}</h5>
                    <p class="card-text">Capacity: {{$val->capacity}} cubic inches</p>
                    <p class="card-text">Balls: 0</p>
                </div>
            </div>
        </div>
        @empty
        @endforelse
    </div>
    <div class="container" id="circleBallContainer"></div>

    <div class="row mt-5">
        <div class="col-md-6">
            <form id="create-bucket-form" action="{{ route('buckets.store') }}" method="POST" class="border p-4">
                @csrf
                <div class="mb-3">
                    <label for="bucket_name" class="form-label">Bucket Name:</label>
                    <input type="text" class="form-control" id="bucket_name" name="bucket_name" required>
                </div>
                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacity (cubic inches):</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Bucket</button>
            </form>
        </div>

        <div class="col-md-6">
            <form id="createBallForm" action="{{ route('balls.store') }}" method="POST" class="border p-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Ball name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="size" class="form-label">Size (cubic inches):</label>
                    <input type="number" class="form-control" id="size" name="size" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Ball</button>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <form action="{{ route('bucket.suggestions') }}" method="POST" class="border p-4">
                @csrf
                <div id="ballFields">
                    @forelse ($balls ?? [] as $key => $ball)
                        <div class="mb-3">
                            <label for="ball_name_{{ $key + 1 }}" class="form-label">Ball {{ $ball->name }} Name:</label>
                            <input type="text" class="form-control" data-id="{{ $ball->id }}" id="ball_name_{{ $key + 1 }}" name="ball_{{ $ball->name }}_{{ $ball->id }}_{{ $ball->size }}" required>
                        </div>
                    @empty
                        <p>No balls available</p>
                    @endforelse
                </div>  
                <button type="submit" class="btn btn-primary">Get Bucket Suggestions</button>
            </form>

        </div>
    </div>
</div>

@endsection
