<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'capacity', 'remaining_space', 'user_id'];

    public function ball()
    {
        return $this->belongsTo(Ball::class);
    }
}
