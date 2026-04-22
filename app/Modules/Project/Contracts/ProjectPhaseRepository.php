<?php

namespace App\Modules\Project\Contracts;

use App\Modules\Project\Models\ProjectPhase;
use Illuminate\Database\Eloquent\Collection;

interface ProjectPhaseRepository
{
    /** @return Collection<int, ProjectPhase> */
    public function listByProject(int $projectId): Collection;

    public function findByProjectAndId(int $projectId, int $id): ?ProjectPhase;

    /** @param array<string, mixed> $data */
    public function create(array $data): ProjectPhase;

    /** @param array<string, mixed> $data */
    public function update(ProjectPhase $phase, array $data): ProjectPhase;

    public function delete(ProjectPhase $phase): void;

    /** @param array<int> $ids 並び順（先頭が sort_order=1） */
    public function sort(int $projectId, array $ids): void;
}
