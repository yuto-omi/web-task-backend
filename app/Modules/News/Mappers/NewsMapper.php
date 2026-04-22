<?php

namespace App\Modules\News\Mappers;

use App\Modules\News\DTO\CreateNewsDTO;
use App\Modules\News\DTO\UpdateNewsDTO;
use App\Modules\News\Entities\NewsEntity;
use App\Modules\News\ValueObjects\NewsSlug;
use App\Modules\News\ValueObjects\NewsStatus;
use Illuminate\Validation\ValidationException;

class NewsMapper
{
    public function toEntity(CreateNewsDTO|UpdateNewsDTO $dto): NewsEntity
    {
        $status = new NewsStatus($dto->status);

        if ($status->requiresPublishedAt() && ! $dto->publishedAt) {
            throw ValidationException::withMessages([
                'published_at' => 'published_at is required when status is published.',
            ]);
        }

        return new NewsEntity(
            categoryId: $dto->categoryId,
            title: $dto->title,
            slug: NewsSlug::fromTitle($dto->title, $dto->slug),
            content: $dto->content,
            thumbnailUrl: $dto->thumbnailUrl,
            publishedAt: $dto->publishedAt,
            status: $status,
            isFeatured: $dto->isFeatured
        );
    }
}
