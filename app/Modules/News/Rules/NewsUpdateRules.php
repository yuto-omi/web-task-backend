<?php

namespace App\Modules\News\Rules;

use App\Modules\News\DTO\UpdateNewsDTO;
use App\Modules\News\Exceptions\NewsValidationException;
use App\Modules\News\Models\News;
use App\Modules\NewsCategory\Models\NewsCategory;

class NewsUpdateRules
{
    public function validate(News $news, UpdateNewsDTO $dto): void
    {
        $this->validateCategory($dto);
    }

    private function validateCategory(UpdateNewsDTO $dto): void
    {
        $category = NewsCategory::find($dto->categoryId);

        if (! $category) {
            throw new NewsValidationException(
                'The selected category does not exist.'
            );
        }
    }
}
