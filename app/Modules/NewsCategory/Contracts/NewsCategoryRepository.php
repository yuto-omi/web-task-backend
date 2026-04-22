<?php

namespace App\Modules\NewsCategory\Contracts;

use App\Modules\NewsCategory\Models\NewsCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NewsCategoryRepository
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateAdmin(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginatePublic(array $filters, int $perPage): LengthAwarePaginator;

    public function findById(int $id): ?NewsCategory;

    public function findPublicBySlug(string $slug): ?NewsCategory;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): NewsCategory;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(NewsCategory $category, array $data): NewsCategory;

    public function delete(NewsCategory $category): void;
}
