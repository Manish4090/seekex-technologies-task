<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Bucket, Ball};

class HomeController extends Controller
{
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
        $bucket = Bucket::get();
        $balls = Ball::get();
        
        return view('home', compact('bucket', 'balls'));
    }
}
