<?php

namespace App\Modules\Task\Providers;

use App\Modules\Task\Events\TaskCreated;
use App\Modules\Task\Events\TaskDeleted;
use App\Modules\Task\Events\TaskStatusChanged;
use App\Modules\Task\Events\TaskUpdated;
use App\Modules\Task\Listeners\LogTaskActivity;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class TaskEventServiceProvider extends ServiceProvider
{
    /** @var array<class-string, list<class-string>> */
    protected $listen = [
        TaskCreated::class       => [LogTaskActivity::class],
        TaskUpdated::class       => [LogTaskActivity::class],
        TaskDeleted::class       => [LogTaskActivity::class],
        TaskStatusChanged::class => [LogTaskActivity::class],
    ];
}
