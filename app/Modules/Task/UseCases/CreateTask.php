<?php

namespace App\Modules\Task\UseCases;

use App\Modules\Task\Contracts\TaskRepository;
use App\Modules\Task\DTO\CreateTaskDTO;
use App\Modules\Task\Events\TaskCreated;
use App\Modules\Task\Mappers\TaskMapper;
use App\Modules\Task\Models\Task;

class CreateTask
{
    public function __construct(private readonly TaskRepository $tasks) {}

    public function handle(CreateTaskDTO $dto, int $createdBy): Task
    {
        $entity = (new TaskMapper)->toEntity($dto);

        $task = $this->tasks->create([
            ...$entity->toArray(),
            'created_by' => $createdBy,
        ]);

        event(new TaskCreated($task));

        return $task;
    }
}
