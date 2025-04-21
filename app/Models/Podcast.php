<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     description="Podcast model",
 *     type="object",
 *     title="Podcast",
 *     @OA\Property(property="id", type="integer", description="The unique identifier", example=1),
 *     @OA\Property(property="title", type="string", description="Title of the podcast", example="Tech Talk"),
 *     @OA\Property(property="category_id", type="integer", description="Category ID the podcast belongs to", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="When the podcast was created", example="2022-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="When the podcast was last updated", example="2022-01-01T12:00:00Z")
 * )
 */

class Podcast extends Model
{
    use HasFactory;



    protected $casts = [
        'is_featured' => 'boolean',
        'platforms' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
