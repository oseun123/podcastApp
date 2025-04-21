<?php

namespace Database\Factories;

use App\Models\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Episode>
 */
class EpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'podcast_id' => Podcast::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(640, 480, 'podcast', true),
            'audio_url' => $this->faker->url,
            'duration' => sprintf('%02d:%02d:%02d', rand(0, 1), rand(0, 59), rand(0, 59)),
            'is_featured' => $this->faker->boolean(20),
        ];
    }
}
