<?php

namespace App\Modules\News\Factories;

use App\Modules\News\Models\News;
use App\Modules\NewsCategory\Models\NewsCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    protected $model = News::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        $status = fake()->randomElement(['draft', 'published', 'archived']);

        return [
            'category_id' => NewsCategory::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 9999),
            'content' => fake()->paragraphs(4, true),
            'thumbnail_url' => fake()->optional()->imageUrl(),
            'published_at' => $status === 'published' ? now()->subDays(1) : null,
            'status' => $status,
            'is_featured' => fake()->boolean(20),
        ];
    }
}
