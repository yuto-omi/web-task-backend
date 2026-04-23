<?php

namespace App\Modules\Task\Contracts;

use App\Modules\Task\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepository
{
    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Task>
     */
    public function list(array $filters): Collection;

    public function findById(int $id): ?Task;

    /** @param array<string, mixed> $data */
    public function create(array $data): Task;

    /** @param array<string, mixed> $data */
    public function update(Task $task, array $data): Task;

    public function delete(Task $task): void;

    public function forceDelete(Task $task): void;

    /** @param array<int> $ids 並び順（先頭が sort_order=1） */
    public function sort(array $ids): void;
}
