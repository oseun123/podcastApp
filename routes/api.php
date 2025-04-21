<?php

use Illuminate\Support\Facades\Route;


use App\Http\Middleware\CorsMiddleware;
use App\Http\Controllers\Api\EpisodeController;
use App\Http\Controllers\Api\PodcastController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\LandingPageController;

Route::prefix('v1')->middleware([CorsMiddleware::class])->group(function () {


    // Landing
    Route::get('landing', [LandingPageController::class, 'index']);
    // Categories
    Route::get('categories/episodes', [CategoryController::class, 'categoryEpisodes']);
    // Podcasts
    Route::get('podcasts', [PodcastController::class, 'index']);
    // Episodes
    Route::get('episodes/{episode}', [EpisodeController::class, 'show']);




    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('register', [UserAuthController::class, 'register']);
        Route::post('login', [UserAuthController::class, 'login']);
        Route::post('forgot-password', [UserAuthController::class, 'forgotPassword']);
        Route::post('reset-password', [UserAuthController::class, 'resetPassword']);

        // Protected routes
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('logout', [UserAuthController::class, 'logout']);
        });
    });
});
