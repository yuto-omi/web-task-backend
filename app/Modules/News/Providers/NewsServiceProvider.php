<?php

namespace App\Modules\News\Providers;

use App\Modules\News\Contracts\NewsRepository;
use App\Modules\News\Repositories\EloquentNewsRepository;
use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NewsRepository::class, EloquentNewsRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__).'/Migrations');
    }
}
