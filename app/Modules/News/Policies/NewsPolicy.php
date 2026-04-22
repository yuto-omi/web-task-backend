<?php

namespace App\Modules\News\Policies;

use App\Modules\News\Models\News;
use App\Modules\User\Models\User;

class NewsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('news.viewAny');
    }

    public function view(User $user, News $news): bool
    {
        return $user->can('news.view');
    }

    public function create(User $user): bool
    {
        return $user->can('news.create');
    }

    public function update(User $user, News $news): bool
    {
        return $user->can('news.update');
    }

    public function delete(User $user, News $news): bool
    {
        return $user->can('news.delete');
    }
}
