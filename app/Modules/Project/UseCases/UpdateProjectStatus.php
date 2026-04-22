<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectRepository;
use App\Modules\Project\Events\ProjectStatusChanged;
use App\Modules\Project\Models\Project;
use App\Modules\Project\ValueObjects\ProjectStatus;

class UpdateProjectStatus
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function handle(Project $project, ProjectStatus $status): Project
    {
        $oldStatus = $project->status;

        $updated = $this->projects->update($project, ['status' => $status->value()]);

        event(new ProjectStatusChanged($updated, $oldStatus, $status->value()));

        return $updated;
    }
}
