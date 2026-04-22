<?php

namespace App\Modules\News\UseCases;

use App\Modules\News\Contracts\NewsRepository;
use App\Modules\News\DTO\UpdateNewsDTO;
use App\Modules\News\Events\NewsUpdated;
use App\Modules\News\Mappers\NewsMapper;
use App\Modules\News\Models\News;

class UpdateNews
{
    public function __construct(private readonly NewsRepository $news) {}

    public function handle(News $news, UpdateNewsDTO $data): News
    {
        $entity = (new NewsMapper)->toEntity($data);

        $updated = $this->news->update($news, $entity->toArray());

        event(new NewsUpdated($updated));

        return $updated;
    }
}
