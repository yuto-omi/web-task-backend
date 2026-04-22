<?php

namespace App\Modules\Task\Events;

use App\Modules\Task\Models\Task;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskDeleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly Task $task) {}
}
