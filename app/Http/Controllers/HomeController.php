<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Bucket, Ball, BucketBallCount};
use DB;
use Auth;
use App\Traits\BasketBallResultTraits;

class HomeController extends Controller
{
    use BasketBallResultTraits;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $buckets = Bucket::get();
        $bucketsCount = Bucket::count();
        $balls = Ball::get();
        $ballCount = Ball::count();
        $buckets = Bucket::where('user_id', auth()->id())->get();
        $allBucketsFull = $buckets->every(function ($bucket) {
            return $bucket->remaining_space == 0;
        });

        $finalBacketResultOutput = $this->getResultOfBucketBalls();

        return view('home', compact('buckets', 'balls', 'finalBacketResultOutput', 'ballCount', 'allBucketsFull','bucketsCount'));
    }
}
