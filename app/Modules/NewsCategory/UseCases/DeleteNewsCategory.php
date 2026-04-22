<?php

namespace App\Modules\NewsCategory\UseCases;

use App\Modules\NewsCategory\Contracts\NewsCategoryRepository;
use App\Modules\NewsCategory\Events\NewsCategoryDeleted;
use App\Modules\NewsCategory\Models\NewsCategory;

class DeleteNewsCategory
{
    public function __construct(private readonly NewsCategoryRepository $categories) {}

    public function handle(NewsCategory $category): void
    {
        $this->categories->delete($category);

        event(new NewsCategoryDeleted($category));
    }
}
