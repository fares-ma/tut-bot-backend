<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landmark extends Model
{
    protected $fillable = [
        'name',
        'region',
        'category',
        'era',
        'price',
        'rating',
        'reviews_count',
        'image',
        'description',
        'lat',
        'lng',
    ];
}
