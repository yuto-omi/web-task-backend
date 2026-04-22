<?php

namespace App\Modules\Project\Rules;

use App\Modules\Project\Exceptions\ProjectValidationException;
use App\Modules\Project\Models\ProjectPhase;

class PhaseDeletionRules
{
    public function validate(ProjectPhase $phase): void
    {
        // 進行中のフェーズは削除不可
        if ($phase->status === 'in_progress') {
            throw new ProjectValidationException(
                '進行中のフェーズは削除できません。先にステータスを変更してください。'
            );
        }
    }
}
