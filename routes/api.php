<?php

use Illuminate\Support\Facades\Route;


use App\Http\Middleware\CorsMiddleware;
use App\Http\Controllers\Api\EpisodeController;
use App\Http\Controllers\Api\PodcastController;
use App\Http\Controllers\Api\CategoryController;
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
});
