<?php

namespace App\Providers;

use App\Modules\News\Models\News;
use App\Modules\News\Policies\NewsPolicy;
use App\Modules\NewsCategory\Models\NewsCategory;
use App\Modules\NewsCategory\Policies\NewsCategoryPolicy;
use App\Modules\Task\Models\Task;
use App\Modules\Task\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        News::class => NewsPolicy::class,
        NewsCategory::class => NewsCategoryPolicy::class,
        Task::class => TaskPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
