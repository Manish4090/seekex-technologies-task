<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Bucket, Ball};

class BallController extends Controller
{
   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'size' => 'required|numeric',
        ]);

       
       

        $ball = Ball::create($request->all());
        $balls = Ball::get();
        $bucket_count = Bucket::count();
        $ball_count = Ball::count();
         if ($ball_count >= 4) {
            return response()->json([
                'error' => "You can only create 4 balls.",
                'ball_count' => $ball_count
            ]);
        }
        $ballNamesHtml = '';
        foreach ($balls as $key => $ball) {
            $ballNamesHtml .= '<div class="mb-3">';
            $ballNamesHtml .= '<label for="ball_name_' . ($key + 1) . '" class="form-label">Ball ' . ucfirst($ball->name) . ' Name:</label>';
            $ballNamesHtml .= '<input type="text" class="form-control" data-id="' . $ball->id . '" id="ball_name_' . ($key + 1) . '" name="ball_' . $ball->id . '_'. $ball->size .'_'. $ball->name . '" >';
            $ballNamesHtml .= '</div>';
        }

        return response()->json([
            'success' => "The ball has been successfully added.",
            'ball_name' => $ball->name,
            'ball_size' => $ball->size,
            'ball_names_html' => $ballNamesHtml,
            'ball_count' => $ball_count,
            'bucket_count' => $bucket_count,
        ]);
    }


}
