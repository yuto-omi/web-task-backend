<?php

namespace App\Modules\Task\Repositories;

use App\Modules\Task\Contracts\TaskRepository;
use App\Modules\Task\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class EloquentTaskRepository implements TaskRepository
{
    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Task>
     */
    public function list(array $filters): Collection
    {
        $query = Task::query()->with('assignee:id,name');

        // project_id=null のとき個人タスクのみ取得
        if (array_key_exists('project_id', $filters)) {
            $projectId = $filters['project_id'];
            $projectId === null
                ? $query->personal()
                : $query->where('project_id', $projectId);
        }

        if (! empty($filters['phase_id'])) {
            $query->where('phase_id', $filters['phase_id']);
        }

        if (! empty($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('sort_order')->orderBy('created_at')->get();
    }

    public function findById(int $id): ?Task
    {
        return Task::query()->with(['subTasks', 'assignee'])->find($id);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): Task
    {
        return Task::query()->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Task $task, array $data): Task
    {
        $task->fill($data);
        $task->save();

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function forceDelete(Task $task): void
    {
        $task->forceDelete();
    }

    /** @param array<int> $ids 並び順（先頭が sort_order=1） */
    public function sort(array $ids): void
    {
        foreach ($ids as $order => $id) {
            Task::query()->where('id', $id)->update(['sort_order' => $order + 1]);
        }
    }
}
