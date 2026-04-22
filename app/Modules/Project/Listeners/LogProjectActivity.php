<?php

namespace App\Modules\Project\Listeners;

use App\Modules\Project\Events\PhaseCreated;
use App\Modules\Project\Events\PhaseDeleted;
use App\Modules\Project\Events\PhaseStatusChanged;
use App\Modules\Project\Events\PhaseUpdated;
use App\Modules\Project\Events\ProjectCreated;
use App\Modules\Project\Events\ProjectDeleted;
use App\Modules\Project\Events\ProjectStatusChanged;
use App\Modules\Project\Events\ProjectUpdated;
use Illuminate\Support\Facades\Log;

class LogProjectActivity
{
    public function handle(
        ProjectCreated|ProjectUpdated|ProjectDeleted|ProjectStatusChanged|PhaseCreated|PhaseUpdated|PhaseDeleted|PhaseStatusChanged $event,
    ): void {
        $context = match (true) {
            $event instanceof ProjectCreated        => ['event' => 'project.created',        'project_id' => $event->project->id],
            $event instanceof ProjectUpdated        => ['event' => 'project.updated',        'project_id' => $event->project->id],
            $event instanceof ProjectDeleted        => ['event' => 'project.deleted',        'project_id' => $event->project->id],
            $event instanceof ProjectStatusChanged  => ['event' => 'project.status_changed', 'project_id' => $event->project->id, 'from' => $event->oldStatus, 'to' => $event->newStatus],
            $event instanceof PhaseCreated          => ['event' => 'phase.created',          'phase_id'   => $event->phase->id,   'project_id' => $event->phase->project_id],
            $event instanceof PhaseUpdated          => ['event' => 'phase.updated',          'phase_id'   => $event->phase->id,   'project_id' => $event->phase->project_id],
            $event instanceof PhaseDeleted          => ['event' => 'phase.deleted',          'phase_id'   => $event->phase->id,   'project_id' => $event->phase->project_id],
            $event instanceof PhaseStatusChanged    => ['event' => 'phase.status_changed',   'phase_id'   => $event->phase->id,   'from' => $event->oldStatus, 'to' => $event->newStatus],
        };

        Log::info('project_activity', $context);
    }
}
