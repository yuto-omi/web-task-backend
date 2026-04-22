<?php

use App\Modules\User\Models\User;

it('requires authentication for admin news categories', function () {
    $this->getJson('/api/admin/news-categories')->assertUnauthorized();
});

it('requires permission for admin news categories', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $this->getJson('/api/admin/news-categories', ['Authorization' => 'Bearer '.$token])
        ->assertForbidden();
});

it('allows admin to manage news categories', function () {
    $auth = actingAsAdmin();

    $createResponse = $this->postJson('/api/admin/news-categories', [
        'name' => 'Tech',
    ], authHeader($auth));

    $createResponse->assertCreated();
    $categoryId = $createResponse->json('data.id');

    $this->getJson('/api/admin/news-categories/'.$categoryId, authHeader($auth))
        ->assertOk();

    $this->patchJson('/api/admin/news-categories/'.$categoryId, [
        'name' => 'Tech Updated',
    ], authHeader($auth))->assertOk();

    $this->deleteJson('/api/admin/news-categories/'.$categoryId, [], authHeader($auth))
        ->assertNoContent();
});
