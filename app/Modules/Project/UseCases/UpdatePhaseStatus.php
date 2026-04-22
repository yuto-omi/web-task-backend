<?php

namespace App\Modules\Project\UseCases;

use App\Modules\Project\Contracts\ProjectPhaseRepository;
use App\Modules\Project\Events\PhaseStatusChanged;
use App\Modules\Project\Models\ProjectPhase;
use App\Modules\Project\ValueObjects\PhaseStatus;

class UpdatePhaseStatus
{
    public function __construct(private readonly ProjectPhaseRepository $phases) {}

    public function handle(ProjectPhase $phase, PhaseStatus $status): ProjectPhase
    {
        $oldStatus = $phase->status;

        $updated = $this->phases->update($phase, ['status' => $status->value()]);

        event(new PhaseStatusChanged($updated, $oldStatus, $status->value()));

        return $updated;
    }
}
