<?php

namespace App\Modules\NewsCategory\Providers;

use App\Modules\NewsCategory\Contracts\NewsCategoryRepository;
use App\Modules\NewsCategory\Repositories\EloquentNewsCategoryRepository;
use Illuminate\Support\ServiceProvider;

class NewsCategoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NewsCategoryRepository::class, EloquentNewsCategoryRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__).'/Migrations');
    }
}
