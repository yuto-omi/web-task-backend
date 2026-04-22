<?php

namespace App\Modules\Project\Events;

use App\Modules\Project\Models\ProjectPhase;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PhaseStatusChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ProjectPhase $phase,
        public readonly string $oldStatus,
        public readonly string $newStatus,
    ) {}
}
