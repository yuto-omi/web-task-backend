<?php

namespace App\Modules\Project\Events;

use App\Modules\Project\Models\ProjectPhase;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PhaseDeleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly ProjectPhase $phase) {}
}
