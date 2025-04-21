<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Episode;
use App\Models\Podcast;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            'News & Storytelling',
            'Educational',
            'Entertainment & Lifestyle',
            'Tech, Sport & Business',
        ];

        // First, create the 4 predefined categories
        foreach ($categories as $name) {
            Category::factory()
                ->has(
                    Podcast::factory()
                        ->count(3)
                        ->has(Episode::factory()->count(4))
                )
                ->create([
                    'name' => $name,
                    'slug' => Str::slug($name),
                ]);
        }

        // Then add 6 more random categories (or any number you want)
        Category::factory()
            ->count(6)
            ->has(
                Podcast::factory()
                    ->count(3)
                    ->has(Episode::factory()->count(4))
            )
            ->create();
    }
}
