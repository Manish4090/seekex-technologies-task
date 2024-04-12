<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bucket;
use App\Models\Ball;
use Auth;

class BucketController extends Controller
{
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'bucket_name' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:1',
        ]);

        // Create a new bucket
        $bucket = new Bucket();
        $bucket->name = $request->input('bucket_name');
        $bucket->capacity = $request->input('capacity');
        $bucket->remaining_space = $request->input('capacity');
        $bucket->user_id = Auth::user()->id;
        $bucket->save();

        return response()->json([
            'bucket_name' => $bucket->name,
            'capacity' => $bucket->capacity,
            'capacity' => $bucket->capacity,
            'ball_count' => 0,
        ]);
    }

//   public function allocateBalls(Request $request)
// {
//     // Retrieve balls and their quantities from the request
//     $ballQuantities = $request->except('_token');

//     // Iterate through ball quantities
//     foreach ($ballQuantities as $ballKey => $quantity) {
//         // Extract ball ID from the key
//         $parts = explode('_', $ballKey);
//         $ballId = end($parts); // Extract the last part as the ball ID

//         // Retrieve ball instance
//         $ball = Ball::find($ballId);

//         // If the ball exists
//         if ($ball) {
//             // Find suitable bucket for the ball based on remaining space
//             $bucket = Bucket::orderBy('remaining_space', 'desc')->where('remaining_space', '>=', $ball->size)->first();

//             // If a suitable bucket is found
//             if ($bucket) {
//                 // Attach the ball to the bucket with the specified quantity
//                 $bucket->balls()->attach($ballId, ['quantity' => $quantity]);

//                 // Update remaining space of the bucket
//                 $bucket->update(['remaining_space' => $bucket->remaining_space - ($ball->size * $quantity)]);
//             } else {
//                 // Handle case where no suitable bucket is found
//                 // This could involve creating a new bucket, rejecting the ball, or other logic based on your requirements
//             }
//         } else {
//             // Handle case where ball with the given ID does not exist
//             // This could involve logging an error, skipping the ball, or other error handling logic
//         }
//     }

//     // Redirect or return a response as needed
// }

    public function allocateBalls(Request $request)
{
    // Retrieve buckets belonging to the authenticated user
    $buckets = Bucket::where('user_id', auth()->id())->get();

    // Calculate ball volumes for each ball
    $ballVolumes = [];
    foreach ($request->all() as $key => $value) {
        if (strpos($key, 'ball_') === 0) {
            $parts = explode('_', $key);
            $ballSize = $parts[4];
            $ballVolumes[$parts[2]] = $ballSize * $value;
        }
    }

    // Sort buckets by remaining space in descending order
    $buckets = $buckets->sortByDesc('remaining_space');

    // Allocate balls to buckets
    foreach ($buckets as $bucket) {
        // Check if there are no more ball volumes to allocate
        if (empty($ballVolumes)) {
            break;
        }

        $remainingSpace = $bucket->remaining_space;

        foreach ($ballVolumes as $ballId => $ballVolume) {
            if ($ballVolume <= $remainingSpace) {
                $remainingSpace -= $ballVolume;
                unset($ballVolumes[$ballId]);
            } else {
                $ballVolumes[$ballId] -= $remainingSpace;
                $remainingSpace = 0;
                break;
            }
        }

        // Update remaining space of the bucket
        $bucket->update(['remaining_space' => $remainingSpace]);
    }

    // Check if all buckets have zero remaining space
    $allFilled = $buckets->every(function ($bucket) {
        return $bucket->remaining_space == 0;
    });

    // Return response based on bucket filling status
    if ($allFilled) {
         $this->emptyAllBuckets();
        return response()->json(['message' => 'done: all buckets filled']);
    } else {
        return response()->json(['message' => 'done: some buckets partially filled']);
    }
}

