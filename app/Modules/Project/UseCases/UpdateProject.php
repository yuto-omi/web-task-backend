<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectRepository;
use App\Modules\Project\DTO\UpdateProjectDTO;
use App\Modules\Project\Events\ProjectUpdated;
use App\Modules\Project\Models\Project;

class UpdateProject
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function handle(Project $project, UpdateProjectDTO $dto): Project
    {
        $this->projects->update($project, $dto->toArray());

        if ($dto->memberIds !== null) {
            // 作成者は必ずメンバーに含める
            $memberIds = array_unique(array_merge([$project->created_by], $dto->memberIds));
            $this->projects->syncMembers($project, $memberIds);
        }

        event(new ProjectUpdated($project));

        return $project->fresh(['members', 'phases']);
    }
}
