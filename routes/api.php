<?php

use App\Http\Controllers\LandmarkController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TravelStoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'ok', 'database' => 'connected']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'database' => 'disconnected', 'error' => $e->getMessage()], 500);
    }
});

Route::get('/landmarks', [LandmarkController::class, 'index']);
Route::get('/landmarks/{landmark}', [LandmarkController::class, 'show']);

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{review}', [ReviewController::class, 'show']);

Route::get('/stories', [TravelStoryController::class, 'index']);
Route::get('/stories/{story}', [TravelStoryController::class, 'show']);
