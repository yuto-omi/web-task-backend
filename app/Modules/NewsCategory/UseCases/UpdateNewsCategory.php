<?php

namespace App\Modules\NewsCategory\UseCases;

use App\Modules\NewsCategory\Contracts\NewsCategoryRepository;
use App\Modules\NewsCategory\DTO\UpdateNewsCategoryDTO;
use App\Modules\NewsCategory\Events\NewsCategoryUpdated;
use App\Modules\NewsCategory\Mappers\NewsCategoryMapper;
use App\Modules\NewsCategory\Models\NewsCategory;

class UpdateNewsCategory
{
    public function __construct(private readonly NewsCategoryRepository $categories) {}

    public function handle(NewsCategory $category, UpdateNewsCategoryDTO $data): NewsCategory
    {
        $entity = (new NewsCategoryMapper)->toEntity($data);

        $updated = $this->categories->update($category, $entity->toArray());

        event(new NewsCategoryUpdated($updated));

        return $updated;
    }
}
