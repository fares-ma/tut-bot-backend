<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelStory extends Model
{
    protected $fillable = [
        'traveler_name',
        'location',
        'category',
        'image',
        'excerpt',
        'likes',
        'comments',
    ];
}
