<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectPhaseRepository;
use App\Modules\Project\DTO\UpdatePhaseDTO;
use App\Modules\Project\Events\PhaseUpdated;
use App\Modules\Project\Models\ProjectPhase;

class UpdatePhase
{
    public function __construct(private readonly ProjectPhaseRepository $phases) {}

    public function handle(ProjectPhase $phase, UpdatePhaseDTO $dto): ProjectPhase
    {
        $updated = $this->phases->update($phase, $dto->toArray());

        event(new PhaseUpdated($updated));

        return $updated;
    }
}
