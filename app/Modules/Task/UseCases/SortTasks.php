<?php

namespace App\Modules\Task\UseCases;

use App\Modules\Task\Contracts\TaskRepository;

class SortTasks
{
    public function __construct(private readonly TaskRepository $tasks) {}

    /**
     * @param array<int> $ids 並び順（先頭が sort_order=1）
     */
    public function handle(array $ids): void
    {
        $this->tasks->sort($ids);
    }
}
