<?php

namespace App\Modules\User\Providers;

use App\Modules\User\Events\UserLoggedIn;
use App\Modules\User\Events\UserLoggedOut;
use App\Modules\User\Events\UserRegistered;
use App\Modules\User\Listeners\SendWelcomeEmail;
use App\Modules\User\Listeners\UserAuthEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class UserEventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, list<class-string>>
     */
    protected $listen = [
        UserRegistered::class => [
            UserAuthEventListener::class,
            SendWelcomeEmail::class,
        ],
        UserLoggedIn::class => [
            UserAuthEventListener::class,
        ],
        UserLoggedOut::class => [
            UserAuthEventListener::class,
        ],
    ];
}
