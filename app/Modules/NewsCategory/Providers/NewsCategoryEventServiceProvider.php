<?php

namespace App\Modules\NewsCategory\Providers;

use App\Modules\NewsCategory\Events\NewsCategoryCreated;
use App\Modules\NewsCategory\Events\NewsCategoryDeleted;
use App\Modules\NewsCategory\Events\NewsCategoryUpdated;
use App\Modules\NewsCategory\Listeners\InvalidateNewsCategoryPublicCache;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class NewsCategoryEventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, list<class-string>>
     */
    protected $listen = [
        NewsCategoryCreated::class => [
            InvalidateNewsCategoryPublicCache::class,
        ],
        NewsCategoryUpdated::class => [
            InvalidateNewsCategoryPublicCache::class,
        ],
        NewsCategoryDeleted::class => [
            InvalidateNewsCategoryPublicCache::class,
        ],
    ];
}
