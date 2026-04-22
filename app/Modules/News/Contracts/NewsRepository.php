<?php

namespace App\Modules\News\Contracts;

use App\Modules\News\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NewsRepository
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateAdmin(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginatePublic(array $filters, int $perPage): LengthAwarePaginator;

    public function findById(int $id): ?News;

    public function findPublicBySlug(string $slug): ?News;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): News;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(News $news, array $data): News;

    public function delete(News $news): void;
}
