<?php

namespace App\Http\Controllers\Api;

use App\Models\Episode;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(name="Category", description="Category related endpoints")
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/categories/episodes",
     *     summary="Get paginated episodes with optional filtering and sorting",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter episodes by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort by popularity (values: popular or latest)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"popular", "latest"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function categoryEpisodes(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'sort' => 'nullable|in:popular,latest',
        ]);

        $query = Episode::with('podcast');

        // Filter by category
        if ($request->category_id) {
            $query->whereHas('podcast', fn($q) => $q->where('category_id', $request->category_id));
        }

        // Sort logic
        if ($request->sort === 'latest') {
            $query->latest();
        } else {
            // Default is most popular
            $query->join('podcasts', 'episodes.podcast_id', '=', 'podcasts.id')
                ->orderBy('podcasts.play_count', 'desc')
                ->select('episodes.*'); // prevent column ambiguity
        }

        $episodes = $query->paginate(10);

        // Explore other categories
        $categories = Category::all();

        return ResponseHelper::withSuccess('Episodes by category and popularity', [
            'episodes' => $episodes->through(function ($ep) {
                // Determine part number (position) within the podcast
                $allEpisodes = Episode::where('podcast_id', $ep->podcast_id)
                    ->orderBy('created_at')
                    ->pluck('id'); // only need IDs

                $part = $allEpisodes->search($ep->id) + 1;

                return [
                    'id' => $ep->id,
                    'part' => $part,
                    'episode_image' => $ep->image,
                    'episode_description' => $ep->description,
                    'podcast_title' => $ep->podcast->title,
                    'episode_title' => $ep->title,
                    'duration' => $ep->duration,
                    'audio_url' => $ep->audio_url,
                    'created_at' => $ep->created_at->toDateTimeString(),
                ];
            }),
            'explore_categories' => $categories->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
            ]),
        ]);
    }
}
