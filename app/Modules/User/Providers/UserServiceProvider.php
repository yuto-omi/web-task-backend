<?php

namespace App\Modules\User\Providers;

use App\Modules\User\Contracts\UserRepository;
use App\Modules\User\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__).'/Migrations');
    }
}
