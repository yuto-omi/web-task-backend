<?php

namespace App\Modules\Task\Rules;

use App\Modules\Task\Models\Task;

class TaskDeletionRules
{
    public function validate(Task $task): void
    {
        // 完了タスク → 物理削除、未完了タスク → ソフトデリート
        // どちらも削除自体は許可（ルール違反なし）
        // 将来的に「サブタスクが残っているとき親を削除不可」等をここに追加する
    }
}
