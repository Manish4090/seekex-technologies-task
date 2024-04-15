<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bucket;
use App\Models\Ball;
use App\Models\BucketBallCount;
use Auth;
use App\Traits\BasketBallResultTraits;

class BucketController extends Controller
{
    use BasketBallResultTraits;
    public function store(Request $request)
    {
        $request->validate([
            'bucket_name' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:1',
        ]);

        $balls = Ball::count();
        if($balls >= 3){
            return response()->json([
            'success' => "You can only create 3 buckets"]);
        }

        // Create a new bucket
        $bucket = new Bucket();
        $bucket->name = $request->input('bucket_name');
        $bucket->capacity = $request->input('capacity');
        $bucket->remaining_space = $request->input('capacity');
        $bucket->user_id = Auth::user()->id;
        $bucket->save();


        return response()->json([
            'success' => "The bucket has been successfully added.",
            'bucket_name' => $bucket->name,
            'capacity' => $bucket->capacity,
            'capacity' => $bucket->capacity,
            'bucket_count' => $bucket->count(),
            'ball_count' => $balls,
        ]);
    }



    public function allocateBalls(Request $request)
    {
        $buckets = Bucket::where('user_id', auth()->id())->get();

        $ballVolumes = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'ball_') === 0) {
                $parts = explode('_', $key);
                
                $ballSize = (int)$parts[2];
                $ballVolumes[$parts[1]] = $ballSize * $value;
            }
        }

        $isAllEmpty = true;

        foreach ($ballVolumes as $value) {
            if ($value != 0) {
                $isAllEmpty = false;
                break;
            }
        }
        

        if ($isAllEmpty) {
            return response()->json(['message' => 'Please enter at least one ball size to allocate.']);
        } else {
           
                $buckets = $buckets->sortByDesc('remaining_space');

                foreach ($buckets as $bucket) {
                    if (empty($ballVolumes)) {
                        break;
                    }

                    $remainingSpace = $bucket->remaining_space;

                    foreach ($ballVolumes as $ballId => $ballVolume) {
                        if ($ballVolume <= $remainingSpace) {
                            $remainingSpace -= $ballVolume;
                            unset($ballVolumes[$ballId]);
                            $allocatedBalls[] = ['ball_id' => $ballId, 'bucket_id' => $bucket->id];
                            BucketBallCount::create([
                                'user_id' => auth()->id(),
                                'bucket_id' => $bucket->id,
                                'ball_id' => $ballId
                            ]);
                        } else {
                            $ballVolumes[$ballId] -= $remainingSpace;
                            BucketBallCount::create([
                                'user_id' => auth()->id(),
                                'bucket_id' => $bucket->id,
                                'ball_id' => $ballId
                            ]);
                            $remainingSpace = 0;
                            break;
                        }
                    }

                    $bucket->update(['remaining_space' => $remainingSpace]);
                }

                /*if (empty($ballVolumes)) {
                    return response()->json(['message' => 'No balls were allocated.', 'empty_space' => true]);
                }*/

                $allFilled = $buckets->every(function ($bucket) {
                    return $bucket->remaining_space == 0;
                });

                $finalOutput = $this->getLatestBucketSize();
                $backetsBallResult = $this->getResultOfBucketBalls();
                

                if ($allFilled) {
                    return response()->json(['message' => 'Great job! All buckets have been filled.', 'empty_space' => true, 'finalBacketHtml' => $finalOutput, 'backetsBallResult' => $backetsBallResult]);
                } else {
                    return response()->json(['message' => 'Keep going! Some buckets are partially filled.', 'empty_space' => false, 'finalBacketHtml' => $finalOutput, 'backetsBallResult' => $backetsBallResult]);
                }
         }
    }

    public function releaseSpacesAllBuckets()
    {
        $buckets = Bucket::where('user_id', auth()->id())->get();

        $allBucketsFull = $buckets->every(function ($bucket) {
            return $bucket->remaining_space == 0;
        });

        if ($allBucketsFull) {
            foreach ($buckets as $bucket) {
                $bucket->update(['remaining_space' => $bucket->capacity]);
            }
        }

         return response()->json(['message' => 'Operation successful: Space released in all buckets.', 'empty_space' => false]);
    }

    public function getLatestBucketSize() {
        $buckets = Bucket::where('user_id', auth()->id())->get();
        $bucketHtml = [];

        foreach ($buckets as $bucket) {
            $bucketHtml[] = '<div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $bucket->name . '</h5>
                                        <p class="card-text">Capacity: ' . $bucket->remaining_space . ' cubic inches</p>
                                    </div>
                                </div>
                            </div>';
        }

        return implode('', $bucketHtml);
    }


   

}
