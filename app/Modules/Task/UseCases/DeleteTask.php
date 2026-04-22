<?php

namespace App\Modules\Task\UseCases;

use App\Modules\Task\Contracts\TaskRepository;
use App\Modules\Task\Events\TaskDeleted;
use App\Modules\Task\Models\Task;
use App\Modules\Task\Rules\TaskDeletionRules;

class DeleteTask
{
    public function __construct(
        private readonly TaskRepository $tasks,
        private readonly TaskDeletionRules $rules,
    ) {}

    public function handle(Task $task): void
    {
        $this->rules->validate($task);

        // 完了タスク → 物理削除、未完了タスク → ソフトデリート
        $task->isCompleted()
            ? $this->tasks->forceDelete($task)
            : $this->tasks->delete($task);

        event(new TaskDeleted($task));
    }
}
