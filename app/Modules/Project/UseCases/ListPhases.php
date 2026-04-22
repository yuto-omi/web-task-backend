<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectPhaseRepository;
use Illuminate\Database\Eloquent\Collection;

class ListPhases
{
    public function __construct(private readonly ProjectPhaseRepository $phases) {}

    public function handle(int $projectId): Collection
    {
        return $this->phases->listByProject($projectId);
    }
}
