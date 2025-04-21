<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     description="Category model",
 *     type="object",
 *     title="Category",
 *     @OA\Property(property="id", type="integer", description="The unique identifier", example=1),
 *     @OA\Property(property="name", type="string", description="Name of the category", example="News & Storytelling"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="When the category was created", example="2022-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="When the category was last updated", example="2022-01-01T12:00:00Z")
 * )
 */

class Category extends Model
{
    use HasFactory;


    public function podcasts()
    {
        return $this->hasMany(Podcast::class);
    }
}
