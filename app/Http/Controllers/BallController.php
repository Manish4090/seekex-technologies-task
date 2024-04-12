<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ball;

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

        $ballNamesHtml = '';
        foreach ($balls as $key => $ball) {
            $ballNamesHtml .= '<div class="mb-3">';
            $ballNamesHtml .= '<label for="ball_name_' . ($key + 1) . '" class="form-label">Ball ' . $ball->name . ' Name:</label>';
            $ballNamesHtml .= '<input type="text" class="form-control" data-id="' . $ball->id . '" id="ball_name_' . ($key + 1) . '" name="ball_' . $ball->name . '_' . $ball->id . '" required>';
            $ballNamesHtml .= '</div>';
        }


        return response()->json([
            'ball_name' => $ball->name,
            'ball_size' => $ball->size,
            'ball_names_html' => $ballNamesHtml,
        ]);
    }

}
