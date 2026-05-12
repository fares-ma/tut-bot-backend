<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        Badge::query()->insert([
            [
                'name' => 'Desert Explorer',
                'description' => 'Unlocked after visiting key desert and plateau landmarks across Egypt.',
                'icon' => 'compass',
                'points_required' => 100,
                'tier' => 'bronze',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Temple Trailblazer',
                'description' => 'Awarded for exploring multiple major temple complexes in Luxor and beyond.',
                'icon' => 'landmark',
                'points_required' => 250,
                'tier' => 'silver',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nile Navigator',
                'description' => 'For travelers who complete iconic routes along the Nile from Cairo to Aswan.',
                'icon' => 'ship',
                'points_required' => 500,
                'tier' => 'gold',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Heritage Guardian',
                'description' => "Recognizes deep engagement with Egypt's archaeological heritage and cultural stories.",
                'icon' => 'shield',
                'points_required' => 1000,
                'tier' => 'platinum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}