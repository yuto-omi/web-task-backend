<?php

use App\Modules\News\Contracts\NewsRepository;
use App\Modules\News\DTO\CreateNewsDTO;
use App\Modules\News\Models\News;
use App\Modules\News\UseCases\CreateNews;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class FakeNewsRepository implements NewsRepository
{
    public function paginateAdmin(array $filters, int $perPage): LengthAwarePaginator
    {
        throw new RuntimeException('Not implemented.');
    }

    public function paginatePublic(array $filters, int $perPage): LengthAwarePaginator
    {
        throw new RuntimeException('Not implemented.');
    }

    public function findById(int $id): ?News
    {
        return null;
    }

    public function findPublicBySlug(string $slug): ?News
    {
        return null;
    }

    public function create(array $data): News
    {
        return new News($data);
    }

    public function update(News $news, array $data): News
    {
        return $news;
    }

    public function delete(News $news): void {}
}

it('requires published_at when status is published', function () {
    $useCase = new CreateNews(new FakeNewsRepository);

    $data = CreateNewsDTO::fromArray([
        'category_id' => 1,
        'title' => 'Sample',
        'content' => 'Body',
        'status' => 'published',
        'published_at' => null,
    ]);

    $useCase->handle($data);
})->throws(ValidationException::class);
