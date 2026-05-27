<?php

namespace App\Modules\Task\Entities;

use App\Modules\Task\ValueObjects\TaskPriority;
use App\Modules\Task\ValueObjects\TaskStatus;

class TaskEntity
{
    public function __construct(
        public readonly int $assigneeId,
        public readonly string $title,
        public readonly ?int $projectId,
        public readonly ?int $phaseId,
        public readonly ?int $parentTaskId,
        public readonly ?string $memo,
        public readonly TaskStatus $status,
        public readonly ?TaskPriority $priority,
        public readonly ?string $typeTag,
        public readonly ?string $dueDate,
        public readonly ?float $estimatedHours,
        public readonly ?int $sortOrder,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'assignee_id' => $this->assigneeId,
            'title' => $this->title,
            'project_id' => $this->projectId,
            'phase_id' => $this->phaseId,
            'parent_task_id' => $this->parentTaskId,
            'memo' => $this->memo,
            'status' => $this->status->value(),
            'priority' => $this->priority?->value(),
            'type_tag' => $this->typeTag,
            'due_date' => $this->dueDate,
            'estimated_hours' => $this->estimatedHours,
            // null のときはDBのデフォルト値（0）を使う
            ...($this->sortOrder !== null ? ['sort_order' => $this->sortOrder] : []),
        ];
    }
}
