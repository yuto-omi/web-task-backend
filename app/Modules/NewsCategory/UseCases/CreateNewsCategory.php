<?php

namespace App\Modules\NewsCategory\UseCases;

use App\Modules\NewsCategory\Contracts\NewsCategoryRepository;
use App\Modules\NewsCategory\DTO\CreateNewsCategoryDTO;
use App\Modules\NewsCategory\Events\NewsCategoryCreated;
use App\Modules\NewsCategory\Mappers\NewsCategoryMapper;
use App\Modules\NewsCategory\Models\NewsCategory;

class CreateNewsCategory
{
    public function __construct(private readonly NewsCategoryRepository $categories) {}

    public function handle(CreateNewsCategoryDTO $data): NewsCategory
    {
        $entity = (new NewsCategoryMapper)->toEntity($data);

        $category = $this->categories->create($entity->toArray());

        event(new NewsCategoryCreated($category));

        return $category;
    }
}
