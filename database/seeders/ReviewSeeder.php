<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        Review::query()->insertOrIgnore([
            [
                'name' => 'Sarah Jenkins',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80',
                'rating' => 5,
                'text' => 'The guided visit to Giza was unforgettable. The storytelling and desert views made the whole day exceptional.',
                'location' => 'London, UK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mark Thompson',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
                'rating' => 5,
                'text' => 'Karnak at sunset was stunning. The app suggestions matched our Egypt trip perfectly.',
                'location' => 'Toronto, Canada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Amina El Sherif',
                'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=300&q=80',
                'rating' => 4,
                'text' => 'Great balance of history, prices, and practical travel details for Luxor and Aswan.',
                'location' => 'Cairo, Egypt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sofia Martinez',
                'avatar' => 'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?auto=format&fit=crop&w=300&q=80',
                'rating' => 5,
                'text' => 'Easy to use, with beautiful landmarks and stories that made planning our Nile trip effortless.',
                'location' => 'Madrid, Spain',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}