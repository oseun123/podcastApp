<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Episode", description="Episode related endpoints")
 */
class EpisodeController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/v1/episodes/{episode}",
     *     summary="Get single episode with next episodes in queue",
     *     tags={"Episodes"},
     *     @OA\Parameter(
     *         name="episode",
     *         in="path",
     *         description="ID of the episode",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Episode details with next episodes in queue",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Episode details"),
     *             @OA\Property(property="payload", type="object",
     *                 @OA\Property(property="episode", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="episode_image", type="string", example="https://example.com/image.jpg"),
     *                     @OA\Property(property="podcast_title", type="string", example="Health Talks"),
     *                     @OA\Property(property="episode_title", type="string", example="Episode 1 - Introduction"),
     *                     @OA\Property(property="episode_description", type="string", example="Episode 1 - Introduction"),
     *                     @OA\Property(property="duration", type="string", example="00:30:45"),
     *                     @OA\Property(property="audio_url", type="string", example="https://example.com/audio.mp3"),
     *                     @OA\Property(property="created_at", type="string", example="2025-04-21 12:34:56")
     *                 ),
     *                 @OA\Property(property="next_in_queue", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="episode_image", type="string"),
     *                         @OA\Property(property="episode_title", type="string"),
     *                         @OA\Property(property="episode_description", type="string"),
     *                         @OA\Property(property="duration", type="string"),
     *                         @OA\Property(property="audio_url", type="string"),
     *                         @OA\Property(property="created_at", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Episode not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Episode not found"),
     *             @OA\Property(property="payload", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function show(Request $request, $episodeId)
    {
        if (!is_numeric($episodeId)) {
            return ResponseHelper::withError('Invalid episode ID', [], 422);
        }

        $episode = Episode::with('podcast')->find($episodeId);

        if (!$episode) {
            return ResponseHelper::withError('Episode not found', [], 404);
        }

        $nextEpisodes = Episode::where('podcast_id', $episode->podcast_id)
            ->where('id', '!=', $episode->id)
            ->orderBy('created_at')
            ->get();

        return ResponseHelper::withSuccess('Episode details', [
            'episode' => [

                'id' => $episode->id,
                'episode_image' => $episode->image,
                'podcast_title' => $episode->podcast->title,
                'episode_title' => $episode->title,
                'episode_description' => $episode->description,
                'duration' => $episode->duration,
                'audio_url' => $episode->audio_url,
                'created_at' => $episode->created_at->toDateTimeString(),
            ],
            'next_in_queue' => $nextEpisodes->map(function ($ep) {
                return [
                    'id' => $ep->id,
                    'episode_image' => $ep->image,
                    'episode_description' => $ep->description,
                    'episode_title' => $ep->title,
                    'duration' => $ep->duration,
                    'audio_url' => $ep->audio_url,
                    'created_at' => $ep->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }
}
