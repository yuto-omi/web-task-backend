<?php

namespace App\Modules\News\Events;

use App\Modules\News\Models\News;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsUpdated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly News $news) {}
}
