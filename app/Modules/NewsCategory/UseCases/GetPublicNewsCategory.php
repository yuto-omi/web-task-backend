<?php

namespace App\Modules\NewsCategory\UseCases;

use App\Modules\NewsCategory\Contracts\NewsCategoryRepository;
use App\Modules\NewsCategory\Models\NewsCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetPublicNewsCategory
{
    public function __construct(private readonly NewsCategoryRepository $categories) {}

    public function handle(string $slug): NewsCategory
    {
        $category = $this->categories->findPublicBySlug($slug);

        if (! $category) {
            throw new ModelNotFoundException;
        }

        return $category;
    }
}
