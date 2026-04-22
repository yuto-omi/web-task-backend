<?php

namespace App\Modules\News\Listeners;

use App\Modules\News\Events\NewsCreated;
use App\Modules\News\Events\NewsDeleted;
use App\Modules\News\Events\NewsUpdated;
use App\Shared\Support\PublicCache;

class InvalidateNewsPublicCache
{
    public function handle(NewsCreated|NewsUpdated|NewsDeleted $event): void
    {
        PublicCache::invalidate(['news']);
    }
}
