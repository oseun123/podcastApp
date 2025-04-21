<?php

namespace App\Http\Controllers\Api;

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\Category;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;

/**
 * @OA\Get(
 *     path="/api/v1/landing",
 *     tags={"Landing Page"},
 *     summary="Get landing page content",
 *     description="Returns Editor’s Picks, Trending Podcasts, Newly Added Episodes, and Episodes grouped by category",
 *     @OA\Response(
 *         response=200,
 *         description="Landing page data loaded successfully"
 *     )
 * )
 */
class LandingPageController extends Controller
{
    public function index()
    {
        // Editor’s Picks - 3 random featured episodes
        $editorsPicks = Episode::where('is_featured', true)
            ->inRandomOrder()
            ->take(3)
            ->with('podcast')
            ->get();

        // Trending Podcasts (where is_featured = true)
        $trending = Podcast::where('is_featured', true)
            ->withCount('episodes')
            ->get();

        // Newly Added Episodes
        $newEpisodes = Episode::latest()
            ->with('podcast')
            ->take(10)
            ->get();

        // Listen by Categories (each category with episodes)
        $categories = Category::with(['podcasts.episodes' => function ($query) {
            $query->latest()->take(5);
        }])->get();

        $payload = [
            'editors_picks' => $editorsPicks->map(fn($ep) => [
                'id' => $ep->id,
                'episode_image' => $ep->image,
                'podcast_title' => $ep->podcast->title,
                'episode_title' => $ep->title,
                'duration' => $ep->duration,
                'audio_url' => $ep->audio_url,
                'created_at' => $ep->created_at->toDateTimeString(),
            ]),
            'trending' => $trending->map(fn($pod) => [
                'id' => $pod->id,
                'podcast_image' => $pod->image,
                'podcast_title' => $pod->title,
                'episode_count' => $pod->episodes_count,
                'description' => $pod->description,
                'platforms' => $pod->platforms,
            ]),
            'newly_added' => $newEpisodes->map(fn($ep) => [
                'id' => $ep->id,
                'episode_image' => $ep->image,
                'podcast_title' => $ep->podcast->title,
                'episode_title' => $ep->title,
                'duration' => $ep->duration,
                'audio_url' => $ep->audio_url,
                'created_at' => $ep->created_at->toDateTimeString(),
            ]),
            'listen_by_category' => $categories->map(function ($cat) {
                return [
                    'category' => $cat->name,
                    'episodes' => $cat->podcasts->flatMap->episodes->map(function ($ep) {
                        return [
                            'id' => $ep->id,
                            'episode_image' => $ep->image,
                            'podcast_title' => $ep->podcast->title,
                            'episode_title' => $ep->title,
                            'duration' => $ep->duration,
                            'audio_url' => $ep->audio_url,
                            'created_at' => $ep->created_at->toDateTimeString(),
                        ];
                    })->take(5)->values(),
                ];
            }),
        ];

        return ResponseHelper::withSuccess('Landing page data retrieved successfully', $payload);
    }
}
