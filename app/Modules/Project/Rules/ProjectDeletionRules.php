<?php

namespace App\Modules\Project\Rules;

use App\Modules\Project\Exceptions\ProjectValidationException;
use App\Modules\Project\Models\Project;

class ProjectDeletionRules
{
    public function validate(Project $project): void
    {
        // 進行中のプロジェクトは削除不可
        if ($project->status === 'in_progress') {
            throw new ProjectValidationException(
                '進行中のプロジェクトは削除できません。先にステータスを変更してください。'
            );
        }
    }
}
