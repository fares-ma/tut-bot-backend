<?php

namespace Database\Seeders;

use App\Models\TravelStory;
use Illuminate\Database\Seeder;

class TravelStorySeeder extends Seeder
{
    public function run(): void
    {
        TravelStory::query()->insertOrIgnore([
            [
                'traveler_name' => 'Omar Hassan',
                'location' => 'Luxor Temple',
                'category' => 'Archaeological',
                'image' => 'https://images.unsplash.com/photo-1513326738677-b964603b136d?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Watching the temple lights come alive after sunset felt like stepping into another era on the banks of the Nile.',
                'likes' => 342,
                'comments' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'traveler_name' => 'Nour Khaled',
                'location' => 'Abu Simbel',
                'category' => 'Archaeological',
                'image' => 'https://images.unsplash.com/photo-1544989164-31dc3c645987?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'The road trip from Aswan was long, but seeing the colossal statues carved out of the rock made every mile worth it.',
                'likes' => 289,
                'comments' => 31,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'traveler_name' => 'Hassan Ali',
                'location' => 'Pyramids of Giza',
                'category' => 'Archaeological',
                'image' => 'https://images.unsplash.com/photo-1503177119275-0aa32b3a9368?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Riding across the plateau before sunrise gave us a calm, cinematic view of the pyramids that we will never forget.',
                'likes' => 415,
                'comments' => 62,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'traveler_name' => 'Mariam Saleh',
                'location' => 'Karnak Temple',
                'category' => 'Archaeological',
                'image' => 'https://images.unsplash.com/photo-1508402476535-6e2a4bca6d73?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'The scale of the columns and the sacred lake made Karnak feel like the beating heart of ancient Thebes.',
                'likes' => 376,
                'comments' => 28,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}