<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with('landmark')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'landmark_id' => 'required|exists:landmarks,id',
            'date' => 'required|date|after_or_equal:today',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'payment_method' => 'required|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'landmark_id' => $validated['landmark_id'],
            'date' => $validated['date'],
            'adults' => $validated['adults'],
            'children' => $validated['children'],
            'payment_method' => $validated['payment_method'],
            'total_price' => $validated['total_price'],
            'status' => 'confirmed',
            'booking_reference' => 'TB-' . strtoupper(Str::random(8)),
        ]);

        return response()->json($booking->load('landmark'), 201);
    }
}
