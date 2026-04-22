<?php

use App\Modules\News\Models\News;
use App\Modules\NewsCategory\Models\NewsCategory;
use Illuminate\Support\Facades\Cache;

it('caches public news list and invalidates on update', function () {
    config(['cache.default' => 'array']);
    Cache::flush();

    $auth = actingAsAdmin();
    $category = NewsCategory::factory()->create();
    $news = News::factory()->create([
        'category_id' => $category->id,
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);

    $this->getJson('/api/public/news')->assertOk();

    $params = [];
    ksort($params);
    $baseKey = 'public.news.index.'.md5(http_build_query($params));

    if (Cache::supportsTags()) {
        expect(Cache::tags(['news'])->has($baseKey))->toBeTrue();
    } else {
        expect(Cache::has('1:'.$baseKey))->toBeTrue();
    }

    $this->patchJson('/api/admin/news/'.$news->id, [
        'title' => 'Updated',
        'status' => 'published',
        'published_at' => now()->toDateTimeString(),
    ], authHeader($auth))->assertOk();

    if (Cache::supportsTags()) {
        expect(Cache::tags(['news'])->has($baseKey))->toBeFalse();
    } else {
        expect(Cache::get('public_cache_version:news'))->toBe(2);
    }

    $this->getJson('/api/public/news')->assertOk();

    if (Cache::supportsTags()) {
        expect(Cache::tags(['news'])->has($baseKey))->toBeTrue();
    } else {
        expect(Cache::has('2:'.$baseKey))->toBeTrue();
    }
});

it('caches public news categories and invalidates on update', function () {
    config(['cache.default' => 'array']);
    Cache::flush();

    $auth = actingAsAdmin();
    $category = NewsCategory::factory()->create();

    $this->getJson('/api/public/news-categories')->assertOk();

    $params = [];
    ksort($params);
    $baseKey = 'public.news_categories.index.'.md5(http_build_query($params));

    if (Cache::supportsTags()) {
        expect(Cache::tags(['news-categories'])->has($baseKey))->toBeTrue();
    } else {
        expect(Cache::has('1:'.$baseKey))->toBeTrue();
    }

    $this->patchJson('/api/admin/news-categories/'.$category->id, [
        'name' => 'Updated',
    ], authHeader($auth))->assertOk();

    if (Cache::supportsTags()) {
        expect(Cache::tags(['news-categories'])->has($baseKey))->toBeFalse();
    } else {
        expect(Cache::get('public_cache_version:news-categories'))->toBe(2);
    }

    $this->getJson('/api/public/news-categories')->assertOk();

    if (Cache::supportsTags()) {
        expect(Cache::tags(['news-categories'])->has($baseKey))->toBeTrue();
    } else {
        expect(Cache::has('2:'.$baseKey))->toBeTrue();
    }
});
