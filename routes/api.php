<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AiChatController;
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
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/ai/chat', [AiChatController::class, 'chat'])->middleware('throttle:30,1');

Route::get('/landmarks', [LandmarkController::class, 'index']);
Route::get('/landmarks/{landmark}', [LandmarkController::class, 'show']);

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{review}', [ReviewController::class, 'show']);

Route::get('/stories', [TravelStoryController::class, 'index']);
Route::get('/stories/{story}', [TravelStoryController::class, 'show']);
