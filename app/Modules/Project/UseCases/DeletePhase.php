<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectPhaseRepository;
use App\Modules\Project\Events\PhaseDeleted;
use App\Modules\Project\Models\ProjectPhase;
use App\Modules\Project\Rules\PhaseDeletionRules;

class DeletePhase
{
    public function __construct(
        private readonly ProjectPhaseRepository $phases,
        private readonly PhaseDeletionRules $rules,
    ) {}

    public function handle(ProjectPhase $phase): void
    {
        $this->rules->validate($phase);

        $this->phases->delete($phase);

        event(new PhaseDeleted($phase));
    }
}
