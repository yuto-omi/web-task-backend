<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectPhaseRepository;

class SortPhases
{
    public function __construct(private readonly ProjectPhaseRepository $phases) {}

    /**
     * @param  array<int>  $ids  並び順（先頭が sort_order=1）
     */
    public function handle(int $projectId, array $ids): void
    {
        $this->phases->sort($projectId, $ids);
    }
}
