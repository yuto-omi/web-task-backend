<?php

namespace App\Modules\Task\Resources;

use App\Modules\Task\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Task */
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'phase_id' => $this->phase_id,
            'parent_task_id' => $this->parent_task_id,
            'assignee_id' => $this->assignee_id,
            'created_by' => $this->created_by,
            'title' => $this->title,
            'memo' => $this->memo,
            'status' => $this->status,
            'priority' => $this->priority,
            'type_tag' => $this->type_tag,
            'due_date' => $this->due_date?->toDateString(),
            'estimated_hours' => $this->estimated_hours,
            'sort_order' => $this->sort_order,
            'assignee' => $this->whenLoaded('assignee', fn () => [
                'id' => $this->assignee->id,
                'name' => $this->assignee->name,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
