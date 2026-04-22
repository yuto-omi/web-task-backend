<?php

namespace App\Modules\Project\Repositories;

use App\Modules\Project\Contracts\ProjectPhaseRepository;
use App\Modules\Project\Models\ProjectPhase;
use Illuminate\Database\Eloquent\Collection;

class EloquentProjectPhaseRepository implements ProjectPhaseRepository
{
    /** @return Collection<int, ProjectPhase> */
    public function listByProject(int $projectId): Collection
    {
        return ProjectPhase::query()
            ->where('project_id', $projectId)
            ->orderBy('sort_order')
            ->get();
    }

    public function findByProjectAndId(int $projectId, int $id): ?ProjectPhase
    {
        return ProjectPhase::query()
            ->where('project_id', $projectId)
            ->find($id);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): ProjectPhase
    {
        return ProjectPhase::query()->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(ProjectPhase $phase, array $data): ProjectPhase
    {
        $phase->fill($data);
        $phase->save();

        return $phase;
    }

    public function delete(ProjectPhase $phase): void
    {
        $phase->delete();
    }

    /** @param array<int> $ids 並び順（先頭が sort_order=1） */
    public function sort(int $projectId, array $ids): void
    {
        foreach ($ids as $order => $id) {
            ProjectPhase::query()
                ->where('project_id', $projectId)
                ->where('id', $id)
                ->update(['sort_order' => $order + 1]);
        }
    }
}