private function emptyAllBuckets()
{
    // Retrieve all buckets owned by the authenticated user
    $buckets = Bucket::where('user_id', auth()->id())->get();

    // Check if all buckets have a remaining space of 0
    $allBucketsFull = $buckets->every(function ($bucket) {
        return $bucket->remaining_space == 0;
    });

    // Reset remaining space of each bucket to its maximum capacity if all buckets are full
    if ($allBucketsFull) {
        foreach ($buckets as $bucket) {
            $bucket->update(['remaining_space' => $bucket->capacity]);
        }
    }
}

    // public function allocateBalls(Request $request)
    // {
      
    //     $ballQuantities = $request->except('_token');

    //     // Iterate through ball quantities
    //     foreach ($ballQuantities as $ballId => $quantity) {
    //         // Retrieve ball instance
    //         $ball = Ball::findOrFail($ballId);

    //         // Find suitable bucket for the ball based on remaining space
    //         $bucket = Bucket::orderBy('remaining_space', 'desc')->where('remaining_space', '>=', $ball->size)->first();

    //         // If a suitable bucket is found
    //         if ($bucket) {
    //             // Attach the ball to the bucket with the specified quantity
    //             $bucket->balls()->attach($ballId, ['quantity' => $quantity]);

    //             // Update remaining space of the bucket
    //             $bucket->update(['remaining_space' => $bucket->remaining_space - ($ball->size * $quantity)]);
    //         } else {
    //             // Handle case where no suitable bucket is found
    //             // This could involve creating a new bucket, rejecting the ball, or other logic based on your requirements
    //         }
    //     }

    //     return response()->json(['message' => 'done'
    //     ]);
       
    // }

    // public function insertBalls(Request $request)
    // {
    //     $ballInputs = [];
    //     foreach ($request->all() as $key => $value) {
    //         if (strpos($key, 'ball_') === 0) {
    //             $ballInputs[$key] = $value;
    //         }
    //     }
      
        

    //     foreach ($ballInputs as $requestValue => $val) {
    //         $parts = explode('_', $requestValue);
    //         //dd($parts);

    //         // Extract the values
    //         $ballName = $parts[1];
    //         $ballId = $parts[2];
    //         $ballSize = $parts[4];

    //         // Output the values
    //         echo "Ball Name: $ballName, ID: $ballId, Ball val : $val , Ball size : $ballSize";

    //         $ballVol = $ballSize * $val;
    //         $largestSpace = Bucket::max('remaining_space')->get();
    //         Bucket::update()...;
    //         dd($ballVol);
    //     }
    //     dd("check");
    //     $ballSize = $request->input('ball_size');
    //     $bucketId = $request->input('bucket_id');

    //     // Fetch bucket details from the database
    //     $bucket = Bucket::find($bucketId);

    //     // Calculate remaining space after inserting the ball
    //     $remainingSpace = $bucket->capacity - $ballSize;

    //     // Update bucket details in the database
    //     $bucket->capacity = $remainingSpace;
    //     $bucket->save();

    //     // Return response with updated bucket data
    //     return response()->json([
    //         'space' => $remainingSpace,
    //         'balls' => $bucket->balls()->count()
    //     ]);
    // }

    //     $ballSize = $request->input('ball_size');
    //     $bucketId = $request->input('bucket_id');

    //     // Fetch bucket details from the database
    //     $bucket = Bucket::find($bucketId);

    //     // Calculate remaining space after inserting the ball
    //     $remainingSpace = $bucket->capacity - $ballSize;

    //     // Update bucket details in the database
    //     $bucket->capacity = $remainingSpace;
    //     $bucket->save();

    //     // Return response with updated bucket data
    //     return response()->json([
    //     'space' => $remainingSpace,
    //     'balls' => $bucket->balls()->count()
    //     ]);

    //     ballId = get size based on the ball id
    //     2 ball vol * size;
    //     3 get max remainingSpace of bucket - (ball vol * size)
    //     4 update remeaning space 

    // remeaning space = user id 

}
