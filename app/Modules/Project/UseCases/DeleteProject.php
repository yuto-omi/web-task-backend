<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectRepository;
use App\Modules\Project\Events\ProjectDeleted;
use App\Modules\Project\Models\Project;
use App\Modules\Project\Rules\ProjectDeletionRules;

class DeleteProject
{
    public function __construct(
        private readonly ProjectRepository $projects,
        private readonly ProjectDeletionRules $rules,
    ) {}

    public function handle(Project $project): void
    {
        $this->rules->validate($project);

        $this->projects->delete($project);

        event(new ProjectDeleted($project));
    }
}
