<?php

namespace App\Modules\Project\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['sometimes', 'string', 'max:255'],
            'client_name'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'start_date'      => ['sometimes', 'nullable', 'date'],
            'deadline'        => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
            'estimated_hours' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:9999.5'],
            'memo'            => ['sometimes', 'nullable', 'string'],
            'member_ids'      => ['sometimes', 'nullable', 'array'],
            'member_ids.*'    => ['integer', 'exists:users,id'],
        ];
    }
}
