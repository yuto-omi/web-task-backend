<?php

namespace App\Modules\Task\UseCases;

use App\Modules\Task\Contracts\TaskRepository;
use App\Modules\Task\DTO\UpdateTaskDTO;
use App\Modules\Task\Events\TaskUpdated;
use App\Modules\Task\Models\Task;

class UpdateTask
{
    public function __construct(private readonly TaskRepository $tasks) {}

    public function handle(Task $task, UpdateTaskDTO $dto): Task
    {
        $updated = $this->tasks->update($task, $dto->toArray());

        event(new TaskUpdated($updated));

        return $updated;
    }
}
