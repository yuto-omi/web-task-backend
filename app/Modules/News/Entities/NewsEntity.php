<?php

namespace App\Modules\News\Entities;

use App\Modules\News\ValueObjects\NewsSlug;
use App\Modules\News\ValueObjects\NewsStatus;
use Carbon\CarbonInterface;

class NewsEntity
{
    public function __construct(
        public readonly int $categoryId,
        public readonly string $title,
        public readonly NewsSlug $slug,
        public readonly string $content,
        public readonly ?string $thumbnailUrl,
        public readonly ?CarbonInterface $publishedAt,
        public readonly NewsStatus $status,
        public readonly bool $isFeatured
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'slug' => $this->slug->value(),
            'content' => $this->content,
            'thumbnail_url' => $this->thumbnailUrl,
            'published_at' => $this->publishedAt,
            'status' => $this->status->value(),
            'is_featured' => $this->isFeatured,
        ];
    }
}
