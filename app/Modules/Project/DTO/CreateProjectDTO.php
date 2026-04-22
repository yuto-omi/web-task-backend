<?php

namespace App\Modules\Project\DTO;

class CreateProjectDTO
{
    /**
     * @param array<int> $memberIds
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $clientName,
        public readonly string $status,
        public readonly ?string $startDate,
        public readonly ?string $deadline,
        public readonly ?float $estimatedHours,
        public readonly array $memberIds,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            clientName: $data['client_name'] ?? null,
            status: (string) ($data['status'] ?? 'not_started'),
            startDate: $data['start_date'] ?? null,
            deadline: $data['deadline'] ?? null,
            estimatedHours: isset($data['estimated_hours']) ? (float) $data['estimated_hours'] : null,
            memberIds: $data['member_ids'] ?? [],
        );
    }
}
