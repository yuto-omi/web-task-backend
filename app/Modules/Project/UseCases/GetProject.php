<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectRepository;
use App\Modules\Project\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetProject
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function handle(int $id): Project
    {
        $project = $this->projects->findById($id);

        if ($project === null) {
            throw new ModelNotFoundException("Project [{$id}] not found.");
        }

        return $project;
    }
}
