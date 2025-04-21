<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Podcast>
 */
class PodcastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(640, 480, 'podcast', true),
            'is_featured' => $this->faker->boolean(30),
            'platforms' => ['spotify', 'apple'],
            'play_count' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
