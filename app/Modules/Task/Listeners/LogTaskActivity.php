<?php

namespace App\Modules\Task\Listeners;

use App\Modules\Task\Events\TaskCreated;
use App\Modules\Task\Events\TaskDeleted;
use App\Modules\Task\Events\TaskStatusChanged;
use App\Modules\Task\Events\TaskUpdated;
use Illuminate\Support\Facades\Log;

class LogTaskActivity
{
    public function handle(TaskCreated|TaskUpdated|TaskDeleted|TaskStatusChanged $event): void
    {
        $context = match (true) {
            $event instanceof TaskCreated       => ['event' => 'task.created',        'task_id' => $event->task->id, 'project_id' => $event->task->project_id],
            $event instanceof TaskUpdated       => ['event' => 'task.updated',        'task_id' => $event->task->id, 'project_id' => $event->task->project_id],
            $event instanceof TaskDeleted       => ['event' => 'task.deleted',        'task_id' => $event->task->id, 'project_id' => $event->task->project_id],
            $event instanceof TaskStatusChanged => ['event' => 'task.status_changed', 'task_id' => $event->task->id, 'from' => $event->oldStatus, 'to' => $event->newStatus],
        };

        Log::info('task_activity', $context);
    }
}
