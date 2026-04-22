<?php

namespace App\Modules\Project\Entities;

use App\Modules\Project\ValueObjects\PhaseStatus;

class ProjectPhaseEntity
{
    public function __construct(
        public readonly string $name,
        public readonly PhaseStatus $status,
        public readonly ?int $assigneeId,
        public readonly ?string $startDate,
        public readonly ?string $endDate,
        public readonly ?float $estimatedHours,
        public readonly ?int $sortOrder,
        public readonly ?int $progressRate,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'name'            => $this->name,
            'status'          => $this->status->value(),
            'assignee_id'     => $this->assigneeId,
            'start_date'      => $this->startDate,
            'end_date'        => $this->endDate,
            'estimated_hours' => $this->estimatedHours,
            'sort_order'      => $this->sortOrder,
            'progress_rate'   => $this->progressRate,
        ];
    }
}
