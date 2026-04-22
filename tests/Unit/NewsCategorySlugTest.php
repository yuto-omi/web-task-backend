<?php

use App\Modules\NewsCategory\ValueObjects\CategorySlug;

it('generates a slug from the category name', function () {
    $slug = CategorySlug::fromName('Breaking News');

    expect($slug->value())->toBe('breaking-news');
});

it('uses a provided slug when available', function () {
    $slug = CategorySlug::fromName('Breaking News', 'custom-slug');

    expect($slug->value())->toBe('custom-slug');
});

it('rejects an empty slug', function () {
    new CategorySlug('');
})->throws(InvalidArgumentException::class, 'Slug cannot be empty.');
