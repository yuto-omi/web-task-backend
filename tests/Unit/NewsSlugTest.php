<?php

use App\Modules\News\ValueObjects\NewsSlug;

it('generates a slug from the news title', function () {
    $slug = NewsSlug::fromTitle('Hello World');

    expect($slug->value())->toBe('hello-world');
});
