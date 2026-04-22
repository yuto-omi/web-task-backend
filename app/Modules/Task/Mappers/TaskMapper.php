<?php

namespace App\Modules\Task\Mappers;

use App\Modules\Task\DTO\CreateTaskDTO;
use App\Modules\Task\Entities\TaskEntity;
use App\Modules\Task\ValueObjects\TaskPriority;
use App\Modules\Task\ValueObjects\TaskStatus;

class TaskMapper
{
    public function toEntity(CreateTaskDTO $dto): TaskEntity
    {
        return new TaskEntity(
            assigneeId: $dto->assigneeId,
            title: $dto->title,
            projectId: $dto->projectId,
            phaseId: $dto->phaseId,
            parentTaskId: $dto->parentTaskId,
            memo: $dto->memo,
            status: new TaskStatus($dto->status),
            priority: $dto->priority !== null ? new TaskPriority($dto->priority) : null,
            typeTag: $dto->typeTag,
            dueDate: $dto->dueDate,
            estimatedHours: $dto->estimatedHours,
            sortOrder: $dto->sortOrder,
        );
    }
}
