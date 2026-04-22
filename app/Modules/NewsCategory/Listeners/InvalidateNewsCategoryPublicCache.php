<?php

namespace App\Modules\NewsCategory\Listeners;

use App\Modules\NewsCategory\Events\NewsCategoryCreated;
use App\Modules\NewsCategory\Events\NewsCategoryDeleted;
use App\Modules\NewsCategory\Events\NewsCategoryUpdated;
use App\Shared\Support\PublicCache;

class InvalidateNewsCategoryPublicCache
{
    public function handle(NewsCategoryCreated|NewsCategoryUpdated|NewsCategoryDeleted $event): void
    {
        PublicCache::invalidate(['news-categories', 'news']);
    }
}
