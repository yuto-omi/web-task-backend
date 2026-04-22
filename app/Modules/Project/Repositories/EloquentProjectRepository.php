<?php

namespace App\Modules\Project\Repositories;

use App\Modules\Project\Contracts\ProjectRepository;
use App\Modules\Project\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentProjectRepository implements ProjectRepository
{
    public function paginateForUser(int $userId, int $perPage): LengthAwarePaginator
    {
        return Project::query()
            ->forUser($userId)
            ->with(['members', 'phases'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Project
    {
        return Project::query()
            ->with(['members', 'phases', 'tasks'])
            ->find($id);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): Project
    {
        return Project::query()->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Project $project, array $data): Project
    {
        $project->fill($data);
        $project->save();

        return $project;
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }

    /** @param array<int> $memberIds */
    public function syncMembers(Project $project, array $memberIds): void
    {
        $project->members()->sync($memberIds);
    }
}
