<?php

namespace App\Modules\Project\DTO;

class UpdatePhaseDTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?int $assigneeId,
        public readonly ?string $startDate,
        public readonly ?string $endDate,
        public readonly ?float $estimatedHours,
        public readonly ?int $sortOrder,
        public readonly ?int $progressRate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            assigneeId: isset($data['assignee_id']) ? (int) $data['assignee_id'] : null,
            startDate: $data['start_date'] ?? null,
            endDate: $data['end_date'] ?? null,
            estimatedHours: isset($data['estimated_hours']) ? (float) $data['estimated_hours'] : null,
            sortOrder: isset($data['sort_order']) ? (int) $data['sort_order'] : null,
            progressRate: array_key_exists('progress_rate', $data) ? (int) $data['progress_rate'] : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'assignee_id' => $this->assigneeId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'estimated_hours' => $this->estimatedHours,
            'sort_order' => $this->sortOrder,
            'progress_rate' => $this->progressRate,
        ], fn ($v) => $v !== null);
    }
}
