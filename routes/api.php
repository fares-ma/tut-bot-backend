<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\LandmarkController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TravelStoryController;
use App\Http\Controllers\BookingController;
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

// Dev-only: seed landmarks — only in local/testing environments
Route::get('/_seed', function () {
    if (!in_array(env('APP_ENV'), ['local', 'testing'])) {
        return response()->json(['error' => 'Seeding not allowed in production'], 403);
    }
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'LandmarkSeeder', '--force' => true]);
        return response()->json(['message' => 'Seeded', 'output' => \Illuminate\Support\Facades\Artisan::output()]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    
    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    
    // Collections
    Route::get('/collections', [\App\Http\Controllers\UserCollectionController::class, 'getCollections']);
    Route::post('/collections/favorites/{id}', [\App\Http\Controllers\UserCollectionController::class, 'toggleFavorite']);
    Route::post('/collections/wishlists/{id}', [\App\Http\Controllers\UserCollectionController::class, 'toggleWishlist']);

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);
});

Route::post('/ai/chat', [AiChatController::class, 'chat'])->middleware('throttle:30,1');

Route::get('/landmarks', [LandmarkController::class, 'index']);
Route::get('/landmarks/{landmark}', [LandmarkController::class, 'show']);

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{review}', [ReviewController::class, 'show']);

Route::get('/stories', [TravelStoryController::class, 'index']);
Route::get('/stories/{story}', [TravelStoryController::class, 'show']);
