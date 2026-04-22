<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectRepository;
use App\Modules\Project\DTO\CreateProjectDTO;
use App\Modules\Project\Events\ProjectCreated;
use App\Modules\Project\Mappers\ProjectMapper;
use App\Modules\Project\Models\Project;

class CreateProject
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function handle(CreateProjectDTO $dto, int $createdBy): Project
    {
        $entity = (new ProjectMapper)->toEntity($dto);

        $project = $this->projects->create([
            ...$entity->toArray(),
            'created_by' => $createdBy,
        ]);

        // 作成者は必ずメンバーに含める
        $memberIds = array_unique(array_merge([$createdBy], $dto->memberIds));
        $this->projects->syncMembers($project, $memberIds);

        event(new ProjectCreated($project));

        return $project->load(['members', 'phases']);
    }
}
