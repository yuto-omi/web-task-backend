<?php

namespace App\Modules\Project\DTO;

class UpdateProjectDTO
{
    /** バリデーション済みデータに含まれていたキー一覧 */
    private array $providedKeys = [];

    /**
     * @param array<int>|null $memberIds
     */
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $clientName,
        public readonly ?string $startDate,
        public readonly ?string $deadline,
        public readonly ?float $estimatedHours,
        public readonly ?string $memo,
        public readonly ?array $memberIds,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $dto = new self(
            name: $data['name'] ?? null,
            clientName: $data['client_name'] ?? null,
            startDate: $data['start_date'] ?? null,
            deadline: $data['deadline'] ?? null,
            estimatedHours: isset($data['estimated_hours']) ? (float) $data['estimated_hours'] : null,
            memo: $data['memo'] ?? null,
            memberIds: $data['member_ids'] ?? null,
        );

        // 送信されたキーのみ記録（nullのクリアと未送信を区別するため）
        $dto->providedKeys = array_keys($data);

        return $dto;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $all = [
            'name'            => $this->name,
            'client_name'     => $this->clientName,
            'start_date'      => $this->startDate,
            'deadline'        => $this->deadline,
            'estimated_hours' => $this->estimatedHours,
            'memo'            => $this->memo,
        ];

        // リクエストで送信されたキーのみ更新対象とする
        return array_intersect_key($all, array_flip($this->providedKeys));
    }
}
