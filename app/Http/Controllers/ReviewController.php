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
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'avatar' => ['required', 'string', 'max:2048'],
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'text' => ['required', 'string'],
                'location' => ['required', 'string', 'max:255'],
            ]);

            $user = $request->user();
            $data['user_id'] = $user ? $user->id : null;
            
            $review = Review::query()->create($data);

            if ($user) {
                $user->xp += 50;
                while ($user->xp >= $user->level * 500) {
                    $user->level += 1;
                }
                $user->save();
            }

            return response()->json($review, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create review.'], 500);
        }
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