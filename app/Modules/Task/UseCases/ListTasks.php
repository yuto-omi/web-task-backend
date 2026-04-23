<?php

namespace App\Modules\Task\UseCases;

use App\Modules\Task\Contracts\TaskRepository;
use App\Modules\Task\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class ListTasks
{
    public function __construct(private readonly TaskRepository $tasks) {}

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Task>
     */
    public function handle(array $filters): Collection
    {
        return $this->tasks->list($filters);
    }
}
