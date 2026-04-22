<?php

namespace App\Modules\NewsCategory\Entities;

use App\Modules\NewsCategory\ValueObjects\CategorySlug;

class NewsCategoryEntity
{
    public function __construct(
        public readonly string $name,
        public readonly CategorySlug $slug
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug->value(),
        ];
    }
}
