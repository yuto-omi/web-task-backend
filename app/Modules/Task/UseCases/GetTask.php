<?php

namespace App\Modules\Task\UseCases;

use App\Modules\Task\Contracts\TaskRepository;
use App\Modules\Task\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetTask
{
    public function __construct(private readonly TaskRepository $tasks) {}

    public function handle(int $id): Task
    {
        $task = $this->tasks->findById($id);

        if ($task === null) {
            throw new ModelNotFoundException("Task [{$id}] not found.");
        }

        return $task;
    }
}
