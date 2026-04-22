<?php

namespace App\Modules\News\UseCases;

use App\Modules\News\Contracts\NewsRepository;
use App\Modules\News\Models\News;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetPublicNews
{
    public function __construct(private readonly NewsRepository $news) {}

    public function handle(string $slug): News
    {
        $item = $this->news->findPublicBySlug($slug);

        if (! $item) {
            throw new ModelNotFoundException;
        }

        return $item;
    }
}
