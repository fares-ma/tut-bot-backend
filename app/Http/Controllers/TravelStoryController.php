<?php

namespace App\Http\Controllers;

use App\Models\TravelStory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TravelStoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(TravelStory::query()->orderBy('id')->get());
    }

    public function show(TravelStory $story): JsonResponse
    {
        return response()->json($story);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'traveler_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:2048'],
            'excerpt' => ['required', 'string'],
            'likes' => ['required', 'integer', 'min:0'],
            'comments' => ['required', 'integer', 'min:0'],
        ]);

        $story = TravelStory::query()->create($data);

        return response()->json($story, 201);
    }

    public function update(Request $request, TravelStory $story): JsonResponse
    {
        $data = $request->validate([
            'traveler_name' => ['sometimes', 'required', 'string', 'max:255'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['sometimes', 'required', 'string', 'max:255'],
            'image' => ['sometimes', 'required', 'string', 'max:2048'],
            'excerpt' => ['sometimes', 'required', 'string'],
            'likes' => ['sometimes', 'required', 'integer', 'min:0'],
            'comments' => ['sometimes', 'required', 'integer', 'min:0'],
        ]);

        $story->update($data);

        return response()->json($story);
    }

    public function destroy(TravelStory $story): JsonResponse
    {
        $story->delete();

        return response()->json(['message' => 'Travel story deleted successfully.']);
    }
}