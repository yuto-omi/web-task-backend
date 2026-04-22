<?php

use App\Modules\NewsCategory\Models\NewsCategory;
use App\Modules\User\Models\User;

it('requires authentication for admin news', function () {
    $this->getJson('/api/admin/news')->assertUnauthorized();
});

it('requires permission for admin news', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $this->getJson('/api/admin/news', ['Authorization' => 'Bearer '.$token])
        ->assertForbidden();
});

it('allows admin to manage news', function () {
    $auth = actingAsAdmin();
    $category = NewsCategory::factory()->create();

    $createResponse = $this->postJson('/api/admin/news', [
        'category_id' => $category->id,
        'title' => 'New item',
        'content' => 'Body',
        'status' => 'draft',
    ], authHeader($auth));

    $createResponse->assertCreated();
    $newsId = $createResponse->json('data.id');

    $this->getJson('/api/admin/news/'.$newsId, authHeader($auth))
        ->assertOk();

    $this->patchJson('/api/admin/news/'.$newsId, [
        'title' => 'Updated title',
        'status' => 'published',
        'published_at' => now()->toDateTimeString(),
    ], authHeader($auth))->assertOk();

    $this->deleteJson('/api/admin/news/'.$newsId, [], authHeader($auth))
        ->assertNoContent();
});
