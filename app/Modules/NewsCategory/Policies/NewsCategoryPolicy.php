<?php

namespace App\Modules\NewsCategory\Policies;

use App\Modules\NewsCategory\Models\NewsCategory;
use App\Modules\User\Models\User;

class NewsCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('newsCategory.viewAny');
    }

    public function view(User $user, NewsCategory $category): bool
    {
        return $user->can('newsCategory.view');
    }

    public function create(User $user): bool
    {
        return $user->can('newsCategory.create');
    }

    public function update(User $user, NewsCategory $category): bool
    {
        return $user->can('newsCategory.update');
    }

    public function delete(User $user, NewsCategory $category): bool
    {
        return $user->can('newsCategory.delete');
    }
}
