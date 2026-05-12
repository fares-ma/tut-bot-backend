<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $reviews = Review::query()->orderBy('id')->paginate($perPage);

        return response()->json($reviews);
    }

    public function show(Review $review): JsonResponse
    {
        return response()->json($review);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['required', 'string', 'max:2048'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'text' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $review = Review::query()->create($data);

        return response()->json($review, 201);
    }

    public function update(Request $request, Review $review): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'avatar' => ['sometimes', 'required', 'string', 'max:2048'],
            'rating' => ['sometimes', 'required', 'integer', 'min:1', 'max:5'],
            'text' => ['sometimes', 'required', 'string'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
        ]);

        $review->update($data);

        return response()->json($review);
    }

    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully.']);
    }
}