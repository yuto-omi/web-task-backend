<?php

namespace App\Modules\News\UseCases;

use App\Modules\News\Contracts\NewsRepository;
use App\Modules\News\Events\NewsDeleted;
use App\Modules\News\Models\News;
use App\Modules\News\Rules\NewsDeletionRules;

class DeleteNews
{
    public function __construct(private readonly NewsRepository $news, private readonly NewsDeletionRules $rules) {}

    public function handle(News $news): void
    {
        $this->rules->validate($news);

        $this->news->delete($news);

        event(new NewsDeleted($news));
    }
}
