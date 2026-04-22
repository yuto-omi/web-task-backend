<?php

namespace App\Modules\Task\DTO;

class CreateTaskDTO
{
    public function __construct(
        public readonly int $assigneeId,
        public readonly string $title,
        public readonly ?int $projectId,
        public readonly ?int $phaseId,
        public readonly ?int $parentTaskId,
        public readonly ?string $memo,
        public readonly string $status,
        public readonly ?string $priority,
        public readonly ?string $typeTag,
        public readonly ?string $dueDate,
        public readonly ?float $estimatedHours,
        public readonly ?int $sortOrder,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            assigneeId: (int) $data['assignee_id'],
            title: (string) $data['title'],
            projectId: isset($data['project_id']) ? (int) $data['project_id'] : null,
            phaseId: isset($data['phase_id']) ? (int) $data['phase_id'] : null,
            parentTaskId: isset($data['parent_task_id']) ? (int) $data['parent_task_id'] : null,
            memo: $data['memo'] ?? null,
            status: (string) ($data['status'] ?? 'pending'),
            priority: $data['priority'] ?? null,
            typeTag: $data['type_tag'] ?? null,
            dueDate: $data['due_date'] ?? null,
            estimatedHours: isset($data['estimated_hours']) ? (float) $data['estimated_hours'] : null,
            sortOrder: isset($data['sort_order']) ? (int) $data['sort_order'] : null,
        );
    }
}
