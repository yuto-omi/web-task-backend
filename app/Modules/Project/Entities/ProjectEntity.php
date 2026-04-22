<?php

namespace App\Modules\Project\Entities;

use App\Modules\Project\ValueObjects\ProjectStatus;

class ProjectEntity
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $clientName,
        public readonly ProjectStatus $status,
        public readonly ?string $startDate,
        public readonly ?string $deadline,
        public readonly ?float $estimatedHours,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'name'            => $this->name,
            'client_name'     => $this->clientName,
            'status'          => $this->status->value(),
            'start_date'      => $this->startDate,
            'deadline'        => $this->deadline,
            'estimated_hours' => $this->estimatedHours,
        ];
    }
}
