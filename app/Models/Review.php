<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'name',
        'avatar',
        'rating',
        'text',
        'location',
        'user_id',
        'landmark_id',
    ];

}
