<?php

namespace App\Modules\NewsCategory\UseCases;

use App\Modules\NewsCategory\Contracts\NewsCategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPublicNewsCategories
{
    public function __construct(private readonly NewsCategoryRepository $categories) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function handle(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->categories->paginatePublic($filters, $perPage);
    }
}
