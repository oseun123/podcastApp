<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     description="Episode model",
 *     type="object",
 *     title="Episode",
 *     @OA\Property(property="id", type="integer", description="The unique identifier", example=1),
 *     @OA\Property(property="title", type="string", description="Title of the episode", example="The Future of Tech"),
 *     @OA\Property(property="podcast_id", type="integer", description="Podcast ID the episode belongs to", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="When the episode was created", example="2022-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="When the episode was last updated", example="2022-01-01T12:00:00Z")
 * )
 */

class Episode extends Model
{
    use HasFactory;

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }
}
