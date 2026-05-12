<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landmark extends Model
{
    protected $fillable = [
        'name',
        'region',
        'city',
        'area',
        'category',
        'raw_category',
        'era',
        'price',
        'rating',
        'reviews',
        'image',
        'fallback_image',
        'panorama_url',
        'description',
        'lat',
        'lng',
        'opening_hours',
        'closing_hours',
        'avg_visit_duration',
        'accessibility_wheelchair',
        'is_outdoor',
        'best_day_visit',
        'best_season',
        'cost_level',
        'entrance_fee_egyptian',
        'entrance_fee_egyptian_student',
        'entrance_fee_foreigner',
        'entrance_fee_foreigner_student',
    ];

    protected $casts = [
        'accessibility_wheelchair' => 'boolean',
        'is_outdoor' => 'boolean',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];
}
