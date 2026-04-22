<?php

namespace App\Modules\NewsCategory\UseCases;

use App\Modules\NewsCategory\Contracts\NewsCategoryRepository;
use App\Modules\NewsCategory\Models\NewsCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetAdminNewsCategory
{
    public function __construct(private readonly NewsCategoryRepository $categories) {}

    public function handle(int $id): NewsCategory
    {
        $category = $this->categories->findById($id);

        if (! $category) {
            throw new ModelNotFoundException;
        }

        return $category;
    }
}
