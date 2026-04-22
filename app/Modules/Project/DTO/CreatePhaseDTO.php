<?php

namespace App\Modules\Project\DTO;

class CreatePhaseDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?int $assigneeId,
        public readonly ?string $startDate,
        public readonly ?string $endDate,
        public readonly ?float $estimatedHours,
        public readonly ?int $sortOrder,
        public readonly ?int $progressRate,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            assigneeId: isset($data['assignee_id']) ? (int) $data['assignee_id'] : null,
            startDate: $data['start_date'] ?? null,
            endDate: $data['end_date'] ?? null,
            estimatedHours: isset($data['estimated_hours']) ? (float) $data['estimated_hours'] : null,
            sortOrder: isset($data['sort_order']) ? (int) $data['sort_order'] : null,
            progressRate: isset($data['progress_rate']) ? (int) $data['progress_rate'] : null,
        );
    }
}
