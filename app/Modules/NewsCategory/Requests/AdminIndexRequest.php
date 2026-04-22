<?php

namespace App\Modules\NewsCategory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'string', 'in:created_at,-created_at,name,-name,slug,-slug'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
