<?php

namespace App\Modules\News\Rules;

use App\Modules\News\DTO\CreateNewsDTO;
use App\Modules\News\Exceptions\NewsValidationException;
use App\Modules\NewsCategory\Models\NewsCategory;

class NewsCreationRules
{
    public function validate(CreateNewsDTO $dto): void
    {
        $this->validateCategory($dto);
    }

    private function validateCategory(CreateNewsDTO $dto): void
    {
        $category = NewsCategory::find($dto->categoryId);

        if (! $category) {
            throw new NewsValidationException(
                'The selected category does not exist.'
            );
        }
    }
}
