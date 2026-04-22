<?php

namespace App\Modules\News\UseCases;

use App\Modules\News\Contracts\NewsRepository;
use App\Modules\News\DTO\CreateNewsDTO;
use App\Modules\News\Events\NewsCreated;
use App\Modules\News\Mappers\NewsMapper;
use App\Modules\News\Models\News;

class CreateNews
{
    public function __construct(private readonly NewsRepository $news) {}

    public function handle(CreateNewsDTO $data): News
    {
        $entity = (new NewsMapper)->toEntity($data);

        $created = $this->news->create($entity->toArray());

        event(new NewsCreated($created));

        return $created;
    }
}
