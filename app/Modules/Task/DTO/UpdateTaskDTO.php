<?php

namespace App\Modules\Task\DTO;

class UpdateTaskDTO
{
    /** バリデーション済みデータに含まれていたキー一覧 */
    private array $providedKeys = [];

    public function __construct(
        public readonly ?int $assigneeId,
        public readonly ?string $title,
        public readonly ?int $phaseId,
        public readonly ?int $parentTaskId,
        public readonly ?string $memo,
        public readonly ?string $status,
        public readonly ?string $priority,
        public readonly ?string $typeTag,
        public readonly ?string $dueDate,
        public readonly ?float $estimatedHours,
        public readonly ?int $sortOrder,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $dto = new self(
            assigneeId: isset($data['assignee_id']) ? (int) $data['assignee_id'] : null,
            title: $data['title'] ?? null,
            phaseId: isset($data['phase_id']) ? (int) $data['phase_id'] : null,
            parentTaskId: isset($data['parent_task_id']) ? (int) $data['parent_task_id'] : null,
            memo: $data['memo'] ?? null,
            status: $data['status'] ?? null,
            priority: $data['priority'] ?? null,
            typeTag: $data['type_tag'] ?? null,
            dueDate: $data['due_date'] ?? null,
            estimatedHours: isset($data['estimated_hours']) ? (float) $data['estimated_hours'] : null,
            sortOrder: isset($data['sort_order']) ? (int) $data['sort_order'] : null,
        );

        // 送信されたキーのみ記録（nullのクリアと未送信を区別するため）
        $dto->providedKeys = array_keys($data);

        return $dto;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $all = [
            'assignee_id' => $this->assigneeId,
            'title' => $this->title,
            'phase_id' => $this->phaseId,
            'parent_task_id' => $this->parentTaskId,
            'memo' => $this->memo,
            'status' => $this->status,
            'priority' => $this->priority,
            'type_tag' => $this->typeTag,
            'due_date' => $this->dueDate,
            'estimated_hours' => $this->estimatedHours,
            'sort_order' => $this->sortOrder,
        ];

        // リクエストで送信されたキーのみ更新対象とする
        return array_intersect_key($all, array_flip($this->providedKeys));
    }
}
