<?php

namespace App\Modules\News\DTO;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class UpdateNewsDTO
{
    public function __construct(
        public readonly int $categoryId,
        public readonly string $title,
        public readonly ?string $slug,
        public readonly string $content,
        public readonly ?string $thumbnailUrl,
        public readonly ?CarbonInterface $publishedAt,
        public readonly string $status,
        public readonly bool $isFeatured
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['category_id'],
            (string) $data['title'],
            $data['slug'] ?? null,
            (string) $data['content'],
            $data['thumbnail_url'] ?? null,
            ! empty($data['published_at']) ? CarbonImmutable::parse($data['published_at']) : null,
            (string) $data['status'],
            array_key_exists('is_featured', $data) ? (bool) $data['is_featured'] : false
        );
    }
}
