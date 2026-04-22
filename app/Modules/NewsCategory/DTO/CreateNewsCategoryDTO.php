<?php

namespace App\Modules\NewsCategory\DTO;

class CreateNewsCategoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $slug
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['name'],
            $data['slug'] ?? null
        );
    }
}
