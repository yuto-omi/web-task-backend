<?php

namespace App\Modules\NewsCategory\Mappers;

use App\Modules\NewsCategory\DTO\CreateNewsCategoryDTO;
use App\Modules\NewsCategory\DTO\UpdateNewsCategoryDTO;
use App\Modules\NewsCategory\Entities\NewsCategoryEntity;
use App\Modules\NewsCategory\ValueObjects\CategorySlug;

class NewsCategoryMapper
{
    public function toEntity(CreateNewsCategoryDTO|UpdateNewsCategoryDTO $dto): NewsCategoryEntity
    {
        return new NewsCategoryEntity(
            name: $dto->name,
            slug: CategorySlug::fromName($dto->name, $dto->slug)
        );
    }
}
