<?php

namespace App\Modules\Task\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'phase_id' => ['nullable', 'integer', 'exists:project_phases,id'],
            'parent_task_id' => ['nullable', 'integer', 'exists:tasks,id'],
            'assignee_id' => ['nullable', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'memo' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', 'in:pending,in_progress,in_review,done'],
            'priority' => ['nullable', 'string', 'in:low,medium,high'],
            'type_tag' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:9999.5'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
