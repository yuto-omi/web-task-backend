<?php

namespace App\Modules\NewsCategory\Events;

use App\Modules\NewsCategory\Models\NewsCategory;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsCategoryCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly NewsCategory $category) {}
}
