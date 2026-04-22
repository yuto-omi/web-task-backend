<?php

namespace App\Modules\Project\Events;

use App\Modules\Project\Models\Project;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectUpdated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly Project $project) {}
}
