<?php

namespace App\Modules\News\Repositories;

use App\Modules\News\Contracts\NewsRepository;
use App\Modules\News\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentNewsRepository implements NewsRepository
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateAdmin(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = News::query()->with('category');

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (array_key_exists('is_featured', $filters)) {
            $query->where('is_featured', (bool) $filters['is_featured']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', (int) $filters['category_id']);
        }

        [$sortField, $direction] = $this->parseSort($filters['sort'] ?? null, ['created_at', 'published_at', 'title']);
        $query->orderBy($sortField, $direction);

        return $query->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginatePublic(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = News::query()
            ->with('category')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if (! empty($filters['category'])) {
            $slug = (string) $filters['category'];
            $query->whereHas('category', function ($builder) use ($slug) {
                $builder->where('slug', $slug);
            });
        }

        if (! empty($filters['q'])) {
            $search = (string) $filters['q'];
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%");
            });
        }

        if (array_key_exists('featured', $filters)) {
            $query->where('is_featured', (bool) $filters['featured']);
        }

        [$sortField, $direction] = $this->parseSort($filters['sort'] ?? null, ['published_at', 'created_at', 'title'], 'published_at', 'desc');
        $query->orderBy($sortField, $direction);

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?News
    {
        return News::query()->with('category')->find($id);
    }

    public function findPublicBySlug(string $slug): ?News
    {
        return News::query()
            ->with('category')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->first();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): News
    {
        return News::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(News $news, array $data): News
    {
        $news->fill($data);
        $news->save();

        return $news;
    }

    public function delete(News $news): void
    {
        $news->delete();
    }

    /**
     * @param  array<int, string>  $allowed
     * @return array{0: string, 1: string}
     */
    private function parseSort(
        ?string $sort,
        array $allowed,
        string $defaultField = 'created_at',
        string $defaultDirection = 'desc'
    ): array {
        if (! $sort) {
            return [$defaultField, $defaultDirection];
        }

        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $field = ltrim($sort, '-');

        if (! in_array($field, $allowed, true)) {
            return [$defaultField, $defaultDirection];
        }

        return [$field, $direction];
    }
}
