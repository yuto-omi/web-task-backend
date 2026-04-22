<?php

use App\Modules\NewsCategory\Models\NewsCategory;

it('lists public news categories', function () {
    NewsCategory::factory()->count(2)->create();

    $response = $this->getJson('/api/public/news-categories');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(2);
});

it('shows a public news category by slug', function () {
    $category = NewsCategory::factory()->create();

    $response = $this->getJson('/api/public/news-categories/'.$category->slug);

    $response
        ->assertOk()
        ->assertJsonPath('data.slug', $category->slug);
});
