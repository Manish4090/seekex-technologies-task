@extends('layouts.guest')

@section('content')
<div class="container">
    
    <div class="container-fluid p-5 bg-primary text-white text-center shadow">
        <h1>Buckets</h1>
        <div class=" row" id="bucketsArea">
            @forelse($buckets as $val)
            <div class="col-md-4 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">{{$val->name}}</h5>
                        <p class="card-text">Capacity: {{$val->capacity}} cubic inches</p>
                    </div>
                </div>
            </div>
            @empty
            <p>No buckets available.</p>
            @endforelse
        </div>
    </div>

       <div class="container-fluid p-5 mt-5 bg-primary text-white text-center shadow">
        <h1>Balls</h1>
        <div class="row" id="ballArea">
            <div class="col-md-12">
                <div class="row" id="circleBallContainer">
                    @forelse($balls as $ball)
                    <div class="col-md-3 mb-4">
                        <div class="circle shadow d-flex flex-column align-items-center justify-content-center">
                            <p>{{ucfirst($ball->name)}}</p>
                            <p class="mt-auto">Size: {{$ball->size}}</p>
                        </div>
                    </div>
                    @empty
                    <p>No balls available.</p>
                    @endforelse    
                </div>
            </div>
        </div>
    </div>




    
    

    <div class="row mt-5 ">
        <div class="col-md-6 ">
            <form id="create-bucket-form" action="{{ route('buckets.store') }}" method="POST" class="shadow p-4">
                @csrf
                <div class="mb-3">
                    <label for="bucket_name" class="form-label">Bucket Name:</label>
                    <input type="text" class="form-control" id="bucket_name" name="bucket_name" required>
                </div>
                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacity (cubic inches):</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" required>
                </div>
                @php
                $bucket = match (true) {
                    $bucketsCount >= 3 => ['attr' => 'disabled', 'text' => 'You can only create 3 buckets.'],
                    default => ['attr' => '', 'text' => 'Create Bucket'],
                };
                @endphp

                <button id="createBucket" {{ $bucket['attr'] }} class="btn btn-primary">{{ $bucket['text'] }}</button>


            </form>
        </div>

        <div class="col-md-6">
            <form id="createBallForm" action="{{ route('balls.store') }}" method="POST" class="shadow p-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Ball name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="size" class="form-label">Size (cubic inches):</label>
                    <input type="number" class="form-control" id="size" name="size" required>
                </div>
                @php
                $ball = match (true) {
                    $ballCount >= 4 => ['attr' => 'disabled', 'text' => 'You can only create 4 balls.'],
                    default => ['attr' => '', 'text' => 'Create Ball'],
                };
                @endphp

                <button id="createBall" {{ $ball['attr'] }} class="btn btn-primary">{{ $ball['text'] }}</button>
                
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6 ">
            <form action="{{ route('bucket.suggestions') }}" id="bucketSuggestionsForm" method="POST" class="shadow p-4 ">
                @csrf
                <div id="ballFields">
                    @forelse ($balls ?? [] as $key => $ball)
                        <div class="mb-3">
                            <label for="ball_name_{{ $key + 1 }}" class="form-label">Ball {{ ucfirst($ball->name) }} Name:</label>
                            <input type="text" class="form-control" data-id="{{ $ball->id }}" id="ball_name_{{ $key + 1 }}" name="ball_{{ $ball->id }}_{{ $ball->size }}_{{ $ball->name }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                    @empty
                        <p>No balls available</p>
                    @endforelse
                </div>
                @php
                    $bucketSuggestions = match (true) {
                        (count($buckets) == 0 && count($balls) == 0) => ['attr' => 'disabled', 'text' => 'Sorry, At least one ball and one bucket are required.'],
                        (count($buckets) > 0 && count($balls) == 0) => ['attr' => 'disabled', 'text' => 'Sorry, At least one ball is required.'],
                        (count($buckets) == 0 && count($balls) > 0) => ['attr' => 'disabled', 'text' => 'Sorry, At least one bucket is required.'],
                        (count($buckets) > 0 && count($balls) > 0) => ['attr' => '', 'text' => 'Get Bucket Suggestions'],
                        default => ['attr' => '', 'text' => 'Get Bucket Suggestions'],
                    };
                @endphp

                <button id="bucketSuggestionsWithBall" {{ $bucketSuggestions['attr'] }} class="btn btn-primary">{{ $bucketSuggestions['text'] }}</button>
            </form>
       
            <button id="releaseSpacesAllBuckets" data-action="{{ route('release-spaces-all-buckets') }}" class="btn btn-primary {{ ($allBucketsFull == false && count($balls) == 0 ) ? 'd-block' : 'd-none' }}">Release Space in All Buckets</button>

            
        </div>



   
     
        <div class="col-md-6 shadow p-4" id="backetsBallResult">
            
            {!! $finalBacketResultOutput !!}

        </div>


    </div>
</div>
<style type="text/css">
    table, th, td {
  border: 1px solid black;
}
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding-top: 10px;
  padding-bottom: 20px;
  padding-left: 30px;
  padding-right: 40px;
}
tr:nth-child(even) {
  background-color: #D6EEEE;
}
    .custom-shadow {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    .circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #ffffff
    }
    .circle p{
        color: #000;
    }
    .circle.shadow p {
        padding-top: 9px;
    }
</style>
@endsection


