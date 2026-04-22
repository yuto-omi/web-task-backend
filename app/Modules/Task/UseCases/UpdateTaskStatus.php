<?php

namespace App\Modules\Task\UseCases;

use App\Modules\Task\Contracts\TaskRepository;
use App\Modules\Task\Events\TaskStatusChanged;
use App\Modules\Task\Models\Task;
use App\Modules\Task\ValueObjects\TaskStatus;

class UpdateTaskStatus
{
    public function __construct(private readonly TaskRepository $tasks) {}

    public function handle(Task $task, TaskStatus $status): Task
    {
        $oldStatus = $task->status;

        $updated = $this->tasks->update($task, ['status' => $status->value()]);

        event(new TaskStatusChanged($updated, $oldStatus, $status->value()));

        return $updated;
    }
}
