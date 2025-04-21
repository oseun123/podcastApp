<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Podcast", description="Podcast related endpoints")
 */
class PodcastController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/v1/podcasts",
     *     tags={"Podcasts"},
     *     summary="Get paginated podcasts with episodes",
     *     description="Returns a paginated list of podcasts. Each podcast includes all its episodes.",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number (1 podcast per page)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated podcast with episodes",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Podcasts retrieved"),
     *             @OA\Property(property="payload", type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="image", type="string"),
     *                     @OA\Property(property="platforms", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="episodes", type="array", @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="title", type="string"),
     *                         @OA\Property(property="description", type="string"),
     *                         @OA\Property(property="image", type="string"),
     *                         @OA\Property(property="duration", type="string"),
     *                         @OA\Property(property="audio_url", type="string"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                     ))
     *                 )),
     *                 @OA\Property(property="meta", type="object"),
     *                 @OA\Property(property="links", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $podcasts = Podcast::with('episodes')->paginate(1);

        $transformed = $podcasts->getCollection()->map(function ($podcast) {
            return [
                'id' => $podcast->id,
                'title' => $podcast->title,
                'description' => $podcast->description,
                'image' => $podcast->image,
                'platforms' => $podcast->platforms,
                'episodes' => $podcast->episodes->map(function ($ep) {
                    return [
                        'id' => $ep->id,
                        'title' => $ep->title,
                        'description' => $ep->description,
                        'image ' => $ep->image,
                        'duration' => $ep->duration,
                        'audio_url' => $ep->audio_url,
                        'created_at' => $ep->created_at,
                    ];
                })
            ];
        });

        $payload = [
            'data' => $transformed,
            'meta' => [
                'current_page' => $podcasts->currentPage(),
                'last_page' => $podcasts->lastPage(),
                'total' => $podcasts->total(),
            ],
            'links' => [
                'next' => $podcasts->nextPageUrl(),
                'prev' => $podcasts->previousPageUrl(),
            ]
        ];

        return ResponseHelper::withSuccess('Podcasts retrieved', $payload);
    }
}
