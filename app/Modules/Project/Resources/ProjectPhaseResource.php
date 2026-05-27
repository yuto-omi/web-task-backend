<?php

namespace App\Modules\Project\Resources;

use App\Modules\Project\Models\ProjectPhase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProjectPhase */
class ProjectPhaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'assignee_id' => $this->assignee_id,
            'name' => $this->name,
            'status' => $this->status,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'estimated_hours' => $this->estimated_hours,
            'sort_order' => $this->sort_order,
            'progress_rate' => $this->progress_rate,
            'created_at' => $this->created_at,
        ];
    }
}
