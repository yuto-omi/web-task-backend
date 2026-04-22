<?php

namespace App\Modules\News\UseCases;

use App\Modules\News\Contracts\NewsRepository;
use App\Modules\News\Models\News;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetAdminNews
{
    public function __construct(private readonly NewsRepository $news) {}

    public function handle(int $id): News
    {
        $item = $this->news->findById($id);

        if (! $item) {
            throw new ModelNotFoundException;
        }

        return $item;
    }
}
