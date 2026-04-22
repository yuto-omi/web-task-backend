<?php

namespace App\Modules\Task\Providers;

use App\Modules\Task\Contracts\TaskRepository;
use App\Modules\Task\Repositories\EloquentTaskRepository;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepository::class, EloquentTaskRepository::class);
    }

    public function boot(): void {}
}
