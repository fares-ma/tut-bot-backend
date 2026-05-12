<?php

namespace App\Http\Controllers;

use App\Models\Landmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LandmarkController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $search = $request->query('search', '');

        $query = Landmark::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
        }

        $landmarks = $query->orderBy('id')->paginate($perPage);

        return response()->json($landmarks);
    }

    public function show(Landmark $landmark): JsonResponse
    {
        return response()->json($landmark);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'region' => ['required', 'string', 'max:255'],
                'category' => ['required', 'string', 'max:255'],
                'era' => ['required', 'string', 'max:255'],
                'price' => ['required', 'integer', 'min:0'],
                'rating' => ['required', 'numeric', 'min:0', 'max:5'],
                'reviews_count' => ['required', 'integer', 'min:0'],
                'image' => ['required', 'string', 'max:2048'],
                'description' => ['required', 'string'],
                'lat' => ['required', 'numeric'],
                'lng' => ['required', 'numeric'],
            ]);

            $landmark = Landmark::query()->create($data);
            return response()->json($landmark, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create landmark.'], 500);
        }
    }

    public function update(Request $request, Landmark $landmark): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'region' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['sometimes', 'required', 'string', 'max:255'],
            'era' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'integer', 'min:0'],
            'rating' => ['sometimes', 'required', 'numeric', 'min:0', 'max:5'],
            'reviews_count' => ['sometimes', 'required', 'integer', 'min:0'],
            'image' => ['sometimes', 'required', 'string', 'max:2048'],
            'description' => ['sometimes', 'required', 'string'],
            'lat' => ['sometimes', 'required', 'numeric'],
            'lng' => ['sometimes', 'required', 'numeric'],
        ]);

        $landmark->update($data);

        return response()->json($landmark);
    }

    public function destroy(Landmark $landmark): JsonResponse
    {
        $landmark->delete();

        return response()->json(['message' => 'Landmark deleted successfully.']);
    }
}