<?php

namespace Database\Seeders;

use App\Models\Landmark;
use Illuminate\Database\Seeder;

class LandmarkSeeder extends Seeder
{
    public function run(): void
    {
        Landmark::query()->insertOrIgnore([
            [
                'name' => 'Pyramids of Giza',
                'region' => 'Giza',
                'category' => 'Archaeological',
                'era' => 'Pharaonic',
                'price' => 150,
                'rating' => 4.9,
                'reviews_count' => 1240,
                'image' => 'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?auto=format&fit=crop&w=1200&q=80',
                'description' => 'The last surviving wonder of the ancient world, rising above the Giza Plateau with timeless majesty.',
                'lat' => 29.9792000,
                'lng' => 31.1342000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Luxor Temple',
                'region' => 'Luxor',
                'category' => 'Archaeological',
                'era' => 'Pharaonic',
                'price' => 120,
                'rating' => 4.8,
                'reviews_count' => 860,
                'image' => 'https://images.unsplash.com/photo-1539650116574-75c0c6d8f76f?auto=format&fit=crop&w=1200&q=80',
                'description' => 'A dramatic temple complex on the Nile East Bank, famous for its grand pylons and illuminated evening atmosphere.',
                'lat' => 25.6995000,
                'lng' => 32.6392000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karnak Temple Complex',
                'region' => 'Luxor',
                'category' => 'Archaeological',
                'era' => 'Pharaonic',
                'price' => 140,
                'rating' => 4.9,
                'reviews_count' => 1520,
                'image' => 'https://images.unsplash.com/photo-1591604574997-6c7f3a7ad1da?auto=format&fit=crop&w=1200&q=80',
                'description' => "One of the largest religious sites ever built, where towering columns and sacred avenues tell Egypt's dynastic story.",
                'lat' => 25.7188000,
                'lng' => 32.6573000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Abu Simbel Temples',
                'region' => 'Aswan',
                'category' => 'Archaeological',
                'era' => 'Pharaonic',
                'price' => 180,
                'rating' => 4.9,
                'reviews_count' => 730,
                'image' => 'https://images.unsplash.com/photo-1582555596372-5f6e4f8d0d7d?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Rock-cut temples dedicated to Ramses II and Nefertari, renowned for their colossal facade and relocation history.',
                'lat' => 22.3372000,
                'lng' => 31.6258000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}