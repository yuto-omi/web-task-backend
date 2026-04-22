<?php

namespace App\Modules\NewsCategory\Repositories;

use App\Modules\NewsCategory\Contracts\NewsCategoryRepository;
use App\Modules\NewsCategory\Models\NewsCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentNewsCategoryRepository implements NewsCategoryRepository
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateAdmin(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = NewsCategory::query();

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        [$sortField, $direction] = $this->parseSort($filters['sort'] ?? null, ['created_at', 'name', 'slug']);
        $query->orderBy($sortField, $direction);

        return $query->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginatePublic(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = NewsCategory::query()
            ->orderBy('name');

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?NewsCategory
    {
        return NewsCategory::query()->find($id);
    }

    public function findPublicBySlug(string $slug): ?NewsCategory
    {
        return NewsCategory::query()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): NewsCategory
    {
        return NewsCategory::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(NewsCategory $category, array $data): NewsCategory
    {
        $category->fill($data);
        $category->save();

        return $category;
    }

    public function delete(NewsCategory $category): void
    {
        $category->delete();
    }

    /**
     * @param  array<int, string>  $allowed
     * @return array{0: string, 1: string}
     */
    private function parseSort(?string $sort, array $allowed): array
    {
        if (! $sort) {
            return ['created_at', 'desc'];
        }

        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $field = ltrim($sort, '-');

        if (! in_array($field, $allowed, true)) {
            return ['created_at', 'desc'];
        }

        return [$field, $direction];
    }
}
