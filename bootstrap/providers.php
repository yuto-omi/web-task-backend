<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\ModuleDiscoveryServiceProvider;

return [
    AppServiceProvider::class,
    ModuleDiscoveryServiceProvider::class,
    AuthServiceProvider::class,
    EventServiceProvider::class,
];
