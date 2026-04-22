<?php

namespace App\Modules\Project\Contracts;

use App\Modules\Project\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepository
{
    public function paginateForUser(int $userId, int $perPage): LengthAwarePaginator;

    public function findById(int $id): ?Project;

    /** @param array<string, mixed> $data */
    public function create(array $data): Project;

    /** @param array<string, mixed> $data */
    public function update(Project $project, array $data): Project;

    public function delete(Project $project): void;

    /** @param array<int> $memberIds */
    public function syncMembers(Project $project, array $memberIds): void;
}
