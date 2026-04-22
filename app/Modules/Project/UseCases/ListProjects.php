<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListProjects
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function handle(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->projects->paginateForUser($userId, $perPage);
    }
}
