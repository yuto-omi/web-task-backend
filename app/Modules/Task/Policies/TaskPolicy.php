<?php

namespace App\Modules\Task\Policies;

use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('task.viewAny');
    }

    public function view(User $user, Task $task): bool
    {
        return $user->can('task.view');
    }

    public function create(User $user): bool
    {
        return $user->can('task.create');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->can('task.update');
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can('task.delete');
    }
}
