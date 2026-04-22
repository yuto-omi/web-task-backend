<?php

namespace App\Modules\News\Providers;

use App\Modules\News\Events\NewsCreated;
use App\Modules\News\Events\NewsDeleted;
use App\Modules\News\Events\NewsUpdated;
use App\Modules\News\Listeners\InvalidateNewsPublicCache;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class NewsEventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, list<class-string>>
     */
    protected $listen = [
        NewsCreated::class => [
            InvalidateNewsPublicCache::class,
        ],
        NewsUpdated::class => [
            InvalidateNewsPublicCache::class,
        ],
        NewsDeleted::class => [
            InvalidateNewsPublicCache::class,
        ],
    ];
}
