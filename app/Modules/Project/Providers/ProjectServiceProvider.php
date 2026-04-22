<?php

namespace App\Modules\Project\Providers;

use App\Modules\Project\Contracts\ProjectPhaseRepository;
use App\Modules\Project\Contracts\ProjectRepository;
use App\Modules\Project\Repositories\EloquentProjectPhaseRepository;
use App\Modules\Project\Repositories\EloquentProjectRepository;
use Illuminate\Support\ServiceProvider;

class ProjectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProjectRepository::class, EloquentProjectRepository::class);
        $this->app->bind(ProjectPhaseRepository::class, EloquentProjectPhaseRepository::class);
    }

    public function boot(): void {}
}
