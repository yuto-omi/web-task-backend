<?php

namespace App\Modules\Project\Providers;

use App\Modules\Project\Events\PhaseCreated;
use App\Modules\Project\Events\PhaseDeleted;
use App\Modules\Project\Events\PhaseStatusChanged;
use App\Modules\Project\Events\PhaseUpdated;
use App\Modules\Project\Events\ProjectCreated;
use App\Modules\Project\Events\ProjectDeleted;
use App\Modules\Project\Events\ProjectStatusChanged;
use App\Modules\Project\Events\ProjectUpdated;
use App\Modules\Project\Listeners\LogProjectActivity;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class ProjectEventServiceProvider extends ServiceProvider
{
    /** @var array<class-string, list<class-string>> */
    protected $listen = [
        ProjectCreated::class       => [LogProjectActivity::class],
        ProjectUpdated::class       => [LogProjectActivity::class],
        ProjectDeleted::class       => [LogProjectActivity::class],
        ProjectStatusChanged::class => [LogProjectActivity::class],
        PhaseCreated::class         => [LogProjectActivity::class],
        PhaseUpdated::class         => [LogProjectActivity::class],
        PhaseDeleted::class         => [LogProjectActivity::class],
        PhaseStatusChanged::class   => [LogProjectActivity::class],
    ];
}
