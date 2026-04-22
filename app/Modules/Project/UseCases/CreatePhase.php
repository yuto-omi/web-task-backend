<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectPhaseRepository;
use App\Modules\Project\Contracts\ProjectRepository;
use App\Modules\Project\DTO\CreatePhaseDTO;
use App\Modules\Project\Events\PhaseCreated;
use App\Modules\Project\Mappers\ProjectPhaseMapper;
use App\Modules\Project\Models\ProjectPhase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreatePhase
{
    public function __construct(
        private readonly ProjectRepository $projects,
        private readonly ProjectPhaseRepository $phases,
    ) {}

    public function handle(int $projectId, CreatePhaseDTO $dto): ProjectPhase
    {
        if ($this->projects->findById($projectId) === null) {
            throw new ModelNotFoundException("Project [{$projectId}] not found.");
        }

        $entity = (new ProjectPhaseMapper)->toEntity($dto);

        // sort_order未指定の場合は既存フェーズ数+1を自動採番
        $data = $entity->toArray();
        if (empty($data['sort_order'])) {
            $data['sort_order'] = $this->phases->listByProject($projectId)->count() + 1;
        }

        $phase = $this->phases->create([
            ...$data,
            'project_id' => $projectId,
        ]);

        event(new PhaseCreated($phase));

        return $phase;
    }
}
