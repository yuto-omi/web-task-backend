<?php

use App\Modules\News\Models\News;
use App\Modules\NewsCategory\Models\NewsCategory;

it('lists only published public news', function () {
    $category = NewsCategory::factory()->create();

    News::factory()->create([
        'category_id' => $category->id,
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    News::factory()->create([
        'category_id' => $category->id,
        'status' => 'draft',
        'published_at' => null,
    ]);

    $response = $this->getJson('/api/public/news');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

it('filters public news by category and query', function () {
    $category = NewsCategory::factory()->create(['slug' => 'tech']);

    News::factory()->create([
        'category_id' => $category->id,
        'title' => 'Laravel Updates',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    News::factory()->create([
        'category_id' => $category->id,
        'title' => 'Other Topic',
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $response = $this->getJson('/api/public/news?category=tech&q=Laravel');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

it('shows a public news item by slug', function () {
    $category = NewsCategory::factory()->create();
    $news = News::factory()->create([
        'category_id' => $category->id,
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $response = $this->getJson('/api/public/news/'.$news->slug);

    $response
        ->assertOk()
        ->assertJsonPath('data.slug', $news->slug);
});
